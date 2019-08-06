<?php

	$env_page = "page_login_logout";

        session_name( "login_session" );
	session_start();
	$_SESSION = array();

	if (isset($_COOKIE[session_name()])) {
    		setcookie(session_name(), '', time()-42000, '/');
	}

	session_destroy();
	echo "<script>document.location='/login';</script>";

?>
