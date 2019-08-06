<?php

	$env_page = "page_buyandsell_write_ok";

	include $_SERVER["DOCUMENT_ROOT"].'/config.php';
	CHECK_LOGIN_SESSION();

	if( !isset($_POST["submit"]) )
		echo "<script>document.location='/buy-and-sell/write';</script>";

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>KSUHK - 벼룩시장 / 글쓰기</title>
	<!-- HTML Header -->
	<?php include $WEB_ROOT.'/templates/html-header.php'; ?>
</head>
<body class="bg-faded">

<!-- Navigator -->
<?php include $WEB_ROOT.'/templates/navigator.php'; ?>

<div class="container my-5">
	<div class="row col-lg-10 offset-lg-1">

<?php

	include $WEB_ROOT.'/templates/status.php';

	include $WEB_ROOT.'/classes/BuyandsellDBManager.php';
	$buyandsell_db = BuyandsellDBManager::createBuyandsellDBManager();

	if( $buyandsell_db !== BuyandsellDBManager::$CONNECT_ERROR ) {
?>
		<form method="post" action="./"><input type="text" name="resultCode" hidden>
		<input type="text" name="BASTitle" value="<?=$_POST["BASTitle"]?>" hidden>
		<input type="number" name="BASType" value="<?=$_POST["BASType"]?>" hidden>
		<input type="number" name="BASPrice" value="<?=$_POST["BASPrice"]?>" hidden>
		<input type="text" name="BASContent" value="<?=$_POST["BASContent"]?>" hidden>
		</form>
		
<?php

		$bas_id = $buyandsell_db->insert_new_bas( $_POST["BASTitle"], $_POST["UserID"], $_POST["BASType"],
							$_POST["BASPrice"], $_POST["BASContent"], sizeof($_POST["images"]) );
		if( $bas_id == BuyandsellDBManager::$INSERT_ERROR ) {
?>
			<script>
				document.getElementsByName('resultCode')[0].value = 1;
				document.getElementsByTagName('form')[0].submit();
			</script>
<?php
		} else {
			if( sizeof($_POST["images"]) > 0 )
				$result = $buyandsell_db->relocate_tempimages( $bas_id, $_POST["images"] );
				$buyandsell_db->delete_tempimages_byuserid( $_POST["UserID"] );
				if( $result == BuyandsellDBManager::$INSERT_ERROR ) {
?>
					<script>
						document.getElementsByName('resultCode')[0].value = 2;
						document.getElementsByTagName('form')[0].submit();
					</script>
<?php
				}

		}
		echo "<script>alert('판매글이 성공적으로 게시되었습니다.');</script>";
		echo "<script>location.href = '/buy-and-sell/view/?postno=$bas_id'</script>";
		
	} else {
		ERROR_PAGE( "현재 서버문제로 게시글을 작성하실 수 없습니다." );
	}
?>

	</div>
</div>

<!-- Footer -->
<?php include $WEB_ROOT.'/templates/footer.php'; ?>

</body>
</html>
