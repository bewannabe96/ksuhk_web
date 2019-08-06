<?php
	
	$env_page = "page_login_ok";

	include $_SERVER["DOCUMENT_ROOT"].'/config.php';

	// IF VALID ACCESS TO THE PAGE
	if( isset($_POST["submit"]) ) {

		include $WEB_ROOT.'/classes/UserDBManager.php';
		$user_db = UserDBManager::createUserDBManager();

		if( $user_db !== UserDBManager::$CONNECT_ERROR ) {
			$login_result = $user_db->check_user_login( $_POST["UserUsername"], hash("sha256", $_POST["UserPassword"]) );

			switch( $login_result ) {
			
			case UserDBManager::$USER_NOT_EXIST :
?>
				<form method="post" action="./"><input type="text" name="resultCode" value="1" hidden></form>
				<script>document.getElementsByTagName("form")[0].submit();</script>
<?php
				break;

			case UserDBManager::$PASSWORD_INCORRECT :
?>
				<form method="post" action="./"><input type="text" name="resultCode" value="2" hidden>
					<input type="text" name="UserUsername" value="<?=$_POST["UserUsername"]?>" hidden>
				</form>
				<script>document.getElementsByTagName("form")[0].submit();</script>
<?php
				break;

			case UserDBManager::$WAITING_ACCEPT :
?>
				<form method="post" action="./"><input type="text" name="resultCode" value="3" hidden></form>
				<script>document.getElementsByTagName("form")[0].submit();</script>
<?php
				break;

			case UserDBManager::$REFUSED_ACCEPT :
?>
				<form method="post" action="./"><input type="text" name="resultCode" value="4" hidden></form>
				<script>document.getElementsByTagName("form")[0].submit();</script>
<?php
				break;

			default :
				if( $login_result["UserID"] == 1 )
					$_SESSION["auth"] = 99;
				else
					$_SESSION["auth"] = 1;
				$_SESSION["user_id"] = $login_result["UserID"];
				$_SESSION["user_name"] = $login_result["UserName"];
				$_SESSION["user_username"] = $login_result["UserUsername"];
				$_SESSION["expire_time"] = date("Y/m/d H:i:s", time() + $SESSION_EXTENSION * 60);
	
				echo "<script>alert('정상적으로 로그인되었습니다.');</script>";
				if( isset( $_GET["direct"] ) )
					echo "<script>location.href = '$_GET[direct]'</script>";
				else
					echo "<script>location.href = '/'</script>";
			}

		} else {
			echo "<script>alert('로그인 하는중 오류가 발생했습니다.');</script>";
			echo "<script>document.location='/login';</script>";
		}


	// IF INVALID ACCESS TO THE PAGE
	} else {
		echo "<script>document.location='/login';</script>";
	}
?>
