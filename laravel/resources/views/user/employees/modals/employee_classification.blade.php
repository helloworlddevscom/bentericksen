<!-- Classification Modal Needed this inside the standard user/employee form-->
<div class="modal fade" id="modalClassifications" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="edit edit_drivers_license">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                    </button>
                    <h4 class="modal-title text-center" id="modalLabel">Edit Employee Classification</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="row">
                                    <label for="" class="col-md-3 col-md-offset-2 control-label">Current:</label>
                                    <div class="col-md-6 padding-top capitalize">
                                        {{ !empty($employee->classification) ? $employee->classification->name : 'n/a' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="row">
                                    <label for="" class="col-md-3 col-md-offset-2 control-label">New:</label>
                                    <div class="col-md-3">
                                        <select class="form-control" id="employee_classification" name="employee_classification[new_classification_id]">
                                            <option value="">- Select One -</option>
                                            @foreach($classifications as $classification)
                                                <option value="{{ $classification->id }}">{{ $classification->name }}</option>
                                            @endforeach
                                            <option value="other">- Other -</option>
                                        </select>
                                        <input type="text" class="form-control hidden" id="employee_classification_name" name="employee_classification[new_classification_name]">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="row">
                                    <label for="employee_classification_date" class="col-md-3 col-md-offset-2 control-label">Effective Date:</label>
                                    <div class="col-md-3">
                                        <div class="input-group">
                                            {!! Form::text('employee_classification[classification_date]', null, ['id' => 'employee_classification_date', 'class' => 'form-control date-picker', 'placeholder' => 'mm/dd/yyyy']) !!}
                                            <span class="input-group-addon"><label for="employee_classification_date"><i class="fa fa-calendar"></i></label></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer text-center">
                    {!! Form::button('CLOSE', ['class' => 'btn btn-default btn-primary', 'data-dismiss' => 'modal']) !!}
                    @if(!empty($employee))
                        {!! Form::button('SAVE', ['type' => 'submit', 'class' => 'btn btn-default btn-primary', 'name' => 'action', 'value' => 'employee_classification']) !!}
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Classification Modal -->
