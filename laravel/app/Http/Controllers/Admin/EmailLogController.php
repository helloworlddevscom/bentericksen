<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\OutgoingEmail;
use App\User;
use Illuminate\Http\Request;

/**
 * Class EmailLogController.
 *
 * Used for showing the emails that were sent to a given user. Part of the Admin portal.
 *
 * @see \App\OutgoingEmail
 */
class EmailLogController extends Controller
{
    /**
     * Display a listing of the emails sent to a given user.
     *
     * @return Response
     */
    public function index($user_id)
    {
        $user = User::with('outgoingEmails')->findOrFail($user_id);

        return view('admin.emailLog.index')
            ->with('user', $user);
    }

    /**
     * Display a listing of the emails sent to a given user.
     *
     * @return Response
     */
    public function show($id)
    {
        $email = OutgoingEmail::with('user')->findOrFail($id);

        return view('admin.emailLog.show')
            ->with('email', $email);
    }

    public function emailList(Request $request)
    {
      return $this->inertia('Admin/EmailLog/Index', [
        '_response' => null
      ]);
    }

    public function getEmails(Request $request)
    {
        $business = $request->input('business_name');
        $contact = $request->input('contact_name');
        $subject = $request->input('subject');
        $email = $request->input('email');

        $sort = $request->input('sort') ?? 'outgoing_emails.sent_at';
        $sortOrder = $request->input('sort_order') ?? 'desc';
        $from = $request->input('from');
        $to = $request->input('to');

        $users = OutgoingEmail::select([
            'business.name',
            'users.first_name',
            'users.last_name',
            'outgoing_emails.subject',
            'outgoing_emails.to_address',
            'outgoing_emails.sent_at',
            'outgoing_emails.status',
        ])
            ->leftJoin('users', 'outgoing_emails.user_id', '=', 'users.id')
            ->leftJoin('business', 'users.business_id', '=', 'business.id')
                ->where([
                    ['outgoing_emails.status', '=', 'sent']
            ]);

        if (! empty($business)) {
            $users = $users->where('business.name', 'like', "%$business%");
        }

        if (! empty($contact)) {
            $users = $users->where(function ($query) use ($contact) {
                $query->where('users.first_name', 'like', "%$contact%")
                    ->orWhere('users.last_name', 'like', "%$contact%");
            });
        }

        if (! empty($email)) {
            $users = $users
                ->where('outgoing_emails.to_address', 'like', "%$email%");
        }

        if (! empty($to) && ! empty($from)) {
            $users = $users->whereBetween('outgoing_emails.sent_at', [$from, $to]);
        }

        if (! empty($subject)) {
            $users = $users->where('outgoing_emails.subject', $subject);
        }

        if (! empty($sort) && ! empty($sortOrder)) {
            $users = $users
                ->orderBy($sort, $sortOrder);
        }

        $users = $users
            ->paginate(10);

        return $users;
    }
}
