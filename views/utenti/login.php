<script>
	var letFormSubmit=false;
</script>
<form class="form-signin" method="POST" id="loginForm"
	action="<?=SERV_URL ?>" method="post" onsubmit="return letFormSubmit;">
	<h2 class="form-signin-heading">Effettua il login</h2>
	<div id="ajaxSubmit1" class="alert" style="display: none"></div>
	<label for="inputUsername" class="sr-only">Username</label> <input
		type="text" id="inputUsername" name="username" class="form-control"
		placeholder="Username" required autofocus> <label for="inputPassword"
		class="sr-only">Password</label> <input type="password"
		id="inputPassword" name="password" class="form-control"
		placeholder="Password" required>
	  <script src="https://www.google.com/recaptcha/api.js?render=<?=RECAPTCHA_SITE_KEY?>"></script>
	<script>
var captchaToken;
grecaptcha.ready(function() {
	doRecaptcha();
});

function doRecaptcha()
{
	$("#btnLogin").attr("disabled","disabled");
	$("#btnLogin").html("Verifico captcha...");
	grecaptcha.execute('<?=RECAPTCHA_SITE_KEY?>', {action: 'login'}).then(function(token) {
		captchaToken=token;
		$("#btnLogin").attr("disabled","").removeAttr("disabled");
		$("#btnLogin").html("Login");
	});
}
</script>
	<p><a href="https://chadpass.mmonline.it/requestCode.php">Forgot your username or password? First access?</a></p>
	<button  type="submit" class="btn btn-lg btn-primary btn-block" id="btnLogin" disabled="disabled" >Verifico captcha...</button>
</form>
<script>

$('input').on("keypress", function(e) {
            /* ENTER PRESSED*/
            if (e.keyCode == 13) {
                $("#btnLogin").trigger( "click" );
            }
        });
	
</script>
<?php

$vars=Array("username"=>"$('#inputUsername').val()",
			"password"=>"$('#inputPassword').val()",
			"recaptchaResponse"=>"captchaToken"
	);

ajaxSubmit ( 1, "utenti", "login", $vars, "btnLogin",
							"letFormSubmit=true; $('#loginForm').submit()"," doRecaptcha(); "
		);

?>
