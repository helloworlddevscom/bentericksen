<script>
  /**
  * The following event is sent when the page loads. You could
  * wrap the event in a JavaScript function so the event is
  * sent when the user performs some action.
  */
  gtag('event', 'screen_view', {
    'app_name': 'HR Director',
    'screen_name': '{{ $view_name }}'
  })
</script>