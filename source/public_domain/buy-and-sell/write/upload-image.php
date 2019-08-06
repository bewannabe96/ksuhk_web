<?php

	if( isset( $_POST["UserID"] ) ) {
		include $_SERVER["DOCUMENT_ROOT"].'/config.php';

		include $WEB_ROOT.'/classes/BuyandsellDBManager.php';
		$buyandsell_db = BuyandsellDBManager::createBuyandsellDBManager();

		$SUCCESS = 1;
		$MAX_IMAGE_UPLOAD = -1;
		$INSERT_ERROR = -2;
		$DELETE_ERROR = -3;
		$FILE_ERROR = -4;
		$DB_ERROR = -5;

		header('Content-Type: text/plain');

		if( $buyandsell_db !== BuyandsellDBManager::$CONNECT_ERROR ) {
			

			if( !isset( $_GET["action"] ) ) {
				if( $buyandsell_db->get_num_tempimages( $_POST["UserID"] ) >= $BAS_MAX_IMG_UPLOAD ) {
					echo $MAX_IMAGE_UPLOAD;
				} else {
					$result = $buyandsell_db->insert_tempimage( $_POST["UserID"], $_FILES["TempImage"] );
					switch( $result ) {
						case BuyandsellDBManager::$INSERT_ERROR:
							echo $INSERT_ERROR;
							break;
						case BuyandsellDBManager::$IMAGE_NULL:
							echo $FILE_ERROR;
							break;
						case BuyandsellDBManager::$INVALID_TYPE:
							echo $FILE_ERROR;
							break;
						case BuyandsellDBManager::$INVALID_SIZE:
							echo $FILE_ERROR;
							break;
						default:
							echo $result;
					}
				}
			} else if( $_GET["action"] == "delete" ) {
				if( $_GET["scope"] == "hash" ) {
					if( $buyandsell_db->delete_tempimage_byhash( $_GET["hash"] ) == BuyandsellDBManager::$DELETE_ERROR )
						echo $DELETE_ERROR;
					else
						echo $SUCCESS;
				} else {
					if( $buyandsell_db->delete_tempimages_byuserid( $_POST["UserID"] ) == BuyandsellDBManager::$DELETE_ERROR )
						echo $DELETE_ERROR;
					else
						echo $SUCCESS;
				}
			}
		} else {
			echo $DB_ERROR;
		}

	} else {
		echo "<script>location.href = '/buy-and-sell'</script>";
	}

?>
