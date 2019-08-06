<?php

	if( isset( $_POST["submit"] ) ) {
		include 'classes/NoticeDBManager.php';
		$notice_db = NoticeDBManager::createNoticeDBManager();

		if( $notice_db !== NoticeDBManager::$CONNECT_ERROR ) {
			switch( $section ) {
				case "recruit":
					$result = $notice_db->insert_new_post( $_POST["PostTitle"], 2, $_POST["PostContent"], $_FILES["PostImage"] );
					break;
	
				case "lecture":
					$result = $notice_db->insert_new_post( $_POST["PostTitle"], 3, $_POST["PostContent"], $_FILES["PostImage"] );
					break;
	
				default:
					$result = $notice_db->insert_new_post( $_POST["PostTitle"], 1, $_POST["PostContent"], $_FILES["PostImage"] );
			}

			switch( $result ) {
				case NoticeDBManager::$INSERT_ERROR :
					echo '<script>alert( "게시글 게시 중 오류가 발생했습니다." );</script>';
					break;
				case NoticeDBManager::$CONNECT_ERROR :
					echo '<script>alert( "데이터베이스를 연결할 수 없습니다." );</script>';
					echo "<script>location.href = '/?page=notice&section=$section'</script>";
					break;
				case NoticeDBManager::$INVALID_SIZE :
					echo '<script>alert( "파일 크기를 확인해 주세요." );</script>';
					break;
				case NoticeDBManager::$INVALID_TYPE :
					echo '<script>alert( "파일 종류를 확인해 주세요." );</script>';
					break;
				default :
					echo '<script>alert( "게시글이 성공적으로 게시되었습니다." );</script>';
					echo "<script>location.href = '/?page=notice&section=$section'</script>";
			}
		} else {
			echo '<script>alert( "데이터베이스를 연결할 수 없습니다." );</script>';
			echo "<script>location.href = '/?page=notice&section=$section'</script>";
		}
	}

?>

<form id="notice-form" method="post" enctype="multipart/form-data">
	<div class="form-group row mt-5">
		<label class="col-2 col-form-label text-right">제목</label>
		<div class="col-10">
			<input class="form-control" type="text" placeholder="제목" id="title-input" name="PostTitle" value="<?php if(isset($_POST["PostTitle"])){echo $_POST["PostTitle"];} ?>" maxlength="60" autocomplete="off">
		</div>
	</div>

	<div class="form-group row">
		<label class="col-2 col-form-label text-right">사진</label>
		<div class="col-10">
			<input id="file-input" type="file" class="form-control" name="PostImage">
			<small class="form-text text-muted">2MB 이하의 이미지 파일(PNG/JPG/JPEG)</small>
		</div>
	</div>

	<div class="form-group row">
		<label class="col-2 col-form-label text-right">본문</label>
		<div class="col-10">
			<textarea class="form-control" id="content-textarea" name="PostContent" rows="15"><?php if(isset($_POST["PostContent"])){echo $_POST["PostContent"];} ?></textarea>
		</div>
	</div>
	<div class="form-group row mb-5">
		<div class="col-10 offset-2 text-right">
			<div class="btn-group">
				<button type="button" class="btn btn-secondary" onclick="location.href = '/?page=notice&section=<?=$section?>'">취소</button>
				<button type="submit" name="submit" class="btn btn-primary">작성</button>
			</div>
		</div>
	</div>
</form>
<script>
	document.getElementById('notice-form').onsubmit = function() {
		var files = document.getElementById('file-input').files;
		if(files.length!=0) {
			if(files[0].size > 2097152) {
				alert('파일 용량이 2MB보다 큽니다.');
				return false;
			}
		}
		return confirm("저장하시겠습니까?");
	}
</script>
