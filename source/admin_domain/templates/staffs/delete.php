<?php

	if( isset( $_POST["confirm"] ) ) {
		include 'classes/StaffDBManager.php';
		$staff_db = StaffDBManager::createStaffDBManager();
	
		if( $staff_db !== StaffDBManager::$CONNECT_ERROR ) {
			switch( $staff_db->delete_staff_byid( $_GET["id"], $_GET["image"], $_GET["priority"] ) ) {
				case StaffDBManager::$DELETE_ERROR :
					echo '<script>alert( "임원진을 삭제하는 중 오류가 발생했습니다." );</script>';
					break;
				case StaffDBManager::$CONNECT_ERROR :
					echo '<script>alert( "데이터베이스를 조회할 수 없습니다." );</script>';
					break;
			}
		} else {
			echo '<script>alert( "데이터베이스를 조회할 수 없습니다." );</script>';
		}
		echo "<script>location.href = '?page=staffs'</script>";

	} else if( isset( $_POST["cancel"] ) ) {
		echo "<script>location.href = '?page=staffs'</script>";
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
