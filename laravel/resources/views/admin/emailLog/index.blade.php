@extends('admin.wrap')

@section('content')
    <div id="main_body">
        <div class="container" id="main">
            <div class="row main_wrap">

                <div class="col-md-12 heading">
                    <h3>Emails sent to user {{$user->full_name}}@if(!empty($user->business)) of {{$user->business->name}}@endif</h3>
                </div>

                <div class="col-md-12 content">
                    <div class="row">
                        <div class="table-responsive">
                            <table class="table table-striped" id="emails_table">
                                <thead>
                                <tr>
                                    <th>
                                        <span>Date</span>
                                    </th>
                                    <th>
                                        <span>Subject</span>
                                    </th>
                                    <th>
                                        <span>Status</span>
                                    </th>
                                    <th class="bg_none">
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($user->outgoingEmails as $email)
                                    <tr>
                                        <td>{{ $email->sent_at }}</td>
                                        <td>{{ $email->subject }}</td>
                                        <td>{{ $email->status }}</td>
                                        <td class="text-right">
                                            <a href="/admin/emails/{{ $email->id }}" class="btn btn-default btn-xs">VIEW</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
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
        $.fn.dataTable.ext.search.push(
            function (settings, data, dataIndex) {
                var start = new Date($('#picker1').val());
                var end = new Date($('#picker2').val());
                var date = new Date(data[3]); // use data for the age column

                if ((isNaN(start.getTime()) && isNaN(end.getTime())) ||
                    (isNaN(start.getTime()) && date.getTime() <= end.getTime()) ||
                    (start.getTime() <= date.getTime() && isNaN(end.getTime())) ||
                    (start.getTime() <= date.getTime() && date.getTime() <= end.getTime())) {
                    return true;
                }
                return false;
            }
        );

        var table = $("#user_table").DataTable({
            "bStateSave": true,
            "orderClasses": false
        });

    </script>
    <script src="/assets/admin/tables.js"></script>
    <script>
        $(".date-box").datepicker({
            changeMonth: true,
            changeYear: true,
            showButtonPanel: true
        });

        $('.table-responsive').on('click', '.status-update', function (e) {
            e.preventDefault();

            var id = $(this).attr('data-id');
            var $element = $(this);
            var json = {};

            json.user_id = id;
            json._token = "{{ csrf_token() }}";

            $.post('/admin/updateUser', json, function (data) {
                $element.parents('tr').toggleClass('disabled');
                $element.html(data.toUpperCase());
            });
        });

    </script>
@stop
