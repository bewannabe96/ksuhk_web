<?php

	$env_page = "page_notice_view";
	$env_section = isset( $_GET["section"] ) ? $_GET["section"] : "news";

	include $_SERVER["DOCUMENT_ROOT"].'/config.php';
	CHECK_LOGIN_SESSION();

	if( !isset( $_GET["postno"] ) )
		echo "<script>location.href = '/notice';</script>";

	switch( $env_section ) {
		case "recruit" :
			$env_friendly_section = "채용설명회";
			break;
		case "lecture" :
			$env_friendly_section = "강연";
			break;
		default :
			$env_friendly_section = "공지사항";
	}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>KSUHK - <?=$env_friendly_section?></title>
	<!-- HTML Header -->
	<?php include $WEB_ROOT.'/templates/html-header.php'; ?>
</head>
<body class="bg-faded">

<!-- Navigator -->
<?php include $WEB_ROOT.'/templates/navigator.php'; ?>

<div class="container my-0 my-sm-5 p-0">
	<div class="row w-100 m-0">
		<div class="col-lg-3 p-0 p-sm-3">
			<div class="card">
				<h5 class="card-header">알림게시판</h5>
				<div class="list-group list-group-flush">
					<a href="/notice/?section=news" class="list-group-item list-group-item-action <?php if($env_section=='news'){ ?>active<?php } ?>">공지사항</a>
					<a href="/notice/?section=recruit" class="list-group-item list-group-item-action <?php if($env_section=='recruit'){ ?>active<?php } ?>">채용설명회</a>
					<a href="/notice/?section=lecture" class="list-group-item list-group-item-action <?php if($env_section=='lecture'){ ?>active<?php } ?>">강연</a>
				</div>
			</div>
		</div>
		<div class="col-lg-9 mt-0 mt-sm-3 mt-lg-0 p-0 p-sm-3">
<?php
			include $WEB_ROOT.'/classes/NoticeDBManager.php';
			$notice_db = NoticeDBManager::createNoticeDBManager();
		
			if( $notice_db !== NoticeDBManager::$CONNECT_ERROR ) {
	
				$post = $notice_db->select_post_byid( $_GET["postno"] );
				if( !$post ) {
					echo "<script>alert('존재하지 않거나 삭제된 게시물입니다.');";
					echo "location.href = '/notice/?section=$env_section';</script>";
				} else {
					$post_content = nl2br( $post["PostContent"] );
?>
					<ol class="breadcrumb mb-0 mb-sm-3">
						<li class="breadcrumb-item">알림게시판</li>
						<li class="breadcrumb-item"><a href="/notice/?section=<?=$env_section?>"><?=$env_friendly_section?></a></li>
						<li class="breadcrumb-item active"><?=$post["PostTitle"]?></li>
					</ol>
					<div class="card">
						<div class="card-header text-right">
							<a href="/notice/?section=<?=$env_section?>" class="btn btn-secondary btn-sm">목록</a>
						</div>
						<div class="card-block py-4 px-sm-4 px-2">
							<h3 class="mb-4"><?=$post["PostTitle"]?></h3>
							<p class="mb-2" style="font-size:0.6rem;">
								<span class="badge badge-pill badge-default">게시자</span> KSUHK
							</p>
							<p style="font-size:0.6rem;">
									<span class="badge badge-pill badge-default">게시일</span> <?=$post["PostPostdate"]?>
									<span class="badge badge-pill badge-default ml-2">수정일</span> <?=$post["PostUpdate"]?>
							</p>
							<hr class="mb-4">
							<div class="text-center">
<?php
							if( isset( $post["PostImage"] ) && $post["PostImage"] != "" ) {
?>
								<img src="/src/image.php?hash=<?=$post["PostImage"]?>" class="w-100 rounded">
<?php
							}
?>
							</div>
							<p id="post-content" class="my-3 mx-1 text-justify"><?=$post_content?></p>
							<?php include $WEB_ROOT.'/templates/urlify.php'; ?>
							<script>
								document.getElementById('post-content').innerHTML =
										urlify(document.getElementById('post-content').innerHTML);
							</script>
						</div>
						<div class="card-footer">
						</div>
					</div>
				</div>
<?php
				}
			} else {
				echo "<script>alert('게시글을 불러오는 중 오류가 발생했습니다.');";
				echo "location.href = '/notice/?section=$env_section';</script>";
			}
?>
	</div>
</div>

<!-- Footer -->
<?php include $WEB_ROOT.'/templates/footer.php'; ?>

</body>
</html>
