<?php

	if( isset( $_POST["UserID"] ) && isset( $_POST["UserUsername"] ) && isset( $_POST["CmtContent"] ) ) {
		include $_SERVER["DOCUMENT_ROOT"].'/config.php';
	
		include $WEB_ROOT.'/classes/FreeboardDBManager.php';
		$freeboard_db = FreeboardDBManager::createFreeboardDBManager();

		$FAIL = -1;
		$RESPONSE = "";

		header('Content-Type: text/plain');
	
		if( $freeboard_db !== FreeboardDBManager::$CONNECT_ERROR ) {
			if( $freeboard_db->insert_comment_byid( $_GET["postno"], $_POST["UserID"], $_POST["CmtContent"] ) == FreeboardDBManager::$INSERT_ERROR ) {
				echo $FAIL;
			} else {
				$comments = $freeboard_db->select_comments_byid( $_GET["postno"] );
				if( is_array( $comments ) ) {
					foreach( $comments as $row ) {
						switch( $row["UserSchool"] ) {
							case 1:
								$school = " / HKUST";
								break;
							case 2:
								$school = " / HKU";
								break;
							case 3:
								$school = " / CUHK";
								break;
							case 4:
								$school = " / POLYU";
								break;
							case 5:
								$school = " / CITYU";
								break;
							default:
								$school = "";
						}
						$content = nl2br( $row["CmtContent"] );
						$RESPONSE .= "<div class='row col-12 justify-content-between mx-0 px-0'><div>";
						$RESPONSE .= "<span class='text-info'>$row[UserName] ($row[UserUsername]$school)</span></div>";
						if( $row["UserUsername"] == $_POST["UserUsername"] ) {
							$RESPONSE .= "<button type='button' class='close ml-2' onclick='deleteComment($row[CmtID])'>";
							$RESPONSE .= "<span class='text-muted fa fa-times'></span></button>";
						}
						$RESPONSE .= "</div><div class='row col-12 text-muted' style='font-size:0.8rem'>$row[CmtPostdate]</div>";
						$RESPONSE .= "<div class='row col-12 mt-2'>$content</div><hr>";
					} 
				}
			}
		} else {
			echo $FAIL;
		}

		echo $RESPONSE;
	} else {
		echo "<script>location.href = '/freeboard'</script>";
	}

?>
