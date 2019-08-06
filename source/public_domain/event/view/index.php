<?php

	$env_page = "page_event_view";
	$env_status = isset( $_GET["status"] ) ? $_GET["status"] : "all";

	include $_SERVER["DOCUMENT_ROOT"].'/config.php';
	CHECK_LOGIN_SESSION();

	if( !isset( $_GET["eventno"] ) )
		echo "<script>location.href = '/event';</script>";

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
<?php
			include $WEB_ROOT.'/classes/EventDBManager.php';
			$event_db = EventDBManager::createEventDBManager();
		
			if( $event_db !== EventDBManager::$CONNECT_ERROR ) {
				$event = $event_db->select_event_byid( $_GET["eventno"] );

				if( !$event ) {
					echo "<script>alert('존재하지 않거나 삭제된 게시물입니다.');";
					echo "location.href = '/event/?status=$env_status';</script>";
				} else {
					$event_content = nl2br( $event["EventContent"] );
?>
					<ol class="breadcrumb mb-0 mb-sm-3">
						<li class="breadcrumb-item active">알림게시판</li>
						<li class="breadcrumb-item"><a href="/event/?status=<?=$env_status?>">행사 · 이벤트</a></li>
						<li class="breadcrumb-item active"><?=$event["EventTitle"]?></li>
					</ol>
					<div class="card">
						<div class="card-header text-right">
							<a href="/event/?status=<?=$env_status?>" class="btn btn-secondary btn-sm">목록</a>
						</div>
						<div class="card-block py-4 px-sm-4 px-2">
							<h3 class="mb-4">
<?php
							if( $event["EventStatus"] == 1 )
								echo '<td scope="row"><span class="badge badge-success">진행중</span></td>';
							else
								echo '<td scope="row"><span class="badge badge-default">종료</span></td>';
?>
								<?=$event["EventTitle"]?>
							</h3>
							<p class="mb-2" style="font-size:0.6rem;">
								<span class="badge badge-pill badge-default">게시자</span> KSUHK
							</p>
							<p style="font-size:0.6rem;">
									<span class="badge badge-pill badge-default">게시일</span> <?=$event["EventPostdate"]?>
									<span class="badge badge-pill badge-default ml-2">수정일</span> <?=$event["EventUpdate"]?>
							</p>
							<hr class="mb-4">
							<div class="text-center">
<?php
							if( isset( $event["EventImage"] ) && $event["EventImage"] != "" ) {
								echo '<img src="/src/image.php?hash=' . $event["EventImage"] . '" class="w-100 rounded">';
							}
?>
							</div>
							<p id="event-content" class="my-3 mx-1 text-justify"><?=$event_content?></p>
							<?php include $WEB_ROOT.'/templates/urlify.php'; ?>
							<script>
								document.getElementById('event-content').innerHTML =
										urlify(document.getElementById('event-content').innerHTML);
							</script>
							<hr>
							<div class="m-sm-4 m-2">
								<h5>
									<span class="badge badge-info mr-2">참가비</span>
<?php
									switch( $event["EventCurrency"] ) {
										case 1 :
											echo 'HKD<span class="fa fa-dollar"></span> ' . $event["EventCost"];
											break;
										case 2 :
											echo 'KRW<span class="fa fa-krw"></span> ' . $event["EventCost"];
											break;
										default :
											echo "무료";
									}
?>
									<span class="badge badge-info ml-4 mr-2">정원</span>
<?php
									if( $event["EventMaxInvite"] == 0 )
										echo "없음";

									else
										echo $event["EventMaxInvite"] . "명";
?>
								</h5>
								<h5 class="my-4">
									<span class="badge badge-info mr-2">일정</span>
<?php
									echo date( "Y년 n월 j일 g:i A", strtotime( $event["EventStart"] ) );
									echo " ~ ";
									echo date( "Y년 n월 j일 g:i A", strtotime( $event["EventEnd"] ) );
?>
								</h5>
<?php
								if( $event["EventAddress"] == "" ) { $event["EventAddress"] =  " 없음"; }
?>
								<h5 class="my-4">
									<span class="badge badge-info mr-2">장소</span><?=$event["EventAddress"]?>
								</h5>
<?php
								if( $event["EventLatitude"] != 0 && $event["EventLongitude"] != 0 ) {
									echo '<div id="map" class="card card-block rounded" style="height:25rem;"></div>';
								}
?>

<?php
								if( $event["EventStatus"] == 1 && $event["EventInviteEnable"] != 1 ) {
									$num_joins = $event_db->get_num_joins_byid( $_GET["eventno"] );
									$is_joined = $event_db->is_joined( $_GET["eventno"], $_SESSION["user_id"] ) == EventDBManager::$ALREADY_JOINED;
?>
								<hr>
								<div class="text-right">
									<h3 class="text-muted lead">
										<p><strong class="text-info"><?=$num_joins?></strong>명이 이벤트에 참가했습니다.</p>
<?php
									if( $event["EventInviteEnable"] == 3 ) {
?>
										<button type="button" class="btn btn-secondary disabled rounded-circle ml-3" style="width:4.5rem;height:4.5rem;">
											<span class="fa fa-close" aria-hidden="true"></span><br>마감
										</button>
<?php
									} else {
										if( $is_joined ) {
?>
											<button type="button" class="btn btn-success rounded-circle ml-3" style="width:4.5rem;height:4.5rem;" onclick="location.href = '/event/join/?eventno=<?=$event["EventID"]?>&action=out'">
												<span class="fa fa-sign-out" aria-hidden="true"></span><br>불참
											</button>
<?php
										} else if( $num_joins >= $event["EventMaxInvite"] && $event["EventMaxInvite"] != 0 ) {
?>
											<button type="button" class="btn btn-danger disabled rounded-circle ml-3" style="width:4.5rem;height:4.5rem;">
												<span class="fa fa-close" aria-hidden="true"></span><br>꽉참
											</button>
<?php
										} else {
?>
											<button type="button" class="btn btn-outline-success rounded-circle ml-3" style="width:4.5rem;height:4.5rem;" onclick="location.href = '/event/join/?eventno=<?=$event["EventID"]?>'">
												<span class="fa fa-sign-in" aria-hidden="true"></span><br>참가
											</button>
<?php
										}
									}
?>
									</h3>
								</div>
<?php
								}
?>
							</div>
						</div>
						<div class="card-footer">
						</div>
					</div>
<?php
				}
			} else {
				echo "<script>alert('게시글을 불러오는 중 오류가 발생했습니다.');";
				echo "location.href = '/event/?status=$env_status';</script>";
			}
?>
		</div>
	</div>
</div>

<?php
	if( $event["EventLatitude"] != 0 && $event["EventLongitude"] != 0 ) {
?>
	<script>
	
		var map;
		function initMap() {
			var centerLatLng = {lat: <?=$event["EventLatitude"]?>, lng: <?=$event["EventLongitude"]?>};
	
			map = new google.maps.Map(document.getElementById('map'), {
				center: centerLatLng,
				zoom: 17,
				clickableIcons: false,
				mapTypeControl: false,
				streetViewControl: false
			});
	
			marker = new google.maps.Marker({
				map: map,
				position: centerLatLng
			});
	
		}
	
	</script>
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDxxKlLaGarkklQArn8j_Ihif5z5jwZRu0&language=en&callback=initMap" async defer></script>
<?php
	}
?>

<!-- Footer -->
<?php include $WEB_ROOT.'/templates/footer.php'; ?>

</body>
</html>
