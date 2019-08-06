<?php

	$env_page = "page_signup";

	include $_SERVER["DOCUMENT_ROOT"].'/config.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>KSUHK - 회원가입</title>
	<!-- HTML Header -->
	<?php include $WEB_ROOT.'/templates/html-header.php'; ?>
</head>
<body class="bg-faded">

<!-- Navigator -->
<?php include $WEB_ROOT.'/templates/navigator.php'; ?>

<div class="container mb-0 my-0 my-lg-5 mb-sm-3 p-0">
<?php
	include $WEB_ROOT.'/templates/popup-message.php';
	include $WEB_ROOT.'/templates/signupform.php';

	/*
	if( $_POST["resultCode"] == 1 )
		include $WEB_ROOT.'/templates/signupform.php';

	else if( isset( $_POST["terms"][0] ) && isset( $_POST["terms"][1] ) )
		include $WEB_ROOT.'/templates/signupform.php';

	else
		include $WEB_ROOT.'/templates/terms.php';
	*/
?>
</div>

<!-- Footer -->
<?php include $WEB_ROOT.'/templates/footer.php'; ?>

</body>
</html>
