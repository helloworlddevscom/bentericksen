<?php

namespace App\Http\Controllers\Employee;

use App\Business;
use App\Http\Controllers\Controller;
use App\User;
use Bentericksen\ViewAs\ViewAs;
use Symfony\Component\Finder\Exception\AccessDeniedException;

class PoliciesController extends Controller
{
    private $user;
    private $business;

    public function __construct(ViewAs $viewAs)
    {
        $this->user = User::findOrFail($viewAs->getUserId());

        //this should be middleware
        $this->checkBusinessId();

        $this->business = Business::findOrFail($this->user->business_id);
    }

    public function checkBusinessId()
    {
        if (is_null($this->user->business_id)) {
            redirect('/')->send();
        }
    }

    /**
     * Allows employees to download the manual.
     *
     * This will serve the previously-generated PDF if it exists. Otherwise, it will generate a new PDF and serve that.
     *
     * @return \Illuminate\Http\Response
     */
    public function downloadManual()
    {
        // Normally, access to the button that calls this route is blocked by a modal if the manual hasn't
        // been generated. (See laravel/resources/views/employee/index.blade.php).
        // This is here in case someone types the URL.
        if (is_null($this->business->manual)) {
            throw new AccessDeniedException("Sorry, the policy manual isn't ready for viewing yet!");
        }

        $headers = [
            'Content-Type' 			=> 'application/pdf',
            'Content-Disposition' 	=> 'inline; '.$this->business->manual.'.pdf',
        ];

        return response()->make(file_get_contents(storage_path('bentericksen/policy/'.$this->business->manual)), 200, $headers);

        /* If they want it to directly prompt download and not open in browser
        $headers = [
            'Content-Description' => 'File Transfer',
            'Content-Type'		  => 'application/pdf',
            //"Content-Disposition" => "attachment;filename=" . $this->business->manual . ".pdf",
            "Content-Disposition" => "attachment;filename=" . $this->business->manual . ".pdf",
            'Content-Transfer-Encoding' => 'binary',
            'Expires'			  => '0',
            'Cache-Control'		  => 'must-revalidate',
            'Pragma'			  => 'public',
            'Content-Length'	  => filesize( storage_path( 'bentericksen/policy/' . $this->business->manual) ),
        ];

        return response()->download(storage_path( 'bentericksen/policy/'. $this->business->manual), "PolicyManual.pdf", $headers);
        */
    }
}
