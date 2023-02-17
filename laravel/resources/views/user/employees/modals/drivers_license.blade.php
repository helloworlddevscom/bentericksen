<div class="modal fade" id="modalDriverInfo" tabindex="-1" role="dialog"
     aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="emergency_contact">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                    </button>
                    <h4 class="modal-title text-center" id="modalLabel">Driver Information</h4>
                </div>
                <div class="modal-body">
                    @if (!empty($employee) && $employee->driversLicense)
                        @include('user.employees.modals.drivers_license.edit')
                    @else
                        @include('user.employees.modals.drivers_license.create')
                    @endif
                </div>
                <div class="modal-footer text-center">
                    {!! Form::button('CLOSE', ['class' => 'btn btn-default btn-primary', 'data-dismiss' => 'modal']) !!}
                    @if(!empty($employee))
                        {!! Form::button('SAVE', ['type' => 'submit', 'class' => 'btn btn-default btn-primary', 'name' => 'action', 'value' => 'drivers_license']) !!}
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>