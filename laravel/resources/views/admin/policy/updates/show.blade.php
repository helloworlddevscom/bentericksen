@extends('admin.wrap')

@section('content')
    <div id="main_body">
        <div class="container" id="main">
            @if (count($errors) > 0)
                @foreach ($errors->all() as $error)
                    <div class="alert alert-danger">{{ $error }}</div>
                @endforeach
            @endif
            <div class="row main_wrap">

                <div class="col-md-12 heading">
                    <h3>Policy Update [Read Only View]</h3>
                </div>

                <style>
                    .form-horizontal .control-label {
                        text-align: left;
                    }
                </style>

                <!-- Policy Updater Details -->
                <div class="col-md-12 content">
                    {{-- From _step3.blade.php --}}
                    <div class="row">
                        <div class="col-md-10 col-md-offset-1 heading">
                            <h4>
                                {{ $updater['title'] }}
                                [{{ $updater['status'] === 'complete' ? 'Sent' : 'Pending Send'}} : {{ $updater['start_date'] }}]
                            </h4>
                        </div>
                        <div class="col-md-10 col-md-offset-1 sub_content">
                            <strong>Inactive Clients:</strong> {{ $updater['inactive_clients'] }} Years
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-10 col-md-offset-1 heading">
                            <h4>Policies</h4>
                        </div>
                        <div class="col-md-10 col-md-offset-1 sub_content">
                            <table class="table table-striped" id="policies_table">
                                <thead>
                                <tr>
                                    <th rowspan="1" colspan="1">
                                        <span>Policy Name</span>
                                    </th>
                                    <th rowspan="1" colspan="1">
                                        <span>Effective Date</span>
                                    </th>
                                    <th rowspan="1" colspan="1" class="text-right">
                                        @if(config('app.env') !== 'production' && $updater['status'] === 'ready' )
                                            <a href="/admin/policies/updates/{{$updater['id']}}/trigger" class="btn btn-info btn-xs">UPDATE PENDING POLICIES NOW</a>
                                        @endif
                                    </th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($updater['policies'] as $policy)
                                    <tr>
                                        <td>
                                            {{ $policy['admin_name'] }}
                                        </td>
                                        <td>
                                            {{ $policy['effective_date'] }}
                                        </td>
                                        <td class="text-right">
                                            <a href="/admin/policies/{{ $policy['policy_updater_id'] }}/edit"
                                               class="btn btn-default btn-xs">EDIT</a>
                                            {{-- This is added for dev convenience. There is no app interface to view this --}}
                                            <!-- policy_template_updates.id: {{ $policy['policy_template_updates_id'] }} -->
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- From _step2.blade.php --}}
                    <div class="row">
                        <div class="col-md-10 col-md-offset-1 heading">
                            <h4>Emails</h4>
                        </div>
                        @if (count($updater['emails']) > 0)
                            <div class="col-md-10 col-md-offset-1 sub_content">
                                <table class="table table-striped" id="emails_table">
                                    <thead>
                                    <tr>
                                        <th class="form-group">
                                            &nbsp;
                                        </th>
                                        <th class="form-group">
                                            <input type="text" class="form-control" id="col_2_search" placeholder="Business Name">
                                        </th>
                                        <th class="form-group">
                                            <input type="text" class="form-control" id="col_3_search" placeholder="Email">
                                        </th>
                                        <th class="form-group">
                                            &nbsp;
                                        </th>
                                    </tr>
                                    <tr>
                                        <th rowspan="1" colspan="1">
                                            <span>State</span>
                                        </th>
                                        <th rowspan="1" colspan="1">
                                            <span>Business Name</span>
                                        </th>
                                        <th rowspan="1" colspan="1">
                                            <span>Email</span>
                                        </th>
                                        <th rowspan="1" colspan="1" class="text-right">
                                            <a class="export btn btn-default btn-xs" href="/admin/policies/updates/{{ $updater['id'] }}/export/emails">EXPORT ALL EMAILS</a>
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($updater['emails'] as $email => $business)
                                        <tr>
                                            <td>{{ $business['state'] }}</td>
                                            <td>{{ $business['name'] }}</td>
                                            <td>{{ $email }}</td>
                                            <td class="text-right">
                                                <a href="/admin/business/{{ $business['id'] }}/view-as"
                                                   class="btn btn-default btn-xs">VIEW AS CLIENT</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>

                    <div class="row">
                        <div class="col-md-10 col-md-offset-1 heading">
                            <h4>Additional Emails</h4>
                        </div>
                        @if (count($updater['additional_emails']) > 0)
                            <div class="col-md-10 col-md-offset-1 sub_content">
                                <div class="col-md-10 col-md-offset-1 sub_content">
                                    <table class="table table-striped" id="additional_emails_table">
                                        <thead>
                                        <tr>
                                            <th rowspan="1" colspan="1">
                                                <span>State</span>
                                            </th>
                                            <th rowspan="1" colspan="1">
                                                <span>Business Name</span>
                                            </th>
                                            <th rowspan="1" colspan="1">
                                                <span>Email</span>
                                            </th>
                                            <th rowspan="1" colspan="1" class="text-right">
                                                <a class="export btn btn-default btn-xs" href="/admin/policies/updates/{{ $updater['id'] }}/export/additional_emails">EXPORT ALL EMAILS</a>
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($updater['additional_emails'] as $email => $business)
                                            <tr>
                                                <td>{{ $business['state'] }}</td>
                                                <td>{{ $business['name'] }}</td>
                                                <td>{{ $email }}</td>
                                                <td class="text-right">
                                                    @if (!empty($business['id']))
                                                        <a href="/admin/business/{{ $business['id'] }}/view-as"
                                                           class="btn btn-default btn-xs">VIEW AS CLIENT</a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td colspan="4">
                                                The table above shows all of the additional emails that were manually
                                                entered when this policy update was created. If there is no business
                                                associated with it, it would not have been sent out.
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="row">
                        <div class="col-md-10 col-md-offset-1 heading">
                            <h4>Active Clients / Consultants</h4>
                        </div>
                        <div class="col-md-10 col-md-offset-1 sub_content">
                            <textarea name="active_clients_text">{{ $updater['active_clients_text'] }}</textarea>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-10 col-md-offset-1 heading">
                            <h4>Inactive Clients:</h4>
                        </div>
                        <div class="col-md-10 col-md-offset-1 sub_content">
                            <textarea name="inactive_clients_text">{{ $updater['inactive_clients_text'] }}</textarea>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-10 col-md-offset-1 heading">
                            <h4>Other / Additional</h4>
                        </div>

                        <div class="col-md-10 col-md-offset-1 sub_content">
                            <textarea name="additional_text">{{ $updater['additional_text'] }}</textarea>
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
      window.PAGE_INIT.push(() => {
        var table = $("#emails_table").DataTable({
            "bStateSave": true,
            "orderClasses": false
        })

        table.search('')
            .columns().search('')
            .draw();

        CKEDITOR.config.readOnly = true;

        CKEDITOR.replace('active_clients_text', {
            customConfig: '/assets/js/plugins/ckeditor/admin_config.js'
        })
        CKEDITOR.replace('inactive_clients_text', {
            customConfig: '/assets/js/plugins/ckeditor/admin_config.js'
        })
        CKEDITOR.replace('additional_text', {
            customConfig: '/assets/js/plugins/ckeditor/admin_config.js'
        })
    })
    </script>
@stop
