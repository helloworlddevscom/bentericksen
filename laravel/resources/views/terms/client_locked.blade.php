<div id="license-agreement-modal in" class="modal fade bs-example-modal-lg license-agreement-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content license-agreement">
            <h3>BENT ERICKSEN & ASSOCIATES</h3>
            <p>Your account has expired, please contact us to renew your subscription.</p>
            <br>
            <p>800.679.2760</p>
            <a href="{{ url('/') }}">www.bentericksen.com</a>
            <div class="license-agreement-buttons-div">
                <div class="license-agreement-buttons">
                    <a class="btn btn-default btn-md license-agreement-decline" href="{{ url('/auth/logout') }}">Close</a>
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