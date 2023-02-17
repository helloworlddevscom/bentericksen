<ul class="nav nav-tabs" role="tablist" id="history_tab"></ul>
<div class="tab-content">
    <div class="tab-pane fade in active" id="history">
        @if($viewUser->hasRole('manager') && !$viewUser->permissions('m148'))

        @else
            <div class="col-md-12 text-center">
                <h4>History</h4>
            </div>
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-striped" id="table">
                        <thead>
                        <tr>
                            <td>
                                <label class="control-label">Date</label>
                            </td>
                            <td>
                                <label class="control-label">Category</label>
                            </td>
                            <td>
                                <label class="control-label">Note</label>
                                {!! $help->button("u3060") !!}
                            </td>
                            <td class="bg_none"></td>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($employee->history as $history)
                            <tr>
                                <td>{{ date('m/d/Y', strtotime($history->created_at)) }}</td>
                                <td>{{ ucfirst($history->type) }}</td>
                                <td>{{ ucwords($history->note) }}</td>
                                <td class="text-right">
                                    <button type="button" class="btn btn-default btn-primary btn-xs history" data-target="#modalHistoryView" data-toggle="modal" data-history-id="{{ $history->id }}">VIEW</button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- Start History View Modal -->
            @include('user.employees.modals.history')
            <!-- End History View Modal -->
        @endif
    </div>
</div>