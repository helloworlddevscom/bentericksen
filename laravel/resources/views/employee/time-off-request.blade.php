@extends('employee.wrap')

@section('content')
    <div id="main_body">
        <div class="container" id="main">
            <div class="row main_wrap">
                <input type="hidden" id="title" name="title" value="">
                <input type="hidden" id="company_id" name="company_id" value="">
                <input type="hidden" id="request_employee_name" name="request_employee_name"
                       value="{{ $employee->first_name }} {{ $employee->last_name }}">
                <input type="hidden" id="request_status" name="request_status" value="pending">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12">
                            <a href="/employee">Dashboard</a> > > Time Off Requests
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-10 col-md-offset-1">
                            <div class="mini-heading">
                                <h4><i class="fa fa-clock-o"></i> Time-Off and Leave of Absence Requests</h4>
                            </div>
                            <div class="content">
                                <form action="/employee/time-off-request/{{ $employee->id }}/submit" method="POST">
                                    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                                    <div class="row">
                                        <div class="col-md-3 col-md-offset-1">
                                            <div class="row">
                                                <label for="type" class="col-md-8">Type:</label>
                                                <div class="col-md-12">
                                                    <select class="form-control time-off" id="type" name="type">
                                                        <option value="">- Select One -</option>
                                                        <option value="Pregnancy">Pregnancy</option>
                                                        <option value="Disability">Disability</option>
                                                        <option value="Worker's Compensation">Worker's Compensation</option>
                                                        <option value="Medical">Medical</option>
                                                        <option value="Military">Military</option>
                                                        <option value="Domestic Violence">Domestic Violence</option>
                                                        <option value="Paid Time Off">Paid Time Off</option>
                                                        <option value="Unpaid Time Off">Unpaid Time Off</option>
                                                        <option value="Other">Other</option>
                                                    </select>
                                                    {!! $errors->first('type', '<span style="color:red;">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <label for="start_at" class="col-md-8">Start Date:</label>
                                                <div class="col-md-12">
                                                    <div class="input-group">
                                                        <input type="input" class="form-control date-picker time-off" id="start_at" name="start_at" placeholder="mm/dd/yyyy" readonly>
                                                        <label for="start_at" class="input-group-addon"><i class="fa fa-calendar"></i></label>
                                                    </div>
                                                    {!! $errors->first('start_at', '<span style="color:red;">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <label for="end_at" class="col-md-8">End Date:</label>
                                                <div class="col-md-12">
                                                    <div class="input-group">
                                                        <input type="input" class="form-control date-picker time-off" id="end_at" name="end_at" placeholder="mm/dd/yyyy" readonly>
                                                        <label for="end_at" class="input-group-addon"><i class="fa fa-calendar"></i></label>
                                                    </div>
                                                    {!! $errors->first('end_at', '<span style="color:red;">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-7">
                                            <div class="row">
                                                <label for="reason" class="col-md-6">Reason:</label>
                                                <div class="col-md-12">
                                                    <textarea class="form-control" id="reason" name="reason"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 text-center buttons">
                                            <a href="/employee" class="btn btn-default btn-xs btn-primary">CANCEL</a>
                                            <button type="submit" class="btn btn-default btn-xs btn-primary">SUBMIT
                                            </button>
                                        </div>
                                    </div>
                                    @if (Session::has('message'))
                                        <div class="row">
                                            <div class="col-md-12 text-center">
                                                <div style="font-size: 25px;">{{ Session::get('message') }}</div>
                                            </div>
                                        </div>
                                    @endif

                                </form>
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
          $(document).ready(function () {
            $(".date-picker").datepicker({
              changeMonth: true,
              changeYear: true,
              showButtonPanel: true,
              yearRange: "-100:+10",
              onSelect: function (selectedDate) {
                let id = $(this).attr("id");
                if (id == "start_at") {
                  $("#end_at").datepicker('option', 'minDate', selectedDate);
                }
              }
            });
            $("#end_at").datepicker('option', 'minDate', new Date($.now()));
            $("#start_at").datepicker('option', 'minDate', new Date($.now()));

          });


        $('.time-off').on('change keyup', function () {
            var request_start_date = $('#start_at').val() || 0;
            var request_end_date = $('#end_at').val() || 0;

            var total_hours = (Date.parse(request_end_date) - Date.parse(request_start_date)) / 1000 / 60 / 60;

            var vac_time = parseFloat($('#vacation').val()) || 0;
            var pto_time = parseFloat($('#pto').val()) || 0;

            if (total_hours > 0) {
                $('#total_hours').val((total_hours).toFixed(2));
                var unpaid_time = (total_hours - (vac_time + pto_time));
                if (unpaid_time > 0) {
                    $('#unpaid_time').val(unpaid_time.toFixed(2));
                } else {
                    $('#unpaid_time').val('00.00');
                }
            } else {
                $('#total_hours').val('00.00');
                $('#unpaid_time').val('00.00');
            }

            var title = '{screen_name}' + ' ' + request_start_date + '-' + request_end_date;
            $('#title').val(title);
        });
    </script>
@stop
