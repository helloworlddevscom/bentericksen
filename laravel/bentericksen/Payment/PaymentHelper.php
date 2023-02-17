<?php

namespace Bentericksen\Payment;

use App\Mail\PaymentFailed;
use App\Mail\PaymentMethodExpiring;
use App\Mail\PaymentSucceeded;
use App\Mail\PaymentUpcoming;
use App\Mail\PaymentEvent;
use Bentericksen\Payment\Clients\StripeClient;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use App\Facades\PaymentService;
use Bentericksen\Payment\Subscriptions\Models\StripeSubscription;
use App\OutgoingEmail;
use Carbon\Carbon;
use App\User;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\DB;
use Bentericksen\Payment\Instruments\Cards\Models\StripeCard;


/**
 * Class PaymentHelper
 * @package Bentericksen\Payment
 */
class PaymentHelper
{

    public static function handlePaymentFunc($obj, $funcParams, $response, $user = null) {

        $responseData = null;

        $stripeClient = $funcParams['stripeClient'];
        $stripeInstance = $funcParams['stripeInstance'];
        $stripeMethod = $funcParams['stripeMethod'];
        $stripeArgs = $funcParams['stripeArgs'];
        $persistMethod = null;
        $persistArgs = null;

        try {

            // Make Stripe request
            $result = PaymentHelper::objToArray(
                call_user_func_array(
                    [
                        $stripeClient->$stripeInstance,
                        $stripeMethod
                    ],
                    $stripeArgs
                )
            );

            if ($response->getStatusCode() === 200) {

                try {

                    // Let's handle customer create, payment instrument create, and subscription create methods here.
                    // Customer delete, payment instrument update and delete, subscription update and delete, and all product methods
                    // are handled via event webhooks.

                    // Prepare persist method
                    if(!isset($funcParams['instrumentType'])) {
                        $persistMethod = "_{$stripeMethod}" . ucfirst(substr($stripeInstance,0,-1));
                    }  else {
                        $persistMethod = "_{$stripeMethod}" . $funcParams['instrumentType'];
                    }

                    // Let's assume we'll handle a method here
                    $handle = true;

                    // Prepare persist method arguments.
                    if(isset($funcParams['instrumentType']) && ($stripeMethod === "createSource")) {
                        $persistArgs = [$result, $user];
                    } else if(
                        ($funcParams['stripeInstance'] === "customers" && $stripeMethod === "create") ||
                        ($funcParams['stripeInstance'] === "subscriptions" && $stripeMethod === "create")
                    ) {
                        $persistArgs = [$result, $user];
                    } else {
                        // Nothing to do here, let's return success
                        $handle = false;
                        $responseData = PaymentHelper::handleEvent($result, null, 'payment_event');
                    }

                    if($handle) {
                        // Save the object updates to the database and handle any errors
                        if (call_user_func_array([$obj, $persistMethod], $persistArgs)) {
                            // Stripe entity updated and object update saved to database (This is where we want to end up).
                            $responseData = PaymentHelper::handleEvent($result, null, 'payment_event');
                        } else {
                            throw new \Exception('Error saving Stripe subscription updates to database.');
                        }
                    }

                } catch (QueryException $e) {

                    // Handle Query exception
                    $responseData = PaymentHelper::handleEvent($e->getMessage(), PaymentHelper::loginfo(), 'payment_exception');

                } catch (\Exception $e) {

                    // Handle exception
                    $responseData = PaymentHelper::handleEvent($e->getMessage(), PaymentHelper::loginfo(), 'payment_exception');

                }

            } else {

                // Prepare Stripe error status/message
                $responseData = PaymentHelper::handleEvent( '520 unknown error', PaymentHelper::loginfo(), 'payment_error');

            }

        } catch (\Exception $e) {

            // Prepare request error response status/message
            $responseData = PaymentHelper::handleEvent($e->getMessage(), PaymentHelper::loginfo(), 'payment_exception');

        }

        return $responseData;

    }

    /**
     * Checks for pertinent Stripe data
     * @param $data
     * @return bool
     */
    public static function isValid($data) {

        if(isset($data) && $data['success'] === true) {
            return true;
        }

        return false;

    }

    /**
     * Evaluates existence of attribute in given object
     * @param $obj
     * @param $attribute
     * @return bool
     */
    public static function hasAttribute($obj, $attribute) {

        $object_vars = Schema::getColumnListing($obj->getTable());
        return in_array($attribute, $object_vars, true);

    }

    /**
     * Converts the given object to an associative array
     * @param $obj
     * @return mixed
     */
    public static function objToArray($obj) {

        return json_decode(json_encode($obj),true);

    }

    /**
     * Converts the given array to a generic object - recursive
     * @param $array
     * @return \stdClass
     */
    public static function ArrayToObj($array) {

        $object = new \stdClass();
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $value = self::ArrayToObj($value);
            }
            $object->$key = $value;
        }
        return $object;

    }

    /**
     * Event handler
     * @param $event
     * @param bool $notif
     * @return array
     */
    public static function handleEvent($event, $loginfo, $type = "payment_event", $log = true, $notif = true) {

        // Set the log channel to either payment_event or payment_error.  We don't have a payment_exception channel.
        $log_channel = "payment_error";

        if($type === "payment_event" || strpos($type, '_succeeded')) {
            $log_channel = "payment_event";
        }

        if(isset($loginfo)) {
            // Log event
            if($log) {
                Log::channel($log_channel)->info($loginfo . json_encode($event));
            }
            // Notify system administrator
            if($notif) {
                self::sendNotification($type, $loginfo . $event);
            }
        }

        // Prepare user response message
        if(array_key_exists($type, trans('messaging.payment'))) {
            $event = __("messaging.payment.{$type}");
        }

        // Return result
        $result = $type === "payment_event" ? true : false;
        return ['success' => $result, 'data' => $event];

    }

    public static function handlePaymentFailed($obj)
    {

        // Prepare event data and recipients
        $event = [];

        // one time payments made without saving card info don't have a customer on them.
        if (is_null($obj['customer'])) {
            // Handle event
            return PaymentHelper::handleEvent('Handling one-time charge failed.', PaymentHelper::loginfo(), 'payment_event', false, false);
        }

        $user = User::where('stripe_id', $obj['customer'])->first();

        if (!isset($user)) {
            // Handle error
            return PaymentHelper::handleEvent('User not found.', PaymentHelper::loginfo(), 'payment_error');
        }

        $customer = PaymentService::customerComponent()->getCustomer($user);
        if (!PaymentHelper::isValid($customer)) {
            // Handle error
            return PaymentHelper::handleEvent('Customer not found.', PaymentHelper::loginfo(), 'payment_error');
        }

        $business = $user->business;
        $asa = $business->asa;
        $source = $customer['data']->default_source;

        if (!isset($asa)) {
            // Handle error
            return PaymentHelper::handleEvent('Business ASA not found.', PaymentHelper::loginfo(), 'payment_error');
        }

        if (!isset($source)) {
            // Handle error
            return PaymentHelper::handleEvent('Default Customer Source not found.', PaymentHelper::loginfo(), 'payment_error');
        }

        $event['business'] = $business->name;

        if (explode("_", $source)[0] === "card") {

            $card = PaymentService::cardComponent()->getPaymentInstrument($user, $source);

            if (PaymentHelper::isValid($card)) {

                $event['card_brand'] = $card['data']->brand;
                $event['card_last4'] = $card['data']->last4;
                $event['source_type'] = "credit_card";
                $event['bank_name'] = "";
                $event['account_last4'] = "";

            } else {

                return PaymentHelper::handleEvent('Missing StripeCard instance.', PaymentHelper::loginfo(), 'payment_error');

            }

        } elseif (explode("_", $source)[0] === "ba") {

            $account = PaymentService::bankAccountComponent()->getPaymentInstrument($user, $source);
            $event['bank_name'] = $account->bank_name;
            $event['account_last4'] = $account->last4;
            $event['source_type'] = "bank_account";
            $event['card_brand'] = "";
            $event['card_last4'] = "";

        } else {

            //Handle error
            return PaymentHelper::handleEvent('', PaymentHelper::loginfo(), 'payment_error');

        }

        $asaTypeParts = explode("-", $asa->type);
        $event['service_type'] = count($asaTypeParts) < 2 ?
            ucfirst($asaTypeParts[0]) :
            ucfirst($asaTypeParts[0]) . " (" . $asaTypeParts[1] . "-" . $asaTypeParts[2] . " employees)";

        if ($obj['object'] === "invoice") {
            $event['amount_paid'] = (int)$obj['amount_due'] * .01;
            // grab the failure message from the invoice charge
            $charge = PaymentHelper::objToArray(StripeClient::getInstance()->charges->retrieve($obj['charge']));
            $event['reason'] = $charge['failure_message'];
        } else {
            $event['amount_paid'] = (int)$obj['amount'] * .01;
            $event['reason'] = $obj['failure_message'];
        }

        // invoice created is when this invoice object was created, not when the invoice was created.
        $payment_date = Carbon::createFromTimestamp(PaymentHelper::objToArray($obj['created']));

        $event['payment_date'] = $payment_date->format('F j, Y');

        // Send notifications
        return self::sendPaymentNotifications($business, new PaymentFailed($event), ['admin', 'owner', 'manager']);

    }

    public static function handlePaymentSucceeded($obj)
    {

        // Prepare event data and recipients
        $event = [];

        $customerId = null;

        if(!is_null($obj['customer'])) {
            $customerId = $obj['customer'];
        } else {
            $customerId = isset($obj['metadata']['stripe_id']) ? $obj['metadata']['stripe_id'] : null;
        }


        if (is_null($customerId)) {
            // Handle event
            return PaymentHelper::handleEvent('No Stripe Customer ID.', PaymentHelper::loginfo(), 'payment_event', false, false);
        }

        $user = User::where('stripe_id', $customerId)->first();

        if (isset($user)) {

            $business = $user->business;
            if (!isset($business)) {
                return PaymentHelper::handleEvent('Business not found', PaymentHelper::loginfo(), 'payment_error', true, false);
            }

            $asa = $business->asa;
            if (!isset($asa)) {
                return PaymentHelper::handleEvent('Asa not found', PaymentHelper::loginfo(), 'payment_error', true, false);
            }

            $asaTypeParts = explode("-", $asa->type);

            if(count($asaTypeParts) === 1) {
                $event['service_type'] = ucfirst($asaTypeParts[0]);
            }

            if(count($asaTypeParts) === 2) {
                $event['service_type'] = ucfirst($asaTypeParts[0]) . " (" . $asaTypeParts[1] . "+" . " employees)";
            }

            if(count($asaTypeParts) === 3) {
                $event['service_type'] = ucfirst($asaTypeParts[0]) . " (" . $asaTypeParts[1] . "-" . $asaTypeParts[2] . " employees)";
            }

            $event['business'] = $business->name;

            $event['amount_paid'] = $obj['object'] === "invoice" ? $obj['amount_paid'] : $obj['amount'];
            $event['amount_paid'] = number_format(round((float)$event['amount_paid'] * .01, 2), 2, '.', '');

            // charge created is when the invoice object was created, not when the invoice was created.
            $payment_date = Carbon::createFromTimestamp(PaymentHelper::objToArray($obj['created']));

            $event['payment_date'] = $payment_date->format('F j, Y');

            $card = StripeCard::where('customer', $user->stripe_id)->first();

            if (isset($card)) {
                $event['last4'] = $card->last4;
            } else {
                $event['last4'] = "Payment method not saved";
            }

            $stripeCustomer = StripeClient::getInstance()->customers->retrieve($user->stripe_id, []);

            if (!isset($stripeCustomer)) {
                return PaymentHelper::handleEvent('Stripe customer not found', PaymentHelper::loginfo(), 'payment_error', true, false);
            }

            $event['sub_total'] = $event['amount_paid'];

            $event['discount'] = !is_null($stripeCustomer->discount) && !isset($obj['metadata']['payment_type']);
            if($event['discount']) {
                $event['discount_name'] = $stripeCustomer->discount->coupon->name;
                if($stripeCustomer->discount->coupon->percent_off === null) {
                    $customerDiscount = round((float)$stripeCustomer->discount->coupon->amount_off * .01, 2);
                    $event['discount_amount'] = "$" . strval(number_format($customerDiscount, 2, '.', ','));
                    $event['sub_total'] = number_format(((float)$event['amount_paid'] + $customerDiscount), 2, '.', '');
                } else {
                    $event['discount_amount'] = $stripeCustomer->discount->coupon->percent_off . "%";
                    $event['sub_total'] = number_format((float)$event['amount_paid'] / round((float)(100 - (int)$event['discount_amount']) * .01, 2), 2, '.', '');
                }
            }

            $event['sub_total'] = number_format($event['sub_total'], 2, '.', ',');
            $event['amount_paid'] = number_format($event['amount_paid'], 2, '.', ',');

            // Send notifications
            return self::sendPaymentNotifications($business, new PaymentSucceeded($event), ['admin', 'owner', 'manager']);

        }

        // Handle error
        return PaymentHelper::handleEvent('Missing User instance.', PaymentHelper::loginfo(), 'payment_error');

    }

    public static function handleUpcomingRenewal($invoice)
    {

        // Prepare event data and recipients
        $user = User::where('stripe_id', $invoice['customer'])->first();
        if (!isset($user)) {
            return PaymentHelper::handleEvent('User not found.', PaymentHelper::loginfo(), 'payment_error', true, false);
        }

        $business = $user->business;
        if (!isset($business)) {
            return PaymentHelper::handleEvent('Business not found.', PaymentHelper::loginfo(), 'payment_event', true, false);
        }

        $asa = $business->asa;
        if (!isset($asa)) {
            return PaymentHelper::handleEvent('ASA not found.', PaymentHelper::loginfo(), 'payment_event', true, false);
        }

        $subscription = StripeSubscription::where('business_id', $business->id)->first();
        if (!isset($subscription)) {
            return PaymentHelper::handleEvent('Subscription not found.', PaymentHelper::loginfo(), 'payment_event', true, false);
        }

        $period_end = json_decode(PaymentHelper::objToArray($subscription)['data'], true)['current_period_end'];

        $renewal_date = Carbon::createFromTimestamp(PaymentHelper::objToArray($period_end));

        $asaTypeParts = explode("-", $asa->type);
        $event['service_type'] = count($asaTypeParts) < 2 ?
            ucfirst($asaTypeParts[0]) :
            ucfirst($asaTypeParts[0]) . " (" . $asaTypeParts[1] . "-" . $asaTypeParts[2] . " employees)";

        $card = StripeCard::where('customer', $user->stripe_id)->first();
        if (!isset($card)) {
            return PaymentHelper::handleEvent('Card not found', PaymentHelper::loginfo(), 'payment_error', true, false);
        }

        $event['last4'] = $card->last4;

        $event['renewal_date'] = $renewal_date->format('F j, Y');

        // Send notifications
        return self::sendPaymentNotifications($business, new PaymentUpcoming($event), ['admin', 'owner', 'manager']);

    }

    public static function handlePaymentMethodExpiring($source) {

        // Prepare event data and recipients
        if($source['object'] === "card") {

            $user = User::where('stripe_id', $source['customer'])->first();

            if(isset($user)) {

                    $business = $user->business;

                    if(isset($business)) {

                        $event = [
                            'card_brand' => $source['brand'],
                            'card_last4' => $source['last4']
                        ];

                        // Send notifications
                        return self::sendPaymentNotifications($business, new PaymentMethodExpiring($event), ['admin', 'owner', 'manager']);

                    }

                    // Handle error
                    return PaymentHelper::handleEvent(
                        'Missing Business instance.', PaymentHelper::loginfo(), 'payment_error'
                    );

            }

            // Handle error
            return PaymentHelper::handleEvent('Missing User instance', PaymentHelper::loginfo(), 'payment_error');

        }

        // This isn't a card, let's return success
        return PaymentHelper::handleEvent(null, null, 'payment_event');

    }

    /**
     * Send error notification
     * @param $notif_type
     * @param $notif_data
     */
    private static function sendNotification($notif_type, $notif_data) {
        //Mail::to(env('SYSADMIN_TO_ADDRESS'))->send(new PaymentEvent($notif_type, $notif_data));
    }

    /**
     * Returns the file and line of the calling function
     * @return string
     */
    public static function loginfo() {

        $ex = new \Exception();
        $trace = $ex->getTrace();

        // If caller isn't a logic layer class then let's return the immediate caller, otherwise let's return the previous caller.
        $logicLayerClasses = ["StripeCustomer.php","StripeCard.php","StripeBankAccount.php","StripeProduct.php","StripeSubscription.php"];
        $lastFileArr = explode("/",$trace[0]['file']);
        $lastFile = end($lastFileArr);
        $origin = !in_array($lastFile, $logicLayerClasses) ? $trace[0] : $trace[1];
        $file = $origin['file'];
        $line = $origin['line'];

        return " " . $file . " (line " . $line . ") ";

    }

    /**
     * Handle event notification
     * @param $mailable
     */
    private static function handlePaymentNotification($mailable, $user = null) {

        try {

            $mailer = new OutgoingEmail([], $mailable);
            if(is_null($user)) {
                $mailer->to_address = config('app.sysadmin.address');
                $mailable->to[0]['address'] = config('app.sysadmin.address');
                $mailable->to[0]['name'] = config('app.sysadmin.name');
            } else {
                $mailer->user_id = $user->id;
            }
            $mailer->related_type = self::class;
            $mailer->send();

        } catch(\Exception $e) {

            // Handle exception
            return PaymentHelper::handleEvent($e->getMessage(), PaymentHelper::loginfo(), 'payment_error');

        }

        return PaymentHelper::handleEvent(null, null, 'payment_event');

    }

    /**
     * Get notification recipients
     * @param $business
     * @param $roles
     * @return array
     */
    private static function getNotificationRecipients($business, $roles) {

        $recipients = [];

        $users = User::where('business_id', $business)->get();
        foreach($users as $user) {

            $role = DB::table('role_user')
                ->join('roles', 'role_user.role_id', '=', 'roles.id')
                ->select('roles.name')
                ->where('role_user.user_id', '=', $user->id)
                ->first()->name;

            if(in_array($role, $roles)) {
                array_push($recipients, $user);
            }

        }

        return $recipients;

    }

    /**
     * Send payment notifications
     * @param $business
     * @param $mailable
     * @param $roles
     * @return array
     */
    private static function sendPaymentNotifications($business, $mailable, $roles) {

        // Get the notification recipients
        $recipients = self::getNotificationRecipients($business->id, $roles);

        // Handle notifications
        if(empty($recipients)) {
            return PaymentHelper::handleEvent(
                'Missing payment notification recipient.', PaymentHelper::loginfo(), 'payment_error'
            );
        }

        foreach($recipients as $recipient) {
            self::handlePaymentNotification($mailable, $recipient);
        }

        if($mailable instanceof PaymentSucceeded || $mailable instanceof PaymentFailed) {
            self::handlePaymentNotification($mailable);
        }

    }

}
