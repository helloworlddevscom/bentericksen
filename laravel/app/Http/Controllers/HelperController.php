<?php

namespace App\Http\Controllers;

use App\User;
use Bentericksen\PolicyUpdater\PolicyUpdater as BentPolicyUpdater;
use Bentericksen\ViewAs\ViewAs;
use Illuminate\Support\Str;
use Response;


class HelperController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
    }

    /**
     * Generate a simple random password.
     *
     * @return Response
     */
    public function generatePassword()
    {
        return Str::random(12);
    }

    /**
     * Generate in memory a csv export of a policy update emails list and provide as download.
     *
     * @param $id
     * @param $which
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function exportPolicyUpdateEmailsList($id, $which)
    {
        $headers = [
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Content-type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=policyUpdateEmailsList_id-'.$id.'-'.time().'.csv',
            'Expires' => '0',
            'Pragma' => 'public',
        ];

        $updater = new BentPolicyUpdater;
        $data = $updater->getPolicyUpdate($id);

        $callback = function () use ($data, $which) {
            $emails = $data[$which];

            $handle = fopen('php://output', 'w');
            $row = [
                'state',
                'business_id',
                'business_name',
                'email',
            ];
            fputcsv($handle, $row);
            foreach ($emails as $email => $business) {
                $row = [
                    $business['state'],
                    $business['id'],
                    $business['name'],
                    $email,
                ];
                fputcsv($handle, $row);
            }
            fclose($handle);
        };

        return Response::stream($callback, 200, $headers);
    }

    /**
     * Generate in memory a csv export of users and provide as download.
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function exportUserList()
    {
        $headers = [
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Content-type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=userList'.time().'.csv',
            'Expires' => '0',
            'Pragma' => 'public',
        ];

        $list = User::orderBy('business_id', 'ASC')->get()->toArray();

        // Add headers for each column in the CSV download
        array_unshift($list, array_keys($list[0]));

        $callback = function () use ($list) {
            $handle = fopen('php://output', 'w');
            foreach ($list as $row) {
                fputcsv($handle, $row);
            }
            fclose($handle);
        };

        return Response::stream($callback, 200, $headers);
    }

    public function setViewAs(ViewAs $viewAs)
    {
        return $viewAs->get();
    }
}
