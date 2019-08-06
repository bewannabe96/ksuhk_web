<?php

	$env_page_group = explode("_", $env_page)[1];

	if( isset( $_SESSION["auth"] ) && $_SESSION["auth"] >= 1 ) {
		if( $_SESSION["expire_time"] < date("Y/m/d H:i:s") ) {
			echo "<script>alert('세션이 만료되었습니다. 다시 로그인을 해주세요.');";
			echo "location.href = '/login/logout.php';</script>";
		} else {
			$_SESSION["expire_time"] = date("Y/m/d H:i:s", time() + $SESSION_EXTENSION * 60);
		}
	}

?>

<nav class="navbar navbar-toggleable navbar-inverse bg-inverse justify-content-end pr-5" style="height:2rem;font-size:0.8rem;">
	<ul class="navbar-nav mr-5 hidden-sm-down">
<?php
	if( !isset( $_SESSION["auth"] ) ) {
?>
		<li class="nav-item"><a class="nav-link <?php if($env_page_group=='signup'){ ?>active<?php } ?>" href="/signup">회원가입</a></li>
		<li class="nav-item"><a class="nav-link <?php if($env_page_group=='login'){ ?>active<?php } ?>" href="/login">로그인</a></li>
<?php
	} else if( $_SESSION["auth"] == 1 ) {
?>
		<li class="nav-item"><a class="nav-link" href="/memberinfo"><?=$_SESSION["user_name"]?> (<?=$_SESSION["user_username"]?>)</a></li>
		<li class="nav-item"><a class="nav-link" href="/login/logout.php">로그아웃</a></li>
<?php
	} else if( $_SESSION["auth"] == 99 ) {
?>
		<li class="nav-item"><a class="nav-link" href="/login/logout.php">로그아웃</a></li>
		<li class="nav-item"><a class="nav-link" href="<?=$ADMIN_DOMAIN?>">관리자페이지</a></li>
<?php
	}
?>
	</ul>
</nav>

<style>
	@media screen and (max-width: 991px) {
		.sticky-mobile-top {
			position: sticky;
			top: 0;
			z-index: 1000;
		}
	}

	.shadow-bottom {
		height: 4px;
	    background: -webkit-linear-gradient(rgba(128,128,128,1), rgba(0,0,0,0));
	    background: -o-linear-gradient(rgba(128,128,128,1), rgba(0,0,0,0));
	    background: -moz-linear-gradient(rgba(128,128,128,1), rgba(0,0,0,0));
	    background: linear-gradient(rgba(128,128,128,1), rgba(0,0,0,0));
	} 
</style>
<div class="sticky-mobile-top">
	<div class="bg-faded">
		<div class="container px-0 px-sm-3">
		<nav class="navbar navbar-toggleable-md navbar-light">
			<button class="navbar-toggler navbar-toggler-right mt-2" type="button" data-toggle="collapse" data-target="#nav-content" aria-expanded="false">
				<span class="navbar-toggler-icon"></span>
			</button>
			<a class="navbar-brand mt-md-4 mb-md-1" href="<?=$MAIN_DOMAIN?>">
				<img src="/src/title-logo.png" class="hidden-sm-down" width="270" height="90" alt="KSUHK_Logo">
				<img src="/src/title-logo.png" class="hidden-md-up" width="150" height="50" alt="KSUHK_Logo">
			</a>
			<div class="collapse navbar-collapse justify-content-lg-end align-self-lg-end" id="nav-content">
				<ul class="navbar-nav">
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle <?php if($env_page_group=='aboutKSUHK'){ ?>active<?php } ?>" href="#" data-toggle="dropdown">KSUHK 소개</a>
						<div class="dropdown-menu">
							<a class="dropdown-item" href="/about-KSUHK/">인사말</a>
							<a class="dropdown-item" href="/about-KSUHK/staff">임원진 소개</a>
						</div>
					</li>
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle <?php if($env_page_group=='notice'){ ?>active<?php } ?>" href="#" data-toggle="dropdown">알림게시판</a>
						<div class="dropdown-menu">
							<a class="dropdown-item" href="/notice/?section=news">공지사항</a>
							<a class="dropdown-item" href="/notice/?section=recruit">채용</a>
							<a class="dropdown-item" href="/notice/?section=lecture">강연</a>
						</div>
					</li>
					<li class="nav-item"><a class="nav-link <?php if($env_page_group=='event'){ ?>active<?php } ?>" href="/event">행사/이벤트</a></li>
					<li class="nav-item"><a class="nav-link <?php if($env_page_group=='freeboard'){ ?>active<?php } ?>" href="/freeboard">자유게시판</a></li>
					<li class="nav-item"><a class="nav-link <?php if($env_page_group=='buyandsell'){ ?>active<?php } ?>" href="/buy-and-sell">벼룩시장</a></li>
				</ul>
				<hr class="hidden-md-up my-2">
				<ul class="navbar-nav hidden-md-up">
		<?php
				if( !isset( $_SESSION["auth"] ) ) {
		?>
					<li class="nav-item"><a class="nav-link <?php if($env_page_group=='signup'){ ?>active<?php } ?>" href="/signup">회원가입</a></li>
					<li class="nav-item"><a class="nav-link <?php if($env_page_group=='login'){ ?>active<?php } ?>" href="/login">로그인</a></li>
		<?php
				} else if( $_SESSION["auth"] == 1 ) {
		?>
					<li class="nav-item"><a class="nav-link" href="/memberinfo"><?=$_SESSION["user_name"]?> (<?=$_SESSION["user_username"]?>)</a></li>
					<li class="nav-item"><a class="nav-link" href="/login/logout.php">로그아웃</a></li>
		<?php
				} else if( $_SESSION["auth"] == 99 ) {
		?>
					<li class="nav-item"><a class="nav-link" href="/login/logout.php">로그아웃</a></li>
					<li class="nav-item"><a class="nav-link" href="<?=$ADMIN_DOMAIN?>">관리자페이지</a></li>
		<?php
				}
		?>
				</ul>
			</div>
		</nav>
		</div>
	</div>
	<div class="shadow-bottom hidden-lg-up"></div>
</div>

<img id="main-cover" src="/src/main-cover.png" class="w-100" draggable="false">
<div class="shadow-bottom"></div>
