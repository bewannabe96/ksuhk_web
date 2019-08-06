<?php

	$env_page = "page_memberinfo_deleteaccount";

	include $_SERVER["DOCUMENT_ROOT"].'/config.php';
	CHECK_LOGIN_SESSION();


?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>KSUHK - 회원탈퇴</title>
	<!-- HTML Header -->
	<?php include $WEB_ROOT.'/templates/html-header.php'; ?>
</head>
<body class="bg-faded">

<!-- Navigator -->
<?php include $WEB_ROOT.'/templates/navigator.php'; ?>

<div class="container mb-0 my-0 my-lg-5 mb-sm-3 p-0">
	<div class="row w-100 m-0">
		<div class="col-lg-3 offset-lg-1 p-0 p-sm-3">
			<div class="card">
				<h5 class="card-header">회원정보</h5>
				<div class="list-group list-group-flush">
					<a href="/memberinfo" class="list-group-item list-group-item-action">회원정보 수정</a>
					<a href="/memberinfo/password" class="list-group-item list-group-item-action">비밀번호 변경</a>
					<a href="/memberinfo/deleteaccount" class="list-group-item list-group-item-action active">계정탈퇴</a>
				</div>
			</div>
		</div>
		<div class="col-lg-7 mt-0 mt-sm-3 mt-lg-0 p-0 p-sm-3">
<?php
		include $WEB_ROOT.'/templates/popup-message.php';
			if( isset( $_POST["confirm"] ) ) {
				include $WEB_ROOT.'/classes/UserDBManager.php';
				$user_db = UserDBManager::createUserDBManager();
		
				if( $user_db !== UserDBManager::$CONNECT_ERROR ) {
					$check = $user_db->check_user_login( $_SESSION["user_username"], hash("sha256", $_POST["UserPassword"]) );
					if( $check != UserDBManager::$PASSWORD_INCORRECT ) {
						if( $user_db->delete_user_byid( $_SESSION["user_id"] ) === UserDBManager::$DELETE_ERROR ) {
							echo '<script>createAlert("회원 탈퇴중 오류가 발생했습니다.", "알림!");</script>';
						} else {
							echo '<script>alert("그동안 서비스를 이용해주셔서 감사합니다.");</script>';
							echo "<script>document.location='/login/logout.php';</script>";
						}
					}
				} else {
					include $WEB_ROOT.'/templates/status.php';
					ERROR_PAGE( "현재 서버문제로 탈퇴하실 수 없습니다." );
				}
			}
?>
			<div class="card">
				<form id="final-form" method="post">
					<h5 class="card-header">계정탈퇴</h5>
					<div class="card-block px-2 px-sm-5">
						<h1 class="text-warning"><span class="fa fa-warning"></span> 경고</h1>
						<p class="text-muted" style="text-align:justify;">
							탈퇴시 본인의 모든 회원정보는 삭제되며, 본 사이트의 서비스를 다시 이용하시려면 
							회원가입 및 관리자의 승인절차를 다시 받으셔야 합니다.<br>
							탈퇴하시겠습니까?
						</p>
						<div id="passwd-form-group" class="form-group mt-4">
							<label>비밀번호 확인</label>
							<input class="form-control form-control-danger" type="password" placeholder="비밀번호" id="passwd-input" autocomplete="off">
							<input type="password" id="passwd-encrypt-input" name="UserPassword" hidden>
						</div>
					</div>
					<div class="card-footer text-right">
						<button type="submit" name="confirm" class="btn btn-primary">탈퇴</button>
					</div>
				</form>
				<script src="/library/sha256.min.js"></script>
				<script>
<?php
				if( isset( $check ) && $check==UserDBManager::$PASSWORD_INCORRECT ) {
?>
					document.getElementById('passwd-form-group').className = "form-group has-danger mt-4";
					createAlert("비밀번호가 일치하지 않습니다.");
					document.getElementById('passwd-input').focus();
<?php
				}
?>
					document.getElementById('final-form').onsubmit = function() {
						if(document.getElementById('passwd-input').value == '') {
							document.getElementById('passwd-form-group').className = "form-group has-danger mt-4";
							createAttention("비밀번호를 확인해 주세요.");
							document.getElementById('passwd-input').focus();
							return false;
						}
						document.getElementById('passwd-encrypt-input').value = sha256(document.getElementById('passwd-input').value);
					}
				</script>
			</div>
		</div>
	</div>
</div>

<!-- Footer -->
<?php include $WEB_ROOT.'/templates/footer.php'; ?>

</body>
</html>
