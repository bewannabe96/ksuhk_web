<?php

	include 'classes/UserDBManager.php';
	$user_db = UserDBManager::createUserDBManager();

	$user_name = isset($_GET["UserName"]) ? $_GET["UserName"] : "";
	$user_username = isset($_GET["UserUsername"]) ? $_GET["UserUsername"] : "";
	$user_school = isset($_GET["UserSchool"]) ? $_GET["UserSchool"] : 0;
	$user_grade = isset($_GET["UserGrade"]) ? $_GET["UserGrade"] : 0;
	$user_major = isset($_GET["UserMajor"]) ? $_GET["UserMajor"] : 0;
	$user_phone_no = isset($_GET["UserPhoneNo"]) ? $_GET["UserPhoneNo"] : "";
	$user_email = isset($_GET["UserEmail"]) ? $_GET["UserEmail"] : "";
	$user_birth = isset($_GET["UserBirth"]) ? $_GET["UserBirth"] : "";
	$user_kakao_id = isset($_GET["UserKakaoID"]) ? $_GET["UserKakaoID"] : "";
	$user_acceptance = isset($_GET["UserAcceptance"]) ? $_GET["UserAcceptance"] : 0;

	$row_limit = 20;
	$mpage = isset( $_GET["mpage"] ) ? $_GET["mpage"] : 1;

	if( $user_db !== UserDBManager::$CONNECT_ERROR ) {
		$search_result = $user_db->search_users( $user_name, $user_username, $user_school, $user_grade,
							$user_major, $user_phone_no, $user_email, $user_birth,
							$user_kakao_id, $user_acceptance, $row_limit, ($mpage-1)*$row_limit );
		$num_users = $search_result[0];
		$users_array = $search_result[1];
	} else {
		echo '<script>alert( "데이터베이스를 조회할 수 없습니다." );</script>';
	}

?>

<div id="acceptModal" class="modal fade">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">회원정보 수정</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span class="fa fa-close" aria-hidden="true"></span>
				</button>
			</div>
			<div class="modal-body">
				<form id="memberinfo-form" method="post" action="./?page=member-update">
					<input id="modal-id-input" name="UserID" hidden>
					<div id="name-form-group" class="row form-group">
						<label class="col-sm-3 col-form-label text-sm-right">이름</label>
						<div class="col-sm-9">
							<input type="text" id="modal-name-input" class="form-control" name="UserName" placeholder="이름" autocomplete="off">
						</div>
					</div>
					<div id="username-form-group" class="row form-group">
						<label class="col-sm-3 col-form-label text-sm-right">아이디</label>
						<div class="col-sm-9">
							<input type="text" id="modal-username-input" class="form-control" name="UserUsername" placeholder="아이디" readonly>
						</div>
					</div>
					<div id="school-form-group" class="row form-group">
						<label class="col-sm-3 col-form-label text-sm-right">학교</label>
						<div class="col-sm-9">
							<select id="modal-school-select" class="form-control form-control-danger" name="UserSchool">
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
							<select id="modal-grade-select" class="form-control form-control-danger" name="UserGrade">
								<option value="1">1 학년</option>
								<option value="2">2 학년</option>
								<option value="3">3 학년</option>
								<option value="4">4 학년</option>
							</select>
						</div> </div>
					<div id="major-form-group" class="row form-group">
						<label class="col-sm-3 col-form-label text-sm-right">전공</label>
						<div class="col-sm-9">
							<select id="modal-major-select" class="form-control form-control-danger" name="UserMajor">
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
							<input type="text" id="modal-phone-no-input" class="form-control" name="UserPhoneNo" placeholder="전화번호" autocomplete="off">
						</div>
					</div>
					<div id="email-form-group" class="row form-group">
						<label class="col-sm-3 col-form-label text-sm-right">이메일</label>
						<div class="col-sm-9">
							<input type="text" id="modal-email-input" class="form-control" name="UserEmail" placeholder="이메일" autocomplete="off">
						</div>
					</div>
					<div id="birth-form-group" class="row form-group">
						<label class="col-sm-3 col-form-label text-sm-right">생년월일</label>
						<div class="col-sm-9">
							<input type="date" id="modal-birth-input" class="form-control" name="UserBirth" autocomplete="off">
						</div>
					</div>
					<div class="row form-group">
						<label class="col-sm-3 col-form-label text-sm-right">카카오톡</label>
						<div class="col-sm-9">
							<input type="text" id="modal-kakao-id-input" class="form-control" name="UserKakaoID" placeholder="카카오톡 아이디" autocomplete="off">
						</div>
					</div>
					<button type="submit" name="submit" class="btn btn-primary btn-block mt-4">수정</button>
				</form>
			</div>
			<div class="modal-footer">
				<button id="accept-btn" type="button" class="btn btn-success">승인</button>
				<button id="decline-btn" type="button" class="btn btn-warning">거부</button>
				<button id="delete-btn" type="button" class="btn btn-danger">삭제</button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal">취소</button>
			</div>
		</div>
	</div>
</div>
<script>
	function user_accept(user_id, user_name, user_username, user_school, user_grade, user_major, user_phone_no,
							user_email, user_birth, user_kakao_id, user_acceptance ) {
		var change = '';
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function() {
			if (this.readyState == 4) {
				if (this.status == 200) {
					switch(this.responseText) {
						case '-1': case '-2': case '-3':
							alert('회원상태를 변경하는 중 요류가 발생했습니다.');
							break;
						case '1':
							document.getElementById('user-row-' + user_id).className = change;
							
					}
				} else {
					alert('회원상태를 변경하는 중 요류가 발생했습니다.');
				}
			}
			$('#acceptModal .close').click();
		};

		document.getElementById('accept-btn').onclick = function() {
			change = 'table-success';
			xhttp.open("POST", "/templates/member-accept.php", true);
			xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xhttp.send('UserID=' + user_id + '&accept=2');
		};
		document.getElementById('decline-btn').onclick = function() {
			change = 'table-danger';
			xhttp.open("POST", "/templates/member-accept.php", true);
			xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xhttp.send('UserID=' + user_id + '&accept=3');
		};
		document.getElementById('delete-btn').onclick = function() {
			if( confirm("정말 삭제하시겠습니까?") ) {
				xhttp.open("POST", "/templates/member-accept.php", true);
				xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				xhttp.send('UserID=' + user_id + '&accept=0');
				location.reload();
			}
		};

		document.getElementById('modal-id-input').value = user_id;
		document.getElementById('modal-name-input').value = user_name;
		document.getElementById('modal-username-input').value = user_username;
		document.getElementById('modal-school-select').value = user_school;
		document.getElementById('modal-grade-select').value = user_grade;
		document.getElementById('modal-major-select').value = user_major;
		document.getElementById('modal-phone-no-input').value = user_phone_no;
		document.getElementById('modal-email-input').value = user_email;
		document.getElementById('modal-birth-input').value = user_birth;
		document.getElementById('modal-kakao-id-input').value = user_kakao_id;

		$('#acceptModal').modal();
	}
</script>

<div class="row">
	<div class="col-12">
		<form id="search-form" class="form-inline" method="get">
			<input name="page" value="members" hidden>
			<div id="name-form-group" class="col-3 my-1 form-group">
				<label class="col-4 col-form-label col-form-label-sm">이름</label>
				<input type="text" class="col-8 form-control form-control-sm" id="name-input" name="UserName" placeholder="이름" value="<?=$user_name?>" autocomplete="off">
			</div>
			<div id="username-form-group" class="col-3 my-1 form-group">
				<label class="col-4 col-form-label col-form-label-sm">아이디</label>
				<input type="text" class="col-8 form-control form-control-sm" id="username-input" name="UserUsername" placeholder="아이디" value="<?=$user_username?>" autocomplete="off">
			</div>
			<div id="school-form-group" class="col-3 my-1 form-group">
				<label class="col-4 col-form-label col-form-label-sm">학교</label>
				<select id="school-select" class="col-8 form-control form-control-sm" name="UserSchool">
					<option value="0" selected>학교</option>
					<option value="1">HKUST / 과기대</option>
					<option value="2">HKU / 홍콩대</option>
					<option value="3">CUHK / 중문대</option>
					<option value="4">POLYU / 이공대</option>
					<option value="5">CITYU / 시립대</option>
				</select>
			</div>
			<div id="grade-form-group" class="col-3 my-1 form-group">
				<label class="col-4 col-form-label col-form-label-sm">학년</label>
				<select id="grade-select" class="col-8 form-control form-control-sm" name="UserGrade">
					<option value="0" selected>학년</option>
					<option value="1">1 학년</option>
					<option value="2">2 학년</option>
					<option value="3">3 학년</option>
					<option value="4">4 학년</option>
				</select>
			</div>
			<div id="major-form-group" class="col-3 my-1 form-group">
				<label class="col-4 col-form-label col-form-label-sm">전공</label>
				<select id="major-select" class="col-8 form-control form-control-sm" name="UserMajor">
					<option value="0" selected>전공</option>
					<option value="1">경영대학</option>
					<option value="2">사회과학대학</option>
					<option value="3">공학대학</option>
					<option value="4">인문예술대학</option>
					<option value="5">과학대학</option>
					<option value="6">기타</option>
				</select>
			</div>
			<div id="phone-no-form-group" class="col-3 my-1 form-group">
				<label class="col-4 col-form-label col-form-label-sm">연락처</label>
				<input type="text" class="col-8 form-control form-control-sm" id="phone-no-input" name="UserPhoneNo" placeholder="전화번호" value="<?=$user_phone_no?>" autocomplete="off">
			</div>
			<div id="email-form-group" class="col-3 my-1 form-group">
				<label class="col-4 col-form-label col-form-label-sm">이메일</label>
				<input type="text" class="col-8 form-control form-control-sm" id="email-input" name="UserEmail" placeholder="이메일" value="<?=$user_email?>" autocomplete="off">
			</div>
			<div id="birth-form-group" class="col-3 my-1 form-group">
				<label class="col-4 col-form-label col-form-label-sm">생년월일</label>
				<input type="date" class="col-8 form-control form-control-sm" id="birth-input" name="UserBirth" value="<?=$user_birth?>" autocomplete="off">
			</div>
			<div class="col-3 my-1 form-group">
				<label class="col-4 col-form-label col-form-label-sm">카카오톡</label>
				<input type="text" class="col-8 form-control form-control-sm" id="kakao-id-input" name="UserKakaoID" placeholder="카카오톡 아이디" value="<?=$user_kakao_id?>" autocomplete="off">
			</div>
			<div class="col-3 my-1 form-group">
				<label class="col-4 col-form-label col-form-label-sm">승인</label>
				<select id="accept-select" class="col-8 form-control form-control-sm" name="UserAcceptance">
					<option value="0" selected>승인여부</option>
					<option value="1">대기</option>
					<option value="2">완료</option>
					<option value="3">거부</option>
				</select>
			</div>
			<div class="col-12 my-1 text-right">
			<span class="mt-3 mb-1 text-right text-muted">
				<span class="fa fa-lightbulb-o" aria-hidden="true"></span> 회원을 클릭해서 수정하세요.
				<span class="text-success"><span class="fa fa-circle ml-2" aria-hidden="true"></span> 완료</span>
				<span class="text-warning"><span class="fa fa-circle ml-1" aria-hidden="true"></span> 대기</span>
				<span class="text-danger"><span class="fa fa-circle ml-1" aria-hidden="true"></span> 거부</span>
			</span>
			<div class="btn-group ml-3">
				<button type="button" id="reset-btn" class="btn btn-outline-primary">초기화</button>
				<button type="submit" class="btn btn-outline-primary btn-block">검색</button>
			</div>
			</div>
		</form>
		<script>
			document.getElementById('school-select').value = <?=$user_school?>;
			document.getElementById('grade-select').value = <?=$user_grade?>;
			document.getElementById('major-select').value = <?=$user_major?>;
			document.getElementById('accept-select').value = <?=$user_acceptance?>;
			
			document.getElementById('reset-btn').onclick = function() {
				location.href = "./?page=members";
			}
		</script>
		<hr class="my-3">
	</div>
	<div class="col-12">
		<table class="table table-hover" width="100%" style="font-size: 0.7rem;">
			<thead class="thead-inverse">
				<tr>
					<th style="width:4rem">이름</th>
					<th style="width:8rem">아이디</th>
					<th class="text-center" style="width:3rem">학교</th>
					<th class="text-center" style="width:3rem">학년</th>
					<th class="text-center" style="width:3rem">전공</th>
					<th style="width:5.8rem">연락처</th>
					<th>이메일</th>
					<th style="width:6.5rem">생년월일</th>
					<th style="width:7rem">카카오톡</th>
					<th style="width:6.5rem">가입일</th>
				</tr>
			</thead>
			<tbody>
<?php
			if( !$users_array ) {
				echo '<tr><td colspan="10" class="text-center">검색결과가 없습니다.</td></tr>';
	
			} else {
				foreach( $users_array as $row ) {
					switch( $row["UserMajor"] ) {
						case 1:
							$major = "fa-money";	
							break;
						case 2:
							$major = "fa-gavel";	
							break;
						case 3:
							$major = "fa-code";	
							break;
						case 4:
							$major = "fa-paint-brush";	
							break;
						case 5:
							$major = "fa-flask";	
							break;
						default:
							$major = "fa-ellipsis-h";
					}

					echo '<tr id="user-row-' . $row["UserID"] . '" class="';
					switch( $row["UserAcceptance"] ) {
						case 2:
							echo "table-success";
							break;
						case 3:
							echo "table-danger";
							break;
						default:
							echo "table-warning";
					}
					echo '" onclick="user_accept(' . $row["UserID"] . ', \''
							. $row["UserName"] . '\', \''
							. $row["UserUsername"] . '\', '
							. $row["UserSchool"] . ', '
							. $row["UserGrade"] . ', '
							. $row["UserMajor"] . ', \''
							. $row["UserPhoneNo"] . '\', \''
							. $row["UserEmail"] . '\', \''
							. $row["UserBirth"] . '\', \''
							. $row["UserKakaoID"] . '\', '
							. $row["UserAcceptance"] . ');">';
?>
					<th scope="row"><?=$row["UserName"]?></th>
					<td><?=$row["UserUsername"]?></td>
					<td class="text-center"><img src="/src/school-logo/<?=$row["UserSchool"]?>.png" style="width:1.2rem;"></td>
					<td class="text-center"><?=$row["UserGrade"]?></td>
					<td class="text-center"><span class="fa <?=$major?>" aria-hidden="true"></span></td>
					<td><?=$row["UserPhoneNo"]?></td>
					<td><?=$row["UserEmail"]?></td>
					<td><?=$row["UserBirth"]?></td>
					<td><?=$row["UserKakaoID"]?></td>
					<td><?=$row["UserRegister"]?></td>
				</tr>
<?php
				}
			}
?>
			</tbody>
		</table>
<?php
		include 'templates/comp/pagination.php';
		CREATE_PAGINATION( $num_users, $row_limit, $mpage, "/?page=members&UserName=$user_name&UserUsername=$user_username"
							. "&UserSchool=$user_school&UserGrade=$user_grade&UserMajor=$user_major&UserPhoneNo=$user_phone_no"
							. "&UserEmail=$user_email&UserBirth=$user_birth&UserKakaoID=$user_kakao_id&UserAcceptance=$user_acceptance", "mpage" );
?>
	</div>
</div>
