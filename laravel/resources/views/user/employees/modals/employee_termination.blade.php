<div class="modal fade" id="modalTerminateAccountWarningEnable" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="edit edit_drivers_license">
                <div class="modal-header">
                    <h4 class="modal-title text-center" id="modalLabel">Terminate Employee</h4>
                </div>
                <div class="modal-body">
                    <div class="col-md-12">
                        <div class="row">
                            <h3 class="col-md-12 text-center">{{ $employee->full_name }}</h3>
                            <div class="col-md-12 even">
                                <div class="form-group">
                                    <div class="row">
                                        <label for="" class="col-md-4 control-label">Termination Date:</label>
                                        <div class="col-md-7">
                                            <div class="input-group">
                                                <input type="text" id="termination_date" class="form-control date-picker" name="employment_termination[date]" placeholder="mm/dd/yyyy">
                                                <label for="termination_date" class="input-group-addon"><i class="fa fa-calendar"></i></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 odd">
                                <div class="form-group">
                                    <div class="row">
                                        <label for="" class="col-md-4 control-label">Type of Termination:</label>
                                        <div class="col-md-7">
                                            <select class="form-control modal-select" name="employment_termination[type]">
                                                <option value="resignation">Resignation</option>
                                                <option value="discharge">Discharge</option>
                                                <option value="layoff">Layoff</option>
                                                <option value="job_abandonment">Job Abandonment</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 even">
                                <div class="form-group">
                                    <div class="row">
                                        <label for="" class="col-md-4 control-label">Eligible for Rehire:</label>
                                        <div class="col-md-4">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <input type="radio" class="" id="eligible_rehire_yes" value="yes" name="employment_termination[rehire]" checked>
                                                    <label for="eligible_rehire_yes">&nbsp;Yes</label>
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="radio" class="" id="eligible_rehire_no" value="no" name="employment_termination[rehire]">
                                                    <label for="eligible_rehire_no">&nbsp;No</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 odd">
                                <div class="form-group">
                                    <div class="row">
                                        <label for="" class="col-md-4 control-label">Reason:</label>
                                        <div class="col-md-7">
                                            <textarea class="form-control" name="employment_termination[reason]"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer text-center">
                    {!! Form::button('CANCEL', ['class' => 'btn btn-default btn-primary', 'data-dismiss' => 'modal'])  !!}
                    {!! Form::button('TERMINATE', ['type' => 'submit', 'class' => 'btn btn-default btn-primary', 'name' => 'action', 'value' => 'employment_termination']) !!}
                </div>
            </div>
        </div>
    </div>
</div>