<?php

	$env_page = "page_freeboard_view";

	include $_SERVER["DOCUMENT_ROOT"].'/config.php';
	CHECK_LOGIN_SESSION();

	if( !isset( $_GET["postno"] ) )
		echo "<script>location.href = '/freeboard';</script>";
	
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>KSUHK - 자유게시판</title>
	<!-- HTML Header -->
	<?php include $WEB_ROOT.'/templates/html-header.php'; ?>
</head>
<body class="bg-faded">

<div id="reportModal" class="modal fade">
	<div class="modal-dialog" role="document">
		<form class="modal-content" action="/freeboard/report/?postno=<?=$_GET["postno"]?>" method="post">
			<div class="modal-header">
				<h5 class="modal-title">게시글 신고</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span class="fa fa-close" aria-hidden="true"></span>
				</button>
			</div>
			<div class="modal-body">
				<p>신고내용</p>
				<textarea class="form-control" id="report-textarea" name="PostReport" rows="10"></textarea>
				<input name="PostReportUser" value="<?=$_SESSION["user_id"]?>" hidden>
				<small class="text-muted">허위신고는 제제의 대상이 됩니다.</small>
			</div>
			<div class="modal-footer">
				<button id="report-btn" type="submit" class="btn btn-warning">신고</button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal">취소</button>
			</div>
		</form>
	</div>
</div>
<script>
	function report_modal() {
		$('#reportModal').modal();
	}
</script>

<!-- Navigator -->
<?php include $WEB_ROOT.'/templates/navigator.php'; ?>

<div class="container my-0 my-sm-5 p-0">
	<div class="row w-100 m-0">
		<div class="col-lg-8 offset-lg-2 p-0 p-sm-3">
<?php
			include $WEB_ROOT.'/classes/FreeboardDBManager.php';
			$freeboard_db = FreeboardDBManager::createFreeboardDBManager();
		
			if( $freeboard_db !== FreeboardDBManager::$CONNECT_ERROR ) {
				$post = $freeboard_db->select_post_byid( $_GET["postno"] );
				if( !$post ) {
					echo "<script>alert('존재하지 않거나 삭제된 게시물입니다.');";
					echo "location.href = '/freeboard';</script>";
				} else {
					$comments = $freeboard_db->select_comments_byid( $_GET["postno"] );
					$post_content = nl2br( htmlspecialchars( $post["PostContent"] ) );
?>
					<ol class="breadcrumb mb-0 mb-sm-3">
						<li class="breadcrumb-item"><a href="/freeboard">자유게시판</a></li>
						<li class="breadcrumb-item active"><?=$post["PostTitle"]?></li>
					</ol>
					<div class="card">
						<div class="card-header text-right">
							<div class="btn-group">
								<a href="/freeboard" class="btn btn-secondary btn-sm">목록</a>
<?php
							if( $_SESSION["user_username"] == $post["UserUsername"] )
								echo "<a href='/freeboard/delete/?postno=$post[PostID]' class='btn btn-danger btn-sm'>삭제</a>";
							else if( $post["PostStatus"] != 2 )
								echo "<btn class='btn btn-warning btn-sm' onclick='report_modal();'>신고</btn>";

							if( $post["PostStatus"] == 2 )
								echo "<a href='#' class='btn btn-warning btn-sm disabled' role='button' aria-disabled='true'>신고됨</a>";
?>
							</div>
						</div>
						<div class="card-block py-4 px-sm-4 px-2">
							<h3 class="mb-4"><?=$post["PostTitle"]?></h3>
							<p class="mb-2" style="font-size:0.6rem;">
								<span class="badge badge-pill badge-default">게시자</span>
								<?=$post["UserName"]?> (<?=$post["UserUsername"]?>)
							</p>
							<p style="font-size:0.6rem;">
									<span class="badge badge-pill badge-default">게시일</span> <?=$post["PostPostdate"]?>
									<span class="badge badge-pill badge-default ml-2">수정일</span> <?=$post["PostUpdate"]?>
							</p>
							<hr class="mb-4">
							<div class="text-center">
<?php
							if( isset( $post["PostImage"] ) && $post["PostImage"] != "" ) {
?>
								<img src="/src/freeboard-image.php?hash=<?=$post["PostImage"]?>" class="w-100 rounded">
<?php
							}
?>
							</div>
							<p id="post-content" class="my-3 mx-1 text-justify"><?=$post_content?></p>
							<?php include $WEB_ROOT.'/templates/urlify.php'; ?>
							<script>
								document.getElementById('post-content').innerHTML =
										urlify(document.getElementById('post-content').innerHTML);
							</script>
						</div>
						<div class="card-footer px-3 px-lg-4">
							<h5><span class="fa fa-comment" aria-hidden="true"></span> 댓글</h5>
							<hr class="mt-0">
							<div id="comments-div">
<?php
							if( is_array( $comments ) ) {
								foreach( $comments as $row ) {
									switch( $row["UserSchool"] ) {
										case 1:
											$school = " / HKUST";
											break;
										case 2:
											$school = " / HKU";
											break;
										case 3:
											$school = " / CUHK";
											break;
										case 4:
											$school = " / POLYU";
											break;
										case 5:
											$school = " / CITYU";
											break;
										default:
											$school = "";
									}
								$cmt_content = nl2br( $row["CmtContent"] );
?>
								<div class="row col-12 justify-content-between mx-0 px-0">
									<div>
										<span class="text-info">
											<?=$row["UserName"]?> (<?=$row["UserUsername"]?><?=$school?>)
										</span>
									</div>
<?php
								if( $row["UserUsername"] == $_SESSION["user_username"] ) {
?>
									<button type="button" class="close ml-2" onclick="deleteComment(<?=$row["CmtID"]?>)">
										<span class="text-muted fa fa-times"></span>
									</button>
<?php
								}
?>
								</div>
								<div class="row col-12 text-muted" style="font-size:0.8rem"><?=$row["CmtPostdate"]?></div>
								<div class="row col-12 mt-2"><?=$cmt_content?></div>
								<hr>
<?php
								} 
							}
?>
							</div>
							<div class="row">
								<div class="col-9 col-lg-10 pr-0">
									<textarea class="form-control w-100" id="comment-box" style="resize:none" onkeyup="enableButton()" placeholder="댓글을 입력하세요..."></textarea>
								</div>
								<div class="col-3 col-lg-2 pl-0">
									<button type="button" id="add-btn" class="btn btn-info btn-block h-100 p-1 disabled" onclick="addComment()">작성</button>
								</div>
								<div id="progress-bar-div" class="col-12"></div>
							</div>
						</div>
					</div>
					<script>
						function enableButton() {
							if(document.getElementById('comment-box').value.match('^(| +)$'))
								document.getElementById('add-btn').className = "btn btn-info btn-block h-100 p-1 disabled";
							else
								document.getElementById('add-btn').className = "btn btn-info btn-block h-100 p-1";
						}

						function addComment() {
							var content = document.getElementById('comment-box').value;
							if(content.match('^(| +)$')) { return; }
							document.getElementById('comment-box').value = "";

							var xhttp = new XMLHttpRequest();
							xhttp.onreadystatechange = function() {
								if (this.readyState == 4) {
									if (this.status == 200) {
										if(this.responseText == -1)
											alert('댓글을 등록하는 중 요류가 발생했습니다.');
										else
											document.getElementById('comments-div').innerHTML = this.responseText;
									} else {
										alert('댓글을 등록하는 중 요류가 발생했습니다.');
									}
								}
							};
							xhttp.open("POST", "/freeboard/view/add-comment.php?postno=<?=$_GET["postno"]?>", true);
							xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
							xhttp.send('UserID=<?=$_SESSION["user_id"]?>&UserUsername=<?=$_SESSION["user_username"]?>&CmtContent=' + content);
						}

						function deleteComment(cmt_id) {
							if( !confirm("댓글을 삭제하시겠습니까?") ) { return; }

							var xhttp = new XMLHttpRequest();
							xhttp.onreadystatechange = function() {
								if (this.readyState == 4) {
									if (this.status == 200) {
										if(this.responseText == -1)
											alert('댓글을 등록하는 중 요류가 발생했습니다.');
										else
											document.getElementById('comments-div').innerHTML = this.responseText;
									} else {
										alert('댓글을 등록하는 중 요류가 발생했습니다.');
									}
								}
							};
							xhttp.open("POST", "/freeboard/view/delete-comment.php?postno=<?=$_GET["postno"]?>", true);
							xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
							xhttp.send('UserID=<?=$_SESSION["user_id"]?>&UserUsername=<?=$_SESSION["user_username"]?>&CmtID=' + cmt_id);
						}
					</script>
<?php
				}
			} else {
				echo "<script>alert('게시글을 불러오는 중 오류가 발생했습니다.');";
				echo "location.href = '/freeboard';</script>";
			}
?>
		</div>
	</div>
</div>

<!-- Footer -->
<?php include $WEB_ROOT.'/templates/footer.php'; ?>

</body>
</html>
