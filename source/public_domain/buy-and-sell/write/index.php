<?php

	$env_page = "page_buyandsell_write";

	include $_SERVER["DOCUMENT_ROOT"].'/config.php';
	CHECK_LOGIN_SESSION();

	include $WEB_ROOT.'/classes/UserDBManager.php';
	$user_db = UserDBManager::createUserDBManager();

	if( $user_db !== UserDBManager::$CONNECT_ERROR ) {
		$user = $user_db->select_user_byusername( $_SESSION["user_username"] );
		if( !$user ) {
			echo "<script>alert('현재 서버문제로 회원정보를 불러올 수 없습니다.');";
			echo "location.href = '/buy-and-sell';</script>";
		}
	} else {
		echo "<script>alert('현재 서버문제로 회원정보를 불러올 수 없습니다.');";
		echo "location.href = '/buy-and-sell';</script>";
	}

	if( $user["UserKakaoID"] == "" ) { $user["UserKakaoID"] = "(정보없음)"; }

	switch( $user["UserSchool"] ) {
		case 1:
			$user["UserSchool"] = '<img src="/src/school-logo/hkust.png" style="height:1.5rem;"/>';
			$user["UserSchool"] .= '<p class="form-control-static text-muted d-inline ml-2">홍콩과학기술대학교(HKUST)</p>';
			break;
		case 2:
			$user["UserSchool"] = '<img src="/src/school-logo/hku.png" style="height:1.5rem;"/>';
			$user["UserSchool"] .= '<p class="form-control-static text-muted d-inline ml-2">홍콩대학교(HKU)</p>';
			break;
		case 3:
			$user["UserSchool"] = '<img src="/src/school-logo/cuhk.png" style="height:1.5rem;" class="d-inline"/>';
			$user["UserSchool"] .= '<p class="form-control-static text-muted d-inline ml-2">홍콩중문대학교(CUHK)</p>';
			break;
		case 4:
			$user["UserSchool"] = '<img src="/src/school-logo/polyu.png" style="height:1.5rem;"/>';
			$user["UserSchool"] .= '<p class="form-control-static text-muted d-inline ml-2">홍콩이공대학교(POLYU)</p>';
			break;
		case 5:
			$user["UserSchool"] = '<img src="/src/school-logo/cityu.png" style="height:1.5rem;"/>';
			$user["UserSchool"] .= '<p class="form-control-static text-muted d-inline ml-2">홍콩시립대학교(CITYU)</p>';
			break;
		default:
			$user["UserSchool"] = '(해당사항 없음)';
	}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>KSUHK - 벼룩시장 / 글쓰기</title>
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
				<li class="breadcrumb-item"><a href="/buy-and-sell">벼룩시장</a></li>
				<li class="breadcrumb-item active">새 판매글</li>
			</ol>
			<div class="card">
				<div class="card-block">
					<h3 class="m-3 mx-sm-0">판매게시글</h3>
					<form id="buy-and-sell-form" method="post" action="./ok.php">
						<div id="title-form-group" class="form-group">
							<label class="col-form-label text-right">제목</label>
							<input class="form-control form-control-danger" type="text" placeholder="제목(물품명)" id="title-input" name="BASTitle" value="<?php if(isset($_POST["BASTitle"])){echo $_POST["BASTitle"];} ?>" maxlength="60" autocomplete="off">
						</div>
						<div id="file-form-group" class="form-group">
							<label class="col-form-label text-right">판매자 정보</label>
							<input name="UserID" value="<?=$_SESSION["user_id"]?>" hidden>
							<div class="card card-block py-2">
								<div class="form-group row">
									<label class="col-4 col-md-2 col-form-label">이름</label>
									<div class="col-8 col-md-3">
										<p class="form-control-static text-muted "><?=$user["UserName"]?></p>
									</div>
									<label class="col-4 col-md-2 col-form-label mt-3 mt-md-0">이메일</label>
									<div class="col-8 col-md-5 mt-3 mt-md-0">
										<p class="form-control-static text-muted"><?=$user["UserEmail"]?></p>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-4 col-md-2 col-form-label">연락처</label>
									<div class="col-8 col-md-3">
										<p class="form-control-static text-muted "><?=$user["UserPhoneNo"]?></p>
									</div>
									<label class="col-4 col-md-2 col-form-label mt-3 mt-md-0">카카오톡</label>
									<div class="col-8 col-md-5 mt-3 mt-md-0">
										<p class="form-control-static text-muted "><?=$user["UserKakaoID"]?></p>
									</div>
								</div>
								<div class="form-group row mb-0">
									<label class="col-4 col-md-2 col-form-label">학교</label>
									<div class="col-8 col-md-10 py-1"><?=$user["UserSchool"]?></div>
								</div>
							</div>
							<small class="form-text text-muted text-right w-100">판매자 정보는 회원정보랑 동일합니다.</small>
						</div>
						<div id="content-form-group row" class="form-group">
							<label class="col-form-label text-right">물품 정보</label>
							<div class="row">
								<div id="type-form-group" class="input-group col-md-6">
									<span class="input-group-addon">분류</span>
									<div class="form-control form-group">
										<select id="type-select" class="form-control h-100 w-100" name="BASType">
											<option disabled="" selected="" hidden="" value="0">분류</option>
											<option value="2">의류</option>
											<option value="3">전자제품</option>
											<option value="4">생활용품</option>
											<option value="5">학용품/사무용품</option>
											<option value="6">운동용품/악기</option>
											<option value="7">재능나눔</option>
											<option value="1">기타</option>
										</select>
									</div>
								</div>
								<div id="price-form-group" class="input-group col-md-6 mt-3 mt-md-0">
									<span class="input-group-addon">판매가</span>
									<span class="input-group-addon">HK $</span>
									<input type="number" id="price-input" class="form-control" name="BASPrice" placeholder="판매가" min="0" max="99999" value="<?php if(isset($_POST["BASPrice"])){echo $_POST["BASPrice"];} ?>">
								</div>
							</div>
						</div>
						<div id="file-form-group" class="form-group">
							<label class="col-form-label text-right">사진</label>
							<?php include 'image-uploader.php'; ?>
						</div>
						<div id="content-form-group" class="form-group">
							<label class="col-form-label text-right">설명</label>
							<textarea class="form-control form-control-danger" id="content-textarea" name="BASContent" rows="15"><?php if(isset($_POST["BASContent"])){echo $_POST["BASContent"];} ?></textarea>
						</div>
						<div class="text-right">
							<div class="btn-group">
								<button type="button" class="btn btn-secondary" onclick="location.href = '/buy-and-sell'">취소</button>
								<button type="submit" name="submit" class="btn btn-primary">등록</button>
							</div>
						</div>
					</form>
					<script>
<?php
						if( isset( $_POST["resultCode"] ) ) {
							if( $_POST["resultCode"] == 2 ) {
								echo "createAlert('이미지를 옮기는 저장하는 중 오류가 발생했습니다.', '알림!');";
							} else {
								echo "createAlert('판매글 게시 중 오류가 발생했습니다.', '알림!');";
							}
						}
?>
						document.getElementById('title-input').onchange = function() {
							if(this.value != "")
								document.getElementById('title-form-group').className = "form-group";
						};

						document.getElementById('type-select').onchange = function() {
							if(this.value != 0)
								document.getElementById('type-form-group').className = "input-group col-md-6";
						};

						document.getElementById('price-input').onchange = function() {
							if(this.value != "") {
								document.getElementById('price-form-group').className = "input-group col-md-6 mt-3 mt-md-0";
								this.value = this.value < 0 ? 0 : (this.value > 99999 ? 99999 : this.value);
							}
						};

						document.getElementById('content-textarea').oncahge = function() {
							if(this.value != 0)
								document.getElementById('content-form-group').className = "form-group"
						};

						document.getElementById('buy-and-sell-form').onsubmit = function() {
							if(document.getElementById('title-input').value=="") {
								createAlert('제목을 입력해 주세요.');
								document.getElementById('title-form-group').className = "form-group has-danger";
								return false;
							}

							if(document.getElementById('type-select').value==0) {
								createAlert('물품분류를 선택해 주세요.');
								document.getElementById('type-form-group').className = "input-group col-md-6 has-danger";
								return false;
							}

							if(document.getElementById('price-input').value=="") {
								createAlert('판매가를 입력해 주세요.');
								document.getElementById('price-form-group').className = "input-group col-md-6 mt-3 mt-md-0 has-danger";
								return false;
							}

							if(document.getElementById('content-textarea').value=="") {
								createAlert('본문을 입력해 주세요.');
								document.getElementById('content-form-group').className = "form-group has-danger";
								return false;
							}

							isSubmit = true;

							if(imgCount == 0)
								return confirm("업로드된 사진이 없습니다. 사진없이 작성하시겠습니까?");
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
