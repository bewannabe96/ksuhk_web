<div class="card">
	<h4 class="card-header">회원정보수정</h4>
	<div class="card-block">
	<form id="memberinfo-form" method="post">
		<div id="name-form-group" class="row form-group">
			<label class="col-sm-3 col-form-label text-sm-right">이름</label>
			<div class="col-sm-9">
				<p class="form-control-static text-muted"><?=$user["UserName"]?></p>
			</div>
		</div>
		<div id="username-form-group" class="row form-group">
			<label class="col-sm-3 col-form-label text-sm-right">아이디</label>
			<div class="col-sm-9">
				<p class="form-control-static text-muted"><?=$user["UserUsername"]?></p>
			</div>
		</div>
		<div id="school-form-group" class="row form-group">
			<label class="col-sm-3 col-form-label text-sm-right">학교</label>
			<div class="col-sm-9">
				<select id="school-select" class="form-control form-control-danger" name="UserSchool">
					<option value="1">Hong Kong University of Science and Technology (HKUST/과기대)</option>
					<option value="2">The University of Hong Kong (HKU/홍콩대)</option>
					<option value="3">The Chinese University of Hong Kong (CUHK/중문대)</option>
					<option value="4">The Hong Kong Polytechnic University (POLYU/이공대)</option>
					<option value="5">City University of Hong Kong (CITYU/시립대)</option>
				</select>
			</div>
		</div>
		<div id="grade-form-group" class="row form-group">
			<label class="col-sm-3 col-form-label text-sm-right">학년</label>
			<div class="col-sm-9">
				<select id="grade-select" class="form-control form-control-danger" name="UserGrade">
					<option value="1">1 학년</option>
					<option value="2">2 학년</option>
					<option value="3">3 학년</option>
					<option value="4">4 학년</option>
				</select>
			</div> </div>
		<div id="major-form-group" class="row form-group">
			<label class="col-sm-3 col-form-label text-sm-right">전공</label>
			<div class="col-sm-9">
				<select id="major-select" class="form-control form-control-danger" name="UserMajor">
					<option value="1">경영대학 (경영, 경제, 호경, 회계, 금융 등등)</option>
					<option value="2">사회과학대학 (인류학, 신방, 사회학, 건축 등등)</option>
					<option value="3">공학대학 (의공, 컴공, 전자전기 등등)</option>
					<option value="4">인문예술대학 (영어, 미술, 역사 등등)</option>
					<option value="5">과학대학 (생물, 화학, 물리 등등)</option>
					<option value="6">그 외 (위에 없는 전공들)</option>
				</select>
			</div>
		</div>
		<div id="phone-no-form-group" class="row form-group">
			<label class="col-sm-3 col-form-label text-sm-right">연락처</label>
			<div class="col-sm-9">
				<input type="text" class="form-control form-control-danger" id="phone-no-input" name="UserPhoneNo" placeholder="전화번호('-'빼고 8자리)" value="<?=$user["UserPhoneNo"]?>" autocomplete="off">
				<div id="phone-error-msg" class="form-text form-control-feedback float-xs-right"></div>
			</div>
		</div>
		<div id="email-form-group" class="row form-group">
			<label class="col-sm-3 col-form-label text-sm-right">이메일</label>
			<div class="col-sm-9">
				<p class="form-control-static text-muted"><?=$user["UserEmail"]?></p>
			</div>
		</div>
		<div id="birth-form-group" class="row form-group">
			<label class="col-sm-3 col-form-label text-sm-right">생년월일</label>
			<div class="col-sm-9">
				<p class="form-control-static text-muted"><?=$user["UserBirth"]?></p>
			</div>
		</div>
		<div class="row form-group">
			<label class="col-sm-3 col-form-label text-sm-right">카카오톡(옵션)</label>
			<div class="col-sm-9">
				<input type="text" class="form-control" name="UserKakaoID" placeholder="카카오톡 아이디" value="<?=$user["UserKakaoID"]?>" autocomplete="off">
			</div>
		</div>
		<hr>
		<div id="passwd-form-group" class="row form-group">
			<label class="col-sm-3 col-form-label text-sm-right">비밀번호확인</label>
			<div class="col-sm-9">
				<input class="form-control form-control-danger" type="password" placeholder="비밀번호" id="passwd-input" autocomplete="off">
				<input type="password" id="passwd-encrypt-input" name="UserPassword" hidden>
			</div>
		</div>
		<p class="text-warning text-right">회원정보 변경시 즉시 로그아웃되며, 관리자의 승인까지 로그인을 하실 수 없습니다.</p>
		<button type="submit" name="submit" class="btn btn-primary btn-block mt-4">수정</button>
	</form>
	<script>
		document.getElementById('school-select').value = <?=$user["UserSchool"]?>;
		document.getElementById('grade-select').value = <?=$user["UserGrade"]?>;
		document.getElementById('major-select').value = <?=$user["UserMajor"]?>;
	</script>
	</div>
</div>

<script src="/library/sha256.min.js"></script>
<script>

<?php
	if( isset( $check ) && $check == UserDBManager::$PASSWORD_INCORRECT ) {
?>
		document.getElementById('passwd-form-group').className = "row form-group has-danger";
		createAttention("비밀번호가 일치하지 않습니다.");
		document.getElementById('passwd-input').focus();
<?php
	}
?>

	isSchoolValid = true;
	isGradeValid = true;
	isMajorValid = true;
	isPhoneValid = true;
	phoneNo = "";

	document.getElementById('passwd-input').onchange = function() {
		if(document.getElementById('passwd-input').value != '') {
			document.getElementById('passwd-form-group').className = "row form-group";
		}
	}

	document.getElementById('school-select').onchange = function() {
		var selectedVal = document.getElementById('school-select').value;

		if(selectedVal>=1 && selectedVal<=5) {
			document.getElementById('school-form-group').className = "row form-group";
			isSchoolValid = true;
		} else {
			isSchoolValid = false;
		}
	}

	document.getElementById('grade-select').onchange = function() {
		var selectedVal = document.getElementById('grade-select').value;

		if(selectedVal>=1 && selectedVal<=4) {
			document.getElementById('grade-form-group').className = "row form-group";
			isGradeValid = true;
		} else {
			isGradeValid = false;
		}
	}

	document.getElementById('major-select').onchange = function() {
		var selectedVal = document.getElementById('major-select').value;

		if(selectedVal>=1 && selectedVal<=6) {
			document.getElementById('major-form-group').className = "row form-group";
			isMajorValid = true;
		} else {
			isMajorValid = false;
		}
	}

	document.getElementById('phone-no-input').onfocus = function() {
		document.getElementById('phone-no-input').value = phoneNo;
		isPhoneValid = false;
	}

	document.getElementById('phone-no-input').onblur = function() {
		phoneNo = document.getElementById('phone-no-input').value;
		var matched = phoneNo.match('^[0-9]{8}$');

		if(matched && phoneNo===matched[0]) {
			document.getElementById('phone-no-form-group').className = "row form-group";
			document.getElementById('phone-error-msg').innerHTML = "";
			document.getElementById('phone-no-input').value = matched[0].match('^[0-9]{4}') + "-" + matched[0].match('[0-9]{4}$');
			isPhoneValid = true;
		} else {
			document.getElementById('phone-no-form-group').className = "row form-group has-danger";
			document.getElementById('phone-error-msg').innerHTML = "유효하지 않은 번호입니다.";
			isPhoneValid = false;
		}
	}

	document.getElementById('memberinfo-form').onsubmit = function() {

		if(!isSchoolValid) {
			document.getElementById('school-form-group').className = "row form-group has-danger";
			createAlert("학교를 확인해 주세요.");
			return false;

		} else if(!isGradeValid) {
			document.getElementById('grade-form-group').className = "row form-group has-danger";
			createAlert("학년을 확인해 주세요.");
			return false;

		} else if(!isMajorValid) {
			document.getElementById('major-form-group').className = "row form-group has-danger";
			createAlert("전공을 확인해 주세요.");
			return false;

		} else if(!isPhoneValid) {
			document.getElementById('phone-no-form-group').className = "row form-group has-danger";
			document.getElementById('phone-no-input').focus();
			createAlert("전화번호를 확인해 주세요.");
			return false;

		} else if(document.getElementById('passwd-input').value == '') {
			document.getElementById('passwd-form-group').className = "row form-group has-danger";
			createAttention("비밀번호를 확인해 주세요.");
			document.getElementById('passwd-input').focus();
			return false;
		}

		document.getElementById('passwd-encrypt-input').value = sha256(document.getElementById('passwd-input').value);
	}
</script>
