<?php

	if( isset( $_POST["confirm"] ) ) {
		include 'classes/EventDBManager.php';
		$event_db = EventDBManager::createEventDBManager();

		if( $event_db !== EventDBManager::$CONNECT_ERROR ) {
			if( $event_db->close_invite( $_GET["id"] ) == EventDBManager::$UPDATE_ERROR )
				echo '<script>alert( "신청 마감중 오류가 발생했습니다." );</script>';
			else
				echo '<script>alert( "신청이 마감되었습니다." );</script>';
		} else {
			echo '<script>alert( "데이터베이스를 조회할 수 없습니다." );</script>';
		}
		echo "<script>location.href = '/?page=event&status=$status&action=view&id=$_GET[id]'</script>";

	} else if( isset( $_POST["cancel"] ) ) {
		echo "<script>location.href = '/?page=event&status=$status&action=view&id=$_GET[id]'</script>";
	}

?>

<form id="confirm-form" method="post">
	<div class="form-group text-center my-4">
		<label>신청을 마감하시겠습니까?</label>
		<div class="form-group mt-2">
			<button type="submit" name="cancel" class="btn btn-secondary">취소</button>
			<button type="submit" name="confirm" class="btn btn-primary">확인</button>
		</div>
	</div>
</form>
