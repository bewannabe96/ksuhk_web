<?php

	if( isset( $_GET["hash"] ) ) {

		include '../classes/ImageDBManager.php';
		$image_db = ImageDBManager::createImageDBManager();

		if( $image_db !== ImageDBManager::$CONNECT_ERROR ) {
			$row = $image_db->select_image_byhash( $_GET["hash"] );

			header("Content-type: image/" . $row["ImageType"]);
			echo $row["ImageData"];
		}
	}

?>
