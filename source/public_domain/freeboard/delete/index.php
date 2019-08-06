<?php

	$env_page = "page_freeboard_delete";

	include $_SERVER["DOCUMENT_ROOT"].'/config.php';
	CHECK_LOGIN_SESSION();

	if( !isset( $_GET["postno"] ) )
		echo "<script>location.href = '/freeboard';</script>";

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>KSUHK - 자유게시판</title>
	<!-- HTML Header -->
	<?php include $WEB_ROOT.'/templates/html-header.php'; ?>
</head>
<body class="bg-faded">

<!-- Navigator -->
<?php include $WEB_ROOT.'/templates/navigator.php'; ?>

<div class="container my-0 my-sm-5 p-0 p-sm-3">
	<div class="row col-lg-10 offset-lg-1">

<?php

	include $WEB_ROOT.'/classes/FreeboardDBManager.php';
	$freeboard_db = FreeboardDBManager::createFreeboardDBManager();

	if( $freeboard_db !== FreeboardDBManager::$CONNECT_ERROR ) {
		if( $freeboard_db->check_authority( $_GET["postno"], $_SESSION["user_id"] ) != FreeboardDBManager::$VALID_AUTH ) {
			echo "<script>alert('게시글에 권한이 없습니다.');</script>";
			echo "<script>location.href = '/freeboard/view/?postno=$_GET[postno]'</script>";
		} else if( $freeboard_db->delete_post_byid( $_GET["postno"] ) == FreeboardDBManager::$DELETE_ERROR ) {
			echo "<script>alert('삭제하는중 오류가 발생했습니다.');</script>";
			echo "<script>location.href = '/freeboard/view/?postno=$_GET[postno]'</script>";
		} else {
			echo "<script>alert('게시글이 정상적으로 삭제되었습니다.');</script>";
			echo "<script>location.href = '/freeboard'</script>";
		}
	} else {
		include $WEB_ROOT.'/templates/status.php';
		ERROR_PAGE( "현재 서버문제로 게시글을 삭제하실 수 없습니다." );
	}
?>

	</div>
</div>

<!-- Footer -->
<?php include $WEB_ROOT.'/templates/footer.php'; ?>

</body>
</html>
