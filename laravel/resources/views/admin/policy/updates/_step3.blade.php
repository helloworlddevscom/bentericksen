@extends('admin.wrap')

@section('content')
    <div id="main_body">
        <div class="container" id="main">
            <div class="row main_wrap">

                <div class="col-md-12 heading">
                    <h3>Policy Update</h3>
                </div>

                <style>
                    .form-horizontal .control-label {
                        text-align: left;
                    }
                </style>

                <form class="form-horizontal" method="post" action="/admin/policies/updates/create?step={{ $next }}&id={{ $id }}">
                    {{ csrf_field() }}
                    <div class="col-md-12 content">
                        <div class="row">
                            <div class="col-md-10 col-md-offset-1 policies_content">
                                <div class="form-group">
                                    <label for="update_title" class="col-md-2 col-md-offset-1 control-label">Subject Line:</label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" id="update_title" name="title" value="{{ $updates->title }}" placeholder="Title" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="update_send_date" class="col-md-2 col-md-offset-1 control-label">Send Date:</label>
                                    <div class="col-md-2">
                                        <span class="input-group">
                                            <input type="text"
                                                   class="form-control date-picker"
                                                   id="update_send_date"
                                                   name="start_date"
                                                   value="{{ (!is_null($updates->start_date) ? date('m/d/y', strtotime($updates->start_date)) : "") }}"
                                                   placeholder="mm/dd/yyyy"
                                                   required
                                                   autocomplete="off">
                                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="update_active_clients_consultants" class="col-md-4 col-md-offset-1">Active Clients / Consultants:</label>
                                    <div class="col-md-10 col-md-offset-1">
                                        <textarea name="active_clients_text">{{ $email_defaults['active_client_default'] }}</textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="update_inactive_clients" class="col-md-4 col-md-offset-1">Inactive Clients:</label>
                                    <div class="col-md-10 col-md-offset-1">
                                        <textarea name="inactive_clients_text">{{ $email_defaults['inactive_client_default'] }}</textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="update_other_additional" class="col-md-4 col-md-offset-1">Other / Additional:</label>
                                    <div class="col-md-10 col-md-offset-1">
                                        <textarea name="additional_text">{{ $email_defaults['other_client_default'] }}</textarea>
                                    </div>
                                </div>
                                <div class="row text-center buttons">
                                    <a href="/admin/policies/updates" class="btn btn-default btn-xs ">CANCEL</a>
                                    <button type="submit" class="btn btn-default btn-xs btn-primary">FINALIZE UPDATE</button>
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
    <script>
      window.PAGE_INIT.push(() => {
        $(document).ready(function () {
            $('.date-picker').each(function () {
                if ($(this).val() == "11/30/0001") {
                    $.datepicker._clearDate(this);
                }
            });
        });

        $(".date-picker").datepicker({
            changeMonth: true,
            changeYear: true,
            showButtonPanel: true,
            yearRange: "-100:+10",
        });

        CKEDITOR.replace('active_clients_text', {
            customConfig: '/assets/js/plugins/ckeditor/admin_config.js'
        });
        CKEDITOR.replace('inactive_clients_text', {
            customConfig: '/assets/js/plugins/ckeditor/admin_config.js'
        });
        CKEDITOR.replace('additional_text', {
            customConfig: '/assets/js/plugins/ckeditor/admin_config.js'
        });
      });
    </script>
@stop	
