<?php

use App\BusinessPermission;
use App\Classification;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InsertMissingClassificationAndPermissionsEntries extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $permissions = $this->preparePermissionsData();
        $classifications = $this->prepareClassificationsData();

        if (count($permissions) > 0) {
            foreach (array_chunk($permissions, 300) as $chunk) {
                DB::table('business_permissions')->insert($chunk);
            }
        }

        if (count($classifications) > 0) {
            foreach (array_chunk($classifications, 300) as $chunk) {
                DB::table('classifications')->insert($chunk);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // do nothing
    }

    protected function preparePermissionsData(): array
    {
        $permissions = new BusinessPermission;
        $permissionsArray = array_fill_keys($permissions->getColumns(), 1);

        // getting every business id that already has a permissions record.
        $ids = DB::table('business_permissions')->groupBy('business_id')->pluck('business_id');

        // getting every business id that is missing a permissions record.
        $businessIds = DB::table('business')->whereNotIn('id', $ids)->pluck('id');
        $arr = [];

        if (count($businessIds) > 0) {
            foreach ($businessIds as $id) {
                $row = array_merge($permissionsArray, ['business_id' => $id]);
                array_push($arr, $row);
            }
        }

        return $arr;
    }

    protected function prepareClassificationsData(): array
    {
        $arr = [];
        $classificationModel = new Classification();

        // getting every business id that already has classification records.
        $ids = DB::table('classifications')->groupBy('business_id')->pluck('business_id');

        // getting every business id that is missing a classification record.
        $businessIds = DB::table('business')->whereNotIn('id', $ids)->pluck('id');

        if (count($businessIds) > 0) {
            foreach ($businessIds as $id) {
                $classifications = $classificationModel->getDefaultClassifications();
                $row = array_map(function ($b) use ($id) {
                    $b['business_id'] = $id;

                    return $b;
                }, $classifications);
                $arr = array_merge($arr, $row);
            }
        }

        return $arr;
    }
}
