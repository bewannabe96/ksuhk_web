<?php

	if( isset( $_POST["confirm"] ) ) {
		include 'classes/EventDBManager.php';
		$event_db = EventDBManager::createEventDBManager();

		if( $event_db !== EventDBManager::$CONNECT_ERROR ) {
			switch( $event_db->delete_event_byid( $_GET["id"] ) ) {
				case EventDBManager::$DELETE_ERROR:
					echo '<script>alert( "삭제 중 오류가 발생했습니다." );</script>';
					break;
				case EventDBManager::$CONNECT_ERROR:
					echo '<script>alert( "데이터베이스를 조회할 수 없습니다." );</script>';
					break;
				default:
					echo '<script>alert( "이벤트가 삭제되었습니다." );</script>';
			}
		} else {
			echo '<script>alert( "데이터베이스를 조회할 수 없습니다." );</script>';
		}
		echo "<script>location.href = '/?page=event&status=$status'</script>";

	} else if( isset( $_POST["cancel"] ) ) {
		echo "<script>location.href = '/?page=event&status=$status&action=view&id=$_GET[id]'</script>";
	}

?>

<form id="confirm-form" method="post">
	<div class="form-group text-center my-4">
		<label>정말 삭제하시겠습니까?</label>
		<div class="form-group mt-2">
			<button type="submit" name="cancel" class="btn btn-secondary">취소</button>
			<button type="submit" name="confirm" class="btn btn-primary">확인</button>
		</div>
	</div>
</form>
