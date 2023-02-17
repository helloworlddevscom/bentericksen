<div class="modal fade" id="modalSessionTimeout" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4>Session Timeout</h4>
            </div>
            <div class="modal-body text-center">
                <div>
                    Your session is about to expire. Click "Continue" to prevent loss of work.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-dismiss="modal">
                        Continue
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    let modalSessionTimeoutTimeouts = []

    function debounce(func, wait = 100) {
      let timeout
      return function(...args) {
        clearTimeout(timeout)
        timeout = setTimeout(() => {
          func.apply(this, args)
        }, wait)
      }
    }

    

    function watchTimeout() {
        const MINUTE = 1000 * 60
        const SESSION_LIFETIME = ({{ config('session.lifetime') }}) * MINUTE
        const AUTOSAVE_DELAY = Math.floor(SESSION_LIFETIME * 0.333333)
        const MODAL_DISPLAY_DELAY = Math.floor(SESSION_LIFETIME * 0.666666)
        
        axios.get('/ping')
        
        return [
            setTimeout(() => {
                if ($('#policyForm').length) {
                    $('#policyForm + div [name=submit]').trigger('click')
                }
                if ($('#job-descriptions-form').length) {
                    $('#job-descriptions-form [type=submit]').trigger('click')
                }
            }, AUTOSAVE_DELAY),

            setTimeout(() => {
                $('#modalSessionTimeout').modal('show')
            }, MODAL_DISPLAY_DELAY),

            setTimeout(() => {
                window.location = '/'
            }, SESSION_LIFETIME + MINUTE)
        ]
    }

    function resetTimeout() {
        modalSessionTimeoutTimeouts.forEach((t) => clearTimeout(t))
        modalSessionTimeoutTimeouts = watchTimeout()
    }

    const resetTimeoutDebounced = debounce(resetTimeout, 1000)

    function bindResetEvents(context) {
        context.addEventListener('keydown', resetTimeoutDebounced)
        context.addEventListener('scroll', resetTimeoutDebounced)
        context.addEventListener('mousedown', resetTimeoutDebounced)
    }

    bindResetEvents(window)

    window.PAGE_INIT.push(function() {
        $('#modalSessionTimeout button').on('click', () => {
            resetTimeout()
        })

        window.CKEDITOR && window.CKEDITOR.on('instanceReady', () => {
          document.querySelectorAll('iframe').forEach((iframe) => {
            bindResetEvents(iframe.contentWindow)
          })    
        })
        
        resetTimeout()  
    })
</script>