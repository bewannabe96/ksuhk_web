<?php

	if( isset( $_POST["UserID"] ) && isset( $_POST["accept"] ) ) {
		include $_SERVER["DOCUMENT_ROOT"].'/classes/UserDBManager.php';
		$user_db = UserDBManager::createUserDBManager();

		$SUCCESS = 1;
		$DELETE_ERROR = -1;
		$UPDATE_ERROR = -2;
		$DB_ERROR = -3;

		header('Content-Type: text/plain');
	
		if( $user_db !== UserDBManager::$CONNECT_ERROR ) {
			if( $_POST["accept"] == 0 ) {
				if( $user_db->delete_user_byid( $_POST["UserID"] ) === UserDBManager::$DELETE_ERROR )
					echo $DELETE_ERROR;
			} else {
				if( $user_db->update_acceptance( $_POST["UserID"], $_POST["accept"] ) === UserDBManager::$UPDATE_ERROR )
					echo $UPDATE_ERROR;
			}
		} else {
			echo $DB_ERROR;
		}

		echo $SUCCESS;
	} else {
		echo "<script>location.href = '/?page=members'</script>";
	}
	
?>
