<div id="modalEmployeeCount" type="warning" class="modal fade" tabindex="-1" role="dialog"
     aria-labelledby="Number of Employees">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-center" id="modalLabel">Number of Employees</h4>
                <button id="ignoreEmployeeReminder" type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="modal-description">
                    <p>Please enter the current number of employees. This is necessary for creating your policy manual.</p>
                </div>
                <div class="form-inline">
                    <label for="employees">Current Employee Number:</label>
                    <input id="employeeCount" type="text" name="employees" class="form-control">
                    <small class="feedback errors"></small>
                </div>
                <div class="modal-contact"></div>
            </div>
            <div class="modal-footer text-center">

                <button type="button"
                        class="btn btn-primary"
                        id="updateEmployeeCount"
                        data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Processing">Save</button>
            </div>
        </div>
    </div>
</div>