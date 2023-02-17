<?php

namespace Bentericksen\Exports;

use App\Business;
use App\User;
use Illuminate\Http\Request;
use Response;
use Illuminate\Support\Facades\Redirect;

/**
 * Class ExportService
 * @package Bentericksen\Exports
 *
 * The Class is for building an export query
 *
 */
class ExportService {
    protected $businesses;

    protected const ALL_ACTIVE_COUNT = 10;

    protected const EMPTY_USER_OBJECT = [
        'prefix' => '',
        'first_name' => '',
        'middle_name' => '',
        'last_name' => '',
        'suffix' => '',
        'email' => ''
    ];

    public function __construct()
    {
    }

    /**
     * For admin `Export Client List` button
     *
     * @param Request $request
     * @return mixed
     */
    function buildExportBusinessList(Request $request) {

        $queries = new ExportQueries();
        $list = $queries->queryBusinessList($request);

        $exportBusiness = $list->toArray();

        if(empty($exportBusiness)) {
            return Redirect::back()->withErrors(['msg' => 'No results found']);
        }

        foreach ($exportBusiness as $k => &$row) {
            $users = $this->getUsersByBusinessId($row['id'], 'owner');

            $row = $this->addColumnData($row, [
                'owner_name' => 'prefix first_name middle_name last_name suffix',
                'owner_email' => 'email'
            ], $users, self::ALL_ACTIVE_COUNT);

            $users = $this->getUsersByBusinessId($row['id'], 'manager');

            $row = $this->addColumnData($row, [
                'manager_name' => 'prefix first_name middle_name last_name suffix',
                'manager_email' => 'email'
            ], $users, self::ALL_ACTIVE_COUNT);
        }

        return $this->generateCSV($exportBusiness, 'businessList');
    }

    protected function addColumnData($baseData, $columns, $users, $count): array
    {
        for ($i = 0; $i < $count; $i++) {
            $countColumn = $this->appendColumnbyCount($columns, $users, $i);
            $baseData = array_merge($baseData, $countColumn);
        }

        return $baseData;
    }

    protected function appendColumnbyCount($columns, array $users, int $current): array
    {
        foreach ($columns as $k => $column) {
            unset($columns[$k]);
            $cell = $current + 1;
            $user = $users[$current] ?? self::EMPTY_USER_OBJECT;

            $columns["{$k}_{$cell}"] = $this->formatOutput($column, $user);
        }

        return $columns;
    }

    protected function getUsersByBusinessId($id, string $type): array
    {
        return User::select([
            'prefix',
            'first_name',
            'middle_name',
            'last_name',
            'suffix',
            'email'
        ])
            ->where('business_id', $id)
            ->where('terminated', NULL)
            ->$type()
            ->limit(self::ALL_ACTIVE_COUNT)
            ->get()
            ->makeHidden(['main_role', 'roles'])
            ->toArray();
    }

    protected function formatOutput(string $template, array $data): string
    {
        foreach($data as $key => &$value) {
            $value = is_null($value) ? "" : $value;
        }
        $output = str_replace(array_keys($data), array_filter(array_values($data), 'is_string'), $template);
        $output = trim(str_replace('  ', ' ', $output));
        return $output;
    }

    public function buildExportEmailList($request)
    {
        $queries = new ExportQueries();
        $list = $queries->queryEmailList($request);
        $list = json_decode(json_encode($list), true);

        if(empty($list)) {
            return Redirect::back()->withErrors(['msg' => 'No results found']);
        }

        foreach($list as &$item) {
            unset($item['id']);
        }

        return $this->generateCSV($list, 'emailList');
    }

    public function generateCSV($list, $type) {

        $headers = [
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Content-type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename= ' . $type . '_'.time().'.csv',
            'Expires' => '0',
            'Pragma' => 'public',
        ];

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
}
