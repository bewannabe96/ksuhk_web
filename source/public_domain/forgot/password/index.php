<?php

	$env_page = "page_forgot_password";

	include $_SERVER["DOCUMENT_ROOT"].'/config.php';

	if( $_POST["UserUsername"] != "" && $_POST["UserEmail"] != "" ) {
		include $WEB_ROOT.'/classes/UserDBManager.php';
		$user_db = UserDBManager::createUserDBManager();

		if( $user_db !== UserDBManager::$CONNECT_ERROR ) {
			$user = $user_db->select_user_byusername( $_POST["UserUsername"] );
			$user_exist = $user==UserDBManager::$USER_NOT_EXIST ? FALSE : ( $user["UserEmail"]==$_POST["UserEmail"] ? TRUE : FALSE );

			if( $user_exist ) {
				$_SESSION["user_username"] = $_POST["UserUsername"];
				$_SESSION["secure_code"] = substr( hash( "sha256", time() ), 0, 11 );

				$mail_subject = "KSUHK 비밀번호 재설정";

				$mail_message = '
					<html>
					<head>
						<title>KSUHK 비밀번호 재설정</title>
					</head>
					<body>
						<h3 style="color:#5d5d5d">KSUHK 홍콩한인유학생 총학생회</h3>
						<h1 style="color:#0d203c;font-size:60px;">KSUHK</h1>
						<h1 style="color:#0d203c;font-size:30px;">비밀번호 재설정</h1><br>
						<p style="font-size:15px">
							아래 보안코드로 계정 비밀번호를 다시 설정해 주세요.<br><br>
							보안코드: <strong>' . $_SESSION["secure_code"] . '<br><br><br>
							감사합니다!<br><br>
						</p>
					</body>
					</html>
				';

				$headers[] = "MIME-Version: 1.0";
				$headers[] = "Content-type: text/html; charset=utf-8";
				$headers[] = "To: $user[UserName] <$user[UserEmail]>";
				$headers[] = "From: KSUHK 홍콩한인유학생 총학생회 <do-not-reply@ksuhk.com>";

				mail($user["UserEmail"], $mail_subject, $mail_message, implode("\r\n", $headers));
			}
		} else {
			ERROR_PAGE( "서버문제로 비밀번호를 재설정할 수 없습니다." );
		}

	} else {
		echo "<script>alert('아이디와 이메일을 모두 입력해 주세요.');</script>";
		echo "<script>document.location='/forgot';</script>";
	}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>KSUHK - 비밀번호 재설정</title>
	<!-- HTML Header -->
	<?php include $WEB_ROOT.'/templates/html-header.php'; ?>
</head>
<body class="bg-faded">

<!-- Navigator -->
<?php include $WEB_ROOT.'/templates/navigator.php'; ?>

<div class="container my-5">
	<div class="row">
		<div class="col-lg-6 offset-lg-3">
			<div class="card w-100">
<?php
			if( $user_exist ) {
?>
				<div class="card-header">
					<h5>보안코드 확인</h5>
				</div>
				<div class="card-block">
					<p>회원가입시 입력하신 이메일로 보안코드가 전송되었습니다.</p>
					<form action="./" method="post" id="resend-form">
						<input name="UserUsername" value="<?=$_POST["UserUsername"]?>" hidden>
						<input name="UserEmail" value="<?=$_POST["UserEmail"]?>" hidden>
					</form>
					<form action="/forgot/password/reset/" method="post" class="text-right">
						<input name="UserUsername" value="<?=$_POST["UserUsername"]?>" hidden>
						<input name="UserEmail" value="<?=$_POST["UserEmail"]?>" hidden>
						<div id="secure-form-group" class="form-group text-left">
							<label>보안코드</label>
							<div class="input-group">
								<span class="input-group-addon fa fa-key"></span>
								<input type="text" class="form-control form-control-danger" name="secureCode" placeholder="보안코드" autocomplete="off">
								<span class="input-group-btn">
									<button class="btn btn-outline-info" type="button" id="resend-btn">보안코드 재전송</button>
								</span>
							</div>
						</div>
						<input type="submit" name="submit" class="btn btn-primary" value="확인">
					</form>
					<script>
						document.getElementById("resend-btn").onclick = function() {
							document.getElementById("resend-form").submit();
							alert("보안코드가 재전송되었습니다.");
						}
					</script>
<?php
						if( isset( $_POST["resultCode"] ) && $_POST["resultCode"] == 1 ) {
							include $WEB_ROOT.'/templates/popup-message.php';
?>
							<script>
								createAttention('보안코드가 일치하지 않습니다.');
								document.getElementById('secure-form-group').className += ' has-danger';
							</script>
<?php
						}
?>
				</div>
<?php
			} else {
?>
				<div class="media card-block text-warning p-5">
					<div class="media-left mr-3"><h1><span class="fa fa-times-circle-o fa-2x"></span></h1></div>
					<div class="media-body mt-2">
						<h2>죄송합니다!</h2>
						<p class="text-muted mb-0">입력하신 정보로 가입된 회원을 찾을 수 없습니다.</p>
					</div>
				</div>
				<div class="card-footer text-right">
					<div class="btn-group">
						<a href="/forgot" class="btn btn-outline-primary">뒤로가기</a>
						<a href="/signup" class="btn btn-outline-primary">회원가입</a>
					</div>
				</div>
<?php
			}
?>
			</div>
		</div>
	</div>
</div>

<!-- Footer -->
<?php include $WEB_ROOT.'/templates/footer.php'; ?>

</body>
</html>
