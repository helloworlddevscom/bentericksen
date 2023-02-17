<?php

namespace Bentericksen\Exports;

use App\Business;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Bentericksen\Settings\States;
use Carbon\Carbon;

class ExportQueries
{
    private $stateList;

    private const STATUS_LIST = ['active', 'cancelled', 'expired', 'renewed'];

    private const ROLE_LIST = [2, 3, 4]; // 2 = owner, 3 = manager, 4 = consultant

    public function __construct()
    {
        $this->stateList = new States();
    }
    /**
     * @param $request
     * @return \Illuminate\Support\Collection
     */
    public function queryEmailList($request)
    {
        $params = $this->prepareQueryParams($request);
        $asaFrom = Carbon::parse($params['asa-date-from'])->toDateTimeString();
        $asaTo = Carbon::parse($params['asa-date-to'])->toDateTimeString();
        $typeQuery = is_null($params['business_type']) ? 'where' : 'whereIn';
        $subTypeQuery = is_null($params['business_sub_type']) ? 'where' : 'whereIn';
        $typeParam = is_null($params['business_type']) ? null : 'business.type';
        $subTypeParam = is_null($params['business_sub_type']) ? null : 'business.subtype';

        $primary = DB::table('business')
            ->select(
                'business.id',
                'roles.name as Role Name',
                DB::raw('concat(users.first_name, \' \', users.last_name) as Name'),
                'users.email as Email',
                'business.state as State',
                'business_asas.expiration as ASA Expiration Date',
                'business.status as Business Status'
            )
            ->leftJoin('business_asas', 'business.id', '=', 'business_asas.business_id')
            ->join('users', 'business.id', '=', 'users.business_id')
            ->join('role_user', 'users.id', '=', 'role_user.user_id')
            ->join('roles', 'role_user.role_id', '=', 'roles.id')
            ->whereIn('business.state', $params['states'])
            ->whereIn('business.status', $params['status'])
            ->whereIn('roles.id', self::ROLE_LIST)
            ->$typeQuery($typeParam, $params['business_type'])
            ->$subTypeQuery($subTypeParam, $params['business_sub_type'])
            ->where('business.additional_employees', '>=', $params['employee-from'])
            ->where('business.additional_employees', '<=', $params['employee-to'])
            ->where('business_asas.expiration', '>=', $asaFrom)
            ->where('business_asas.expiration', '<=', $asaTo)
            ->where('business.do_not_contact', '=', 0)
            ->where('users.terminated', null)
            ->orderBy('business.id')->get();
        
        $secondary1 = DB::table('business')
            ->select(
                'business.id',
                DB::raw('concat(\'secondary\') as \'Role Name\''),
                DB::raw('concat(business.secondary_1_first_name, \' \', business.secondary_1_last_name) as Name'),
                'business.secondary_1_email as Email',
                'business.state as State',
                'business_asas.expiration as ASA Expiration Date',
                'business.status as Business Status'
            )
            ->leftJoin('business_asas', 'business.id', '=', 'business_asas.business_id')
            ->whereIn('business.state', $params['states'])
            ->whereIn('business.status', $params['status'])
            ->$typeQuery($typeParam, $params['business_type'])
            ->$subTypeQuery($subTypeParam, $params['business_sub_type'])
            ->where('business.additional_employees', '>=', $params['employee-from'])
            ->where('business.additional_employees', '<=', $params['employee-to'])
            ->where('business_asas.expiration', '>=', $asaFrom)
            ->where('business_asas.expiration', '<=', $asaTo)
            ->where('business.do_not_contact', '=', 0)
            ->whereNotNull('business.secondary_1_email')
            ->where('business.secondary_1_email', '<>', '')
            ->orderBy('business.id')->get();
        
        $secondary2 = DB::table('business')
          ->select(
              'business.id',
              DB::raw('concat(\'secondary\') as \'Role Name\''),
              DB::raw('concat(business.secondary_2_first_name, \' \', business.secondary_2_last_name) as Name'),
              'business.secondary_2_email as Email',
              'business.state as State',
              'business_asas.expiration as ASA Expiration Date',
              'business.status as Business Status'
          )
          ->leftJoin('business_asas', 'business.id', '=', 'business_asas.business_id')
          ->whereIn('business.state', $params['states'])
          ->whereIn('business.status', $params['status'])
          ->$typeQuery($typeParam, $params['business_type'])
          ->$subTypeQuery($subTypeParam, $params['business_sub_type'])
          ->where('business.additional_employees', '>=', $params['employee-from'])
          ->where('business.additional_employees', '<=', $params['employee-to'])
          ->where('business_asas.expiration', '>=', $asaFrom)
          ->where('business_asas.expiration', '<=', $asaTo)
          ->where('business.do_not_contact', '=', 0)
          ->whereNotNull('business.secondary_2_email')
          ->where('business.secondary_2_email', '<>', '')
          ->orderBy('business.id')->get();
        
        return $primary->merge($secondary1)->merge($secondary2)->sortBy('id');
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function queryBusinessList(Request $request)
    {
        $params = $this->prepareQueryParams($request);
        $typeQuery = is_null($params['business_type']) ? 'where' : 'whereIn';
        $subTypeQuery = is_null($params['business_sub_type']) ? 'where' : 'whereIn';
        $typeParam = is_null($params['business_type']) ? null : 'business.type';
        $subTypeParam = is_null($params['business_sub_type']) ? null : 'business.subtype';

        return Business::select(
            [
                'business.*',
                'business_asas.expiration as asa_expiration',
                'business_asas.type as asa_type',
                'users.accepted_terms',
                'users.first_name as primary_first_name',
                'users.middle_name as primary_middle_name',
                'users.last_name as primary_last_name',
                'users.prefix as primary_prefix',
                'users.suffix as primary_suffix',
                'users.email as primary_email',
                'users.last_login as primary_last_login'
            ])
            ->leftJoin('business_asas', 'business.id', '=', 'business_asas.business_id')
            ->join('users', 'users.id', '=', 'business.primary_user_id')
            ->whereIn('business.state', $params['states'])
            ->whereIn('business.status', $params['status'])
            ->$typeQuery($typeParam, $params['business_type'])
            ->$subTypeQuery($subTypeParam, $params['business_sub_type'])
            ->where('business.additional_employees', '>=', $params['employee-from'])
            ->where('business.additional_employees', '<=', $params['employee-to'])->get();
    }

    /**
     * @param $request
     * @return array
     */
    private function prepareQueryParams($request): array
    {
        return [
            'employee-from' => $request->input('employee-num-from'),
            'employee-to' => $request->input('employee-num-to'),
            'asa-date-from' => $request->input('asa-date-from'),
            'asa-date-to' => $request->input('asa-date-to'),
            'states' => $request->input('states')[0] === 'all' ? array_keys($this->stateList->states()) : $request->input('states'),
            'status' => $request->input('status')[0] === 'all' ? self::STATUS_LIST : $request->input('status'),
            'business_type' => $request->input('business_type')[0] === 'all' ? null : $request->input('business_type'),
            'business_sub_type' => $request->input('business_sub_type')[0] === 'all' ? null : $request->input('business_sub_type'),
        ];
    }
}
