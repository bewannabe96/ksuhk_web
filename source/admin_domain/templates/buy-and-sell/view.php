<?php

	include 'classes/BuyandsellDBManager.php';
	$buyandsell_db = BuyandsellDBManager::createBuyandsellDBManager();

	if( $buyandsell_db !== BuyandsellDBManager::$CONNECT_ERROR ) {
		$bas = $buyandsell_db->select_bas_byid( $_GET["id"] );
		$images = $buyandsell_db->select_images_byid( $_GET["id"] );
		if( !$bas ) {
			echo '<script>alert( "판매글을 불러오는 중 오류가 발생했습니다." );</script>';
			echo "<script>location.href = '/?page=buy-and-sell&status=$status'</script>";
			die;
		}

	} else {
		echo '<script>alert( "데이터베이스를 연결할 수 없습니다." );</script>';
		die;
	}

?>

<form>
	<div class="form-group row">
		<label class="col-2 col-form-label text-right">제목</label>
		<div class="col-10">
			<p class="form-control-static"><strong><?=$bas["BASTitle"]?></strong></p>
		</div>
	</div>

	<div class="form-group row">
		<label class="col-2 col-form-label text-right">게시일</label>
		<div class="col-10">
			<p class="form-control-static"><?=$bas["BASPostdate"]?></p>
		</div>
	</div>

	<div class="form-group row">
		<label class="col-2 col-form-label text-right">수정일</label>
		<div class="col-10">
			<p class="form-control-static"><?=$bas["BASUpdate"]?></p>
		</div>
	</div>
	<div class="form-group row">
		<label class="col-2 col-form-label text-right">판매자</label>
		<div class="col-10">
			<p class="form-control-static">
				<?=$bas["UserName"]?> (<?=$bas["UserUsername"]?>) - 
<?php
			switch( $bas["UserSchool"] ) {
				case 1:
					echo "홍콩과학기술대학교(HKUST)";
					break;
				case 2:
					echo "홍콩대학교(HKU)";
					break;
				case 3:
					echo "홍콩중문대학교(CUHK)";
					break;
				case 4:
					echo "홍콩이공대학교(POLYU)";
					break;
				case 5:
					echo "홍콩시립대학교(CITYU)";
					break;
				default:
					echo "(학교 없음)";
			}
?>
			</p>
		</div>
	</div>

<?php
	if( isset( $post["PostImage"] ) && $post["PostImage"] != "" ) {
?>
	<div class="form-group row">
		<div class="col-10 offset-2">
			<img src="src/freeboard-image.php?hash=<?=$post["PostImage"]?>" class="w-50 rounded">
		</div>
	</div>
<?php
	}
?>
	<div class="form-group row">
		<label class="col-2 col-form-label text-right">본문</label>
		<div class="col-10">
			<blockquote class="blockquote" style="font-size:1rem;">
				<p class="form-control-static"><?php echo nl2br( htmlspecialchars( $bas["BASContent"] ) ); ?></p>
			</blockquote>
		</div>
	</div>
	<div class="form-group row">
		<label class="col-2 col-form-label text-right">사진</label>
		<div class="col-10 d-flex" style="height:15rem;">
<?php
			foreach( $images as $image ) {
?>
				<div class="w-100 text-center" style="height:15rem;">
					<img class="mw-100 mh-100" src="/src/buy-and-sell-image.php?basid=<?=$bas["BASID"]?>&hash=<?=$image?>">
				</div>
<?php
			}
?>
		</div>
	</div>
	<div class="form-group row">
<?php
		if( $bas["BASReportStatus"] == 2 ) {
			$report = $buyandsell_db->select_report_byid( $_GET["id"] );
?>
			<label class="col-2 col-form-label text-right">신고내용</label>
			<div class="col-10">
				<div class="card">
					<div class="card-header text-warning"><?=$report["UserName"]?> (<?=$report["UserUsername"]?>)</div>
					<div class="card-block"><?=$report["BASReport"]?></div>
				</div>
			</div>
<?php
		}
?>
	</div>
	<div class="form-group row">
		<div class="col-10 offset-2">
			<div class="btn-group float-right">
<?php
			if( $bas["BASReportStatus"] == 2 ) {
				echo "<a href='/?page=buy-and-sell&status=$status&action=allow&id=$bas[BASID]' class='btn btn-secondary'>신고해제</a>";
			}
?>
				<a href="/?page=buy-and-sell&status=<?=$status?>&action=delete&id=<?=$bas["BASID"]?>" class="btn btn-danger">삭제</a>
			</div>
		</div>
	</div>
</form>
