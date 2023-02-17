@extends('user.wrap')

@section('content')
    <div id="main_body">
        <div class="container" id="main">
            <div class="row main_wrap">
                <div class="col-md-12 content">
                    <div class="col-md-8 col-md-offset-2 content">
                        @if(Session::has('success'))
                            <div class="alert alert-success"><strong>{!! session('success') !!}</strong></div>
                        @endif
                        <div class="row text-center">
                            <h3>BonusPro - Plans List</h3>
                            <a href="/bonuspro/create" class="btn btn-default btn-xs btn-primary">ADD NEW PLAN</a>
                        </div>
                        @if(count($errors) > 0)
                            @include('shared.errors')
                        @endif
                        <div class="table-responsive">
                            <table class="table table-striped" id="active_table">
                                <thead>
                                <tr>
                                    <th><span>Plan Name</span></th>
                                    <th><span>Start Date</span></th>
                                    <th class="bg_none"></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($plans as $plan)
                                    <tr>
                                        <td>{{ $plan->plan_id }} - {{ $plan->plan_name }}</td>
                                        <td>{{ $plan->start_month }} / {{ $plan->start_year }}</td>
                                        <td class="text-right">
                                            @if($plan->completed == 0)
                                                <a href="/bonuspro/{{ $plan->id }}/resume" class="btn btn-default btn-xs btn-wd btn-primary">RESUME</a>
                                            @else
                                                <a href="/bonuspro/{{ $plan->id }}/edit" class="btn btn-default btn-xs btn-wd btn-primary">VIEW</a>
                                            @endif
                                            <a href="#" class="delete-plan modal-button btn btn-danger btn-xs btn-wd" data-name="{{ $plan->name }}" data-plan="{{ $plan->id }}">DELETE</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            @include('bonuspro.modals.confirmAndDeletePlan', [
                                'modalId' => 'modalConfirmAndDeletePlan',
                                'title' => 'Delete plan',
                            ])
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
        $(document).on('click', '.delete-plan', function (e) {
            e.preventDefault();
            $('#plan_id').attr('value', $(this).data('plan'));
            $('#plan_name').text($(this).data('name'));
            $('#modalConfirmAndDeletePlan').modal('show');
        });
    </script>
@stop