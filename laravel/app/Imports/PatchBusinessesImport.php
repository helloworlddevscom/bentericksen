<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;

class PatchBusinessesImport implements ToModel
{
    // The EmployeesController is currently using the ToArray Concern as:
    //   Excel::toArray(new EmployeesImport, ...)
    // Changing this class to implement ToArray didn't seem to do anything :/

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return null;
    }
}
