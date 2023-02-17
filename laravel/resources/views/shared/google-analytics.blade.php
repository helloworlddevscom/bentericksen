<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id={{ config('analytics.google.measurement_id') }}"></script>
<script>
window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}

gtag('js', new Date())
gtag('config', '{{ config('analytics.google.measurement_id') }}')
</script>
<!-- Google tag (gtag.js) -->