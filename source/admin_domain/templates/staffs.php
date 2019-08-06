<?php
	if( !isset( $_GET["action"] ) ) {
		include 'templates/staffs/list.php';

	} else {
		switch( $_GET["action"] ) {
			case "new":
				include 'templates/staffs/new.php';
				break;	
	
			case "edit":
				include 'templates/staffs/edit.php';
				break;	
	
			case "delete":
				include 'templates/staffs/delete.php';
				break;	
	
			case "move":
				include 'templates/staffs/move.php';
				break;	
	
			default:
				include 'templates/staffs/list.php';
		}
	}
?>
