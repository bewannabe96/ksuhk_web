<?php

	include 'classes/NoticeDBManager.php';
	$notice_db = NoticeDBManager::createNoticeDBManager();

	if( $notice_db !== NoticeDBManager::$CONNECT_ERROR ) {
		if( isset( $_POST["submit"] ) ) {
			if( $notice_db->update_post_byid( $_POST["PostID"], $_POST["PostTitle"], $_POST["PostContent"] ) === NoticeDBManager::$UPDATE_ERROR ) {
				echo '<script>alert( "게시글을 변경하는 중 오류가 발생했습니다." );</script>';
			} else {
				echo '<script>alert( "게시글이 성공적으로 변경되었습니다." );</script>';
				echo "<script>location.href = '/?page=notice&section=$section&action=view&id=$_POST[PostID]'</script>";
			}

		}

		$post = $notice_db->select_post_byid( $_GET["id"] );
		if( !$post ) {
			echo '<script>alert( "게시글을 불러오는 중 오류가 발생했습니다." );</script>';
			echo "<script>location.href = '/?page=notice&section=$section'</script>";
		}

	} else {
		echo '<script>alert( "데이터베이스를 연결할 수 없습니다." );</script>';
		echo "<script>location.href = '/?page=notice&section=$section'</script>";
	}

?>

<form id="notice-form" method="post" enctype="multipart/form-data">
	<input type="text" name="PostID" value="<?=$post["PostID"]?>" hidden>
	<div class="form-group row">
		<label class="col-2 col-form-label text-right">제목</label>
		<div class="col-10">
			<input class="form-control" type="text" placeholder="제목" id="title-input" name="PostTitle" value="<?=$post["PostTitle"]?>" maxlength="30" autocomplete="off">
		</div>
	</div>

<?php
	if( isset( $post["PostImage"] ) && $post["PostImage"] != "" ) {
?>
	<div class="form-group row">
		<div class="col-10 offset-2">
			<img src="src/image.php?hash=<?=$post["PostImage"]?>" class="w-100 rounded">
		</div>
	</div>
<?php
	}
?>

	<div class="form-group row">
		<label class="col-2 col-form-label text-right">본문</label>
		<div class="col-10">
			<textarea class="form-control" id="content-textarea" name="PostContent" rows="15"><?=$post["PostContent"]?></textarea>
		</div>
	</div>
	<div class="form-group row">
		<div class="col-10 offset-2 text-right">
			<div class="btn-group">
				<button type="button" class="btn btn-secondary" onclick="location.href = '/?page=notice&section=<?=$section?>&action=view&id=<?=$_GET["id"]?>'">취소</button>
				<button type="submit" name="submit" class="btn btn-primary">수정</button>
			</div>
		</div>
	</div>
</form>
<script>document.getElementById('notice-form').onsubmit = function() { return confirm("저장하시겠습니까?"); }</script>
