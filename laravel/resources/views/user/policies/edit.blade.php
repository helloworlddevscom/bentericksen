@extends('user.wrap')

@section('content')
    <div id="main_body">
        <div class="container" id="main">
            <div class="row main_wrap">
                {!! Form::open(['route' => ['user.policies.update', $policy->id], 'method' => 'PUT', 'id' => 'policyForm', 'class' => 'form-horizontal']) !!}
                <div class="col-md-12 content">
                    <div class="row">
                        <div class="col-md-10 col-md-offset-1">
                            @if (count($errors) > 0)
                                @foreach ($errors->all() as $error)
                                    <div class="alert alert-danger">{{ $error }}</div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                <div class="col-md-6 col-md-offset-3 text-center">
                    <h3>
                        <input type="text"
                               class="form-control text-uppercase"
                               name="manual_name"
                               value="{{ $policy->manual_name }}"
                               @if(!$policy->userCanEdit($viewUser))
                                    readonly
                               @endif
                        />
                    </h3>
                </div>
                <div class="col-md-12 content">
                    <div class="row">
                        <div class="col-md-10 col-md-offset-1">
                            <div class="form-group">
                                <label for="category" class="col-md-4 control-label">Category:</label>
                                <div class="col-md-4">
                                    <select id="category" class="form-control select" name="category_id">
                                        <option value=""> - Select One -</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if($displayRequiredSelect)
                        <div class="row">
                            <div class="col-md-10 col-md-offset-1">
                                <div class="form-group">
                                    <label for="required" class="col-md-4 control-label">Required:</label>
                                    <div class="col-md-4">
                                        <select id="required" class="form-control select" name="required">
                                            <option value=""> - Select One -</option>
                                            <option value="required" @if($policy->requirement === "required") selected @endif>Required</option>
                                            <option value="optional" @if($policy->requirement !== "required") selected @endif>Optional</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="row">
                        <div class="col-md-offset-1 col-md-12">
                            <div class="checkbox">
                                <label class="control-label">
                                  <input type="checkbox" name="new_page" {{ $policy->new_page ? 'checked' : '' }}> Page Break
                                </label>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-10 col-md-offset-1">
                            <div class="form-group" id="policy_default">
                                <div class="col-md-12" id="editor-container">
                                    {{-- This textarea is turned into the CKEditor by code in assets/js/custom_cke.js --}}
                                    <textarea id="content_raw" class="form-control" style="display:none" name="content_raw">{{ $policy->content_raw }}</textarea>

                                </div>
                                <div id="mod_counters" class="hidden">
                                    <div class="col-md-12">
                                        <label for="mod_counter" class="col-md-4 control-label">Total Changes:</label>
                                        <input type="text" id="mod_counter" name="mod_counter" readonly>
                                    </div>
                                    <div class="col-md-12">
                                        <label for="mod_counter_approved" class="col-md-4 control-label">Total Changes Approved:</label>
                                        <input type="text" id="mod_counter_approved" name="mod_counter_approved" disabled>
                                    </div>
                                    <div class="col-md-12">
                                        <label for="mod_counter_rejected" class="col-md-4 control-label">Total Changes Rejected:</label>
                                        <input type="text" id="mod_counter_rejected" name="mod_counter_rejected" disabled>
                                    </div>
                                    <div class="col-md-12">
                                        <label for="mod_counter_pending" class="col-md-4 control-label">Total Changes Pending:</label>
                                        <input type="text" id="mod_counter_pending" name="mod_counter_pending" disabled>
                                    </div>
                                    <div class="col-md-12">
                                        <input type="hidden" id="is_tracking" name="is_tracking" value="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="submit" name="submitter" style="visibility:hidden;" >submitter</button>
                {!! Form::close() !!}
                <div class="col-md-12 text-center buttons">
                    @if(($policy->userCanEdit($viewUser)) && ($policy->edited != "yes"))
                        <button type="button" name="toggleStatus" class="{{ $statusButton['class'] }}">{{ $statusButton['label'] }}</button>
                    @else
                        <button type="button" class="{{ $statusButton['class'] }}" disabled>{{ $statusButton['label'] }}</button>
                    @endif

                    @if( $policy->special == "selected" )
                        <button type="button" name="resetSpecial" class="btn btn-default btn-primary btn-xs form-btn reset-select">RESET SELECT</button>
                    @endif

                    <a href="/user/policies#policy_{{ $policy->id }}" class="btn btn-default btn-primary btn-xs">CANCEL</a>

                    @if($policy->userCanEdit($viewUser))
                        <button type="button" name="submit" class="btn btn-default btn-xs btn-primary form-btn">SAVE</button>
                    @else
                        <button type="button" name="submit" class="btn btn-default btn-xs btn-primary form-btn disabled" disabled>SAVE</button>
                    @endif

                    @if($policy->userCanEdit($viewUser) && $policy->template_id !== 0)
                        <button type="button" name="reset" class="btn btn-xs btn-primary text-uppercase form-btn">Reset</button>
                    @else
                        <button type="button" name="reset" class="btn btn-xs btn-primary text-uppercase form-btn disabled" disabled>Reset</button>
                    @endif


                    @if( $policy->is_custom && $viewUser->hasRole('admin') )
                        <button type="button" name="delete" class="btn btn-danger btn-primary btn-xs form-btn">
                            DELETE
                        </button>
                    @endif

                </div>

                @if ($displayApprovalButtons)
                    <div id="modification_buttons" class="col-md-12 text-center buttons">
                        <button type="button" class="btn btn-lg btn-primary" id="accept" disabled>ACCEPT & NOTIFY</button>
                        <button type="button" class="btn btn-lg btn-danger" id="reject" disabled>REJECT & NOTIFY</button>
                    </div>
                @endif
            </div>
            @include('user.modals.policyReset')
            @include('user.modals.policyResetSpecial')
     @if( $policy->is_custom )
            @include('user.modals.policyDelete')
     @endif
        </div>
    </div>
@stop

@section('foot')
    @parent
    <script>
        window.ck_info.policy = {
            type: "{{ $policy->requirement }}"
        };
        const POLICY_CATEGORY_ID = {{ $policy->category_id }}
        
        window.POLICY_ID = "{{ $policy->id }}";
    </script>
@stop
