<div class="modal fade" id="modalEmergencyContact" tabindex="-1" role="dialog"
     aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="emergency_contact">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                    </button>
                    <h4 class="modal-title text-center" id="modalLabel">Emergency Contacts</h4>
                </div>
                <div class="modal-body">
                    @if (!empty($employee) && $employee->emergencyContacts->count() > 0)
                        @include('user.employees.modals.emergency_contacts.edit')
                    @else
                        @include('user.employees.modals.emergency_contacts.create')
                    @endif
                </div>
                <div class="modal-footer text-center">
                    {!! Form::button('CLOSE', ['class' => 'btn btn-default btn-primary', 'data-dismiss' => 'modal']) !!}
                    @if(!empty($employee))
                        {!! Form::button('SAVE', ['type' => 'submit', 'class' => 'btn btn-default btn-primary', 'name' => 'action', 'value' => 'emergency']) !!}
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>