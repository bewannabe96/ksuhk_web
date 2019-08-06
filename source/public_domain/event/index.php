<?php

	$env_page = "page_event";
	$env_status = isset( $_GET["status"] ) ? $_GET["status"] : "all";

	include $_SERVER["DOCUMENT_ROOT"].'/config.php';
	CHECK_LOGIN_SESSION();

	$row_limit = 10;
	$page = isset( $_GET["page"] ) ? $_GET["page"] : 1;

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>KSUHK - 행사 / 이벤트</title>
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
				<h5 class="card-header">행사 / 이벤트</h5>
				<div class="list-group list-group-flush">
					<a href="/event/?status=all" class="list-group-item list-group-item-action <?php if($env_status=='all'){ ?>active<?php } ?>">전체</a>
					<a href="/event/?status=ongoing" class="list-group-item list-group-item-action <?php if($env_status=='ongoing'){ ?>active<?php } ?>">진행중</a>
					<a href="/event/?status=closed" class="list-group-item list-group-item-action <?php if($env_status=='closed'){ ?>active<?php } ?>">종료</a>
				</div>
			</div>
		</div>
		<div class="col-lg-9 mt-0 mt-sm-3 mt-lg-0 p-0 p-sm-3">
			<ol class="breadcrumb mb-0 mb-sm-3">
				<li class="breadcrumb-item active">알림게시판</li>
				<li class="breadcrumb-item active">행사 · 이벤트</li>
			</ol>
<?php
		include $WEB_ROOT.'/classes/EventDBManager.php';
		$event_db = EventDBManager::createEventDBManager();
	
		if( $event_db !== EventDBManager::$CONNECT_ERROR ) {
			switch( $env_status ) {
				case "ongoing":
					$events = $event_db->select_events_bystatus( 1, $row_limit, ($page-1)*$row_limit );
					$num_events = $event_db->get_num_events_bystatus( 1 );
					break;
	
				case "closed":
					$events = $event_db->select_events_bystatus( 2, $row_limit, ($page-1)*$row_limit );
					$num_events = $event_db->get_num_events_bystatus( 2 );
					break;
	
				default:
					$events = $event_db->select_events_bystatus( 0, $row_limit, ($page-1)*$row_limit );
					$num_events = $event_db->get_num_events_bystatus( 0 );
			}
?>
			<div class="card">
				<div class="card-block p-0 py-sm-4 px-sm-3">
					<table class="table <?php if($events!=FALSE) {?>table-hover<?php } ?>" style="font-size:0.8rem;">
						<thead class="thead-inverse">
							<tr>
								<th style="width:4rem">상태</th>
								<th>제목</th>
								<th style="width:8rem" class="hidden-xs-down">게시일</th>
							</tr>
						</thead>
						<tbody>
<?php 
						if( !$events ) {
							echo '<tr><td class="text-center" colspan="0">등록된 게시글이 없습니다.</td></tr>';
	
						} else {
							foreach( $events as $row ) {
?>
								<tr onclick="location.href = '/event/view/?status=<?=$env_status?>&eventno=<?=$row["EventID"]?>'">
<?php
								if( $row["EventStatus"] == 1 )
									echo '<td scope="row"><span class="badge badge-success">진행중</span></td>';
								else
									echo '<td scope="row"><span class="badge badge-default">종료</span></td>';
?>
									<th><a href="/event/view/?status=<?=$env_status?>&eventno=<?=$row["EventID"]?>"><?=$row["EventTitle"]?></a></th>
									<td class="text-right text-sm-left hidden-xs-down"><?=$row["EventPostdate"]?></td>
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
				CREATE_PAGINATION( $num_events, $row_limit, $page, "/event/?status=$env_status", "page" );
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
