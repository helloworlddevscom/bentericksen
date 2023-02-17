<?php

namespace App\Http\Controllers\User;

use App\Business;
use App\Facades\PaymentService;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\UserBusinessEditRequest;
use App\Mail\AccountUpdateEmail;
use App\OutgoingEmail;
use App\User;
use Auth;
use Bentericksen\ViewAs\ViewAs;
use DB;
use Illuminate\Http\Request;
use Bentericksen\Payment\PaymentService as PayService;

class AccountController extends Controller
{
    private $businessId;

    private $business;

    private $user;

    public function __construct(ViewAs $viewAs)
    {
        $this->user = User::findOrFail($viewAs->getUserId());

        $this->businessId = $this->user->business_id;
        $this->business = Business::where('id', $this->user->business_id)->first();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $business = Business::find($this->businessId);
        $business->primary = User::find($business->primary_user_id);

        $business->secondary = [
            (object) [
                'is_enabled' => ($business->secondary_first_1_name.$business->secondary_1_last_name != '' ? 1 : 0),
                'prefix' => $business->secondary_1_prefix,
                'first_name' => $business->secondary_1_first_name,
                'middle_name' => $business->secondary_1_middle_name,
                'last_name' => $business->secondary_1_last_name,
                'suffix' => $business->secondary_1_suffix,
                'email' => $business->secondary_1_email,
                'role' => $business->secondary_1_role,
                'index' => 1,
            ],
            (object) [
                'is_enabled' => ($business->secondary_first_2_name.$business->secondary_2_last_name != '' ? 1 : 0),
                'prefix' => $business->secondary_2_prefix,
                'first_name' => $business->secondary_2_first_name,
                'middle_name' => $business->secondary_2_middle_name,
                'last_name' => $business->secondary_2_last_name,
                'suffix' => $business->secondary_2_suffix,
                'email' => $business->secondary_2_email,
                'role' => $business->secondary_2_role,
                'index' => 2,
            ],
        ];

        $phone_fields = [
            'phone1',
            'phone2',
            'phone3',
            'fax',
        ];

        // Ensure consistent telephone numbers for display
        foreach ($phone_fields as $phone_field) {
            $business->{$phone_field} = preg_replace('~.*(\d{3})[^\d]{0,7}(\d{3})[^\d]{0,7}(\d{4}).*~', '($1) $2-$3', $business->{$phone_field});
        }

        $business->asa = DB::table('business_asas')->where('business_id', $business->id)->first();

        $userPaymentAccess = $this->user->getPaymentAccess();

        $business->defaultCardInfo = $userPaymentAccess === 'full' ? PayService::getDefaultCardInfo($business->getPrimaryUser($return_object = true)) : [];

        return $this->inertia('User/Account/Index', [
          'business' => $business
        ]);
    }

    public function secondary(Request $request, $id)
    {
        $business = Business::findOrFail($id);

        foreach ($request->input('secondary') as $key => $row) {
            $business->{'secondary_'.$key.'_first_name'} = $row['first_name'];
            $business->{'secondary_'.$key.'_middle_name'} = $row['middle_name'];
            $business->{'secondary_'.$key.'_last_name'} = $row['last_name'];
            $business->{'secondary_'.$key.'_prefix'} = $row['prefix'];
            $business->{'secondary_'.$key.'_suffix'} = $row['suffix'];
            $business->{'secondary_'.$key.'_email'} = $row['email'];
        }

        $business->save();

        return redirect()->route('user.account.index');
    }

    public function deleteSecondary(Request $request, $index)
    {
        $fields = [
            'secondary_index_first_name',
            'secondary_index_middle_name',
            'secondary_index_last_name',
            'secondary_index_prefix',
            'secondary_index_suffix',
            'secondary_index_email',
        ];

        foreach ($fields as $field) {
            $field = str_replace('index', $index, $field);
            $this->business->{$field} = null;
        }

        $this->business->save();

        return redirect('/user/account');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        $business = Business::findOrFail($id);

        $states = (new \Bentericksen\Settings\States)->states();

        return view('user.account.edit', compact(['business', 'states']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update(UserBusinessEditRequest $request, $id)
    {
        $business = Business::findOrFail($id);
        $business->address1 = $request->input('address1');
        $business->address2 = $request->input('address2');
        $business->city = $request->input('city');
        $business->postal_code = $request->input('postal_code');
        $business->phone1 = $request->input('phone1');
        $business->phone1_type = $request->input('phone1_type');
        $business->phone2 = $request->input('phone2');
        $business->phone2_type = $request->input('phone2_type');
        $business->phone3 = $request->input('phone3');
        $business->phone3_type = $request->input('phone3_type');
        $business->fax = $request->input('fax');
        $business->website = $request->input('website');
        $business->save();

        // Send an e-mail update to info@bentericksen.com if not an admin
        if (! Auth::user()->hasRole('admin')) {
            $this->sendBusinessUpdateNotification($business);
        }

        return redirect()->route('user.account.index');
    }

    /**
     * Sends a notification when the business information has been updated.
     *
     * @param array $modification_approval_result
     */
    private function sendBusinessUpdateNotification($business)
    {
        $mailable = new AccountUpdateEmail(Auth::user(), $business);
        $mailer = new OutgoingEmail([], $mailable);
        $mailer->to_address = 'info@bentericksen.com';
        $mailer->user_id = User::where('email', 'info@bentericksen.com')->first()->id;
        $mailer->send();
    }
}
