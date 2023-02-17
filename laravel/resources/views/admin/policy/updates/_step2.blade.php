@extends('admin.wrap')

@section('content')
    <div id="main_body">
        <div class="container" id="main">
            <div class="row main_wrap">
                <div class="col-md-12 heading">
                    <h3>Policy Update</h3>
                </div>
                <form class="form-horizontal" method="post" action="/admin/policies/updates/create?step={{ $next }}&id={{ $id }}">
                    {{ csrf_field() }}
                    <div class="col-md-12 content">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <span>There were some problems with your input.</span>
                                <br><br>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-10 col-md-offset-1 policies_content">
                                <div class="col-md-10 col-md-offset-1">
                                    <h3>Email Lists</h3>
                                    <p>This page allows you to customize the addresses that will receive an email about this policy update.</p>
                                </div>
                                <div class="form-group">
                                    <label for="business_name" class="col-md-4 control-label">Active:</label>
                                    <div class="col-md-5">
                                        <button type="button" class="btn btn-default btn-sm btn-email" data-toggle="modal" data-target="#email_modal" value="active">VIEW LIST</button>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="business_name" class="col-md-4 control-label">Inactive:</label>
                                    <div class="col-md-5">
                                        <button type="button" class="btn btn-default btn-sm btn-email" data-toggle="modal" data-target="#email_modal" value="inactive">VIEW LIST</button>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="business_name" class="col-md-4 control-label">Other:</label>
                                    <div class="col-md-5">
                                        <button type="button" class="btn btn-default btn-sm btn-email" data-toggle="modal" data-target="#email_modal" value="other">VIEW LIST</button>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="business_name" class="col-md-4 control-label">Consultants:</label>
                                    <div class="col-md-5">
                                        <button type="button" class="btn btn-default btn-sm btn-email" data-toggle="modal" data-target="#email_modal" value="consultants">VIEW LIST</button>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="faq_long_answer" class="col-md-4 control-label">Additional:</label>
                                    <div class="col-md-6">
                                        <textarea class="form-control" id="update_email_list_additional" rows="6" name="additional_emails">@foreach($updates->additional_emails as $email){{ $email . PHP_EOL }}@endforeach</textarea>
                                    </div>
                                </div>
                                <div id="emails"></div>
                                <div class="row text-center buttons">
                                    <input type="submit" class="btn btn-default btn-primary btn-sm" value="NEXT">
                                </div>
                                <div class="col-md-6 col-md-offset-3">
                                    <div id="progressbar"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@stop

@section('foot')
    @parent
    <div class="modal fade" id="email_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="email_list_label">Modal title</h4>
                </div>
                <div class="modal-body">
                    <div class="email-list-available">
                        <p>Available:</p>
                        <p>
                            <select class="email-list" id="active_list" name="active_list" multiple></select>
                            <select class="email-list" id="inactive_list" name="inactive_list" multiple></select>
                            <select class="email-list" id="other_list" name="other_list" multiple></select>
                            <select class="email-list" id="consultant_list" name="consultant_list" multiple></select>
                        </p>
                    </div>

                    <div class="email-list-buttons">
                        <button class="email-list-button add">></button>
                        <br>
                        <button class="email-list-button remove"><</button>
                    </div>


                    <div class="email-list-selected">
                        <p>Selected:</p>
                        <p>
                            <select class="email-list" id="email_list" name="email_list" multiple></select>
                        </p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
      window.PAGE_INIT.push(() => {
        var list = <?php echo json_encode($list); ?>;
        var other_list = <?php echo json_encode($other_list); ?>;
        var sendInactive = <?php echo json_encode($sendInactive); ?>;
        var included = [];

        function addToSendList(email, value, emailList) {
            $('#email_list').append($("<option />").val(value['email']).text(value['email']).prop('id', value['email']).attr('data-list', emailList));

            var emailInput = document.createElement("input");
            emailInput.setAttribute('id', email + '_hidden');
            emailInput.setAttribute('value', email);
            emailInput.setAttribute('name', 'emails[]');
            emailInput.setAttribute('type', 'hidden');
            document.getElementById('emails').appendChild(emailInput);
        };

        $.each(list, function (key, value) {
            if (value['status'] == 'active') {
                if (included.indexOf(value['email']) >= 0) {
                    return;
                }
                included.push(value['email']);
                addToSendList(value['email'], value, 'active_list');

            } else {
                if (sendInactive) {
                    addToSendList(value['email'], value, 'inactive_list');
                } else {
                    $('#inactive_list').append($("<option />").val(value['email']).text(value['email']).prop('id', value['email']).attr('data-list', 'inactive_list'));
                }
            }
        });

        $.each(other_list, function (key, value) {
            if (value['status'] == 'active') {
                if (included.indexOf(value['email']) >= 0) {
                    return;
                }
                included.push(value['email']);

                if (value['role'] == 'manager' || value['role'] == 'employee') {
                    $('#active_list').append($("<option />").val(value['email']).text(value['email']).prop('id', value['email']).attr('data-list', 'active_list'));
                } else if (value['role'] == 'consultant') {
                    $('#consultant_list').append($("<option />").val(value['email']).text(value['email']).prop('id', value['email']).attr('data-list', 'consultant_list'));
                } else {
                    $('#other_list').append($("<option />").val(value['email']).text(value['email']).prop('id', value['email']).attr('data-list', 'other_list'));
                }
            } else {
                $('#inactive_list').append($("<option />").val(value['email']).text(value['email']).prop('id', value['email']).attr('data-list', 'inactive_list'));
            }

        });

        emailList = 'email_list';

        $(".btn-email").on("click", function () {
            var $this = $(this)
            if ($this.val() == "active") {
                $("#active_list").removeClass("hidden");
                $("#inactive_list").addClass("hidden");
                $("#other_list").addClass("hidden");
                $("#consultant_list").addClass("hidden");
                $("#email_list_label").text("Active");
                list = 'active_list';
            } else if ($this.val() == "inactive") {
                $("#active_list").addClass("hidden");
                $("#inactive_list").removeClass("hidden");
                $("#other_list").addClass("hidden");
                $("#consultant_list").addClass("hidden");
                $("#email_list_label").text("Inactive");
                list = 'inactive_list';
            } else if ($this.val() == "other") {
                $("#active_list").addClass("hidden");
                $("#inactive_list").addClass("hidden");
                $("#other_list").removeClass("hidden");
                $("#consultant_list").addClass("hidden");
                $("#email_list_label").text("Other");
                list = 'other_list';
            } else if ($this.val() == "consultants") {
                $("#active_list").addClass("hidden");
                $("#inactive_list").addClass("hidden");
                $("#other_list").addClass("hidden");
                $("#consultant_list").removeClass("hidden");
                $("#email_list_label").text("Consultants");
                list = 'consultant_list';
            };
        });

        $('.email-list-button').on('click', function () {
            button = $(this);

            if (button.hasClass('add')) {
                selected = $('#' + list + ' > option:selected');
                $.each(selected, function (key, value) {

                    email = document.getElementById(emailList);
                    parent = document.getElementById(list);
                    child = document.getElementById($(this).attr('id'));
                    email.appendChild(child);

                    emailInput = document.createElement("INPUT");
                    emailInput.setAttribute('id', $(this).text() + '_hidden');
                    emailInput.setAttribute('value', $(this).text());
                    emailInput.setAttribute('name', 'emails[]');
                    emailInput.setAttribute('type', 'hidden');
                    emails = document.getElementById('emails');
                    emails.appendChild(emailInput);
                });
            } else {
                selected = $('#' + emailList + ' > option:selected');
                $.each(selected, function (key, value) {

                    oldList = document.getElementById($(this).attr('data-list'));
                    parent = document.getElementById(emailList);
                    child = document.getElementById($(this).attr('id'));
                    oldList.appendChild(child);

                    removeEmail = document.getElementById($(this).text() + '_hidden');

                    emails = document.getElementById('emails');
                    emails.removeChild(removeEmail);

                });
            }

            $('#' + list + ' > option').each(function () {
                $(this).prop('selected', false);
            });

            $('#' + emailList + ' > option').each(function () {
                $(this).prop('selected', false);
            });
        });
      })
    </script>
@stop
