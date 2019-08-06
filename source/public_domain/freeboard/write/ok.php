<?php

	$env_page = "page_freeboard_write_ok";

	include $_SERVER["DOCUMENT_ROOT"].'/config.php';
	CHECK_LOGIN_SESSION();

	if( !isset($_POST["submit"]) )
		echo "<script>document.location='/freeboard/write';</script>";

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>KSUHK - 자유게시판 / 글쓰기</title>
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

	include $WEB_ROOT.'/classes/FreeboardDBManager.php';
	$freeboard_db = FreeboardDBManager::createFreeboardDBManager();

	if( $freeboard_db !== FreeboardDBManager::$CONNECT_ERROR ) {
?>
		<form method="post" action="./"><input type="text" name="resultCode" hidden>
		<input type="text" name="PostTitle" value="<?=$_POST["PostTitle"]?>" hidden>
		<input type="text" name="PostContent" value="<?=$_POST["PostContent"]?>" hidden>
		</form>
<?php
		$result = $freeboard_db->insert_new_post( $_POST["PostTitle"], $_SESSION["user_id"], $_POST["PostContent"], $_FILES["PostImage"] );
		switch( $result ) {
			case FreeboardDBManager::$INSERT_ERROR :
?>
				<script>
					document.getElementsByName('resultCode')[0].value = 1;
					document.getElementsByTagName('form')[0].submit();
				</script>
<?php
				break;
			case FreeboardDBManager::$INVALID_SIZE :
?>
				<script>
					document.getElementsByName('resultCode')[0].value = 2;
					document.getElementsByTagName('form')[0].submit();
				</script>
<?php
				break;
			case FreeboardDBManager::$INVALID_TYPE :
?>
				<script>
					document.getElementsByName('resultCode')[0].value = 3;
					document.getElementsByTagName('form')[0].submit();
				</script>
<?php
				break;
			default :
				echo "<script>alert('게시글이 성공적으로 게시되었습니다.');</script>";
				echo "<script>location.href = '/freeboard'</script>";
		}
	} else {
		ERROR_PAGE( "현재 서버문제로 게시글을 작성하실 수 없습니다." );
	}
?>

	</div>
</div>

<!-- Footer -->
<?php include $WEB_ROOT.'/templates/footer.php'; ?>

</body>
</html>
