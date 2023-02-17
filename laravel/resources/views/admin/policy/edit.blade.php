@extends('admin.wrap')

@section('content')
    <div id="main_body">
        <div class="container" id="main">
            <div class="row main_wrap">
                {!! Form::open(['route' => ['admin.policies.update', $policy->id], 'class' => 'form-horizontal', 'method' => 'put']) !!}
                <div class="col-md-12 heading">
                    <h3>New Policy / General</h3>
                </div>
                <div class="col-md-12 content">
                    <div class="col-md-10 col-md-offset-1 policies_content">
                        <div class="form-group">
                            <label for="policy_admin_name" class="col-md-4 control-label">Policy Admin Name:</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" id="policy_admin_name" name="admin_name" value="{{ $policy->admin_name }}" placeholder="Policy Admin Name">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="policy_manual_name" class="col-md-4 control-label">Policy Manual Name:</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" id="policy_manual_name" name="manual_name" value="{{ $policy->manual_name }}" placeholder="Policy Manual Name">
                            </div>
                        </div>
                        <input type="hidden" name="benefit_type" value="none">
                        <div class="form-group">
                            <label for="category" class="col-md-4 control-label">Category:</label>
                            <div class="col-md-4">
                                {!! Form::select('category_id', $categories, $policy->category_id, ['id' => 'policy_category', 'class' => 'form-control']) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="employees" class="col-md-4 control-label">Employees:</label>
                            <div class="col-md-4">
                                <input type="text" size="4" class="form-control date-box" id="employee_range_min" name="employee_range_min" value="{{ $policy->min_employee }}">
                                <span> - to - </span>
                                <input type="text" size="4" class="form-control date-box" id="employee_range_max" name="employee_range_max" value="{{ $policy->max_employee }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="policy_benefit_state" class="col-md-4 control-label">State:</label>
                            <div class="col-md-4">
                                {!! Form::select('benefit_state[]', $states, $policy->state, ['multiple'=>'multiple', 'id' => 'policy_benefit_state', 'class' => 'form-control']) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="policy_effective_date" class="col-md-4 control-label">Effective Date:</label>
                            <div class="col-md-3">
                                <div class="input-group">
                                    <input type="text"
                                           class="form-control date-picker"
                                           id="policy_effective_date"
                                           name="policy_effective_date"
                                           placeholder="mm/dd/yyyy"
                                           value="{{ $policy->effective_date->format('m/d/Y')}}"
                                           autocomplete="off">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="include_in_benefits_summary_no" class="col-md-4 control-label">Include In Benefits Summary:</label>
                            <div class="col-md-6">
                                <div class="input-radio-group">
                                    <input type="radio" class="include_in_benefits_summary" id="include_in_benefits_summary_no" name="include_in_benefits_summary" value="0"@if (!$policy->include_in_benefits_summary) checked @endif><label class="control-label" for="include_in_benefits_summary_no">NO</label>
                                </div>
                                <div class="input-radio-group">
                                    <input type="radio" class="include_in_benefits_summary" id="include_in_benefits_summary_yes" name="include_in_benefits_summary" value="1"@if ($policy->include_in_benefits_summary) checked @endif><label class="control-label" for="include_in_benefits_summary_yes">YES</label>&nbsp;&nbsp;
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 text-center">
                            <h4>Default Policy</h4>
                        </div>

                        <div class="form-group">
                            <label for="policy_required_default" class="col-md-4 control-label">Required:</label>
                            <div class="col-md-3">
                                {!! Form::select('requirement[]', $options, $policy->requirement, ['multiple'=>'multiple', 'id' => 'policy_required_default', 'class' => 'form-control']) !!}
                            </div>
                        </div>

                        <div class="form-group" id="policy_default">
                            <div class="col-md-10 col-md-offset-1">
                                <textarea class="form-control ckeditor" name="content">{{ $policy->content }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 text-center buttons">
                        <a href="/admin/policies/{{ $policy->id }}/changeStatus" class="btn btn-xs btn-primary">
                            @if( $policy->status == 'enabled' )
                                DISABLE
                            @else
                                ENABLE
                            @endif
                        </a>
                        <a href="/admin/policies" class="btn btn-default btn-xs ">CANCEL</a>
                        <button type="submit" class="btn btn-default btn-xs btn-primary">SAVE</button>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@stop
