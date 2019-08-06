<?php

	$env_page = "page_freeboard_write";

	include $_SERVER["DOCUMENT_ROOT"].'/config.php';
	CHECK_LOGIN_SESSION();

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>KSUHK - 자유게시판 / 글쓰기</title>
	<!-- HTML Header -->
	<?php include $WEB_ROOT.'/templates/html-header.php'; ?>
</head>
<body class="bg-faded">

<!-- Navigator -->
<?php include $WEB_ROOT.'/templates/navigator.php'; ?>

<div class="container my-0 my-sm-5 p-0">
	<div class="row w-100 m-0">
		<div class="col-lg-8 offset-lg-2 p-0 p-sm-3">
			<ol class="breadcrumb mb-0 mb-sm-3">
				<li class="breadcrumb-item"><a href="/freeboard">자유게시판</a></li>
				<li class="breadcrumb-item active">새 자유게시글</li>
			</ol>
			<div class="card">
				<div class="card-block">
					<h3 class="m-3 mx-sm-0">자유게시글</h3>
					<form id="freeboard-form" method="post" action="./ok.php" enctype="multipart/form-data">
						<div id="title-form-group" class="form-group">
							<label class="col-form-label text-right">제목</label>
							<input class="form-control form-control-danger" type="text" placeholder="제목" id="title-input" name="PostTitle" value="<?php if(isset($_POST["PostTitle"])){echo $_POST["PostTitle"];} ?>" maxlength="60" autocomplete="off">
						</div>
						<div id="content-form-group" class="form-group">
							<label class="col-form-label text-right">본문</label>
							<textarea class="form-control form-control-danger" id="content-textarea" name="PostContent" rows="15"><?php if(isset($_POST["PostContent"])){echo $_POST["PostContent"];} ?></textarea>
						</div>
						<div id="file-form-group" class="form-group">
							<label class="col-form-label text-right">사진</label>
							<input id="file-input" type="file" class="form-control form-control-danger" name="PostImage">
							<small id="file-error-msg" class="form-text text-muted">2MB 이하의 이미지 파일(PNG/JPG/JPEG)</small>
						</div>

						<div class="text-right">
							<div class="btn-group">
								<button type="button" class="btn btn-secondary" onclick="location.href = '/freeboard'">취소</button>
								<button type="submit" name="submit" class="btn btn-primary">작성</button>
							</div>
						</div>
					</form>
					<?php include $WEB_ROOT.'/templates/popup-message.php'; ?>
					<script>
<?php
					if( isset( $_POST["resultCode"] ) ) {
						if( $_POST["resultCode"] == 2 ) {
							echo "createAlert('파일 크기를 확인해 주세요.');";
						} else if( $_POST["resultCode"] == 3 ) {
							echo "createAlert('파일 종류를 확인해 주세요.');";
						} else {
							echo "createAlert('게시글 게시 중 오류가 발생했습니다.', '알림!');";
						}
					}
?>
						var fileSelect = document.getElementById('file-input');
						fileSelect.onchange = function() {
							var file = fileSelect.files[0];

							if(file.size >= 2097152) {
								createAlert('파일의 크기를 다시 확인해주세요.');
								fileSelect.value = '';
								document.getElementById('file-form-group').className = "form-group has-danger";
								document.getElementById('file-error-msg').className = "form-text form-control-feedback";
							} else if(!fileSelect.files[0].type.match('image')) {
								createAlert('파일의 종류를 다시 확인해주세요.');
								fileSelect.value = '';
								document.getElementById('file-form-group').className = "form-group has-danger";
								document.getElementById('file-error-msg').className = "form-text form-control-feedback";
							} else {
								document.getElementById('file-form-group').className = "form-group";
								document.getElementById('file-error-msg').className = "form-text text-muted";
							}
						}
						
						document.getElementById('freeboard-form').onsubmit = function() {
							if(document.getElementById('title-input').value=="") {
								createAlert('제목을 입력해 주세요.');
								document.getElementById('title-form-group').className = "form-group has-danger";
								return false;
							} else {
								document.getElementById('title-form-group').className = "form-group";
							}

							if(document.getElementById('content-textarea').value=="") {
								createAlert('본문을 입력해 주세요.');
								document.getElementById('content-form-group').className = "form-group has-danger";
								return false;
							} else {
								document.getElementById('content-form-group').className = "form-group";
							}

							return confirm("정말 작성하시겠습니까?");
						}
					</script>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Footer -->
<?php include $WEB_ROOT.'/templates/footer.php'; ?>

</body>
</html>
