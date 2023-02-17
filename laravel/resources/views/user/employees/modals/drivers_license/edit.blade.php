<div class="row">
    {!! Form::hidden('drivers_license[id]', $employee->driversLicense->id) !!}
    <div class="col-md-12">
        <div class="form-group">
            <div class="row">
                <label for="expiration" class="col-md-4 col-md-offset-1 control-label">License Expiration Date:</label>
                <div class="col-md-3">
                    {!! Form::text('drivers_license[expiration]', ($employee->driversLicense->expiration ? $employee->driversLicense->expiration->format('m/d/Y') : null), ['id' => 'expiration', 'class' => 'form-control date-picker', 'placeholder' => 'mm/dd/yyyy']) !!}
                </div>
                <div class="col-md-1 padding-top text-center">
                    <label for="expiration"><i class="fa fa-calendar"></i></label>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            <div class="row">
                <label for="agent" class="col-md-4 col-md-offset-1 control-label">Insurance Carrier:</label>
                <div class="col-md-4">
                    {!! Form::text('drivers_license[agent]', $employee->driversLicense->agent, ['id' => 'agent', 'class' => 'form-control', 'placeholder' => 'Name']) !!}
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            <div class="row">
                <label for="agent_phone" class="col-md-4 col-md-offset-1 control-label">Carrier Phone Number:</label>
                <div class="col-md-4">
                    {!! Form::text('drivers_license[agent_phone]', $employee->driversLicense->agent_phone, ['id' => 'agent_phone', 'class' => 'form-control phone_number']) !!}
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            <div class="row">
                <label for="policy_number" class="col-md-4 col-md-offset-1 control-label">Insurance Policy Number:</label>
                <div class="col-md-4">
                    {!! Form::text('drivers_license[policy_number]', $employee->driversLicense->policy_number, ['id' => 'policy_number', 'class' => 'form-control']) !!}
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            <div class="row">
                <label for="policy_expiration" class="col-md-4 col-md-offset-1 control-label">Insurance Expiration Date:</label>
                <div class="col-md-3">
                    {!! Form::text('drivers_license[policy_expiration]', ($employee->driversLicense->policy_expiration ? $employee->driversLicense->policy_expiration->format('m/d/Y') : null), ['id' => 'policy_expiration', 'class' => 'form-control date-picker', 'placeholder' => 'mm/dd/yyyy']) !!}
                </div>
                <div class="col-md-1 padding-top text-center">
                    <label for="policy_expiration"><i class="fa fa-calendar"></i></label>
                </div>
            </div>
        </div>
    </div>
</div>