<?php

	$env_page = "page_event_join";

	include $_SERVER["DOCUMENT_ROOT"].'/config.php';
	CHECK_LOGIN_SESSION();

	if( !isset( $_GET["eventno"] ) || $_GET["eventno"] == "" ) {
		echo "<script>location.href = '/event';</script>";
	
	} else {
		include $WEB_ROOT.'/classes/EventDBManager.php';
		$event_db = EventDBManager::createEventDBManager();

		if( $event_db !== EventDBManager::$CONNECT_ERROR ) {
			if( isset( $_GET["action"] ) && $_GET["action"] == "out" ) {
				if( $event_db->out_event( $_GET["eventno"], $_SESSION["user_id"] ) == EventDBManager::$DELETE_ERROR ) {
					echo "<script>alert('참가신청을 취소하는 중 오류가 발생했습니다.');</script>";
					echo "<script>location.href = '/event/view/?eventno=$_GET[eventno]';</script>";
				}
				echo "<script>alert('참가신청이 정상적으로 취소되었습니다.');</script>";
				echo "<script>location.href = '/event/view/?eventno=$_GET[eventno]';</script>";
				die();
			}

			switch( $event_db->can_join( $_GET["eventno"] ) ) {
				case EventDBManager::$JOIN_INVALID :
					echo "<script>alert('신청이 불가능한 게시물입니다.');</script>";
					echo "<script>location.href = '/event/view/?eventno=$_GET[eventno]';</script>";
					die();
				case EventDBManager::$JOINED_FULL :
					echo "<script>alert('정원이 모두 찼습니다.');</script>";
					echo "<script>location.href = '/event/view/?eventno=$_GET[eventno]';</script>";
					die();
				case EventDBManager::$JOIN_CLOSED :
					echo "<script>alert('신청이 마감 되었습니다.');</script>";
					echo "<script>location.href = '/event/view/?eventno=$_GET[eventno]';</script>";
					die();
			}

			if( $event_db->is_joined( $_GET["eventno"], $_SESSION["user_id"] ) == EventDBManager::$ALREADY_JOINED ) {
				echo "<script>alert('이벤트에 이미 참가하셨습니다.');</script>";
				echo "<script>location.href = '/event/view/?eventno=$_GET[eventno]';</script>";
				die();
			}

			if( $event_db->join_event( $_GET["eventno"], $_SESSION["user_id"] ) == EventDBManager::$INSERT_ERROR ) {
				echo "<script>alert('이벤트에 참가하는 중 오류가 발생했습니다.');</script>";
				echo "<script>location.href = '/event/view/?eventno=$_GET[eventno]';</script>";
				die();
			}
				
			echo "<script>alert('참가신청이 정상적으로 완료되었습니다.');</script>";
			echo "<script>location.href = '/event/view/?eventno=$_GET[eventno]';</script>";

		} else {
			echo "<script>alert('이벤트에 참가하는 중 오류가 발생했습니다.');</script>";
			echo "<script>location.href = '/event/view/?eventno=$_GET[eventno]';</script>";
		}
	}

?>
