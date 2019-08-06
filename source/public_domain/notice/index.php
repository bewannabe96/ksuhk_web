<?php

	$env_page = "page_notice";
	$env_section = isset( $_GET["section"] ) ? $_GET["section"] : "news";

	include $_SERVER["DOCUMENT_ROOT"].'/config.php';
	CHECK_LOGIN_SESSION();

	$row_limit = 10;
	$page = isset( $_GET["page"] ) ? $_GET["page"] : 1;

	switch( $env_section ) {
		case "recruit" :
			$env_friendly_section = "채용";
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
					<a href="/notice/?section=recruit" class="list-group-item list-group-item-action <?php if($env_section=='recruit'){ ?>active<?php } ?>">채용</a>
					<a href="/notice/?section=lecture" class="list-group-item list-group-item-action <?php if($env_section=='lecture'){ ?>active<?php } ?>">강연</a>
				</div>
			</div>
		</div>
		<div class="col-lg-9 mt-0 mt-sm-3 mt-lg-0 p-0 p-sm-3">
			<ol class="breadcrumb mb-0 mb-sm-3">
				<li class="breadcrumb-item active">알림게시판</li>
				<li class="breadcrumb-item active"><?=$env_friendly_section?></li>
			</ol>
<?php
		include $WEB_ROOT.'/classes/NoticeDBManager.php';
		$notice_db = NoticeDBManager::createNoticeDBManager();
	
		if( $notice_db !== NoticeDBManager::$CONNECT_ERROR ) {
			switch( $env_section ) {
				case "recruit" :
					$posts = $notice_db->select_posts_bycategory( 2, $row_limit, ($page-1)*$row_limit );
					$num_posts = $notice_db->get_num_posts_bycategory( 2 );
					break;
				case "lecture" :
					$posts = $notice_db->select_posts_bycategory( 3, $row_limit, ($page-1)*$row_limit );
					$num_posts = $notice_db->get_num_posts_bycategory( 3 );
					break;
				default :
					$posts = $notice_db->select_posts_bycategory( 1, $row_limit, ($page-1)*$row_limit );
					$num_posts = $notice_db->get_num_posts_bycategory( 1 );
			}
?>
			<div class="card">
				<div class="card-block p-0 py-sm-4 px-sm-3">
					<table class="table <?php if($posts!=FALSE) {?>table-hover<?php } ?>" style="font-size:0.8rem;">
						<thead class="thead-inverse">
							<tr>
								<th>제목</th>
								<th style="width:8rem" class="hidden-xs-down">게시일</th>
							</tr>
						</thead>
						<tbody>
<?php 
						if( !$posts ) {
							echo '<tr><td class="text-center" colspan="0">등록된 게시글이 없습니다.</td></tr>';
	
						} else {
							foreach( $posts as $row ) {
?>
								<tr onclick="location.href = '/notice/view/?section=<?=$env_section?>&postno=<?=$row["PostID"]?>'">
									<th scope="row">
										<a href="/notice/view/?section=<?=$env_section?>&postno=<?=$row["PostID"]?>"><?=$row["PostTitle"]?></a>
									</th>
									<td class="text-right text-sm-left hidden-xs-down"><?=$row["PostPostdate"]?></td>
								</tr>
<?php
							} 
						}
?>
						</tbody>
					</table>
				</div>
				<div class="card-footer">
<?php
				include $WEB_ROOT.'/templates/pagination.php';
				CREATE_PAGINATION( $num_posts, $row_limit, $page, "/notice/?section=$env_section", "page" );
?>
				</div>
			</div>
<?php
		} else {
			include $WEB_ROOT.'/templates/status.php';
			ERROR_PAGE( "서버문제로 게시글 목록을 불러올 수 없습니다." );
		}
?>
		</div>
	</div>
</div>

<!-- Footer -->
<?php include $WEB_ROOT.'/templates/footer.php'; ?>

</body>
</html>
