<div class="modal fade" id="modalLeaveOfAbsence" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
                <div class="edit">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                        </button>
                        <h4 class="modal-title text-center" id="modalLabel">New Leave of Absence</h4>
                    </div>
                    <div class="modal-body">
                        <div class="col-md-12">
                            <div class="col-md-12 sub_content">
                                <div class="row">
                                    <label for="request_start_date" class="col-md-12">Start Date:</label>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <input type="text" class="form-control date-picker time-off" id="request_start_date" name="leave_of_absence[start_at]" placeholder="mm/dd/yyyy">
                                            <span class="input-group-addon"><label for="request_start_date"><i class="fa fa-calendar"></i></label></span>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <label for="request_end_date" class="col-md-12">End Date:</label>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <input type="text" class="form-control date-picker time-off" id="request_end_date" name="leave_of_absence[end_at]" placeholder="mm/dd/yyyy">
                                            <span class="input-group-addon"><label for="request_end_date"><i class="fa fa-calendar"></i></label></span>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <label for="request_pto_type" class="col-md-12">Leave Type:</label>
                                    <div class="col-md-5">
                                        <div class="input-group">
                                            <select id="request_pto_type" class="form-control" name="leave_of_absence[type]">
                                                <option value="">- Select One -</option>
                                                @foreach( $leaveTypes as $key => $type )
                                                    <option value="{{ $key }}">{{ $type }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <br>

                                <div class="row">
                                    <label for="status" class="col-md-12">Status:</label>
                                    <div class="col-md-5">
                                        <div class="input-group">
                                            <select id="status" class="form-control" name="leave_of_absence[status]">
                                                <option value="approved" selected>Approved</option>
                                                <option value="pending">Pending</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <label for="request_reason" class="col-md-12">Reason:</label>
                                    <div class="col-md-10">
                                        <textarea class="form-control request_textarea" id="request_reason" name="leave_of_absence[reason]"></textarea>
                                    </div>
                                </div>
                                <br>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer text-center">
                        <button type="button" class="btn btn-default btn-primary" data-dismiss="modal">CANCEL</button>
                        <button class="btn btn-default btn-primary" name="action" value="leave_of_absence">SAVE</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>