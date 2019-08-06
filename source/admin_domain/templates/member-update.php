<?php

	if( isset( $_POST["UserID"] ) ) {
		$user_name = $_POST["UserName"];
		$user_username = $_POST["UserUsername"];
		$user_school = $_POST["UserSchool"];
		$user_grade = $_POST["UserGrade"];
		$user_major = $_POST["UserMajor"];
		$user_phone_no = $_POST["UserPhoneNo"];
		$user_email = $_POST["UserEmail"];
		$user_birth = $_POST["UserBirth"];
		$user_kakao_id = $_POST["UserKakaoID"];

		include $_SERVER["DOCUMENT_ROOT"].'/classes/UserDBManager.php';
		$user_db = UserDBManager::createUserDBManager();
	
		if( $user_db !== UserDBManager::$CONNECT_ERROR ) {
			if( $user_db->update_fulluser_byid( $_POST["UserID"], $user_name, $user_username, $user_school, $user_grade, $user_major,
												$user_phone_no, $user_email, $user_birth, $user_kakao_id ) == UserDBManager::$UPDATE_ERROR ) {
				echo "<script>alert('회원정보 수정 중 오류가 발생했습니다.');</script>";
				echo "<script>location.href = '/?page=members'</script>";
			}
		} else {
			echo "<script>alert('데이터베이스 연결에 오류가 발생했습니다.');</script>";
			echo "<script>location.href = '/?page=members'</script>";
		}

		echo "<script>alert('회원정보가 성공적으로 수정되었습니다.');</script>";
		echo "<script>location.href = '/?page=members&UserName=$user_name&UserUsername=$user_username"
					. "&UserSchool=$user_school&UserGrade=$user_grade&UserMajor=$user_major&UserPhoneNo=$user_phone_no"
					. "&UserEmail=$user_email&UserBirth=$user_birth&UserKakaoID=$user_kakao_id';</script>";
		
	} else {
		echo "<script>location.href = '/?page=members'</script>";
	}
	
?>
