<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Bentericksen\Exports\ExportService;
use Bentericksen\Settings\States;
use Bentericksen\Settings\Industries;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExportFilterController extends Controller
{
    /** @var States  */
    private $states;

    /** @var ExportService  */
    private $exportService;

    private $industries;

    public function __construct(
        States $states,
        ExportService $exportService
    )
    {
        $this->states = $states;
        $this->exportService = $exportService;
        $this->industries = (new Industries)->getIndustries();
    }

    /**
     * Display export filter form
     *
     * @param string $type
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(string $type): View
    {
        return view('admin.business.export')->with([
            'type' => $type,
            'states' => $this->states->states(),
            'industries' => $this->industries
        ]);
    }

    /**
     * Process export filter form request
     *
     * @param string $type
     * @param Request $request
     * @return mixed
     */
    public function exportList(string $type, Request $request)
    {
        if($type === "email-list") {
            $request->validate([
                'states' => 'required|array',
                'status' => 'required|array',
                'asa-date-from' => 'required|string',
                'asa-date-to' => 'required|string',
                'employee-num-from' => 'required|string|min:1|max:5',
                'employee-num-to' => 'required|string|min:1|max:5',
            ]);
        } else {
            $request->validate([
                'states' => 'required|array',
                'status' => 'required|array',
                'employee-num-from' => 'required|string|min:1|max:5',
                'employee-num-to' => 'required|string|min:1|max:5',
            ]);
        }

        if($type === "email-list") {
            return $this->exportService->buildExportEmailList($request);
        }

        if($type === "business-list") {
            return $this->exportService->buildExportBusinessList($request);
        }
    }
}
