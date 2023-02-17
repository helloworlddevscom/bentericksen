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
                    <h3>Policy Update List</h3>
                </div>

                <div class="col-md-12 content">
                    <div class="table-responsive">
                        <table class="table table-striped" id="policy_updater_table">
                            <thead>
                            <tr class="sub-heading">
                                <th rowspan="2">
                                    <span>Title</span>
                                </th>
                                <th rowspan="2">
                                    <span>Send Date</span>
                                </th>
                                <th colspan="2">
                                    <span>Current Client</span>
                                </th>
                                <th colspan="2">
                                    <span>Past Client</span>
                                </th>
                            </tr>
                            <tr>
                                <th>
                                    <span>Send</span>
                                </th>
                                <th>
                                    <span>Accepted</span>
                                </th>
                                <th>
                                    <span>Send</span>
                                </th>
                                <th class="bg_none"></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($updates as $update)
                                <tr>
                                    <td>{{ $update->title ?? "pending"}}</td>
                                    @if ($update->start_date)
                                        <td>{{date('m/d/Y', strtotime($update->start_date))}}</td>
                                    @else
                                        <td></td>
                                    @endif
                                    <td>...</td>
                                    <td>...</td>
                                    <td>...</td>
                                    <td class="text-right">
                                        @if(!$update->is_finalized)
                                            <a href="/admin/policies/updates/create?step={{$update->step}}&id={{$update->id}}" class="btn btn-default btn-xs">RESUME</a>
                                            <a href="#" class="policy-updater-delete btn btn-danger btn-xs" data-updater="{{ $update->id }}">DELETE</a>
                                        @else
                                            @if($update->status === 'ready')
                                                @if(config('app.env') !== 'production')
                                                    <a href="/admin/policies/updates/{{$update->id}}/trigger" class="btn btn-info btn-xs">UPDATE NOW</a>
                                                @endif
                                                <a href="/admin/policies/updates/{{$update->id}}" class="btn btn-info btn-xs">PENDING</a>
                                            @else
                                                <a href="/admin/policies/updates/{{$update->id}}" class="btn btn-default btn-xs">VIEW</a>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="col-md-12 text-center">
                            <a href="/admin/policies/updates/create" class="btn btn-default btn-primary btn-xs">ADD NEW</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Warning -->
    <div class="modal fade" id="modalDeleteWarning" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="edit">
                    <div class="modal-header">
                        <h4 class="modal-title text-center" id="modalLabel">Warning</h4>
                    </div>
                    <div class="modal-body">
                        <p>You're about to delete an <strong>in-progress policy update process</strong>. This operation can't be undone.</p>
                        <p>Are you sure you want to proceed?<strong> Note:</strong> The updated policy will not be affected.</p>
                    </div>
                    <div class="modal-footer text-center">
                        <a href="#" class="btn btn-default btn-primary" data-dismiss="modal">CANCEL</a>
                        <a href="#" id="modal_delete_confirm" class="btn btn-danger btn-primary">DELETE</a>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    <!-- End Delete Warning -->
@stop

@section('foot')
    @parent
@stop
