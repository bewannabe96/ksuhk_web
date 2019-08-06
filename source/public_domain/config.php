<?php

	session_name( "login_session" );
	session_start();

	ini_set('display_errors', 0);

	date_default_timezone_set( 'Asia/Hong_Kong' );

	$MAIN_DOMAIN = 'http://www.ksuhk.com';
	$ADMIN_DOMAIN = 'http://www.admin.ksuhk.com';
	$WEB_ROOT = $_SERVER['DOCUMENT_ROOT'];

	$SESSION_EXTENSION = 120; // in minute

	$LANG = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);

	$BAS_MAX_IMG_UPLOAD = 5;

	function CHECK_LOGIN_SESSION() {
		if( !( isset( $_SESSION["auth"] ) && $_SESSION["auth"] >= 1 ) ) {
			echo "<script>alert('로그인이 필요한 페이지입니다.');";
			echo "location.href = '/login/?direct=$_SERVER[REQUEST_URI]';</script>";
			die;
		}
	}

?>
