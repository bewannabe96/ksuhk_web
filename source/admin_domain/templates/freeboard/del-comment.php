<?php

	if( isset( $_GET["id"] ) ) {
		if( isset( $_GET["commentno"] ) ) {
			include 'classes/FreeboardDBManager.php';
			$freeboard_db = FreeboardDBManager::createFreeboardDBManager();
			if( $freeboard_db !== FreeboardDBManager::$CONNECT_ERROR ) {
				if( $freeboard_db->delete_comment_byid( $_GET["id"], $_GET["commentno"] ) == FreeboardDBManager::$DELETE_ERROR )
					echo '<script>alert( "삭제 중 오류가 발생했습니다." );</script>';
				else
					echo '<script>alert( "댓글이 삭제되었습니다." );</script>';
			} else {
				echo '<script>alert( "데이터베이스를 조회할 수 없습니다." );</script>';
			}
		}

		echo "<script>location.href = '/?page=freeboard&status=$status&action=view&id=$_GET[id]'</script>";
	}

	echo "<script>location.href = '/?page=freeboard&status=$status'</script>";

?>
