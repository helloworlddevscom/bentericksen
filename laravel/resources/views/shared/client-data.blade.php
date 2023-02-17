<script type="text/javascript">
    @include('shared.ck')

    Object.assign(window, {
      PAGE_INIT: [],
      EMPLOYEE_COUNT_WARNING_SHOW_WARNING:  {{ Session::get('employee_count_warning')['show_warning'] ? 'true' : 'false' }},
      EMPLOYEE_COUNT_WARNING_SHOW_REMINDER:  {{ Session::get('employee_count_warning')['show_reminder'] ? 'true' : 'false' }},
      SESSION_SET_IGNORED:  {{ Session::get('ignored') ? 'true' : 'false' }},
      SESSION_SET_ACCEPTED:  '{{ Session::get("accepted") }}',
      ASA_EXPIRATION:  {{ isset($asaExpiration) ? "'{$asaExpiration}'" : 'null'}},
      LARAVEL_TOKEN:  "{{ csrf_token() }}",
      CERT_OPTIONS:  "<option value=''>- Select One -</option>",
    })

    
    window.CERT_OPTIONS += "<option value='new'>Add New</option>";

    @if(!empty($licensures))
      window.CERT_OPTIONS += " @foreach($licensures as $licensure) <option value='{{ $licensure->id }}'>{{ $licensure->name }}</option> @endforeach ";
    @endif
</script>

<?php if(config('app.env') !== "production") {
    $stripePubKey = config('stripe.api.pub_key');
} else {
    $stripePubKey = config('stripe.api.prod.pub_key');
} ?>

<script type="text/javascript">
    Object.defineProperty(window, 'env', {
        get() {
            return {
                STRIPE_KEY: "{{ $stripePubKey }}"
            }
        }
    })
</script>