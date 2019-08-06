<?php

	session_name( "admin_session" );
	session_start();

	ini_set('display_errors', 1);

	date_default_timezone_set( 'Asia/Hong_Kong' );

	$MAIN_DOMAIN = 'http://localhost';
	$ADMIN_DOMAIN = 'http://localhost';

	$SESSION_EXTENSION = 30; // in minute

	$page = isset( $_GET["page"] ) ? $_GET["page"] : "members";

?>

<!DOCTYPE html>
<html lang="en">
<!-- KSUHK Website ver.1.4 -->
<head>
	<title>KSUHK - 관리자 페이지</title>
	<!-- HTML Header -->
	<meta charset="utf-8">
	<meta name="viewport" initial-scale=1, shrink-to-fit="yes">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	
	<link rel="shortcut icon" type="image/x-icon" href="/src/logo.ico" />
	
	<link rel="stylesheet" href="/library/bootstrap/css/bootstrap.css">
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js" inxtegrity="sha384-3ceskX3iaEnIogmQchP8opvBy3Mi7Ce34nWjpBIwVTHfGYWQS9jwHDVRnpKKHJg7" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.3.7/js/tether.min.js" integrity="sha384-XTs3FgkjiBgo8qjEjBk0tGmf3wPrWtA6coPfQDfFEY8AnYJwjalXCiosYRBIBZX8" crossorigin="anonymous"></script>
	<script src="/library/bootstrap/js/bootstrap.min.js"></script>
	
	<link rel="stylesheet" href="/library/font-awesome-4.7.0/css/font-awesome.min.css">
	
</head>
<body class="bg-faded">
<?php

	if( !isset( $_SESSION["auth"] ) || $_SESSION["auth"] != 100 ) {
		include 'templates/login.php';
	} else {
		if( $_SESSION["auth"] === 100 ) {
			//echo $_SESSION["expire_time"];
			if( $_SESSION["expire_time"] < date("Y/m/d H:i:s") ) {
				echo "<script>alert('세션이 만료되었습니다. 다시 로그인을 해주세요.');";
				echo "location.href = '/?page=logout';</script>";
			} else {
				$_SESSION["expire_time"] = date("Y/m/d H:i:s", time() + $SESSION_EXTENSION * 60);
			}
		}
?>

		<nav class="navbar navbar-full navbar-toggleable navbar-light bg-faded py-3">
				<a class="navbar-brand" href="<?=$ADMIN_DOMAIN?>">
					<img src="/src/title-logo.png" width="270" height="90" alt="KSUHK_Logo">
				</a>
				<div class="w-100 d-flex justify-content-end align-self-start">
					<ul class="navbar-nav">
						<li class="nav-item"><a class="nav-link" href="/?page=logout">로그아웃</a></li>
					</ul>
				</div>
		</nav>

		<div class="container-fluid">
			<div class="card">
  				<div class="card-header">	
					<ul class="nav nav-tabs card-header-tabs float-left">
						<li class="nav-item">
							<a class="nav-link <?php if($page==='members'){ ?>active<?php } ?>" href="/?page=members">
								<span class="fa fa-users" aria-hidden="true"></span> 회원관리
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link <?php if($page==='staffs'){ ?>active<?php } ?>" href="/?page=staffs">
								<span class="fa fa-id-card-o" aria-hidden="true"></span> 임원진관리
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link <?php if($page==='notice'){ ?>active<?php } ?>" href="/?page=notice">
								<span class="fa fa-newspaper-o" aria-hidden="true"></span> 알림게시판
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link <?php if($page==='event'){ ?>active<?php } ?>" href="/?page=event">
								<span class="fa fa-birthday-cake" aria-hidden="true"></span> 행사/이벤트
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link <?php if($page==='freeboard'){ ?>active<?php } ?>" href="/?page=freeboard">
								<span class="fa fa-newspaper-o" aria-hidden="true"></span> 자유게시판
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link <?php if($page==='buy-and-sell'){ ?>active<?php } ?>" href="/?page=buy-and-sell">
								<span class="fa fa-shopping-basket" aria-hidden="true"></span> 벼룩시장
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link <?php if($page==='sponsor'){ ?>active<?php } ?>" href="/?page=sponsor">
								<span class="fa fa-handshake-o" aria-hidden="true"></span> 스폰서
							</a>
						</li>
						<!--
						<li class="nav-item">
							<a class="nav-link <?php if($page==='stats'){ ?>active<?php } ?>" href="/?page=stats">
								<span class="fa fa-pie-chart" aria-hidden="true"></span> 통계
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link <?php if($page==='settings'){ ?>active<?php } ?>" href="/?page=settings">
								<span class="fa fa-cog" aria-hidden="true"></span> 설정
							</a>
						</li>
						-->
					</ul>
				</div>
				<div class="card-block">
<?php
				switch( $page ) {
					case "members":
						include 'templates/members.php';
						break;

					case "member-update":
						include 'templates/member-update.php';
						break;

					case "staffs":
						include 'templates/staffs.php';
						break;

					case "notice":
						include 'templates/notice.php';
						break;

					case "event":
						include 'templates/event.php';
						break;

					case "freeboard":
						include 'templates/freeboard.php';
						break;

					case "buy-and-sell":
						include 'templates/buy-and-sell.php';
						break;

					case "sponsor":
						include 'templates/sponsor.php';
						break;

					default:
						echo "<script>document.location='$ADMIN_DOMAIN';</script>";
				}
?>
				</div>
			</div>
			<div class="row my-4 justify-content-center text-muted">
				<p><span class="fa fa-copyright" aria-hidden="true"></span> 2017 KSUHK</p>
			</div>
		</div>
<?php
	}
?>
</body>
</html>
