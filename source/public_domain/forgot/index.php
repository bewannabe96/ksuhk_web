<?php

	$env_page = "page_forgot";

	include $_SERVER["DOCUMENT_ROOT"].'/config.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>KSUHK - 아이디 / 비밀번호 찾기</title>
	<!-- HTML Header -->
	<?php include $WEB_ROOT.'/templates/html-header.php'; ?>
</head>
<body class="bg-faded">

<!-- Navigator -->
<?php include $WEB_ROOT.'/templates/navigator.php'; ?>

<div class="container my-5">
	<div class="row">
		<div class="col-lg-4 offset-lg-2">
			<h4>아이디 찾기</h4>
			<div class="card card-block text-right">
				<p class="text-left mb-4">이름과 이메일을 입력해주세요.</p>
				<form action="./username/" method="post">
					<div class="form-group text-left">
						<label class="form-label">이름</label>
						<input type="text" name="UserName" class="form-control" placeholder="이름" autocomplete="off">
					</div>
					<div class="form-group text-left">
						<label class="form-label">이메일</label>
						<input type="email" name="UserEmail" class="form-control" placeholder="이메일" autocomplete="off">
					</div>
					<input type="submit" name="submit" class="btn btn-outline-info" value="아이디 찾기">
				</form>
			</div>
		</div>
		<div class="col-lg-4 mt-5 mt-lg-0">
			<h4>비밀번호 재설정</h4>
			<div class="card card-block text-right">
				<p class="text-left mb-4">아이디와 이메일을 입력해주세요.</p>
				<form action="./password/" method="post">
					<div class="form-group text-left">
						<label class="form-label">KSUHK 아이디</label>
						<input type="text" name="UserUsername" class="form-control" placeholder="아이디" autocomplete="off">
					</div>
					<div class="form-group text-left">
						<label class="form-label">이메일</label>
						<input type="email" name="UserEmail" class="form-control" placeholder="이메일" autocomplete="off">
					</div>
					<input type="submit" name="submit" class="btn btn-outline-info" value="인증번호 받기">
				</form>
			</div>
		</div>
	</div>
</div>

<!-- Footer -->
<?php include $WEB_ROOT.'/templates/footer.php'; ?>

</body>
</html>
