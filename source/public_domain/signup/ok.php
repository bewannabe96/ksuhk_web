<?php

	$env_page = "page_signup_ok";

	include $_SERVER["DOCUMENT_ROOT"].'/config.php';

	if( !isset($_POST["submit"]) )
		echo "<script>document.location='/signup';</script>";

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>KSUHK - 회원가입</title>
	<!-- HTML Header -->
	<?php include $WEB_ROOT.'/templates/html-header.php'; ?>
</head>
<body class="bg-faded">

<!-- Navigator -->
<?php include $WEB_ROOT.'/templates/navigator.php'; ?>

<div class="container my-5">
	<div class="row col-lg-10 offset-lg-1">

<?php

	include $WEB_ROOT.'/templates/status.php';

	include $WEB_ROOT.'/classes/UserDBManager.php';
	$user_db = UserDBManager::createUserDBManager();

	if( $user_db !== UserDBManager::$CONNECT_ERROR ) {
		// IF USER AND EMAIL DOES NOT EXIST
		if( !( $user_db->user_exist( $_POST["UserUsername"] ) ) && !( $user_db->email_exist( $_POST["UserEmail"] ) ) ) {
			if( $user_db->insert_new_user( $_POST["UserName"], $_POST["UserUsername"], hash("sha256", $_POST["UserPassword"]),
					$_POST["UserSchool"], $_POST["UserGrade"], $_POST["UserMajor"], $_POST["UserPhoneNo"], $_POST["UserEmail"],
					$_POST["UserBirth"], $_POST["UserKakaoID"] ) === UserDBManager::$INSERT_ERROR ) {
				ERROR_PAGE( "회원정보 등록중 오류가 발생했습니다." );
			} else {
?>
				<div class="card w-100">
					<div class="media card-block text-info p-5">
						<div class="media-left mr-3"><h1><span class="fa fa-info-circle fa-3x"></span></h1></div>
						<div class="media-body align-bottom">
							<h2>환영합니다!</h2>
							<p class="text-muted mb-0">관리자로부터 가입승인을 기다리고 있습니다.</p>
							<p class="text-muted my-0">최대한 빨리 처리해 드리겠습니다.</p>
						</div>
					</div>
					<div class="card-footer text-right">
						<a href="<?=$MAIN_DOMAIN?>" class="btn btn-outline-primary">홈으로</a>
					</div>
				</div>
<?php
			}

		// IF USER OR EMAIL ALREADY EXIST
		} else {
			$result_code = $user_db->user_exist( $_POST["UserUsername"] ) ? 1 : 2;
?>
			<form method="post" action="./"><input type="text" name="resultCode" value="<?=$result_code?>" hidden>
			<input type="text" name="UserName" value="<?=$_POST["UserName"]?>" hidden>
			<input type="text" name="UserUsername" value="<?=$_POST["UserUsername"]?>" hidden>
			<input type="text" name="UserPhoneNo" value="<?=$_POST["UserPhoneNo"]?>" hidden>
			<input type="text" name="UserEmail" value="<?=$_POST["UserEmail"]?>" hidden>
			<input type="date" name="UserBirth" value="<?=$_POST["UserBirth"]?>" hidden>
			<input type="text" name="UserKakaoID" value="<?=$_POST["UserKakaoID"]?>" hidden>
			</form>
			<script>document.getElementsByTagName("form")[0].submit();</script>
<?php
		}

	} else {
		ERROR_PAGE( "현재 서버문제로 회원가입하실 수 없습니다." );
	}
?>

	</div>
</div>

<!-- Footer -->
<?php include $WEB_ROOT.'/templates/footer.php'; ?>

</body>
</html>
