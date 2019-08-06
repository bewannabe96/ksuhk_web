<div class="row w-100 m-0">
	<div class="col-lg-6 offset-lg-3 p-0 p-sm-3">
	<div class="card">
		<h4 class="card-header">회원가입</h4>
		<div class="card-block">
		<form id="signup-form" method="post" action="./ok.php">
			<div id="name-form-group" class="row form-group">
				<label class="col-sm-3 col-form-label">이름</label>
				<div class="col-sm-9">
					<input type="text" class="form-control form-control-danger" id="name-input" name="UserName" placeholder="이름" value="<?php if(isset($_POST["UserName"])){echo $_POST["UserName"];} ?>" autocomplete="off">
					<div id="name-error-msg" class="form-text form-control-feedback float-xs-right"></div>
				</div>
			</div>
			<div id="username-form-group" class="row form-group <?php if(isset($_POST["resultCode"])&&$_POST["resultCode"]==1) { echo "has-danger"; } ?>">
				<label class="col-sm-3 col-form-label">아이디</label>
				<div class="col-sm-9">
					<input type="text" class="form-control form-control-danger form-control-warning" id="username-input" name="UserUsername" placeholder="아이디(4-10 글자)" value="<?php if(isset($_POST["UserUsername"])){echo $_POST["UserUsername"];} ?>" autocomplete="off">
					<div id="username-error-msg" class="form-text form-control-feedback float-xs-right">
						<?php if( isset($_POST["resultCode"]) && $_POST["resultCode"]==1 ) { echo "이미 사용자가 존재합니다."; } ?>
					</div>
				</div>
			</div>
			<div id="passwd-form-group" class="row form-group">
				<label class="col-sm-3 col-form-label">비밀번호</label>
				<div class="col-sm-9">
					<input type="password" class="form-control form-control-success form-control-warning form-control-danger" id="passwd-input" placeholder="비밀번호" autocomplete="off">
					<input type="password" id="passwd-encrypt-input" name="UserPassword" hidden>
					<div id="passwd-error-msg" class="text-muted form-text float-xs-right">알파벳 대소문자, 숫자 포함한 8-20 글자</div>
				</div>
			</div>
			<div id="passwd-confirm-form-group" class="row form-group">
				<div class="offset-sm-3 col-sm-9">
					<input type="password" class="form-control form-control-danger form-control-success" id="passwd-confirm-input" placeholder="비밀번호 확인">
					<div id="passwd-confirm-error-msg" class="form-text form-control-feedback float-xs-right"></div>
				</div>
			</div>
			<div id="school-form-group" class="row form-group">
				<label class="col-sm-3 col-form-label">학교</label>
				<div class="col-sm-9">
					<select id="school-select" class="form-control form-control-danger" name="UserSchool">
						<option disabled selected hidden>학교</option>
						<option value="1">Hong Kong University of Science and Technology (HKUST/과기대)</option>
						<option value="2">The University of Hong Kong (HKU/홍콩대)</option>
						<option value="3">The Chinese University of Hong Kong (CUHK/중문대)</option>
						<option value="4">The Hong Kong Polytechnic University (POLYU/이공대)</option>
						<option value="5">City University of Hong Kong (CITYU/시립대)</option>
					</select>
				</div>
			</div>
			<div id="grade-form-group" class="row form-group">
				<label class="col-sm-3 col-form-label">학년</label>
				<div class="col-sm-9">
					<select id="grade-select" class="form-control form-control-danger" name="UserGrade">
						<option disabled selected hidden>학년</option>
						<option value="1">1 학년</option>
						<option value="2">2 학년</option>
						<option value="3">3 학년</option>
						<option value="4">4 학년</option>
					</select>
				</div> </div>
			<div id="major-form-group" class="row form-group">
				<label class="col-sm-3 col-form-label">전공</label>
				<div class="col-sm-9">
					<select id="major-select" class="form-control form-control-danger" name="UserMajor">
						<option disabled selected hidden>전공</option>
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
				<label class="col-sm-3 col-form-label">연락처</label>
				<div class="col-sm-9">
					<input type="text" class="form-control form-control-danger" id="phone-no-input" name="UserPhoneNo" placeholder="전화번호('-'빼고 8자리)" value="<?php if(isset($_POST["UserPhoneNo"])){echo $_POST["UserPhoneNo"];} ?>" autocomplete="off">
					<div id="phone-error-msg" class="form-text form-control-feedback float-xs-right"></div>
				</div>
			</div>
			<div id="email-form-group" class="row form-group <?php if(isset($_POST["resultCode"])&&$_POST["resultCode"]==2) { echo "has-danger"; } ?>">
				<label class="col-sm-3 col-form-label">이메일</label>
				<div class="col-sm-9">
					<input type="text" class="form-control form-control-danger" id="email-input" name="UserEmail" placeholder="이메일" value="<?php if(isset($_POST["UserEmail"])){echo $_POST["UserEmail"];} ?>" autocomplete="off">
					<div id="email-error-msg" class="form-text form-control-feedback float-xs-right"></div>
				</div>
			</div>
			<div id="birth-form-group" class="row form-group">
				<label class="col-sm-3 col-form-label">생년월일</label>
				<div class="col-sm-9">
					<input type="date" class="form-control" id="birth-input" name="UserBirth" value="<?php if(isset($_POST["UserBirth"])){echo $_POST["UserBirth"];} ?>">
				</div>
			</div>
			<div class="row form-group">
				<label class="col-sm-3 col-form-label">카카오톡(옵션)</label>
				<div class="col-sm-9">
					<input type="text" class="form-control" name="UserKakaoID" placeholder="카카오톡 아이디" value="<?php if(isset($_POST["UserKakaoID"])){echo $_POST["UserKakaoID"];} ?>" autocomplete="off" maxlength="15">
				</div>
			</div>
			<button type="submit" name="submit" class="btn btn-primary btn-block mt-4">회원가입</button>
		</form>
		</div>
	</div>
	</div>
</div>

<script src="/library/sha256.min.js"></script>
<script>
	isNameValid = false;
	isUsernameValid = false;
	isPasswdValid = false;
	isPasswdConfirmed = false;
	isSchoolValid = false;
	isGradeValid = false;
	isMajorValid = false;
	isPhoneValid = false;
	isEmailValid = false;
	isBirthValid = false;
	phoneNo = "";

<?php
	if( isset( $_POST["resultCode"] ) ) {
?>
		isNameValid = true;
		isPasswdValid = true;
		isPasswdConfirmed = true;
		isPhoneValid = true;
		isBirthValid = true;
<?php
		if( $_POST["resultCode"] == 1 ) {
?>
			isEmailValid = true;
			createAttention("이미 존재하는 아이디입니다.");
<?php
		} else if( $_POST["resultCode"] == 2 ) {
?>
			isUsernameValid = true;
			createAttention("이미 존재하는 이메일입니다.");
<?php
		}
	}
?>

	document.getElementById('name-input').onchange = function() {
		var nameVal = document.getElementById('name-input').value;

		if(!nameVal.match('[0-9]')) {
			document.getElementById('name-form-group').className = "row form-group";
			document.getElementById('name-error-msg').innerHTML = "";
			isNameValid = true;
		} else {
			document.getElementById('name-form-group').className = "row form-group has-danger";
			document.getElementById('name-error-msg').innerHTML = "올바른 이름이 아닙니다.";
			isNameValid = false;
		}
	}

	document.getElementById('username-input').onchange = function() {
		var usernameVal = document.getElementById('username-input').value;
		var matched = usernameVal.match('^[0-9a-zA-Z]{4,10}$');
		
		if(matched && usernameVal===matched[0]) {
			document.getElementById('username-form-group').className = "row form-group";
			document.getElementById('username-error-msg').innerHTML = "";
			isUsernameValid = true;
		} else {
			document.getElementById('username-form-group').className = "row form-group has-warning";
			document.getElementById('username-error-msg').innerHTML = "영문과 숫자로 이루어진 4-10 글자"; isUsernameValid = false;
		}
	}

	document.getElementById('passwd-input').onfocus = function() {
		document.getElementById('passwd-confirm-input').value = "";
		document.getElementById('passwd-confirm-error-msg').innerHTML = "";
		document.getElementById('passwd-confirm-form-group').className = "row form-group";
	}

	document.getElementById('passwd-input').onkeyup = function() {
		var passwdVal = document.getElementById('passwd-input').value;

		if(passwdVal.length<8 || passwdVal.length>20 || !passwdVal.match('[0-9]') || !passwdVal.match('[a-z]') || !passwdVal.match('[A-Z]')) {
			document.getElementById('passwd-form-group').className = "row form-group";
			document.getElementById('passwd-error-msg').className = "text-muted form-text float-xs-right";
			isPasswdValid = false;
		} else {
			document.getElementById('passwd-form-group').className = "row form-group has-success";
			document.getElementById('passwd-error-msg').className = "form-text form-control-feedback float-xs-right";
			isPasswdValid = true;
		}
	}

	document.getElementById('passwd-input').onchange = function() {
		if(!isPasswdValid) {
			document.getElementById('passwd-form-group').className = "row form-group has-warning";
			document.getElementById('passwd-error-msg').className = "form-text form-control-feedback float-xs-right";
		}
	}

	document.getElementById('passwd-confirm-input').onchange = function() {
		if(!isPasswdValid){return;}

		var passwdVal = document.getElementById('passwd-input').value;
		var confirmVal = document.getElementById('passwd-confirm-input').value;

		if(passwdVal!==confirmVal) {
			document.getElementById('passwd-confirm-error-msg').innerHTML = "비밀번호가 일치하지 않습니다.";
			document.getElementById('passwd-confirm-form-group').className = "row form-group has-danger";
			isPasswdConfirmed = false;
		} else {
			document.getElementById('passwd-confirm-error-msg').innerHTML = "";
			document.getElementById('passwd-confirm-form-group').className = "row form-group has-success";
			isPasswdConfirmed = true;
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

	document.getElementById('email-input').onchange = function() {
		var emailVal = document.getElementById('email-input').value;

		if(emailVal.match(/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/)) {
			document.getElementById('email-form-group').className = "row form-group";
			document.getElementById('email-error-msg').innerHTML = "";
			isEmailValid = true;
		} else {
			document.getElementById('email-form-group').className = "row form-group has-danger";
			document.getElementById('email-error-msg').innerHTML = "이메일 형식에 맞지 않습니다.";
			isEmailValid = false;
		}
	}

	document.getElementById('birth-input').onchange = function() {
		var birthVal = document.getElementById('birth-input').value;

		if(birthVal!="") {
			document.getElementById('birth-form-group').className = "row form-group";
			document.getElementById('birth-input').className = "form-control";
			isBirthValid = true;
		} else {
			isBirthValid = false;
		}
	}

	document.getElementById('signup-form').onsubmit = function() {
		if(!isNameValid) {
			document.getElementById('name-form-group').className = "row form-group has-danger";
			document.getElementById('name-input').focus();
			createAlert("이름을 확인해 주세요.");
			return false;

		} else if(!isUsernameValid) {
			document.getElementById('username-form-group').className = "row form-group has-danger";
			document.getElementById('username-input').focus();
			createAlert("아이디를 확인해 주세요.");
			return false;

		} else if(!isPasswdValid) {
			document.getElementById('passwd-form-group').className = "row form-group has-danger";
			document.getElementById('passwd-error-msg').className = "form-text form-control-feedback float-xs-right";
			document.getElementById('passwd-input').focus();
			createAlert("비밀번호를 확인해 주세요.");
			return false;

		} else if(!isPasswdConfirmed) {
			document.getElementById('passwd-confirm-form-group').className = "row form-group has-danger";
			document.getElementById('passwd-confirm-input').focus();
			createAlert("비밀번호를 확인해 주세요.");
			return false;

		} else if(!isSchoolValid) {
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

		} else if(!isEmailValid) {
			document.getElementById('email-form-group').className = "row form-group has-danger";
			document.getElementById('email-input').focus();
			createAlert("이메일을 확인해 주세요.");
			return false;

		} else if(!isBirthValid) {
			document.getElementById('birth-form-group').className = "row form-group has-danger";
			document.getElementById('birth-input').className = "form-control form-control-danger";
			document.getElementById('birth-input').focus();
			createAlert("생년월일을 확인해 주세요.");
			return false;
		}

		document.getElementById('passwd-encrypt-input').value = sha256(document.getElementById('passwd-input').value);
	}
</script>
