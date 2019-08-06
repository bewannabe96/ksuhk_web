<?php

	$env_page = "page_forgot_username";

	include $_SERVER["DOCUMENT_ROOT"].'/config.php';

	if( $_POST["UserName"] != "" && $_POST["UserEmail"] != "" ) {
		include $WEB_ROOT.'/classes/UserDBManager.php';
		$user_db = UserDBManager::createUserDBManager();

		if( $user_db !== UserDBManager::$CONNECT_ERROR ) {
			$user_username = $user_db->select_username_byemail( $_POST["UserName"], $_POST["UserEmail"] );
			if( $user_username != UserDBManager::$USER_NOT_EXIST ) {
				$mail_subject = "KSUHK 분실 아이디";

				$mail_message = '
					<html>
					<head>
						<title>KSUHK 분실 아이디</title>
					</head>
					<body>
						<h3 style="color:#5d5d5d">KSUHK 홍콩한인유학생 총학생회</h3>
						<h1 style="color:#0d203c;font-size:60px;">KSUHK</h1>
						<h1 style="color:#0d203c;font-size:30px;">분실된 아이디</h1><br>
						<p style="font-size:15px">
							분실하신 KSUHK 아이디는 다음과 같습니다.<br><br>
							아이디: <strong>' . $user_username . '<br><br><br>
							감사합니다!<br><br>
						</p>
					</body>
					</html>
				';

				$headers[] = "MIME-Version: 1.0";
				$headers[] = "Content-type: text/html; charset=utf-8";
				$headers[] = "To: $_POST[UserName] <$_POST[UserEmail]>";
				$headers[] = "From: KSUHK 홍콩한인유학생 총학생회 <do-not-reply@ksuhk.com>";

				mail($_POST["UserEmail"], $mail_subject, $mail_message, implode("\r\n", $headers));
			}
		} else {
			ERROR_PAGE( "서버문제로 아이디를 검색할 수 없습니다." );
		}
		
	} else {
		echo "<script>alert('이름과 이메일을 모두 입력해 주세요.');</script>";
		echo "<script>document.location='/forgot';</script>";
	}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>KSUHK - 아이디 찾기</title>
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
			if( $user_username == UserDBManager::$USER_NOT_EXIST ) {
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
			} else {
?>
				<div class="media card-block text-success p-5">
					<div class="media-left mr-3"><h1><span class="fa fa-check-circle-o fa-2x"></span></h1></div>
					<div class="media-body mt-2">
						<h2>감사합니다!</h2>
						<p class="text-muted mb-0">입력하신 이메일로 회원아이디가 전달되었습니다.</p>
					</div>
				</div>
				<div class="card-footer text-right">
					<div class="btn-group">
						<a href="/forgot" class="btn btn-outline-primary">뒤로가기</a>
						<a href="/login" class="btn btn-outline-primary">로그인</a>
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
