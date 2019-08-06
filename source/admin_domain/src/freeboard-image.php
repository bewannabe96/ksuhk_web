<?php

	if( isset( $_GET["hash"] ) ) {

		include '../classes/FreeboardDBManager.php';
		$freeboard_db = FreeboardDBManager::createFreeboardDBManager();

		if( $freeboard_db !== FreeboardDBManager::$CONNECT_ERROR ) {
			$row = $freeboard_db->select_image_byhash( $_GET["hash"] );

			header("Content-type: image/" . $row["ImageType"]);
			echo $row["ImageData"];
		}
	}

?>
