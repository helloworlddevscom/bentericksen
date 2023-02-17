@extends('user.wrap')

@section('content')
    <div id="main_body">
        <div class="container" id="main">
            <div class="row main_wrap">

                <div class="col-md-12 text-center">
                    <h3>Job Descriptions</h3>
                </div>

                <div class="col-md-12 text-center">
                    <a href="/user/job-descriptions/create" class="btn btn-primary btn-xs">CREATE NEW JOB
                        DESCRIPTION</a>
                </div>
                <div class="col-md-12 content">
                    <div class="col-md-10 col-md-offset-1 table-responsive">
                        <table class="table table-striped" id="job_description_table">
                            <thead>
                            <tr>
                                <th>
                                    <span>Job Description</span>
                                </th>
                                <th>
                                    <span>Employee(s)</span>
                                </th>
                                <th class="bg_none"></th>
                            </tr>
                            </thead>
                            <tbody class="jobs">
                            <?php $i = 0; ?>
                            @foreach($jobDescriptions AS $job)
                                @if(!$job->is_default)
                                    <tr>
                                        <td>{{ $job->name }}</td>
                                        <td>
                                            <?php $i = 0; ?>
                                            <?php foreach($job->assigned as $assigned): ?>
                                                @if ($assigned->status == 'enabled')
                                                    @if ($i >= 2)
                                                        <?php break; ?>
                                                    @endif
                                                    <span>{{ $assigned->first_name }} {{ $assigned->last_name }}@if ($i !== count($job->assigned) -1),@endif</span>
                                                    <?php $i++ ?>
                                                @endif
                                            <?php endforeach; ?>
                                            @if(count($job->assigned) > 2)
                                                <span>...</span>
                                            @endif
                                        </td>

                                        <td class="text-right">
                                            @if( ! $job->is_default)
                                                <a href="/user/job-descriptions/{{ $job->id }}/print"
                                                   class="btn btn-default btn-xs btn-primary" target="_blank">PRINT</a>
                                                <a href="/user/job-descriptions/{{ $job->id }}/edit"
                                                   class="btn btn-default btn-xs btn-primary">EDIT</a>
                                            @endif
                                            <a href="/user/job-descriptions/{{ $job->id }}/duplicate"
                                               class="btn btn-default btn-xs btn-primary">DUPLICATE</a>
                                            @if( ! $job->is_default)
                                                <button class="btn btn-default btn-xs btn-delete btn-primary modal-button"
                                                        data-job-id="{{ $job->id }}">DELETE
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                    <?php $i++; ?>
                                @endif
                            @endforeach
                            <?php if ($i === 0): ?>
                            <tr>
                                <td colspan='3'><strong>No Job Descriptions Created</strong></td>
                            </tr>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="modal fade" id="deletionModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
                     aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form method="post" action="/user/job-descriptions/0">
                                {{ csrf_field() }}
                                <input type="hidden" name="_method" value="delete">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                                                class="sr-only">Close</span></button>
                                    <h4 class="modal-title" id="modalLabel">Confirm Deletion</h4>
                                </div>
                                <div class="modal-body">
                                    Are you sure you want to delete this Job Description?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Delete</button>
                                </div>
                            </form>
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
        $('.jobs').on('click', '.modal-button', function () {
            var jobId = $(this).attr('data-job-id');
            var action = $('#deletionModal form').attr('action');
            action = action.substring(0, action.length - 1) + jobId;

            $('#deletionModal form').attr('action', action);
            $('#deletionModal').modal();
        });
    </script>
@stop
