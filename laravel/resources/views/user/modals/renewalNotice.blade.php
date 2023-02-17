<?php use \App\Http\Controllers\User\DashboardController; ?>
<div class='modal fade in' id='modalRenewalNotice' tabindex='-1' role='dialog' aria-labelledby='modalLabel' aria-hidden='true' data-backdrop="static" data-keyboard="false">
    <div class='modal-dialog'>
        <div class='modal-content'>
            <div class='modal-header'>
                <h4 class='modal-title text-center' id='modalLabel'>Annual Support Renewal</h4>
            </div>
            <div class='modal-body'>
                <p>
                    Your annual support and ongoing access to the HR Director program is due to renew on <strong>{{ $asaExpiration }}</strong>. Your support plan will automatically be renewed with the credit card on file before the expiration date.
                </p>
                <p>
                    If we do not have a valid credit card on file for you, you will receive an invoice for your renewal. 
                </p>
                <p>
                    If you have already made a payment, please disregard this message. Please call us if you have any questions! <strong>(800) 679-2760</strong> 
                </p>
            </div>
            <div class='modal-footer text-center'>
                    <button type='button' id="renewalNoticeCancel" class='btn btn-default btn-primary' data-dismiss='modal'>OK</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    window.PAGE_INIT.push(async () => {
      await import('https://cdn.jsdelivr.net/npm/js-cookie@2/src/js.cookie.min.js')
      let asaDate = new Date("<?php echo $asaExpiration ?>")
      let today = new Date();
      let timeDifference = asaDate.getTime() - today.getTime();
      let dayDifference = timeDifference / (1000 * 3600 * 24);

      if(dayDifference <= 30 && Cookies.get('asaNotice') != 'false') {
          Cookies.set('asaNotice', true);
          $("#modalRenewalNotice").show();
      }

      $("#renewalNoticeCancel").on('click', function() {
          Cookies.set('asaNotice', false, {expires: 1});
          $("#modalRenewalNotice").hide();
      });
    })
    

</script>