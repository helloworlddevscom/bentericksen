{{--
"Employee Locked" modal, which shows when a regular employee logs in before the owner/manager has accepted the terms.
See laravel/app/Http/Middleware/AcceptLicense.php
--}}
<div id="license-agreement-modal in" class="modal fade bs-example-modal-lg license-agreement-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content license-agreement">
            <h3>BENT ERICKSEN & ASSOCIATES</h3>
            <p>Your Account Is Not Active, Please See Your Administrator.</p>
            <div class="license-agreement-buttons-div">
                <div class="license-agreement-buttons">
                    <a class="btn btn-default btn-md license-agreement-decline" href="/auth/logout">Close</a>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        $('.license-agreement-modal').modal('show');
    });
</script>
