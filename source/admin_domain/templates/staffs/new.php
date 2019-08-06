<?php

	if( isset( $_POST["submit"] ) ) {
		include 'classes/StaffDBManager.php';
		$staff_db = StaffDBManager::createStaffDBManager();

		if( $staff_db !== StaffDBManager::$CONNECT_ERROR ) {
			$result = $staff_db->insert_new_staff( $_POST["StaffName"], $_POST["StaffEngName"], $_POST["StaffPosition"], $_POST["StaffSchool"],
								$_POST["StaffPhoneNo"], $_POST["StaffEmail"], $_POST["priority"] );
			if( $result === StaffDBManager::$INSERT_ERROR ) {
				echo '<script>alert( "임원진 정보를 추가하는 중 오류가 발생했습니다." );</script>';
			} else {
				if( $_FILES["StaffImage"]["size"] > 0 ) {
					switch( $staff_db->update_staff_image_byid( $result, $_FILES["StaffImage"], "" ) ) {
						case StaffDBManager::$UPDATE_ERROR :
							echo '<script>alert( "임원진 사진을 추가하는 중 오류가 발생했습니다." );</script>';
							echo "<script>location.href = '?page=staffs'</script>";
							break;
						case StaffDBManager::$CONNECT_ERROR :
							echo '<script>alert( "데이터베이스를 연결할 수 없습니다." );</script>';
							echo "<script>location.href = '?page=staffs'</script>";
							break;
						case StaffDBManager::$INVALID_SIZE :
							echo '<script>alert( "파일 크기를 확인해 주세요." );</script>';
							echo "<script>location.href = '?page=staffs'</script>";
							break;
						case StaffDBManager::$INVALID_TYPE :
							echo '<script>alert( "파일 종류를 확인해 주세요." );</script>';
							echo "<script>location.href = '?page=staffs'</script>";
							break;
					}
				}
			}
			echo "<script>location.href = '?page=staffs'</script>";

		} else {
			echo '<script>alert( "데이터베이스를 조회할 수 없습니다." );</script>';
		}
	}

?>

<div class="col-8 offset-2">
	<form id="staff-form" method="post" enctype="multipart/form-data">
		<div class="card card-block">
			<div class="row">
				<div class="col-6">
					<input type="text" name="StaffName" class="form-control w-100" value="<?php if(isset($_POST["StaffName"])){echo $_POST["StaffName"];} ?>" placeholder="이름" autocomplete="off">
				</div>
				<div class="col-6">
					<input type="text" name="StaffEngName" class="form-control w-100" value="<?php if(isset($_POST["StaffEngName"])){echo $_POST["StaffEngName"];} ?>" placeholder="영문이름" autocomplete="off">
				</div>
			</div>
			<div class="row mt-4">
				<div class="col-4">
					<select name="StaffSchool" class="form-control form-control-sm w-100">
						<option value="1">HKUST / 과기대</option>
						<option value="2">HKU / 홍콩대</option>
						<option value="3">CUHK / 중문대</option>
						<option value="4">POLYU / 이공대</option>
						<option value="5">CITYU / 시립대</option>
					</select>
				</div>
				<script>
					document.getElementById('staff-select').value = <?php if(isset($_POST["StaffSchool"])){echo $_POST["StaffSchool"];} ?>;
				</script>
				<div class="col-8">
					<input type="text" name="StaffPosition" class="form-control form-control-sm w-100" value="<?php if(isset($_POST["StaffPosition"])){echo $_POST["StaffPosition"];} ?>" placeholder="직책" autocomplete="off">
				</div>
			</div>
			<div class="row mt-4">
				<div class="col-2 form-group">
					<label class="col-form-label">순서</label>
					<select name="priority" class="form-control form-control-sm ml-2">
<?php
					for ($i = 1; $i <= $_GET["staffsnum"]+1; $i++)
						echo "<option value='$i'>$i</option>";
?>
					</select>
				</div>
				<div class="col-6">
					<div class="input-group w-100">
						<span class="input-group-addon" style="width:3rem"><span class="fa fa-envelope"></span></span>
						<input type="text" name="StaffEmail" class="form-control" value="<?php if(isset($_POST["StaffEmail"])){echo $_POST["StaffEmail"];} ?>" placeholder="이메일" autocomplete="off">
					</div>
				</div>
				<div class="col-4">
					<div class="input-group w-100">
						<span class="input-group-addon" style="width:3rem"><span class="fa fa-phone"></span></span>
						<input type="text" name="StaffPhoneNo" class="form-control" value="<?php if(isset($_POST["StaffPhoneNo"])){echo $_POST["StaffPhoneNo"];} ?>" placeholder="연락처" autocomplete="off">
					</div>
				</div>
			</div>
			<div class="row mt-4">
				<div class="d-flex align-items-end mx-3">
					<div class="form-group">
						<input type="file" class="form-control" name="StaffImage">
						<input type="text" name="prevImage" value="<?php if(isset($staff["StaffImage"])){echo $staff["StaffImage"];} ?>" hidden>
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
				<button type="submit" name="submit" class="btn btn-primary">확인</button>
			</div>
		</div>
	</form>
</div>
<script>document.getElementById('staff-form').onsubmit = function() { return confirm("저장하시겠습니까?"); }</script>
