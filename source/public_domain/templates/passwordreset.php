<div class="card">
	<h4 class="card-header">비밀번호 재설정</h4>
	<div class="card-block">
		<form id="password-form" method="post">
			<div id="passwd-form-group" class="form-group">
				<label class="form-label">새 비밀번호</label>
				<input type="password" class="form-control form-control-success form-control-warning form-control-danger" id="passwd-input" placeholder="새 비밀번호" autocomplete="off">
				<input type="password" id="passwd-encrypt-input" name="UserPassword" hidden>
				<div id="passwd-error-msg" class="text-muted form-text float-xs-right">알파벳 대소문자, 숫자 포함한 8-20 글자</div>
			</div>
			<div id="passwd-confirm-form-group" class="form-group">
				<label class="form-label">새 비밀번호 확인</label>
				<input type="password" class="form-control form-control-danger form-control-success" id="passwd-confirm-input" placeholder="새 비밀번호 확인">
				<div id="passwd-confirm-error-msg" class="form-text form-control-feedback float-xs-right"></div>
			</div>
			<input type="submit" name="reset-submit" class="btn btn-primary btn-block mt-4" value="변경">
		</form>
	</div>
</div>
<script src="/library/sha256.min.js"></script>
<script>
	isPasswdValid = false;
	isPasswdConfirmed = false;

	document.getElementById('passwd-input').onfocus = function() {
		document.getElementById('passwd-confirm-input').value = "";
		document.getElementById('passwd-confirm-error-msg').innerHTML = "";
		document.getElementById('passwd-confirm-form-group').className = "form-group";
	}

	document.getElementById('passwd-input').onkeyup = function() {
		var passwdVal = document.getElementById('passwd-input').value;

		if(passwdVal.length<8||passwdVal.length>20||!passwdVal.match('[0-9]')||!passwdVal.match('[a-z]')||!passwdVal.match('[A-Z]')) {
			document.getElementById('passwd-form-group').className = "form-group";
			document.getElementById('passwd-error-msg').className = "text-muted form-text float-xs-right";
			isPasswdValid = false;
		} else {
			document.getElementById('passwd-form-group').className = "form-group has-success";
			document.getElementById('passwd-error-msg').className = "form-text form-control-feedback float-xs-right";
			isPasswdValid = true;
		}
	}

	document.getElementById('passwd-input').onchange = function() {
		if(!isPasswdValid) {
			document.getElementById('passwd-form-group').className = "form-group has-warning";
			document.getElementById('passwd-error-msg').className = "form-text form-control-feedback float-xs-right";
		}
	}

	document.getElementById('passwd-confirm-input').onchange = function() {
		if(!isPasswdValid){return;}

		var passwdVal = document.getElementById('passwd-input').value;
		var confirmVal = document.getElementById('passwd-confirm-input').value;

		if(passwdVal!==confirmVal) {
			document.getElementById('passwd-confirm-error-msg').innerHTML = "비밀번호가 일치하지 않습니다.";
			document.getElementById('passwd-confirm-form-group').className = "form-group has-danger";
			isPasswdConfirmed = false;
		} else {
			document.getElementById('passwd-confirm-error-msg').innerHTML = "";
			document.getElementById('passwd-confirm-form-group').className = "form-group has-success";
			isPasswdConfirmed = true;
		}
	}

	document.getElementById('password-form').onsubmit = function() {
		if(!isPasswdValid) {
			document.getElementById('passwd-form-group').className = "form-group has-danger";
			document.getElementById('passwd-error-msg').className = "form-text form-control-feedback float-xs-right";
			document.getElementById('passwd-input').focus();
			createAlert("새 비밀번호를 확인해 주세요.");
			return false;

		} else if(!isPasswdConfirmed) {
			document.getElementById('passwd-confirm-form-group').className = "form-group has-danger";
			document.getElementById('passwd-confirm-input').focus();
			createAlert("비밀번호를 확인해 주세요.");
			return false;

		}

		document.getElementById('passwd-encrypt-input').value = sha256(document.getElementById('passwd-input').value);
	}
</script>
