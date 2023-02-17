<?php

use Illuminate\Support\Facades\DB;

trait MigrationHelper
{
    public function getDbTableColumnConstraints($table, $column)
    {
        $sql = "SELECT
                TABLE_NAME,
                COLUMN_NAME,
                CONSTRAINT_NAME,
                REFERENCED_TABLE_NAME,
                REFERENCED_COLUMN_NAME
            FROM
                INFORMATION_SCHEMA.KEY_COLUMN_USAGE
            WHERE
                REFERENCED_TABLE_NAME = '".$table."'
                AND REFERENCED_COLUMN_NAME = '".$column."'";

        return DB::select(DB::raw($sql));
    }
}
