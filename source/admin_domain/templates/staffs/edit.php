<?php

	include 'classes/StaffDBManager.php';
	$staff_db = StaffDBManager::createStaffDBManager();

	if( $staff_db !== StaffDBManager::$CONNECT_ERROR ) {
		if( isset( $_POST["submit"] ) ) {
			if( $_FILES["StaffImage"]["size"] > 0 ) {
				switch( $staff_db->update_staff_image_byid( $_GET["id"], $_FILES["StaffImage"], $_POST["prevImage"] ) ) {
					case StaffDBManager::$UPDATE_ERROR :
						echo '<script>alert( "임원진 사진을 변경하는 중 오류가 발생했습니다." );</script>';
						echo "<script>location.href = '?page=staffs'</script>";
						break;
					case StaffDBManager::$CONNECT_ERROR :
						echo '<script>alert( "데이터베이스를 연결할 수 없습니다." );</script>';
						echo "<script>location.href = '?page=staffs'</script>";
						break;
					case StaffDBManager::$INVALID_SIZE :
						echo '<script>alert( "파일 크기를 확인해 주세요." );</script>';
						echo "<script>location.href = '?page=staffs&action=edit&id=$_GET[id]'</script>";
						break;
					case StaffDBManager::$INVALID_TYPE :
						echo '<script>alert( "파일 종류를 확인해 주세요." );</script>';
						echo "<script>location.href = '?page=staffs&action=edit&id=$_GET[id]'</script>";
						break;
				}
			}

			if( $staff_db->update_staff_byid( $_GET["id"], $_POST["StaffName"], $_POST["StaffEngName"], $_POST["StaffPosition"],
								$_POST["StaffSchool"], $_POST["StaffPhoneNo"], $_POST["StaffEmail"] ) === StaffDBManager::$UPDATE_ERROR ) {
				echo '<script>alert( "임원진 정보를 변경하는 중 오류가 발생했습니다." );</script>';
			}
			echo "<script>location.href = '?page=staffs'</script>";
		}

		$staff = $staff_db->select_staff_byid( $_GET["id"] );
	} else {
		echo '<script>alert( "데이터베이스를 조회할 수 없습니다." );</script>';
		echo "<script>location.href = '?page=staffs'</script>";
	}

?>

<div class="col-8 offset-2">
	<form id="staff-form" method="post" enctype="multipart/form-data" class="form-inline">
		<div class="card">
			<div class="list-group list-group-flush">
				<div class="list-group-item media py-4">
					<div class="media-left">
<?php
					if( isset( $staff["StaffImage"] ) ) {
?>
						<img src="/src/image.php?hash=<?=$staff["StaffImage"]?>" class="rounded-circle" style="width:12rem; height:12rem;">
<?php
					} else {
?>
						<img src="/src/placeholder.jpeg" class="rounded-circle" style="width:12rem; height:12rem;">
<?php
					}
?>
					</div>
					<div class="media-body align-middle px-4">
						<input type="text" name="StaffName" class="form-control" value="<?=$staff["StaffName"]?>" placeholder="이름" autocomplete="off">
						<input type="text" name="StaffEngName" class="form-control" value="<?=$staff["StaffEngName"]?>" placeholder="영문이름" autocomplete="off">
						<select name="StaffSchool" class="form-control form-control-sm mt-1">
							<option value="1" <?php if($staff["StaffSchool"]==1){ ?>selected<?php } ?>>HKUST / 과기대</option>
							<option value="2" <?php if($staff["StaffSchool"]==2){ ?>selected<?php } ?>>HKU / 홍콩대</option>
							<option value="3" <?php if($staff["StaffSchool"]==3){ ?>selected<?php } ?>>CUHK / 중문대</option>
							<option value="4" <?php if($staff["StaffSchool"]==4){ ?>selected<?php } ?>>POLYU / 이공대</option>
							<option value="5" <?php if($staff["StaffSchool"]==5){ ?>selected<?php } ?>>CITYU / 시립대</option>
						</select><br>
						<input type="text" name="StaffPosition" class="form-control form-control-sm w-100 mt-1" value="<?=$staff["StaffPosition"]?>" placeholder="직책" autocomplete="off">
						<hr>
						<span class="fa fa-envelope"></span>
						<input type="text" name="StaffEmail" class="form-control form-control-sm" value="<?=$staff["StaffEmail"]?>" placeholder="이메일" autocomplete="off">
						<span class="fa fa-phone ml-1"></span>
						<input type="text" name="StaffPhoneNo" class="form-control form-control-sm" value="<?=$staff["StaffPhoneNo"]?>" placeholder="연락처" autocomplete="off">
					</div>
				</div>
				<div class="list-group-item py-2 align-items-end">
					<div class="form-group">
						<input type="file" class="form-control" name="StaffImage">
						<input type="text" name="prevImage" value="<?=$staff["StaffImage"]?>" hidden>
					</div>
					<div class="form-group ml-2">
						<small class="form-text text-muted">2MB 이하의 이미지 파일(PNG/JPG/JPEG)</small>
					</div>
				</div>
			</div>
		</div>
		<div class="w-100 d-flex justify-content-end mt-3">
			<div class="btn-group">
				<a href="/?page=staffs" class="btn btn-secondary">취소</a>
				<button type="submit" name="submit" class="btn btn-primary">변경</button>
			</div>
		</div>
	</form>
</div>
<script>document.getElementById('staff-form').onsubmit = function() { return confirm("저장하시겠습니까?"); }</script>
