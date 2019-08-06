<?php

	include 'classes/StaffDBManager.php';
	$staff_db = StaffDBManager::createStaffDBManager();

	if( $staff_db !== StaffDBManager::$CONNECT_ERROR ) {
		if( $_GET["direction"] == "up" ) {
			$result = $staff_db->flipflop_priority_bypriority( $_GET["priority"],  $_GET["priority"]-1 );
		} else if( $_GET["direction"] == "down" ) {
			$result = $staff_db->flipflop_priority_bypriority( $_GET["priority"],  $_GET["priority"]+1 );
		}
		if( $result == StaffDBManager::$UPDATE_ERROR ) {
			echo '<script>alert( "임원진 순서를 변경하는 중 오류가 발생했습니다." );</script>';
		}
	} else {
		echo '<script>alert( "데이터베이스를 조회할 수 없습니다." );</script>';
	}
	
	echo "<script>location.href = '?page=staffs'</script>";

?>
