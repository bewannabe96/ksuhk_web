<?php

	$env_page = "page_buyandsell_report";

	include $_SERVER["DOCUMENT_ROOT"].'/config.php';
	CHECK_LOGIN_SESSION();

	if( !isset( $_GET["postno"] ) ) {
		echo "<script>location.href = '/buy-and-sell';</script>";
		die;
	}

	if( isset( $_POST["BASReportUser"] ) && isset( $_POST["BASReport"] ) ) {
		include $WEB_ROOT.'/classes/BuyandsellDBManager.php';
		$buyandsell_db = BuyandsellDBManager::createBuyandsellDBManager();
	
		if( $buyandsell_db !== BuyandsellDBManager::$CONNECT_ERROR ) {
			if( $buyandsell_db->update_reportstatus_byid( $_GET["postno"], 2, $_POST["BASReportUser"], $_POST["BASReport"] )
						== BuyandsellDBManager::$UPDATE_ERROR ) {
				echo "<script>alert('신고하는 중 오류가 발생했습니다.');</script>";
			} else {
				echo "<script>alert('신고가 정상적으로 접수되었습니다.');</script>";
			}
		} else {
			echo "<script>alert('현재 서버문제로 판매글을 신고하실 수 없습니다.');</script>";
		}
	}

	echo "<script>location.href = '/buy-and-sell/view/?postno=$_GET[postno]'</script>";
?>
