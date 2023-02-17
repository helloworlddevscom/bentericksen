<form action="https://streamdent.net/" method="post" name="login">
  <input type="hidden" name="refer" value="">
  <input type="hidden" name="username" id="username" maxlength="20" value="{{ $username }}">
  <input type="hidden" name="password" id="password" maxlength="20" value="{{ $password }}">
</form>

<script>
  window.onload = function(){
    document.forms['login'].submit();
  }
</script>
