<?php

	include 'classes/FreeboardDBManager.php';
	$freeboard_db = FreeboardDBManager::createFreeboardDBManager();

	if( $freeboard_db !== FreeboardDBManager::$CONNECT_ERROR ) {
		$post = $freeboard_db->select_post_byid( $_GET["id"] );
		$comments = $freeboard_db->select_comments_byid( $_GET["id"] );
		if( !$post ) {
			echo '<script>alert( "게시글을 불러오는 중 오류가 발생했습니다." );</script>';
			echo "<script>location.href = '/?page=freeboard&status=$status'</script>";
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
			<img src="src/freeboard-image.php?hash=<?=$post["PostImage"]?>" class="w-50 rounded">
		</div>
	</div>
<?php
	}
?>

	<div class="form-group row">
		<label class="col-2 col-form-label text-right">본문</label>
		<div class="col-10">
			<blockquote class="blockquote" style="font-size:1rem;">
				<p class="form-control-static"><?php echo nl2br( htmlspecialchars( $post["PostContent"] ) ); ?></p>
			</blockquote>
		</div>
	</div>
	<div class="form-group row">
		<label class="col-2 col-form-label text-right">댓글</label>
		<div id="comments-div" class="col-10">
			<hr>
<?php
		if( is_array( $comments ) ) {
			foreach( $comments as $row ) {
?>
			<div class="row col-12 justify-content-between">
				<div>
					<span class="text-info"><?=$row["UserName"]?> (<?=$row["UserUsername"]?>)</span><span class="text-muted ml-2" style="font-size:0.8rem"><?=$row["CmtPostdate"]?></span>
				</div>
				<button type="button" class="close ml-2" onclick="location.href='/?page=freeboard&status=<?=$status?>&action=del-comment&id=<?=$post["PostID"]?>&commentno=<?=$row["CmtID"]?>';">
					<span class="text-muted fa fa-times"></span>
				</button>
			</div>
			<div class="row col-12 mt-2">
				<?=$row["CmtContent"]?>
			</div>
			<hr>
<?php
			} 
		} else {
			echo '<p class="text-center">작성된 댓글이 없습니다.</p><hr>';
		}
?>
		</div>

<?php
		if( $post["PostStatus"] == 2 ) {
			$report = $freeboard_db->select_report_byid( $_GET["id"] );
?>
			<label class="col-2 col-form-label text-right">신고내용</label>
			<div class="col-10">
				<div class="card">
					<div class="card-header text-warning"><?=$report["UserName"]?> (<?=$report["UserUsername"]?>)</div>
					<div class="card-block"><?=$report["PostReport"]?></div>
				</div>
			</div>
<?php
		}
?>
	</div>
	<div class="form-group row">
		<div class="col-10 offset-2">
			<div class="btn-group float-right">
<?php
			if( $post["PostStatus"] == 2 ) {
				echo "<a href='/?page=freeboard&status=$status&action=allow&id=$post[PostID]' class='btn btn-secondary'>신고해제</a>";
			}
?>
				<a href="/?page=freeboard&status=<?=$status?>&action=delete&id=<?=$post["PostID"]?>" class="btn btn-danger">삭제</a>
			</div>
		</div>
	</div>
</form>
