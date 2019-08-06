<?php

	$env_page = "page_buyandsell_view";

	include $_SERVER["DOCUMENT_ROOT"].'/config.php';
	CHECK_LOGIN_SESSION();

	if( !isset( $_GET["postno"] ) )
		echo "<script>location.href = '/buy-and-sell';</script>";
	
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>KSUHK - 벼룩시장</title>
	<!-- HTML Header -->
	<?php include $WEB_ROOT.'/templates/html-header.php'; ?>
</head>
<body class="bg-faded">

<div id="reportModal" class="modal fade">
	<div class="modal-dialog" role="document">
		<form class="modal-content" action="/buy-and-sell/report/?postno=<?=$_GET["postno"]?>" method="post">
			<div class="modal-header">
				<h5 class="modal-title">게시글 신고</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span class="fa fa-close" aria-hidden="true"></span>
				</button>
			</div>
			<div class="modal-body">
				<p>신고내용</p>
				<textarea class="form-control" id="report-textarea" name="BASReport" rows="10"></textarea>
				<input name="BASReportUser" value="<?=$_SESSION["user_id"]?>" hidden>
				<small class="text-muted">허위신고는 제제의 대상이 됩니다.</small>
			</div>
			<div class="modal-footer">
				<button id="report-btn" type="submit" class="btn btn-warning">신고</button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal">취소</button>
			</div>
		</form>
	</div>
</div>
<script>
	function report_modal() {
		$('#reportModal').modal();
	}
</script>

<!-- Navigator -->
<?php include $WEB_ROOT.'/templates/navigator.php'; ?>

<div class="container my-0 my-sm-5 p-0">
	<div class="row w-100 m-0">
		<div class="col-lg-8 offset-lg-2 p-0 p-sm-3">
<?php
			include $WEB_ROOT.'/classes/BuyandsellDBManager.php';
			$buyandsell_db = BuyandsellDBManager::createBuyandsellDBManager();
		
			if( $buyandsell_db !== BuyandsellDBManager::$CONNECT_ERROR ) {
				$buyandsell_db->increase_view_byid( $_GET["postno"] );
				$bas = $buyandsell_db->select_bas_byid( $_GET["postno"] );
				if( $bas["BASImages"] != 0 )
					$images = $buyandsell_db->select_images_byid( $_GET["postno"] );
				if( !$bas ) {
					echo "<script>alert('존재하지 않거나 삭제된 게시물입니다.');";
					echo "location.href = '/buy-and-sell';</script>";
				} else {
					$bas_content = nl2br( htmlspecialchars( $bas["BASContent"] ) );
					$bas["UserKakaoID"] = $bas["UserKakaoID"] == "" ? "(카카오톡 정보없음)" : $bas["UserKakaoID"];
					$bas["BASPrice"] = number_format( $bas["BASPrice"] );

					switch( $bas["UserSchool"] ) {
						case 1:
							$bas["UserSchool"] = '<img src="/src/school-logo/hkust.png" style="height:1.5rem;"/>';
							$bas["UserSchool"] .= '<p class="d-inline ml-2">홍콩과학기술대학교(HKUST)</p>';
							break;
						case 2:
							$bas["UserSchool"] = '<img src="/src/school-logo/hku.png" style="height:1.5rem;"/>';
							$bas["UserSchool"] .= '<p class="d-inline ml-2">홍콩대학교(HKU)</p>';
							break;
						case 3:
							$bas["UserSchool"] = '<img src="/src/school-logo/cuhk.png" style="height:1.5rem;" class="d-inline"/>';
							$bas["UserSchool"] .= '<p class="d-inline ml-2">홍콩중문대학교(CUHK)</p>';
							break;
						case 4:
							$bas["UserSchool"] = '<img src="/src/school-logo/polyu.png" style="height:1.5rem;"/>';
							$bas["UserSchool"] .= '<p class="d-inline ml-2">홍콩이공대학교(POLYU)</p>';
							break;
						case 5:
							$bas["UserSchool"] = '<img src="/src/school-logo/cityu.png" style="height:1.5rem;"/>';
							$bas["UserSchool"] .= '<p class="d-inline ml-2">홍콩시립대학교(CITYU)</p>';
							break;
						default:
							$bas["UserSchool"] = '(해당사항 없음)';
					}

					switch( $bas["BASType"] ) {
						case 2:
							$bas["BASType"] = "의류";
							break;
						case 3:
							$bas["BASType"] = "전자제품";
							break;
						case 4:
							$bas["BASType"] = "생활용품";
							break;
						case 5:
							$bas["BASType"] = "학용품/사무용품";
							break;
						case 6:
							$bas["BASType"] = "운동용품/악기";
							break;
						case 7:
							$bas["BASType"] = "재능나눔";
							break;
						default:
							$bas["BASType"] = "기타";
							break;
					}
?>
					<ol class="breadcrumb mb-0 mb-sm-3">
						<li class="breadcrumb-item"><a href="/buy-and-sell">벼룩시장</a></li>
						<li class="breadcrumb-item active"><?=$bas["BASTitle"]?></li>
					</ol>
					<div class="card">
						<div class="card-header d-flex justify-content-between">
							<label class="m-0">
								<span class="fa fa-eye" aria-hidden="true"></span> 조회수:
								<span class="text-muted"><?=$bas["BASView"]?></span>
							</label>
							<div class="btn-group">
								<a href="/buy-and-sell" class="btn btn-secondary btn-sm">목록</a>
<?php
							if( $_SESSION["user_username"] == $bas["UserUsername"] )
								echo "<a href='/buy-and-sell/delete/?postno=$bas[BASID]' class='btn btn-danger btn-sm'>삭제</a>";
							else if( $bas["BASReportStatus"] != 2 )
								echo "<btn class='btn btn-warning btn-sm' onclick='report_modal();'>신고</btn>";

							if( $bas["BASReportStatus"] == 2 )
								echo "<a href='#' class='btn btn-warning btn-sm disabled' role='button' aria-disabled='true'>신고됨</a>";
?>
							</div>
						</div>
						<div class="card-block py-4 px-sm-4 px-2">
							<h3 class="mb-4"><?=$bas["BASTitle"]?></h3>
							<p class="mb-2" style="font-size:0.6rem;">
								<span class="badge badge-pill badge-default">게시자</span>
								<?=$bas["UserName"]?> (<?=$bas["UserUsername"]?>)
							</p>
							<p style="font-size:0.6rem;">
									<span class="badge badge-pill badge-default">게시일</span> <?=$bas["BASPostdate"]?>
									<span class="badge badge-pill badge-default ml-2">수정일</span> <?=$bas["BASUpdate"]?>
							</p>
							<hr class="mb-4">
							<div class="d-md-flex">
								<style>
									.center-img {
										max-width: 100%;
										max-height: 100%;
									}
								</style>
								<div class="col-md-6 d-flex flex-column justify-content-between pl-md-0" style="height:31rem;">
									<div class="text-center" style="height:25rem;background-color:black;">
										<div class="d-inline-flex h-100 align-items-center">
<?php
									if( $bas["BASImages"] != 0 ) {
										echo "<img id='img-box' class='center-img' src='/src/buy-and-sell-image.php?basid=$_GET[postno]&hash=$images[0]'>";
									} else {
										echo "<img class='center-img' src='/src/placeholder.png'>";
									}
?>
										</div>
									</div>
									<div class="d-flex" style="height:4rem;border:1px solid #CCCCCC">
									<script>var images = []</script>
<?php
									for($i = 0; $i < $BAS_MAX_IMG_UPLOAD; $i++){
										if( !isset( $images[$i] ) ) {
?>
											<div class="text-center p-2" style="width:20%;">
												<div class="d-inline-flex h-100 align-items-center">
													<img class="center-img" src="/src/placeholder.png">
												</div>
											</div>
<?php
										
										} else {
											$images[$i] = "/src/buy-and-sell-image.php?basid=$_GET[postno]&hash=$images[$i]";
?>
											<div class="text-center" style="width:20%;" onclick="change_img(<?=$i?>);">
												<div class="d-inline-flex h-100 align-items-center">
													<img class="center-img" src="<?=$images[$i]?>">
												</div>
											</div>
											<script>images[<?=$i?>] = '<?=$images[$i]?>';</script>
<?php
										}
									}
?>
									</div>
								</div>
								<script>
									function change_img(idx) {
										document.getElementById('img-box').src = images[idx];
									}
								</script>
								<div class="col-md-6 pr-md-0 mt-3 mt-md-0 d-flex flex-column justify-content-between" style="height:31rem;">
									<div>
<?php
									if( $bas["BASStatus"] == 2 ) {
?>
										<div class="w-100 bg-warning p-1 text-center">
											<h2 class="text-white m-0"><span class="fa fa-check-circle-o" aria-hidden="true"></span> 거래완료</h2>
										</div>
<?php
									} else {
?>
										<div class="w-100 bg-success p-1 text-center">
											<h2 class="text-white m-0"><span class="fa fa-shopping-cart" aria-hidden="true"></span> 거래중</h2>
										</div>
<?php
									}
?>
									</div>
									<div>
										<p class="m-0">Price:</p>
										<h1 class="display-4 mb-3 text-primary">HK$<?=$bas["BASPrice"]?></h1>
										<hr class="my-3">
										<div class="w-100 bg-faded p-1 text-center mb-2">
											<p class="m-0"><span class="fa fa-tags" aria-hidden="true"></span> <?=$bas["BASType"]?></p>
										</div>
										<div class="w-100 bg-inverse p-1 text-center">
											<p class="text-white m-0"><span class="fa fa-id-card-o" aria-hidden="true"></span> 판매자 정보</p>
										</div>
										<table class="table m-0">
											<tbody>
												<tr>
													<th class="bg-faded text-center" scope="row" style="width:3rem;">
														<span class="fa fa-user-circle-o" aria-hidden="true"></span>
													</th>
													<td><?=$bas["UserName"]?></td>
												</tr>
												<tr>
													<th class="bg-faded text-center" scope="row">
														<span class="fa fa-envelope" aria-hidden="true"></span>
													</th>
													<td><?=$bas["UserEmail"]?></td>
												</tr>
												<tr>
													<th class="bg-faded text-center" scope="row">
														<span class="fa fa-phone" aria-hidden="true"></span>
													</th>
													<td><?=$bas["UserPhoneNo"]?></td>
												</tr>
												<tr>
													<th class="bg-faded text-center" scope="row">
														<span class="fa fa-comment" aria-hidden="true"></span>
													</th>
													<td><?=$bas["UserKakaoID"]?></td>
												</tr>
												<tr>
													<th class="bg-faded text-center" scope="row">
														<span class="fa fa-graduation-cap" aria-hidden="true"></span>
													</th>
													<td><?=$bas["UserSchool"]?></td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
							</div>
							<hr>
							<blockquote class="blockquote my-4">
								<p id="bas-content" class="my-3 mx-1 p-3 text-justify"><?=$bas_content?></p>
							</blockquote>
							<?php include $WEB_ROOT.'/templates/urlify.php'; ?>
							<script>
								document.getElementById('bas-content').innerHTML =
										urlify(document.getElementById('bas-content').innerHTML);
							</script>
<?php
							if( $_SESSION["user_username"] == $bas["UserUsername"] ) {
?>
								<hr>
								<div class="text-center">
									<div class="btn-group btn-group-lg" role="group">
										<!--<a href="#" class="btn btn-secondary">수정</a>-->
<?php
										if( $bas["BASStatus"] != 2 )
											echo "<a href='./close.php?postno=$bas[BASID]' class='btn btn-danger'>거래완료</a>";
?>
									</div>
								</div>
<?php
							}

?>
						</div>
					</div>
<?php
				}
			} else {
				echo "<script>alert('게시글을 불러오는 중 오류가 발생했습니다.');";
				echo "location.href = '/buy-and-sell';</script>";
			}
?>
		</div>
	</div>
</div>

<!-- Footer -->
<?php include $WEB_ROOT.'/templates/footer.php'; ?>

</body>
</html>
