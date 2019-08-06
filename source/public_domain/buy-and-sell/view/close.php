<?php

	$env_page = "page_buyandsell_report";

	include $_SERVER["DOCUMENT_ROOT"].'/config.php';
	CHECK_LOGIN_SESSION();

	if( !isset( $_GET["postno"] ) ) {
		echo "<script>location.href = '/buy-and-sell';</script>";
		die;
	}

	include $WEB_ROOT.'/classes/BuyandsellDBManager.php';
	$buyandsell_db = BuyandsellDBManager::createBuyandsellDBManager();

	if( $buyandsell_db !== BuyandsellDBManager::$CONNECT_ERROR ) {
		if( $buyandsell_db->check_authority( $_GET["postno"], $_SESSION["user_id"] ) != BuyandsellDBManager::$VALID_AUTH ) {
			echo "<script>alert('판매글에 권한이 없습니다.');</script>";
		} else if( $buyandsell_db->close_bas_byid( $_GET["postno"] ) == BuyandsellDBManager::$UPDATE_ERROR ) {
			echo "<script>alert('거래를 완료하는 중 오류가 발생했습니다.');</script>";
		} else {
			echo "<script>alert('거래가 정상적으로 완료되었습니다.');</script>";
		}
	} else {
		echo "<script>alert('현재 서버문제로 거래를 완료하실 수 없습니다.');</script>";
	}

	echo "<script>location.href = '/buy-and-sell/view/?postno=$_GET[postno]'</script>";
?>
