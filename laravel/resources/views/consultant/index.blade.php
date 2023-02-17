@extends('consultant.wrap')

@section('content')
    @if($consultant->status == 'disabled')
        <div style="width: 100%; height: 710px; background-color: #fff;">
            <div class="container" id="main">
                <div class="row main_wrap">
                    <div class="col-md-12 content">
                        Your account has been disabled, please contact your administrator for more information.
                    </div>
                </div>
            </div>
        </div>
    @else
        {{--<div>--}}
            {{--<div id="main_body">--}}
                {{--<div class="container" id="main">--}}

                        <div class="col-md-12 heading">
                            <h3>Consultant Administration</h3>
                        </div>
                        <div class="col-md-12 content">
                            <div class="table-responsive">
                                <table class="table table-striped" id="consultant_table">
                                    <thead>
                                    <tr>
                                        <th class="form-group">
                                            <input type="text" class="form-control" id="col_1_search"
                                                   placeholder="Company Name">
                                        </th>
                                        <th class="form-group">
                                            <input type="text" class="form-control" id="col_2_search"
                                                   placeholder="Client Name">
                                        </th>
                                        <th class="form-group">
                                        </th>
                                        <th class="form-group">
                                            <select class="form-control" id="col_4_select_search">
                                                <option value="">All</option>
                                                <option value="Basic">Basic</option>
                                                <option value="limited">limited</option>
                                                <option value="Comprehensive">Comprehensive</option>
                                            </select>
                                        </th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                    <tr>
                                        <th>
                                            <span>Company Name</span>
                                        </th>
                                        <th>
                                            <span>Client Name</span>
                                        </th>
                                        <th>
                                            <span>State</span>
                                        </th>
                                        <th>
                                            <span>ASA</span>
                                        </th>
                                        <th>
                                            <span>Expiration Date</span>
                                        </th>
                                        <th class="bg_none">
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($businesses AS $business)
                                        <tr>
                                            <td>{{ $business->name }}</td>
                                            <td>{{ $business->primary->prefix }} {{ $business->primary->first_name }} {{ $business->primary->last_name }}</td>
                                            <td>{{ $business->state }}</td>
                                            <td>{{ $business->asa ? ucfirst($business->asa->type) : '' }}</td>
                                            <td>{{ $business->asa ? date('m/d/Y', strtotime($business->asa->expiration)) : '' }}</td>
                                            <td>
                                                @if($business->active_business)
                                                    <a href="/consultant/business/{{ $business->id }}/view-as"
                                                       class="btn btn-default btn-xs">VIEW AS</a>
                                                @else
                                                    <a href="#" class="btn btn-default btn-xs" data-toggle="modal" data-dismiss="modal" data-target="#modalInactiveBusinessReject">VIEW AS</a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
    @endif
@stop

@section('foot')
    @parent

    <script>
        var table = $("#consultant_table").DataTable({
            "bStateSave": false,
            "orderClasses": false
        });

    </script>
    <script src="/assets/admin/tables.js"></script>
@stop
