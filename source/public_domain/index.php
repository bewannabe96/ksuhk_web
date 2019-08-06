<?php

	$env_page = "page_main";

	include $_SERVER["DOCUMENT_ROOT"].'/config.php';

	include $WEB_ROOT.'/classes/NoticeDBManager.php';
	$notice_db = NoticeDBManager::createNoticeDBManager();

	include 'classes/EventDBManager.php';
	$event_db = EventDBManager::createEventDBManager();

	include 'classes/FreeboardDBManager.php';
	$freeboard_db = FreeboardDBManager::createFreeboardDBManager();

	include 'classes/BuyandsellDBManager.php';
	$buyandsell_db = BuyandsellDBManager::createBuyandsellDBManager();

	include 'classes/SponsorDBManager.php';
	$sponsor_db = SponsorDBManager::createSponsorDBManager();

	if( $notice_db !== NoticeDBManager::$CONNECT_ERROR ) {
		$news_array = $notice_db->select_posts_bycategory( 1, 5, 0 );
		$recruit_array = $notice_db->select_posts_bycategory( 2, 5, 0 );
		$lecture_array = $notice_db->select_posts_bycategory( 3, 5, 0 );
		$event_array = $event_db->select_events_bystatus( 0, 5, 0 );
		$free_array = $freeboard_db->select_posts( 5, 0 );
		$bas_array = $buyandsell_db->select_bases( 8, 0 );
		$sponsors_array = $sponsor_db->select_all_sponsors();

	} else {
		echo '<script>alert( "페이지를 표시하는 중 문제가 발생했습니다." );</script>';
	}

?>

<!DOCTYPE html>
<html lang="en">
<!-- KSUHK Website ver.1.3 -->
<head>
	<title>KSUHK - 홍콩 한인유학생 총학생회</title>
	<!-- HTML Header -->
	<?php include $WEB_ROOT.'/templates/html-header.php'; ?>
</head>
<body class="bg-faded">

<!-- Navigator -->
<?php include $WEB_ROOT.'/templates/navigator.php'; ?>

<div class="container mb-0 my-0 my-lg-5 mb-sm-3">
	<div class="row">
		<div class="col-lg-11 m-0">
			<div class="row">
				<div class="col-12 col-lg-8 mt-0 mt-sm-3 mt-lg-0 px-0 px-lg-3 d-flex flex-column justify-content-between">
					<div class="card">
						<div class="card-header">공지사항</div>
						<div class="card-block p-0 p-sm-2">
							<table class="table table-sm <?php if($news_array!=FALSE) {?>table-hover<?php } ?>">
								<tbody>
		<?php 
								if( !$news_array ) {
									echo '<tr><td class="text-center" colspan="0">등록된 게시글이 없습니다.</td></tr>';

								} else {
									foreach( $news_array as $row ) {
		?>
										<tr onclick="location.href = '/notice/view/?section=news&postno=<?=$row["PostID"]?>'">
											<th scope="row">
												<a href="/notice/view/?section=news&postno=<?=$row["PostID"]?>"><?=$row["PostTitle"]?></a>
											</th>
											<td class="text-right" style="width:7rem;"><?=$row["PostPostdate"]?></td>
										</tr>
		<?php
									} 
								}
		?>
								</tbody>
							</table>
						</div>
						<div class="card-footer text-right">
							<a href="/notice/?section=news" class="btn btn-outline-primary btn-sm">더보기</a>
						</div>
					</div>
					<div class="card mt-0 mt-sm-3">
						<div class="card-header">채용설명회</div>
						<div class="card-block p-0 p-sm-2">
							<table class="table table-sm <?php if($recruit_array!=FALSE) {?>table-hover<?php } ?>">
								<tbody>
		<?php 
								if( !$recruit_array ) {
									echo '<tr><td class="text-center">등록된 게시글이 없습니다.</td></tr>';

								} else {
									foreach( $recruit_array as $row ) {
		?>
										<tr onclick="location.href = '/notice/view/?section=recruit&postno=<?=$row["PostID"]?>'">
											<th scope="row">
												<a href="/notice/view/?section=recruit&postno=<?=$row["PostID"]?>"><?=$row["PostTitle"]?></a>
											</th>
											<td class="text-right" style="width:7rem;"><?=$row["PostPostdate"]?></td>
										</tr>
		<?php
									} 
								}
		?>
								</tbody>
							</table>
						</div>
						<div class="card-footer text-right">
							<a href="/notice/?section=recruit" class="btn btn-outline-primary btn-sm">더보기</a>
						</div>
					</div>
					<div class="card mt-0 mt-sm-3">
						<div class="card-header">강연</div>
						<div class="card-block p-0 p-sm-2">
							<table class="table table-sm <?php if($lecture_array!=FALSE) {?>table-hover<?php } ?>">
								<tbody>
		<?php 
								if( !$lecture_array ) {
									echo '<tr><td class="text-center">등록된 게시글이 없습니다.</td></tr>';

								} else {
									foreach( $lecture_array as $row ) {
		?>
										<tr onclick="location.href = '/notice/view/?section=lecture&postno=<?=$row["PostID"]?>'">
											<th scope="row">
												<a href="/notice/view/?section=lecture&postno=<?=$row["PostID"]?>"><?=$row["PostTitle"]?></a>
											</th>
											<td class="text-right" style="width:7rem;"><?=$row["PostPostdate"]?></td>
										</tr>
		<?php
									} 
								}
		?>
								</tbody>
							</table>
						</div>
						<div class="card-footer text-right">
							<a href="/notice/?section=lecture" class="btn btn-outline-primary btn-sm">더보기</a>
						</div>
					</div>
				</div>
				<div class="col-12 col-lg-4 mt-0 mt-sm-3 mt-lg-0 px-0 px-lg-3">
					<div class="card h-100">
						<div class="card-header">벼룩시장</div>
						<div class="card-block p-0">
							<table class="table table-sm m-0 <?php if($bas_array!=FALSE) {?>table-hover<?php } ?>">
								<tbody>
		<?php 
								if( !$bas_array ) {
									echo '<tr><td class="text-center">등록된 게시글이 없습니다.</td></tr>';

								} else {
									foreach( $bas_array as $row ) {
										$row["BASPrice"] = number_format( $row["BASPrice"] );
		?>
										<tr onclick="location.href = '/buy-and-sell/view/?postno=<?=$row["BASID"]?>'">
											<th scope="row" class="d-flex p-0">
												<div class="text-center col-3 bg-faded p-1" style="height:6rem;">
													<div class="d-inline-flex h-100 align-items-center">
		<?php
													if( isset( $row["BASTopImage"] ) ) {
														echo "<img class='mh-100 mw-100' src='/src/buy-and-sell-image.php?basid=$row[BASID]&hash=$row[BASTopImage]'>";
													} else {
														echo "<img class='mh-100 mw-100' src='/src/placeholder.png'>";
													}
		?>
													</div>
												</div>
												<div class="col-9 p-3 d-flex flex-column justify-content-between">
													<div>
														<p class="overflowcut">
		<?php
																if( $row["BASStatus"] == 1 )
																	echo "<span class='text-success'>[거래중]</span>";
																else
																	echo "<span class='text-warning'>[거래완료]</span>";
		?>
															<?=$row["BASTitle"]?>		
														</p>
													</div>
													<div class="d-flex justify-content-between">
															<span class="text-muted">
																<span class="fa fa-eye" aria-hidden="true"></span> <?=$row["BASView"]?>
															</span>
															<span class="text-primary">HK$ <?=$row["BASPrice"]?></span>
													</div>
												</div>
											</th>
										</tr>
		<?php
									} 
								}
		?>
								</tbody>
							</table>
						</div>
						<div class="card-footer text-right">
							<div class="btn-group">
								<a href="/buy-and-sell" class="btn btn-outline-primary btn-sm">더보기</a>
								<a href="/buy-and-sell/write" class="btn btn-outline-primary btn-sm">글쓰기</a>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row mt-lg-3 flex-row-reverse">
				<div class="col-12 col-lg-8 mt-0 mt-sm-3 mt-lg-0 px-0 px-lg-3 d-flex flex-column justify-content-between">
					<div class="card">
						<div class="card-header">행사/이벤트</div>
						<div class="card-block p-0 p-sm-2">
							<table class="table table-sm <?php if($event_array!=FALSE) {?>table-hover<?php } ?>">
								<thead class="table-inverse">
									<tr>
										<th class="text-center" style="width:3rem">상태</th>
										<th>제목</th>
										<th class="hidden-md-down" style="width:7rem">비용</th>
										<th style="width:7rem"><span class="hidden-sm-down">게시일</span></th>
									</tr>
								</thead>
								<tbody>
		<?php 
								if( !$event_array ) {
									echo '<tr><td class="text-center">등록된 게시글이 없습니다.</td></tr>';

								} else {
									foreach( $event_array as $row ) {
		?>
										<tr onclick="location.href = '/event/view/?eventno=<?=$row["EventID"]?>'">
		<?php
										if( $row["EventStatus"] == 1 )
											echo '<td class="text-center" scope="row"><span class="badge badge-success">진행중</span></td>';
										else
											echo '<td class="text-center" scope="row"><span class="badge badge-default">종료</span></td>';
		?>
											<th><a href="/event/view/?eventno=<?=$row["EventID"]?>"><?=$row["EventTitle"]?></a></th>
											<td class="hidden-md-down">
		<?php
											switch( $row["EventCurrency"] ) {
												case 1 :
													echo 'HKD<span class="fa fa-dollar"></span> ' . $row["EventCost"];
													break;
												case 2 :
													echo 'KRW<span class="fa fa-krw"></span> ' . $row["EventCost"];
													break;
												default :
													echo "무료";
											}
		?>
											</td>
											<td class="text-right text-md-left"><?=$row["EventPostdate"]?></td>
										</tr>
		<?php
									} 
								}
		?>
								</tbody>
							</table>
						</div>
						<div class="card-footer text-right">
							<a href="/event" class="btn btn-outline-primary btn-sm">더보기</a>
						</div>
					</div>
					<div class="card mt-0 mt-sm-3">
						<div class="card-header">자유게시판</div>
						<div class="card-block p-0 p-sm-2">
							<table class="table table-sm table-hover">
								<tbody>
		<?php 
								if( !$free_array ) {
									echo '<tr><td class="text-center">등록된 게시글이 없습니다.</td></tr>';

								} else {
									foreach( $free_array as $row ) {
		?>
										<tr onclick="location.href = '/freeboard/view/?postno=<?=$row["PostID"]?>'">
											<th scope="row">
												<a href="/freeboard/view/?postno=<?=$row["PostID"]?>"><?=$row["PostTitle"]?></a>
											</th>
											<td class="text-right text-md-left" style="width:11rem;">
												<?=$row["UserName"]?> (<?=$row["UserUsername"]?>)
											</td>
											<td class="text-right hidden-md-down" style="width:7rem;"><?=$row["PostPostdate"]?></td>
										</tr>
		<?php
									} 
								}
		?>
								</tbody>
							</table>
						</div>
						<div class="card-footer text-right">
							<div class="btn-group">
								<a href="/freeboard" class="btn btn-outline-primary btn-sm">더보기</a>
								<a href="/freeboard/write" class="btn btn-outline-primary btn-sm">글쓰기</a>
							</div>
						</div>
					</div>
				</div>
				<div class="col-12 col-lg-4 mt-0 mt-sm-3 mt-lg-0 px-0 px-lg-3">
					<div class="card h-100">
						<div class="card-header">후원</div>
						<div class="card-block p-0">
							<table class="table table-sm m-0">
								<tbody>
		<?php 
								if( !$sponsors_array ) {
									echo '<tr><td class="text-center">등록된 후원이 없습니다.</td></tr>';

								} else {
									foreach( $sponsors_array as $row ) {
		?>
										<tr>
											<th scope="row" class="d-flex p-0">
												<div class="text-center w-100 p-1" style="height:6rem;">
													<div class="d-inline-flex h-100 align-items-center">
														<img class="mh-100 mw-100" src="/src/image.php?hash=<?=$row["SponsorImage"]?>">
													</div>
												</div>
											</th>
										</tr>
		<?php
									} 
								}
		?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-1 m-0 mt-sm-3 mt-lg-0 p-0">
			<style>
				div.school-link {
					height: 6rem;
				}

				div.school-link:hover {
					background-color: #ebebeb;
				}
			</style>
			<div class="card">
				<div class="card-header p-1 text-center">
					바로가기
				</div>
				<div class="card-block p-0 d-flex flex-row flex-lg-column justify-content-between">
					<div class="school-link p-2" onclick="location.href='https://www.facebook.com/groups/ksa.hkust/';">
						<div class="h-75 w-100 text-center">
							<img class="mh-100 mw-100" src="/src/school-logo/hkust.png">
						</div>
						<div class="h-25 w-100 text-center">
							<h6>HKUST</h6>
						</div>
					</div>
					<div class="school-link p-2" onclick="location.href='https://www.facebook.com/groups/kascu/';">
						<div class="h-75 w-100 text-center">
							<img class="mh-100 mw-100" src="/src/school-logo/cityu.png">
						</div>
						<div class="h-25 w-100 text-center">
							<h6>CityU</h6>
						</div>
					</div>
					<div class="school-link p-2" onclick="location.href='https://www.facebook.com/polyuksa/';">
						<div class="h-75 w-100 text-center">
							<img class="mh-100 mw-100" src="/src/school-logo/polyu.png">
						</div>
						<div class="h-25 w-100 text-center">
							<h6>PolyU</h6>
						</div>
					</div>
					<div class="school-link p-2" onclick="location.href='https://www.facebook.com/cuksa.cuhk/';">
						<div class="h-75 w-100 text-center">
							<img class="mh-100 mw-100" src="/src/school-logo/cuhk.png">
						</div>
						<div class="h-25 w-100 text-center">
							<h6>CUHK</h6>
						</div>
					</div>
					<div class="school-link p-2" onclick="location.href='https://www.facebook.com/hkuksa/';">
						<div class="h-75 w-100 text-center">
							<img class="mh-100 mw-100" src="/src/school-logo/hku.png">
						</div>
						<div class="h-25 w-100 text-center">
							<h6>HKU</h6>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Footer -->
<?php include $WEB_ROOT.'/templates/footer.php'; ?>

</body>
</html>
