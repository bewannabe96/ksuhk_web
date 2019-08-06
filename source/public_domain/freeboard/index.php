<?php

	$env_page = "page_freeboard";

	include $_SERVER["DOCUMENT_ROOT"].'/config.php';
	CHECK_LOGIN_SESSION();

	$row_limit = 10;
	$page = isset( $_GET["page"] ) ? $_GET["page"] : 1;

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>KSUHK - 자유게시판</title>
	<!-- HTML Header -->
	<?php include $WEB_ROOT.'/templates/html-header.php'; ?>
</head>
<body class="bg-faded">

<!-- Navigator -->
<?php include $WEB_ROOT.'/templates/navigator.php'; ?>

<div class="container my-0 my-sm-5 p-0">
	<div class="row w-100 m-0">
		<div class="col-lg-10 offset-lg-1 p-0 p-sm-3">
			<ol class="breadcrumb mb-0 mb-sm-3">
				<li class="breadcrumb-item active">자유게시판</li>
			</ol>
<?php
		include $WEB_ROOT.'/classes/FreeboardDBManager.php';
		$freeboard_db = FreeboardDBManager::createFreeboardDBManager();
	
		if( $freeboard_db !== FreeboardDBManager::$CONNECT_ERROR ) {
			$posts = $freeboard_db->select_posts( $row_limit, ($page-1)*$row_limit );
			$num_posts = $freeboard_db->get_num_posts();
?>
			<div class="card">
				<div class="card-block p-0 py-sm-4 px-sm-3">
					<h3 class="m-3 mx-sm-0"><span class="fa fa-newspaper-o" aria-hidden="true"></span> 자유게시판</h3>
					<table class="table <?php if($posts!=FALSE) {?>table-hover<?php } ?>" style="font-size:0.8rem;">
						<thead class="thead-inverse">
							<tr>
								<th>제목</th>
								<th style="width:10rem" class="hidden-xs-down">작성자</th>
								<th style="width:8rem" class="hidden-md-down">게시일</th>
							</tr>
						</thead>
						<tbody>
<?php 
						if( !$posts ) {
							echo '<tr><td class="text-center" colspan="0">등록된 게시글이 없습니다.</td></tr>';
	
						} else {
							foreach( $posts as $row ) {
?>
								<tr onclick="location.href = '/freeboard/view/?postno=<?=$row["PostID"]?>'">
									<th scope="row">
										<a href="/freeboard/view/?postno=<?=$row["PostID"]?>"><?=$row["PostTitle"]?></a>
									</th>
									<td class="hidden-xs-down"><?=$row["UserName"]?> (<?=$row["UserUsername"]?>)</td>
									<td class="hidden-md-down"><?=$row["PostPostdate"]?></td>
								</tr>
<?php
							} 
						}
?>
						</tbody>
					</table>
				</div>
				<div class="card-footer d-flex justify-content-between">
					<a href="/freeboard/write" class="btn btn-primary btn-sm">글쓰기</a>
<?php
				include $WEB_ROOT.'/templates/pagination.php';
				CREATE_PAGINATION( $num_posts, $row_limit, $page, "/freeboard/?", "page" );
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
