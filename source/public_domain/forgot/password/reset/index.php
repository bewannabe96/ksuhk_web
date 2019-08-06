<?php

	$env_page = "page_forgot_password_reset";

	include $_SERVER["DOCUMENT_ROOT"].'/config.php';

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
<?php
		if( isset( $_POST["reset-submit"] ) && $_POST["UserPassword"] != "" ) {
			include $WEB_ROOT.'/classes/UserDBManager.php';
			$user_db = UserDBManager::createUserDBManager();
	
			if( $user_db !== UserDBManager::$CONNECT_ERROR ) {
				if( $user_db->update_user_password( $_SESSION["user_username"],
						hash("sha256", $_POST["UserPassword"]) ) === UserDBManager::$UPDATE_ERROR ) {
					echo '<script>createAlert("비밀번호 재설정중 오류가 발생했습니다.", "알림!");</script>';
				} else {
					echo '<script>alert("비밀번호가 정상적으로 변경되었습니다. 다시 로그인해주세요.");</script>';
					echo "<script>document.location='/login';</script>";
				}
			} else {
				include $WEB_ROOT.'/templates/status.php';
				ERROR_PAGE( "서버문제로 비밀번호를 재설정할 수 없습니다." );
			}
	
		} else if( isset( $_POST["submit"] ) && $_POST["secureCode"] != "" ) {
			if( $_POST["secureCode"] == $_SESSION["secure_code"] ) {
				include $WEB_ROOT.'/templates/popup-message.php';
				include $WEB_ROOT.'/templates/passwordreset.php';
			} else {
?>
				<form action="../" method="post" id="return-form">
					<input name="resultCode" value="1" hidden>
					<input name="UserUsername" value="<?=$_POST["UserUsername"]?>" hidden>
					<input name="UserEmail" value="<?=$_POST["UserEmail"]?>" hidden>
				</form>
				<script>document.getElementById("return-form").submit();</script>
<?php
			}
	
		} else {
			echo "<script>document.location='/forgot';</script>";
		}
?>
		</div>
	</div>
</div>

<!-- Footer -->
<?php include $WEB_ROOT.'/templates/footer.php'; ?>

</body>
</html>
