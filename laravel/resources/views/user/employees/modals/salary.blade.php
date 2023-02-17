<div class="modal fade" id="modalSalary" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="edit edit_status">
                <div class="edit edit_salary">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                        </button>
                        <h4 class="modal-title text-center" id="modalLabel">Edit Salary</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    @if(!empty($employee) && $employee->salary)
                                        <div class="row">
                                            <label for="" class="col-md-3 col-md-offset-2 control-label">Current:</label>
                                            <div class="col-md-6 padding-top">
                                                {!! Form::hidden('salary[current_salary]', $employee->salary->salary) !!}
                                                {!! Form::hidden('salary[current_rate]', $employee->salary->rate) !!}
                                                <strong>${{ number_format($employee->salary->salary, 2, '.', ',') }} per {{ $employee->salary->rate }}</strong>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="row">
                                        <label for="employee_salary" class="col-md-3 col-md-offset-2 control-label">New:</label>
                                        <div class="col-md-5">
                                            <div class="row">
                                                <div class="col-md-6 no-padding-right">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">$</span>
                                                        {!! Form::text('salary[salary]', null, ['id' => 'employee_salary', 'class' => 'input-money form-control', 'placeholder' => '00.00']) !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    {!! Form::select('salary[rate]', ['hour' => 'Per Hour', 'day' => 'Per Day', 'month' => 'Per Month', 'year' => 'Per Year'], (!empty($employee) && $employee->salary ? $employee->salary->rate : null), ['id' => 'employee_salary_rate', 'class' => 'form-control']) !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="row">
                                        <label for="employee_salary_effective_date" class="col-md-3 col-md-offset-2 control-label">Effective Date:</label>
                                        <div class="col-md-5">
                                            <div class="row">
                                                <div class="col-md-6 no-padding-right">
                                                    <div class="input-group">
                                                        {!! Form::text('salary[effective_at]', null, ['id' => 'employee_salary_effective_date', 'class' => 'form-control date-picker', 'placeholder' => 'mm/dd/yyyy']) !!}
                                                        <span class="input-group-addon"><label for="employee_salary_effective_date"><i class="fa fa-calendar"></i></label></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="row">
                                        <label for="employee_salary_change_reason" class="col-md-3 col-md-offset-2 control-label">Reason:</label>
                                        <div class="col-md-5">
                                            {!! Form::textarea('salary[reason]', null, ['id' => 'employee_salary_change_reason', 'class' => 'form-control']) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer text-center">
                        {!! Form::button('CLOSE', ['class' => 'btn btn-default btn-primary', 'data-dismiss' => 'modal']) !!}
                        @if(!empty($employee))
                            {!! Form::button('SAVE', ['type' => 'submit', 'class' => 'btn btn-default btn-primary', 'name' => 'action', 'value' => 'salary']) !!}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>