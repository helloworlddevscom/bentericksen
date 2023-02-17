<ul class="nav nav-tabs" role="tablist" id="authorization_tab">
    <li class="active last">
        <a href="#system_access" role="tab" data-toggle="tab">System Access</a>
        <span class="caret emp-caret"></span>
    </li>
</ul>
<div class="tab-content">
    <div class="tab-pane fade in active" id="system_access">
        <div class="col-md-12 text-center">
            <h4>System Access</h4>
        </div>
        <div class="col-md-12">
            <div class="row">
                <div class="form-group">
                    <label for="access_can_access_the_system" class="col-md-4 col-md-offset-1 control-label">Can Access The System:</label>
                    <div class="col-md-1">
                        <div class="row">
                            <div class="col-md-6">
                                <input type="hidden" name="authorization[can_access_system]" value="0">
                                @if (($viewUser->roles[0]->pivot->role_id > $employee->roles[0]->pivot->role_id && $viewUser->roles[0]->pivot->role_id != '4') || ($viewUser->roles[0]->pivot->role_id == '3' && $employee->roles[0]->pivot->role_id == '4'))
                                    {!! Form::checkbox('authorization[can_access_system]', 1, $employee->can_access_system == 1, ['id' => 'access_can_access_the_system', 'class' => 'form-control check-box', 'disabled' => 'disabled']) !!}
                                @else 
                                    {!! Form::checkbox('authorization[can_access_system]', 1, $employee->can_access_system == 1, ['id' => 'access_can_access_the_system', 'class' => 'form-control check-box']) !!}
                                @endif
                            </div>
                            <div class="col-md-6 padding-top-5">
                                {!! $help->button("u3061") !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 padding-top">
            <div class="row">
                <div class="form-group">
                    <label for="role_id" class="col-md-4 col-md-offset-1 control-label">Permissions Level:</label>
                    <div class="col-md-3">
                        @if (($viewUser->roles[0]->pivot->role_id > $employee->roles[0]->pivot->role_id && $viewUser->roles[0]->pivot->role_id != '4') || ($viewUser->roles[0]->pivot->role_id == '3' && $employee->roles[0]->pivot->role_id == '4'))
                        <select class="form-control" id="role_id" name="authorization[role_id]">
                            @foreach($roles as $role)
                                @if ($employee->hasRole('manager') && $role->display_name == 'Owner')
                                    @else
                                <option disabled @if($employee->hasRole($role->name)) selected @endif value="{{ $role->id }}">{{ $role->display_name }}</option>
                                @endif
                            @endforeach
                        </select>
                        @else 
                        <select class="form-control" id="role_id" name="authorization[role_id]">
                            @foreach($roles as $role)
                                @if ($user->hasRole('manager') && $role->display_name == 'Owner')
                                    @elseif ($user->hasRole('manager') && $employee->hasRole('owner'))
                                    <option selected value="0">Owner</option>
                                        @break
                                    @else
                                <option @if($employee->hasRole($role->name)) selected @endif value="{{ $role->id }}">{{ $role->display_name }}</option>
                                @endif
                            @endforeach
                        </select>
                        @endif
                        {!! $help->button("u3064") !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="row">
                <div class="form-group">
                    <div class="col-md-3 col-md-offset-5">
                        @if (($viewUser->roles[0]->pivot->role_id > $employee->roles[0]->pivot->role_id && $viewUser->roles[0]->pivot->role_id != '4') || ($viewUser->roles[0]->pivot->role_id == '3' && $employee->roles[0]->pivot->role_id == '4'))
                            <button disabled type="submit" class="btn btn-default btn-primary btn-xs" name="action" value="authorization">Save</button>
                        @else 
                            <button type="submit" class="btn btn-default btn-primary btn-xs" name="action" value="authorization">Save</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="row">
                <div class="form-group">
                    <label for="" class="col-md-4 col-md-offset-1 control-label">Last Login Date:</label>
                    <div class="col-md-3 padding-top">
                        <strong>
                            @if( is_null($employee->last_login) )
                                Never
                            @else
                                {{ $employee->last_login->format('m/d/Y') }}
                            @endif
                        </strong>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="row text-center">
                <div class="form-group">
                    <button type="submit" class="btn btn-default btn-primary btn-xs" name="action" value="resend_email">Resend Activation Email</button>
                    <p>{!! $help->button("u3065") !!}</p>
                </div>
            </div>
        </div>
    </div>
</div>

@include('user.modals.managerAuthConfirm')

@section('foot')
    @parent
    <script src="/assets/js/employee/managerAuthModal.js"></script>
@stop
