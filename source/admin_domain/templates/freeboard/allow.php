<?php

	if( isset( $_GET["id"] ) ) {
		include 'classes/FreeboardDBManager.php';
		$freeboard_db = FreeboardDBManager::createFreeboardDBManager();

		if( $freeboard_db !== FreeboardDBManager::$CONNECT_ERROR ) {
			if( $freeboard_db->update_status_byid( $_GET["id"], 1 ) == FreeboardDBManager::$UPDATE_ERROR )
				echo '<script>alert( "변경 중 오류가 발생했습니다." );</script>';
			else
				echo '<script>alert( "정상적으로 상태가 변경되었습니다." );</script>';
		} else {
			echo '<script>alert( "데이터베이스를 조회할 수 없습니다." );</script>';
		}
		echo "<script>location.href = '/?page=freeboard&status=all&action=view&id=$_GET[id]'</script>";

	} else {
		echo "<script>location.href = '/?page=freeboard&status=$status'</script>";
	}

?>
