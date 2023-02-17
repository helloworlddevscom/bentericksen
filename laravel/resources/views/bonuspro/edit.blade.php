@extends('user.wrap')

@section('content')
    <div id="main_body">
        <div class="container" id="main">
            <div class="row main_wrap">
                <div class="col-md-12 content">
                    <div class="content">
                        <div id="app">
                            <div class="col-md-12 form-horizontal content">
                                <bp-edit-form
                                    :plan-data="{{ json_encode($plan) }}"
                                    :business-users="{{ json_encode($businessUsers) }}"
                                    :created-by="{{ $user->id }}"
                                    :states="{{ json_encode($states) }}"
                                    :years="{{ json_encode($years) }}"
                                    :months="{{ json_encode($months) }}"
                                    :business-id="{{ $user->business_id }}"></bp-edit-form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('foot')
    @parent
    <script>
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })
    </script>
@stop