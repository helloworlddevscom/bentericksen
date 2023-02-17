@extends('shared.modalWrap')

@section('body')
    <div class="row">
        <div class="col-md-2">
            <input type="password" name="password" id="password" class="form-control">
        </div>
    </div>
    <input type="hidden" id="plan_id" name="plan_id" value="{{ $plan->id }}">
@stop

@section('buttons')
    @parent
    <div id="feedback"></div>
    <div class="buttons">
        <button type='button' class='btn btn-default btn-primary confirm-and-delete'>Submit</button>
    </div>

    <script>
        $(document).on('click', '.confirm-and-delete', function () {
            var json = {
                password: $('#password').val(),
                plan_id: $('#plan_id').val()
            };

            $.post('/bonuspro/confirm-and-delete', json, function (data) {
                if(data.valid === true) {
                    window.location.reload();
                }
                $('#feedback').empty().text(data.message);
            });
        });
    </script>
@overwrite
