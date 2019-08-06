<?php

	$env_page = "page_aboutKSUHK_staff";

	include $_SERVER["DOCUMENT_ROOT"].'/config.php';

	include $WEB_ROOT.'/templates/status.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>KSUHK - KSUHK 소개 / 임원진 소개</title>
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
				<h5 class="card-header">KSUHK 소개</h5>
				<div class="list-group list-group-flush">
					<a href="/about-KSUHK" class="list-group-item list-group-item-action">인사말</a>
					<a href="/about-KSUHK/staff" class="list-group-item list-group-item-action active">임원진 소개</a>
				</div>
			</div>
		</div>
		<div class="col-lg-9 mt-0 mt-sm-3 mt-lg-0 p-0 p-sm-3">
			<ol class="breadcrumb mb-0 mb-sm-3">
				<li class="breadcrumb-item active">KSUHK 소개</li>
				<li class="breadcrumb-item active">임원진 소개</li>
			</ol>
<?php
		include $WEB_ROOT.'/classes/StaffDBManager.php';
		$staff_db = StaffDBManager::createStaffDBManager();
	
		if( $staff_db !== StaffDBManager::$CONNECT_ERROR ) {
			$staff_rows = $staff_db->select_all_staffs();
			if( !$staff_rows ) {
				ERROR_PAGE( "임원진 데이터가 없습니다." );
			} else {	
?>
				<div class="card">
					<div class="list-group list-group-flush">
					<!-- staff group photo -->
					<div class="text-center p-1">
						<div class="d-inline-flex h-100 align-items-center">
							<img class="mh-100 mw-100" src="/src/groupphoto.png">
						</div>
					</div>
<?php
					foreach( $staff_rows as $row ) {
						switch( $row["StaffSchool"] ) {
							case 1:
								$school = "Hong Kong University of Science and Technology (과기대)";
								break;
							case 2:
								$school = "The University of Hong Kong (홍콩대)";
								break;
							case 3:
								$school = "The Chinese University of Hong Kong (중문대)";
								break;
							case 4:
								$school = "The Hong Kong Polytechnic University (이공대)";
								break;
							case 5:
								$school = "City University of Hong Kong (시립대)";
								break;
						}
?>
						<div class="list-group-item card-block py-3 p-lg-5">
							<div class="col-md-4 text-center">
<?php
							if( isset( $row["StaffImage"] ) )
								echo '<img src="/src/image.php?hash=' . $row["StaffImage"] . '" class="rounded-circle" style="width:12rem; height:12rem;">';
							else
								echo '<img src="/src/placeholder.png" class="rounded-circle" style="width:12rem; height:12rem;">';
?>
							</div>
							<div class="col-md-8 px-0 px-sm-4 mt-4 mt-lg-0">
								<h4 class="text-primary">
									<?=$row["StaffName"]?> <br class="hidden-md-up">(<?=$row["StaffEngName"]?>)
								</h4><br>
								<h5><?=$row["StaffPosition"]?></h5>
								<p><?=$school?></p>
								<hr class="my-1">
								<span class="fa fa-envelope"></span> <?=$row["StaffEmail"]?><br>
								<span class="fa fa-phone"></span> (852)<?=$row["StaffPhoneNo"]?>
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
			ERROR_PAGE( "서버문제로 임원진 정보를 불러올 수 없습니다." );
		}
?>
		</div>
	</div>
</div>

<!-- Footer -->
<?php include $WEB_ROOT.'/templates/footer.php'; ?>

</body>
</html>
