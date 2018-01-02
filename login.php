<script type="text/javascript" src="sha512.js"></script>
<script type="text/javascript" src="forms.js"></script>
<?php

?>
<form action="process_login.php" method="post" name="login_form">
   username: <input type="text" name="userid" /><br />
   Password: <input type="password" name="password" id="password"/><br />
   <input type="button" value="Login" onclick="formhash(this.form, this.form.password);" />
</form>
<?

?>