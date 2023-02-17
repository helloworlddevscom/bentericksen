<div class="modal fade" id="modalLicensureCertifications" tabindex="-1" role="dialog"
     aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="emergency_contact">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title text-center" id="modalLabel">Licensure/Certifications</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th>
                                            <label class="control-label">Type of License</label>
                                        </th>
                                        <th>
                                            <label class="control-label">Expiration Date</label>
                                        </th>
                                        <th class="bg_none">
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody class="empLicenseCertification">
                                    @if(!empty($employee) && $employee->licensures)
                                        @foreach ($employee->licensures as $licensure)
                                            <tr class="cert-row" data-id="{{ $licensure->id }}">
                                                <td><p>{{ $licensure->name }}</p></td>
                                                <td>
                                                    <div class="input-group">
                                                        <input type="text" id="licensure-{{ $licensure->id }}" name="licensure[update][{{ $licensure->id }}]" class="form-control date-picker date-box" value="{{ date('m/d/Y', strtotime($licensure->pivot->expiration)) }}" placeholder="mm/dd/yyyy" disabled>
                                                        <span class="input-group-addon"><label for="licensure-{{ $licensure->id }}"><i class="fa fa-calendar"></i></label></span>
                                                    </div>
                                                </td>
                                                <td class="text-right">
                                                    <button type="button" class="btn btn-default btn-primary btn-xs edit-cert">EDIT</button>
                                                    <button type="button" class="btn btn-danger btn-primary btn-xs remove-cert">DELETE</button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                </table>
                                <a class="btn add-cert" data-group="addLicenseCertification"><img src="/assets/images/add.png" class="add" height="15" width="15" alt="Add">Add License / Certification</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer text-center">
                    {!! Form::button('CLOSE', ['class' => 'btn btn-default btn-primary', 'data-dismiss' => 'modal']) !!}
                    @if(!empty($employee))
                        {!! Form::button('SAVE', ['type' => 'submit', 'class' => 'btn btn-default btn-primary', 'name' => 'action', 'value' => 'licensure']) !!}
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>