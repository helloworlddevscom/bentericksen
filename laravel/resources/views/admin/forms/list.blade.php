@extends('admin.wrap')

@section('content')

    <div id="main_body">
        <div class="container" id="main">
            <div class="row main_wrap">

                <div class="col-md-12 heading">
                    <h3>Forms list</h3>
                </div>
                <div class="col-md-12 content">
                    <div class="col-md-12">
                        <div class="row text-center">
                            <div class="pull-right">
                                <a href="/admin/forms/create">
                                    <img src="/assets/images/add.png" class="add" height="15" width="15" alt="Add New User Login"> Add New Form</a>
                                <span style="width:5px;">&nbsp;</span>
                                <a href="/admin/categories/create">
                                    <img src="/assets/images/add.png" class="add" height="15" width="15" alt="Add New User Login"> Add New Category</a>
                            </div>
                            <h3>Forms List</h3>
                        </div>

                        <div class="row sub_content">
                            <div class="col-md-12 table-responsive">
                                <table class="table table-striped" id="policy_table">
                                    <thead>
                                    <tr>
                                        <th>
                                            <span>Form Name</span>
                                        </th>
                                        <th>
                                            <span>Category</span>
                                        </th>
                                        <th>
                                            <span>Regular File / Confidential File</span>
                                        </th>
                                        <th class="bg_none"></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($forms as $policy)
                                        <tr @if($policy->status == 'disabled') class="disabled" @endif>
                                            <td>{{ $policy->name }}</td>
                                            <td>{{ $policy->category_name }}</td>
                                            <td>{{ ucfirst($policy->type) }}</td>
                                            <td class="text-right">
                                                <a href="/admin/forms/{{ $policy->id }}/edit" class="btn btn-default btn-xs ">EDIT</a>
                                                <a href="/admin/forms/{{$policy->id}}/changeStatus" class="status-update btn btn-default btn-xs">@if($policy->status == "active" or $policy->status === "enabled") DISABLE @else ENABLE @endif</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @stop
