<ul class="nav nav-tabs" role="tablist" id="non_benefits_tab">
    <li class="active">
        <a href="#attendance" role="tab" data-toggle="tab">Attendance</a>
        <span class="caret emp-caret"></span>
    </li>
    <li>
        <a href="#leave_of_absence" role="tab" data-toggle="tab">Leave of Absence</a>
        <span class="caret emp-caret"></span>
    </li>
    <li>
        <a href="#job_description" role="tab" data-toggle="tab">Job Description</a>
        <span class="caret emp-caret"></span>
    </li>
    <li class="last">
        <a href="#paperwork" role="tab" data-toggle="tab">Paperwork</a>
        <span class="caret emp-caret"></span>
    </li>
</ul>
<div class="tab-content">
    <!-- Attendance -->
    <div class="tab-pane fade in active" id="attendance">
        @include('user.employees.partials.tabs.attendance')
    </div>
    <!-- End Attendance -->

    <!-- Leave of Absence -->
    <div class="tab-pane fade" id="leave_of_absence">
        @include('user.employees.partials.tabs.leave_of_absence')
    </div>
    <!-- End Leave of Absence -->

    <!-- Job Description -->
    <div class="tab-pane fade" id="job_description">
        @include('user.employees.partials.tabs.job_description')
    </div>
    <!-- End Job Description -->

    <!-- Paperwork -->
    <div class="tab-pane fade" id="paperwork">
        @include('user.employees.partials.tabs.paperwork')
    </div>
    <!-- End Paperwork -->

</div>