<?php

namespace Bentericksen\Employees;

use DB;
use App\History AS HistoryModel;

class History
{

    private $userId;

    public function __construct($userId)
    {
        $this->userId = $userId;
    }

    public function getAll()
    {
        $results = DB::table('history')
            ->where('user_id', $this->userId)->orderBy('id', 'desc')
            ->get();
        return $results;
    }

    public function save($values)
    {
        HistoryModel::create($values);
    }
}