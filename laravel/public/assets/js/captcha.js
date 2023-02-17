(function (window, document, $) {

  // Intialize recaptcha
  var scripts = document.getElementsByTagName('script');
  var index = scripts.length - 1;
  var this_script = scripts[index];
  var site_key = this_script.getAttribute('data-site_key');
  window.onloadCallback = function () {
    grecaptcha.render('html_element', {
      'sitekey': site_key
    });
  }

// Handle submit
  window.onload = function () {

    var btn = _('password-btn');
    var form = _('password-form');
    var response = _('g-recaptcha-response');
    var message = _('captcha-message');

    message.classList.remove('hidden');
    message.style.display = "none";

    btn.addEventListener('click', function () {

      // Submit form if captcha completed
      if (response.value.length > 0) {
        form.action = window.location.origin + '/password/email';
        form.method = "POST";
        form.submit();
        return;
      }

      // Display captcha message
      displayCaptchaIncompleteMessage(message);

    });

  };

  function displayCaptchaIncompleteMessage(message) {
    $(message).show();
    setTimeout(function () {
      $(message).hide();
    }, 5000);
  }

  function _(id) {
    return document.getElementById(id);
  }

})(window, document, $);


