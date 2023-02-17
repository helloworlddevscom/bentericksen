@extends('user.wrap')

@section('content')
    <div id="main_body">
        <div class="container" id="main">
            <div class="row main_wrap">
                <div class="col-md-12 content">
                    <div class="content">
                        <div id="app">
                            <div class="col-md-12 form-horizontal content">
                                <bonuspro-plan-create-form
                                    :plan-data="{{ json_encode($plan) }}"
                                    :bonus-percentage="{{ json_encode($plan) }}"
                                    :states="{{ json_encode($states) }}"
                                    :business-users="{{ json_encode($businessUsers) }}"
                                    :created-by="{{ $user->id }}"
                                    :business-id="{{ $user->business_id }}"></bonuspro-plan-create-form>
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
