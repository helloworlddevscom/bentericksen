@extends('user.wrap')

@section('head')
    @parent
    <link href="/css/changes.css" rel="stylesheet">
@stop

@section('content')
    <div id="main_body">
        <div class="container" id="main" style="overflow:hidden;">
            <div class="row main_wrap">
                <div class="col-md-12 content">
                    <div id="message">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                <strong>{{ session('success') }}</strong>
                            </div>
                        @endif
                        @if (count($errors) > 0)
                            <div class="alert alert-danger alert-dismissible">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                <strong>Whoops!</strong> There were some problems with your submission.<br><br>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                    <div class="row text-center border-bottom">
                        <h2>{{ $employee->full_name }}</h2>
                    </div>
                    <div class="row">
                    {!! Form::open(['route' => ['user.employees.update', $employee->id], 'id' => 'test', 'class' => 'form-horizontal', 'method' => 'put']) !!}
                    {!! Form::hidden('user_id', $employee->id) !!}
                    <!-- Left Column -->
                        <div class="col-md-4 no-padding-both emp-content">
                            <div class="row">
                                @include('user.employees.partials.employment_status')
                            </div>
                            <div class="row">
                                @include('user.employees.partials.personal')
                            </div>
                            <div class="row">
                                @include('user.employees.partials.contact')
                            </div>
                        </div>
                    <!-- End Left Column -->

                    <!-- Right Column -->
                        <div class="col-md-8">
                            <div class="row">
                                <div id="employeeTabs" class="col-md-12 nav-tabs">
                                    <div class="row">
                                        <ul class="nav nav-tabs" role="tablist" id="employee_tab">
                                            <li class="active">
                                                <a href="#tracking" role="tab" data-toggle="tab">Tracking</a>
                                            </li>
                                            <li>
                                                <a href="#history" role="tab" data-toggle="tab">History</a>
                                            </li>
                                            <li>
                                                <a href="#authorization" role="tab" data-toggle="tab">Authorization</a>
                                            </li>
                                        </ul>
                                        <div class="tab-content with-subnav sub-tab">
                                            @foreach($tabs as $key => $tab)
                                                <div class="tab-pane fade @if($key == 0) in active @endif" id="{{ $tab->name }}">
                                                    @include($tab->view)
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <!-- End Right Column -->
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('foot')
    @parent
    <script>
        $(document).ready(function () {
            $('.date-picker').each(function () {
                var val = $(this).val();
                if (val == "11/30/0001") {
                    $.datepicker._clearDate(this);
                }
            });
        });

        $('.attendance').on('click', function () {
            attendanceId = $(this).attr('data-attendance-id');
            $('.attendance_modal .modal-body').addClass('hidden');
            $('.attendance_modal').find("[data-attendance-id='" + attendanceId + "']").removeClass('hidden');
        });

        $('.attendance-delete').on('click', function () {
            attendanceId = $(this).attr('data-attendance-id');
            $('.attendance_delete_modal .modal-body').addClass('hidden');
            $('.attendance_delete_modal').find("[data-attendance-id='" + attendanceId + "']").removeClass('hidden');
        });

        $('.attendance_delete_modal').find('button.delete').on('click', function() {
            attendanceId = $('.attendance_delete_modal .modal-body').not('.hidden').attr('data-attendance-id');
            performAttendanceDeleteRequest('/user/employees/attendance/delete/' + attendanceId, 'GET');
        });

        $('.history').on('click', function () {
            historyId = $(this).attr('data-history-id');
            $('.history_modal .modal-body').addClass('hidden');
            $('.history_modal').find("[data-history-id='" + historyId + "']").removeClass('hidden');
        });

        $('.leave-button').on('click', function () {
            var leaveId = $(this).attr('data-leave-id');
            $('.leave-modal .timeoff').addClass('hidden')
                .find('.form-control').attr('disabled', true);
            $('.leave-modal').find("[data-leave-id='" + leaveId + "']").removeClass('hidden')
                .find('.form-control').attr('disabled', false);
        });

        $('#employee_classification').on('change', function () {
            if ($(this).find('option:selected').val() === "other") {
                $('#employee_classification_name').removeClass('hidden').attr('disabled', false);
            } else {
                $('#employee_classification_name').addClass('hidden').attr('disabled', true);
            }
        });

        $(".date-picker").datepicker({
            changeMonth: true,
            changeYear: true,
            showButtonPanel: true,
            yearRange: "-100:+10"
        });

        $(".time-picker").datetimepicker({
            datepicker: false,
            format: 'H:i',
            timeFormat: "hh:mm tt",
            value: '12:00'
        });

        $('#employeeTabs a').click(function (e) {
            history.pushState(null, null, $(this).attr('href'));
        });

        $('#modalLicensureCertifications form').on('submit', function (event) {
            var newLicense = $('.empLicenseCertification .new .certType').val();
            var newDate = $('.empLicenseCertification .new .date-picker').datepicker('getDate');

            if (newLicense == '') {
                $('.empLicenseCertification .new .certType').addClass('required-outline');
                event.preventDefault();
            } else {
                $('.empLicenseCertification .new .date-picker').removeClass('required-outline');
            }
        });

        var hash = window.location.hash;
        $('#employeeTabs a[href="' + hash + '"]').tab('show');

        function showDefault() {
            var hash = window.location.hash.split("/");
            $('#employeeTabs a[href="' + hash[0] + '"]').tab('show');

            if (hash.length > 1) {
                $('#employeeTabs a[href="#' + hash[1] + '"]').tab('show');
            }
        }

        showDefault();

        $('form').on('submit', function () {
            path = window.location.pathname;
            hash = window.location.hash;
            $('._return').val(path + hash);
        });

        $('#resend_email').click(function (event) {
            $('#resend_email_input').val('yes');
        });

        $('.emergency_relationship').on('change', function () {
            var valueSelected = this.value;
            var otherInput = $(this).attr('data-target');

            if (valueSelected === 'other') {
                $(otherInput).attr('disabled', false).removeClass('hidden');
            } else {
                $(otherInput).attr('disabled', true).addClass('hidden');
            }
        });

        function performAttendanceDeleteRequest(url, method, data = null) {
            $.ajax({
                type: method,
                url: url,
                data:data,
                beforeSend: function (request) {
                    request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                    return request;
                },
                success: function(response){
                    displayAttendanceDeleteStatus(response);
                },
                error: function(response){
                    displayAttendanceDeleteStatus(response);
                }
            });
        }

        function displayAttendanceDeleteStatus(response) {

            var status = typeof(response.status) === "undefined" ? "" : response.status;
            var data = typeof(response.data) === "undefined" ? "" : response.data;

            $('.alert').slice(1).hide();
            $('#modalAttendanceDelete').modal("hide");

            if(status === "success") {
                $('.attendance-delete').each(function() {
                    var _this = $(this);
                    if(_this.attr('data-attendance-id') === data) {
                        _this.parent('td').parent('tr').remove();
                    }
                });
            }

            var statusMessage = status === "success" ?
                "Attendance record " + data + " successfully deleted" :
                "Error deleting attendance record " + data;

            var statusHTML = '<div class="alert alert-' + status + ' alert-dismissible">' +
                '                 <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' +
                '                 <strong>' + statusMessage  + '</strong>' +
                '             </div>';

            $('#message').append(statusHTML);
            $('html, body').animate({scrollTop:0}, 100);

        }

    </script>
@stop
