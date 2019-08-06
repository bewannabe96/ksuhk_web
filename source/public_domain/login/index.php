<?php

	$env_page = "page_login";

	include $_SERVER["DOCUMENT_ROOT"].'/config.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>KSUHK - 로그인</title>
	<!-- HTML Header -->
	<?php include $WEB_ROOT.'/templates/html-header.php'; ?>
</head>
<body class="bg-faded">

<!-- Navigator -->
<?php include $WEB_ROOT.'/templates/navigator.php'; ?>

<div class="container mb-0 my-0 my-lg-5 mb-sm-3 p-0">
	<div class="row w-100 m-0">
		<div class="col-lg-6 offset-lg-3 p-0 p-sm-3">
		<div class="card">
			<h4 class="card-header">로그인</h4>
			<div class="card-block">
			<form id="login-form" method="post" action="./ok.php<?php if(isset($_GET["direct"])){ echo "?direct=$_GET[direct]"; }?>">
				<div id="username-form-group" class="form-group <?php if(isset($_POST["resultCode"])&&$_POST["resultCode"]==1){echo "has-warning";} ?>">
					<label>아이디</label>
					<input class="form-control form-control-warning" type="text" placeholder="아이디" id="username-input" name="UserUsername" value="<?php if(isset($_POST["UserUsername"])){echo $_POST["UserUsername"];} ?>" autocomplete="off">
				</div>
				<div id="passwd-form-group" class="form-group <?php if(isset($_POST["resultCode"])&&$_POST["resultCode"]==2){echo "has-danger";} ?>">
					<label>비밀번호</label>
					<input class="form-control form-control-danger" type="password" placeholder="비밀번호" id="passwd-input" autocomplete="off">
					<input type="password" id="passwd-encrypt-input" name="UserPassword" hidden>
				</div>
				<div class="form-group mt-3">
					<a href="/forgot"class="float-xs-right">아이디/비밀번호 찾기</a>
					<span class="float-xs-right mx-1">·</span>
					<a href="/signup" class="float-xs-right">회원가입</a>
				</div>
				<button type="submit" name="submit" class="btn btn-primary btn-block mt-4">로그인</button>
			</form>
			<script src="/library/sha256.min.js"></script>
			<script>
				document.getElementById('login-form').onsubmit = function() {
					document.getElementById('passwd-encrypt-input').value = sha256(document.getElementById('passwd-input').value);
				}
			</script>
			</div>
		</div>
		</div>
	</div>
</div>
<?php include $WEB_ROOT.'/templates/popup-message.php'; ?>
	<script>

<?php
	if( isset ( $_POST["resultCode"] ) ) {
		if( $_POST["resultCode"] == 1 ) {
?>
			createAttention("사용자가 존재하지 않습니다.");
			document.getElementById('username-input').focus()
<?php
		} else if( $_POST["resultCode"] == 2 ) {
?>
			createAttention("비밀번호가 일치하지 않습니다.");
			document.getElementById('passwd-input').focus()
<?php
		} else if( $_POST["resultCode"] == 3 ) {
?>
			createInfo("회원가입 승인을 기다리고 있습니다.");
<?php
		} else if( $_POST["resultCode"] == 4 ) {
?>
			createAlert("회원가입이 거부되셨습니다.", "죄송합니다!");
<?php
		}
	}
?>
	</script>

<!-- Footer -->
<?php include $WEB_ROOT.'/templates/footer.php'; ?>

</body>
</html>
