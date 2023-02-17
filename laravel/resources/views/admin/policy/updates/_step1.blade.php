@extends('admin.wrap')

@section('content')
    <div id="main_body">
        <div class="container" id="main">
            <div class="row main_wrap">

                <div class="col-md-12 heading">
                    <h3>Policy Update</h3>
                </div>

                <style>
                    .form-horizontal .control-label {
                        text-align: left !important;
                    }
                </style>

                <form class="form-horizontal" method="post" action="/admin/policies/updates/create?step={{ $next ?? 2 }}&id={{ $id }}">
                    {{ csrf_field() }}
                    <div class="col-md-12 content">
                        <div class="row">
                            <div class="col-md-10 col-md-offset-1 policies_content">
                                <div class="row">
                                    <div class="col-md-8 col-md-offset-2 heading">
                                        <h4>Inactive Clients</h4>
                                    </div>
                                    <div class="col-md-9 col-md-offset-1 sub_content">
                                        <div class="form-group">
                                            <div class="col-md-3  col-md-offset-2">
                                                <input type="text" class="form-control" id="client_inactive_years" name="inactive_clients" value="{{ $updates->inactive_clients ?? "0" }}" required>
                                            </div>
                                            <label for="client_inactive_years" class="col-md-3 control-label">Years</label>
                                            <small>Enter zero (0) if inactive clients are not to be notified about this update.</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8 col-md-offset-2 heading">
                                        <h4>Policies</h4>
                                    </div>
                                    <div class="col-md-9 col-md-offset-1 sub_content">
                                        @foreach( $policies as $policy )
                                            <div class="form-group">
                                                <div class="col-md-1  col-md-offset-2">
                                                    <input type="checkbox" class="form-control check-box pui" id="policy_{{ $policy->template_id }}" @if( isset($policy->checked) && $policy->checked == 1) checked @endif name="policies[{{ $policy->template_id }}]" value="{{ $policy->id }}">
                                                </div>
                                                <label for="policy_{{ $policy->template_id }}" class="col-md-8 control-label capitalize">{{ $policy->admin_name }} <span style="font-weight: normal;">(Effective Date: {{ $policy->effective_date->format('m/d/Y') }})</span>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="row text-center buttons">
                                    <button type="submit" class="btn btn-defult btn-primary btn-sm btn-step">NEXT</button>
                                </div>
                                <div class="col-md-6 col-md-offset-3">
                                    <div id="progressbar"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('foot')
    @parent
@stop
