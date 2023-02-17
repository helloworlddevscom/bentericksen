@extends('admin.wrap')

@section('content')

    <div id="main_body">
        <div class="container" id="main">
            <div class="row main_wrap">

                <div class="col-md-12 heading">
                    <h3>Policy list</h3>
                </div>
                <div class="col-md-12 content">
                    <div class="col-md-4">
                        <div class="col-md-12 sub_heading">
                            <h4>Search Policy By</h4>
                        </div>
                        <div class="col-md-12 sub_content">
                            <div class="row">
                                <label for="col_1_search" class="col-md-6 policy_search">Policy Name:</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control policy_search_inputs" id="col_1_search" name="col_1_search" placeholder="Policy Name">
                                </div>
                            </div>
                            <div class="row">
                                <label for="col_3_select_search" class="col-md-6 policy_search">Category:</label>
                                <div class="col-md-6">
                                    <select class="form-control policy_search_inputs" id="col_3_select_search" name="col_3_select_search">
                                        <option value=""> - Select One -</option>
                                        @foreach($categories as $key => $category)
                                            <option value="{{ $key }}">{{ $category }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <label for="col_4_select_search" class="col-md-6 policy_search">Required?:</label>
                                <div class="col-md-6">
                                    <select class="form-control policy_search_inputs" id="col_4_select_search" name="col_4_select_search">
                                        <option value="">All</option>
                                        <optgroup label="General:">
                                            <option value="required">Required</option>
                                            <option value="optional">Optional</option>
                                        </optgroup>
                                        <optgroup label="Industry Specific:">
                                            <optgroup label="Dental">
                                                <option value="drequired">Required</option>
                                                <option value="doptional">Optional</option>
                                            </optgroup>
                                            <optgroup label="Commercial">
                                                <option value="crequired">Required</option>
                                                <option value="coptional">Optional</option>
                                            </optgroup>
                                            <optgroup label="Veterinarian">
                                                <option value="vrequired">Required</option>
                                                <option value="voptional">Optional</option>
                                            </optgroup>
                                            <optgroup label="Medical">
                                                <option value="mrequired">Required</option>
                                                <option value="moptional">Optional</option>
                                            </optgroup>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <label for="col_5_select_search" class="col-md-6 policy_search">State:</label>
                                <div class="col-md-6">
                                    <select class="form-control policy_search_inputs" id="col_5_select_search" name="col_5_select_search">
                                        @foreach($states as $key => $state)
                                            <option value="@if($key != "ALL"){{$key}} @endif">{{ $state }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <label for="picker1" class="col-md-6 policy_search">Number of Employees:</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control number-box" id="picker1" name="picker1">
                                    - to -
                                    <input type="text" class="form-control number-box" id="picker2" name="picker2">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="row text-center">
                            <div class="pull-right">
                                <a href="/admin/policies/create"><img src="/assets/images/add.png" class="add" height="15" width="15" alt="Add New User Login"> Add New Policy</a>
                            </div>
                            <h3>Policy List</h3>
                        </div>

                        <div class="row sub_content">
                            <div class="col-md-12 table-responsive">
                                <table class="table table-striped" id="policy_table">
                                    <thead>
                                    <tr>
                                        <th>
                                            <span>Policy Name</span>
                                        </th>
                                        <th>
                                            <span>Effective Date</span>
                                        </th>
                                        <th>
                                            <span>Category</span>
                                        </th>
                                        <th>
                                            <span>Required</span>
                                        </th>
                                        <th>
                                            <span>State</span>
                                        </th>
                                        <th>
                                            <span>Min Number of Employees</span>
                                        </th>
                                        <th>
                                            <span>Max Number of Employees</span>
                                        </th>
                                        <th>
                                            <span>Benefit Type</span>
                                        </th>
                                        <th class="bg_none"></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($policies as $policy)
                                        <tr class="{{ $policy->status }}">
                                            <td>{{ $policy->admin_name }}</td>
                                            <td>{{ $policy->effective_date->format('m/d/Y') }}</td>
                                            <td>{{ $policy->category_id }}</td>
                                            <td>{{ implode(', ', $policy->requirement) }}</td>
                                            <td>{{ $policy->state }}</td>
                                            <td>{{ $policy->min_employee }}</td>
                                            <td>{{ $policy->max_employee }}</td>
                                            <td>{{ $policy->benefit_type }}</td>
                                            <td class="text-right">
                                                <a href="/admin/policies/{{ $policy->id }}/edit" class="btn btn-default btn-xs ">EDIT</a>
                                                <a href="/admin/policies/{{$policy->id}}/changeStatus" class="status-update btn btn-default btn-xs">@if($policy->status == "enabled")DISABLE @else ENABLE @endif</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <td class="text-right" colspan="9">
                                            {!! $help->button('a1301') !!}
                                            <a href="/admin/policies/sort" class="btn btn-default btn-primary btn-xs">SORT</a>
                                        </td>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('foot')
    @parent
    <script>
       

    </script>
    
@stop