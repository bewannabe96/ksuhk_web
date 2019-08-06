<?php

	$env_page = "page_buyandsell";

	include $_SERVER["DOCUMENT_ROOT"].'/config.php';
	CHECK_LOGIN_SESSION();

	$row_limit = 10;
	$page = isset( $_GET["page"] ) ? $_GET["page"] : 1;
	$category = isset( $_GET["category"] ) ? $_GET["category"] : 0;
	$query = isset( $_GET["query"] ) ? $_GET["query"] : "";

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>KSUHK - 벼룩시장</title>
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
				<li class="breadcrumb-item active">벼룩시장</li>
			</ol>
<?php
		include $WEB_ROOT.'/classes/BuyandsellDBManager.php';
		$buyandsell_db = BuyandsellDBManager::createBuyandsellDBManager();
	
		if( $buyandsell_db !== BuyandsellDBManager::$CONNECT_ERROR ) {
			$search_result = $buyandsell_db->search_bases( $category, $query, $row_limit, ($page-1)*$row_limit );
			$num_bases = $search_result[0];
			$bases = $search_result[1];
?>
			<div class="card">
				<div class="card-block p-0 py-sm-4 px-sm-3">
					<div class="d-md-flex justify-content-between">
						<h3 class="m-3 mx-sm-0"><span class="fa fa-shopping-basket" aria-hidden="true"></span> 벼룩시장</h3>
						<div class="d-flex align-items-center flex-row-reverse m-3 mx-sm-0">
							<form id="search-form" class="d-flex" method="get">
								<select class="form-control mr-1" name="category">
									<option value="0">분류</option>
									<option value="2" <?php if(isset($_GET["category"])&&$_GET["category"]==2){echo "selected";} ?>>의류</option>
									<option value="3" <?php if(isset($_GET["category"])&&$_GET["category"]==3){echo "selected";} ?>>전자제품</option>
									<option value="4" <?php if(isset($_GET["category"])&&$_GET["category"]==4){echo "selected";} ?>>생활용품</option>
									<option value="5" <?php if(isset($_GET["category"])&&$_GET["category"]==5){echo "selected";} ?>>학용품/사무용품</option>
									<option value="6" <?php if(isset($_GET["category"])&&$_GET["category"]==6){echo "selected";} ?>>운동용품/악기</option>
									<option value="7" <?php if(isset($_GET["category"])&&$_GET["category"]==7){echo "selected";} ?>>재능나눔</option>
									<option value="1" <?php if(isset($_GET["category"])&&$_GET["category"]==1){echo "selected";} ?>>기타</option>
								</select>
								<input id="search-word" name="query" hidden>
								<input id="search-word-temp" name="search" class="form-control mr-1" type="text" placeholder="검색" value="<?php if(isset($_GET["search"])){echo $_GET["search"];} ?>">
								<button class="btn btn-outline-success" type="submit">검색</button>
							</form>
							<script>
								document.getElementById('search-form').onsubmit = function() {
									document.getElementById('search-word').value
										= '%' + document.getElementById('search-word-temp').value.split('').join('%') + '%';
								}
							</script>
						</div>
					</div>
					<table class="table <?php if($bases!=FALSE) {?>table-hover<?php } ?>">
						<thead class="thead-inverse">
							<tr><th></th></tr>
						</thead>
						<tbody>
<?php 
						if( !$bases ) {
							echo '<tr><td class="text-center">검색된 결과가 없습니다.</td></tr>';
	
						} else {
							foreach( $bases as $row ) {
								$row["BASPrice"] = number_format( $row["BASPrice"] );
?>
								<tr onclick="location.href = '/buy-and-sell/view/?postno=<?=$row["BASID"]?>'">
									<td scope="row" class="p-0">
										<div class="d-flex flex-column flex-sm-row h-100 w-100">
											<div class="text-center col-12 col-sm-3 bg-faded p-2" style="height:12rem;">
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
											<div class="d-flex flex-column col-12 col-sm-9 mt-3 mt-sm-0 p-3 p-sm-5">
												<div class="w-100 d-flex justify-content-between">
													<p class="text-muted d-inline m-0"><?=$row["BASPostdate"]?></p>
													<p class="d-inline text-info d-inline m-0">
														<span class="fa fa-eye" aria-hidden="true"></span> <?=$row["BASView"]?>
													</p>
												</div>
												<div class="w-100 mb-auto mt-1">
													<h4>
<?php
														if( $row["BASStatus"] == 1 )
															echo "<span class='text-success'>[거래중]</span>";
														else
															echo "<span class='text-warning'>[거래완료]</span>";
?>
														<?=$row["BASTitle"]?>
													</h4>
												</div>
												<div class="w-100 d-flex flex-row-reverse justify-content-between align-items-end">
													<h4 class="text-warning m-0">HK$ <?=$row["BASPrice"]?></h4>
													<h6 class="text-muted m-0 hidden-sm-down"><?=$row["UserName"]?>(<?=$row["UserUsername"]?>)</h6>
												</div>
											</div>
										</div>
									</td>
								</tr>
<?php
							} 
						}
?>
						</tbody>
					</table>
				</div>
				<div class="card-footer d-flex justify-content-between">
					<a href="/buy-and-sell/write" class="btn btn-primary btn-sm">글쓰기</a>
<?php
				include $WEB_ROOT.'/templates/pagination.php';
				CREATE_PAGINATION( $num_bases, $row_limit, $page, "/buy-and-sell/?category=$category&query=$query&search=$_GET[search]", "page" );
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
