<?php

	$env_page = "page_freeboard_report";

	include $_SERVER["DOCUMENT_ROOT"].'/config.php';
	CHECK_LOGIN_SESSION();

	if( !isset( $_GET["postno"] ) ) {
		echo "<script>location.href = '/freeboard';</script>";
		die;
	}

	if( isset( $_POST["PostReportUser"] ) && isset( $_POST["PostReport"] ) ) {
		include $WEB_ROOT.'/classes/FreeboardDBManager.php';
		$freeboard_db = FreeboardDBManager::createFreeboardDBManager();
	
		if( $freeboard_db !== FreeboardDBManager::$CONNECT_ERROR ) {
			if( $freeboard_db->update_status_byid( $_GET["postno"], 2, $_POST["PostReportUser"], $_POST["PostReport"] ) == FreeboardDBManager::$UPDATE_ERROR ) {
				echo "<script>alert('신고하는 중 오류가 발생했습니다.');</script>";
			} else {
				echo "<script>alert('신고가 정상적으로 접수되었습니다.');</script>";
			}
		} else {
			echo "<script>alert('현재 서버문제로 게시글을 신고하실 수 없습니다.');</script>";
		}
	}

	echo "<script>location.href = '/freeboard/view/?postno=$_GET[postno]'</script>";
?>
