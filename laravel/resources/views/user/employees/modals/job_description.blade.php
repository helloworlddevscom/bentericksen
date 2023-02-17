<div class="modal fade" id="modalJobDescriptions" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-center" id="modalLabel">Job Description</h4>
            </div>
            <div class="modal-body">
                @foreach( $employee->jobDescriptions as $jobDescription)
                    <h4>{{ $jobDescription->name }}</h4>
                    <div>{!! clean($jobDescription->description) !!}</div>
                @endforeach
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>