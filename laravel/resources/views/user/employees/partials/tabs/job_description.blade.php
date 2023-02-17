<div class="col-md-12 text-center">
    <h4>Job Description</h4>
</div>
<div class="col-md-12 text-center">
    <div class="form-group">
        @if($employee->jobDescriptions->count() > 0)
            <button type="button" class="btn btn-default btn-primary btn-xs modal-button" id="view_job_description" data-toggle="modal" data-target="#modalJobDescriptions">VIEW JOB DESCRIPTION</button>
        @endif
    </div>
</div>
<div class="col-md-12">
    <div class="form-group">
        <div class="row">
            <label for="job_description_reports_to" class="col-md-3 col-md-offset-1 control-label">Reports To:</label>
            <div class="col-md-4">
                @if (($viewUser->roles[0]->pivot->role_id > $employee->roles[0]->pivot->role_id && $viewUser->roles[0]->pivot->role_id != '4') || ($viewUser->roles[0]->pivot->role_id == '3' && $employee->roles[0]->pivot->role_id == '4'))
                <input disabled type="text" class="form-control" id="job_description_reports_to" name="job_description[job_reports_to]" placeholder="Name" value="{{ $employee->job_reports_to }}">
                @else 
                <input type="text" class="form-control" id="job_description_reports_to" name="job_description[job_reports_to]" placeholder="Name" value="{{ $employee->job_reports_to }}">
                @endif
            
            </div>
        </div>
    </div>
</div>
<div class="col-md-12">
    <div class="form-group">
        <div class="row">
            <label for="job_description_location" class="col-md-3 col-md-offset-1 control-label">Location:</label>
            <div class="col-md-4">
                @if (($viewUser->roles[0]->pivot->role_id > $employee->roles[0]->pivot->role_id && $viewUser->roles[0]->pivot->role_id != '4') || ($viewUser->roles[0]->pivot->role_id == '3' && $employee->roles[0]->pivot->role_id == '4'))
                <input disabled type="text" class="form-control" id="job_description_location" name="job_description[job_location]" placeholder="Location" value="{{ $employee->job_location }}">
                @else 
                <input type="text" class="form-control" id="job_description_location" name="job_description[job_location]" placeholder="Location" value="{{ $employee->job_location }}">
                @endif
                
            </div>
        </div>
    </div>
</div>
<div class="col-md-12">
    <div class="form-group">
        <div class="row">
            <label for="job_description_department" class="col-md-3 col-md-offset-1 control-label">Department:</label>
            <div class="col-md-4">
                @if (($viewUser->roles[0]->pivot->role_id > $employee->roles[0]->pivot->role_id && $viewUser->roles[0]->pivot->role_id != '4') || ($viewUser->roles[0]->pivot->role_id == '3' && $employee->roles[0]->pivot->role_id == '4'))
                <input disabled type="text" class="form-control" id="job_description_department" name="job_description[job_department]" placeholder="Department" value="{{ $employee->job_department }}">
                @else 
                <input type="text" class="form-control" id="job_description_department" name="job_description[job_department]" placeholder="Department" value="{{ $employee->job_department }}">
                @endif
                
            </div>
        </div>
    </div>
</div>
<!-- Job Descriptions View -->
@if($employee->jobDescriptions->count() > 0)
    @include('user.employees.modals.job_description')
@endif
<!-- End Job Descriptions View -->
<div class="col-md-12 text-center">
    <div class="form-group">
        @if (($viewUser->roles[0]->pivot->role_id > $employee->roles[0]->pivot->role_id && $viewUser->roles[0]->pivot->role_id != '4') || ($viewUser->roles[0]->pivot->role_id == '3' && $employee->roles[0]->pivot->role_id == '4'))
            <button disabled type="submit" class="btn btn-default btn-primary btn-xs" name="action" value="job_description">Update</button>
        @else 
            <button type="submit" class="btn btn-default btn-primary btn-xs" name="action" value="job_description">Update</button>
        @endif
    </div>
</div>
