<?php

	include 'classes/SponsorDBManager.php';
	$sponsor_db = SponsorDBManager::createSponsorDBManager();

	if( $sponsor_db !== SponsorDBManager::$CONNECT_ERROR ) {
		if( isset( $_GET["action"] ) && $_GET["action"] == "delete" && isset( $_GET["id"] ) ) {
			if( $sponsor_db->delete_sponsor_byid( $_GET["id"] ) == SponsorDBManager::$DELETE_ERROR )
				echo '<script>alert( "삭제중 오류가 발생했습니다." );</script>';
			echo "<script>location.href = '/?page=sponsor'</script>";

		} else if( isset( $_POST["new-submit"] ) && $_POST["SponsorTitle"] != "" && is_array( $_FILES["SponsorImage"] ) ) {
			$result = $sponsor_db->insert_new_sponsor( $_POST["SponsorTitle"], $_FILES["SponsorImage"] );

			switch( $result ) {
				case SponsorDBManager::$INSERT_ERROR :
					echo '<script>alert( "추가중 오류가 발생했습니다." );</script>';
					break;
				case SponsorDBManager::$CONNECT_ERROR :
					echo '<script>alert( "데이터베이스를 연결할 수 없습니다." );</script>';
					break;
				case SponsorDBManager::$INVALID_SIZE :
					echo '<script>alert( "파일 크기를 확인해 주세요." );</script>';
					break;
				case SponsorDBManager::$INVALID_TYPE :
					echo '<script>alert( "파일 종류를 확인해 주세요." );</script>';
					break;
				default :
					echo '<script>alert( "스폰서가 성공적으로 추가되었습니다." );</script>';
			}
		}

		$sponsors_array = $sponsor_db->select_all_sponsors();
	} else {
		echo '<script>alert( "데이터베이스를 조회할 수 없습니다." );</script>';
	}
	
?>

<div class="row">
	<div class="col-8">
		<div class="card">
			<div class="card-header">스폰서</div>
			<div class="card-block card-columns">
<?php
			if( !$sponsors_array ) {
?>
				<h6 class="text-muted">등록된 스폰서가 없습니다.</h6>
<?php
			} else {
				for( $idx = 0; $idx < count( $sponsors_array ); $idx++ ) {
?>
				<div class="card">
					<img class="card-img-top img-fluid" src="/src/image.php?hash=<?=$sponsors_array[$idx]["SponsorImage"]?>">
					<div class="card-block d-flex justify-content-between" style="height:4rem;">
						<p><?=$sponsors_array[$idx]["SponsorTitle"]?></p>
						<a href="/?page=sponsor&action=delete&id=<?=$sponsors_array[$idx]["SponsorID"]?>">
							<span class="fa fa-trash-o" aria-hidden="true"></span>
						</a>
					</div>
				</div>
<?php
				}
			}
?>
			</div>
		</div>
	</div>
	<div class="col-4">
		<div class="card">
			<div class="card-header">새 스폰서</div>
			<div class="card-block">
				<form id="sponsor-form" method="post" enctype="multipart/form-data">
					<div class="form-group">
						<label>이름</label>
						<input type="text" class="form-control" id="title-input" name="SponsorTitle" placeholder="스폰서명">
					</div>
					<div class="form-group">
						<label>배너</label>
						<input type="file" class="form-control" name="SponsorImage">
						<small class="form-text text-muted">2MB 이하의 이미지 파일(PNG/JPG/JPEG)</small>
					</div>
					<div class="form-group text-right mt-4">
						<button type="submit" name="new-submit" class="btn btn-primary btn-block">추가</button>
					</div>
				</form>
			</div>
		</div>
		<p class="text-muted">스폰서 업로드는 페이지 로딩속도에 큰 영향을 줍니다.<br>(최대 5개 스폰서를 유지해 주세요)</p>
	</div>
</div>
