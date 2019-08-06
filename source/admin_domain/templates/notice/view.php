<?php

	include 'classes/NoticeDBManager.php';
	$notice_db = NoticeDBManager::createNoticeDBManager();

	if( $notice_db !== NoticeDBManager::$CONNECT_ERROR ) {
		$post = $notice_db->select_post_byid( $_GET["id"] );
		if( !$post ) {
			echo '<script>alert( "게시글을 불러오는 중 오류가 발생했습니다." );</script>';
			echo "<script>location.href = '/?page=notice&section=$section'</script>";
			die;
		}

	} else {
		echo '<script>alert( "데이터베이스를 연결할 수 없습니다." );</script>';
		die;
	}

?>

<form>
	<div class="form-group row">
		<label class="col-2 col-form-label text-right">제목</label>
		<div class="col-10">
			<p class="form-control-static"><strong><?=$post["PostTitle"]?></strong></p>
		</div>
	</div>

	<div class="form-group row">
		<label class="col-2 col-form-label text-right">게시일</label>
		<div class="col-10">
			<p class="form-control-static"><?=$post["PostPostdate"]?></p>
		</div>
	</div>

	<div class="form-group row">
		<label class="col-2 col-form-label text-right">수정일</label>
		<div class="col-10">
			<p class="form-control-static"><?=$post["PostUpdate"]?></p>
		</div>
	</div>

<?php
	if( isset( $post["PostImage"] ) && $post["PostImage"] != "" ) {
?>
	<div class="form-group row">
		<div class="col-10 offset-2">
			<img src="src/image.php?hash=<?=$post["PostImage"]?>" class="w-50 rounded">
		</div>
	</div>
<?php
	}
?>

	<div class="form-group row">
		<label class="col-2 col-form-label text-right">본문</label>
		<div class="col-10">
			<blockquote class="blockquote" style="font-size:1rem;">
				<p class="form-control-static"><?php echo nl2br( $post["PostContent"] ); ?></p>
			</blockquote>
		</div>
	</div>
	<div class="form-group row">
		<div class="col-10 offset-2">
			<div class="btn-group float-right">
				<a href="/?page=notice&section=<?=$section?>&action=edit&id=<?=$post["PostID"]?>" class="btn btn-secondary">수정</a>
				<a href="/?page=notice&section=<?=$section?>&action=delete&id=<?=$post["PostID"]?>" class="btn btn-danger">삭제</a>
			</div>
		</div>
	</div>
</form>
