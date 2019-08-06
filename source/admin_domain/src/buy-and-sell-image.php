<?php

	if( isset( $_GET["basid"] ) && isset( $_GET["hash"] ) ) {

		include '../classes/BuyandsellDBManager.php';
		$buyandsell_db = BuyandsellDBManager::createBuyandsellDBManager();

		if( $buyandsell_db !== BuyandsellDBManager::$CONNECT_ERROR ) {
			$_GET["basid"] = $_GET["basid"] == "temp" ? 0 : $_GET["basid"];
			
			$row = $buyandsell_db->select_image_byhash( $_GET["basid"], $_GET["hash"] );

			header("Content-type: image/" . $row["ImageType"]);
			echo $row["ImageData"];
		}
	}

?>
