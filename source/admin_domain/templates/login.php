<?php
	
	include 'classes/UserDBManager.php';
	$login_result = -1;
	if( isset($_POST["submit"]) ) {
		$user_db = UserDBManager::createUserDBManager();
		
		if( $user_db !== UserDBManager::$CONNECT_ERROR ) {
			$login_result = $user_db->verify_admin_auth( $_POST["AdminUsername"], hash("sha256", $_POST["AdminPassword"]) );
	
			if( $login_result === UserDBManager::$VERIFIED ) {
				$_SESSION["auth"] = 100;
				$_SESSION["expire_time"] = date("Y/m/d H:i:s", time() + $SESSION_EXTENSION * 60);
				
				echo "<script>location.href = '/'</script>";
			}
			
		} else {
			echo '<script>alert( "데이터베이스를 조회할 수 없습니다." );</script>';
		}
	}

?>

<div class="container">
	<div class="row" style="margin-top: 5rem; margin-bottom: 5rem;">
		<div class="col-6 offset-3">
		<div class="card">
			<h4 class="card-header">관리자 로그인</h4>
			<div class="card-block">
			<form id="login-form" method="post">
				<div id="username-form-group" class="form-group <?php if($login_result===UserDBManager::$USER_NOT_EXIST){echo "has-danger";} ?>">
					<label>아이디</label>
					<input class="form-control form-control-danger" type="text" placeholder="아이디" id="username-input" name="AdminUsername" value="<?php if(isset($_POST["AdminUsername"])){echo $_POST["AdminUsername"];} ?>" autocomplete="off">
				</div>
				<div id="passwd-form-group" class="form-group <?php if($login_result===UserDBManager::$PASSWORD_INCORRECT){ ?> has-danger <?php } ?>">
					<label>비밀번호</label>
					<input class="form-control form-control-danger" type="password" placeholder="비밀번호" id="passwd-input" autocomplete="off">
					<input type="password" id="passwd-encrypt-input" name="AdminPassword" hidden>
				</div>
				<div class="form-group">
					<button type="submit" name="submit" class="btn btn-primary btn-block">로그인</button>
				</div>
			</form>

			<script src="/library/sha256.min.js"></script>
			<script>

<?php
			if( $login_result === UserDBManager::$USER_NOT_EXIST ) {
?>
				alert("사용자이름이 일치하지 않습니다.");
				document.getElementById('username-input').focus()
<?php
			} else if( $login_result === UserDBManager::$PASSWORD_INCORRECT ) {
?>
				alert("비밀번호가 일치하지 않습니다.");
				document.getElementById('passwd-input').focus()
<?php
			}
?>

				document.getElementById('login-form').onsubmit = function() {
					document.getElementById('passwd-encrypt-input').value = sha256(document.getElementById('passwd-input').value);
				}
			</script>
			</div>
		</div>
		</div>
	</div>
</div>
