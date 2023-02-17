@extends('user.wrap')

@section('content')
    <div id="main_body">
        <div class="container policy-pick" id="main">
            <div class="row main_wrap">
                <div class="col-md-6 col-md-offset-3 text-center">
                    <h3>
                        Pick Policy
                    </h3>
                </div>
                <div class="col-md-12 content">
                    <form method="post" action="/user/policies/{{ $stub->id }}/select">
                        {{ csrf_field() }}
                        <input type="hidden" name="_method" value="PUT">
                        <div class="row">
                            <div class="col-md-7 col-md-offset-5">
                                @foreach( $policies AS $policy )
                                    <div class="checkbox">
                                        <label>
                                            @if( ! is_null( $policy->alternate_name ) )
                                                <input name="policySelect" @if($default == $policy->id) checked
                                                       @endif value="{{ $policy->id }}"
                                                       type="radio"> {{ $policy->alternate_name }}
                                            @else
                                                <input name="policySelect" @if($default == $policy->id) checked
                                                       @endif value="{{ $policy->id }}"
                                                       type="radio"> {{ $policy->manual_name }}
                                            @endif
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <br>

                        <div class="row">
                            <div class="col-md-7 col-md-offset-5">
                                <div class="form-group" id="policy_default">
                                    <div class="col-md-12">
                                        <input type="submit" class="btn btn-default" value="Select Policy">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('foot')
    @parent
@stop
