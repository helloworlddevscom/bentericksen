import '@/assets/styles/global.scss'
import '@/lib/bootstrap/bootstrap-3-3-1.min.css'
import '@/lib/font-awesome/font-awesome.css'

import('@/lib/jquery/jquery-1.10.1.min.js').then((jq) => {
    window.jQuery = window.$ = jq.default
    return Promise.all([
        import ('@/lib/bootstrap/bootstrap-3-3-1.min.js'),
        import ('@/lib/captcha')
    ])
}).then(() => {
    let timeout;

    let email = document.cookie.match('email=([^;]+)');

    if (email && email.length > 0) {
        $('input[name="email"]').val(decodeURIComponent(email[1]));
        $('input[name="remember"]').prop('checked', true);
    }

    timeout = setTimeout(() => {
        window.location.reload()
    }, SESSION_LIFETIME)
    
    document.addEventListener('visibilitychange', () => {
        document.visibilityState === 'visible' && window.location.reload()
    })
})