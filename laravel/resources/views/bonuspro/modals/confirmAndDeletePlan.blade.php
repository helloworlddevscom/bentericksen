@extends('shared.modalWrap')

@section('body')
    <div id="feedback"></div>
    <div class="confirmation-fields">
        <h3>Are you sure you want to delete the following plan?</h3>
        <p>Plan Name: <span id="plan_name"></span></p>
        <h4>This operation can't be undone.</h4>
    </div>
    <div class="password-submit-fields hidden">
        <h3>Please enter the plan's password:</h3>
        <input type="password" id="password" name="password">
        <input type="hidden" id="plan_id" name="id">
    </div>
@overwrite

@section('buttons')
    <div class="buttons">
        <div class="confirmation-buttons">
            <button type='button' class='btn btn-danger show-password-confirmation'>Yes</button>
            <button type='button' class='btn btn-primary close-delete-plan-modal'>No</button>
        </div>
        <div class="password-submit-buttons hidden">
            <button type='button' class='btn btn-default btn-primary confirm-and-delete'>SUBMIT</button>
            <button type='button' class='btn btn-primary close-delete-plan-modal'>CANCEL</button>
        </div>
    </div>
    <script>
        $(document).on('click', '.show-password-confirmation', function (e) {
            e.preventDefault();
            $('.confirmation-buttons').addClass('hidden');
            $('.confirmation-fields').addClass('hidden');
            $('.password-submit-fields').removeClass('hidden');
            $('.password-submit-buttons').removeClass('hidden');
        });

        $(document).on('click', '.close-delete-plan-modal', function(e) {
            e.preventDefault();
            $('.confirmation-buttons').removeClass('hidden');
            $('.confirmation-fields').removeClass('hidden');
            $('.password-submit-fields').addClass('hidden');
            $('.password-submit-buttons').addClass('hidden');
            $('#modalConfirmAndDeletePlan').modal('hide');
        });

        $(document).on('click', '.confirm-and-delete', function (e) {
            e.preventDefault();
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