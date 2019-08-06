<?php

	$status = isset( $_GET["status"] ) ? $_GET["status"] : "all";

?>

<div class="row">
	<div class="col-3" style="border-right: 1px solid #BBBBBB;">
		<nav class="nav nav-pills flex-column">
			<a class="nav-link <?php if($status==='all'){ ?>active<?php } ?>" href="/?page=freeboard&status=all">전체</a>
			<a class="nav-link <?php if($status==='report'){ ?>active<?php } ?>" href="/?page=freeboard&status=report">신고접수</a>
		</nav>
	</div>
	<div class="col-9">
<?php
		if( !isset( $_GET["action"] ) ) {
			include 'templates/freeboard/list.php';

		} else {
			switch( $_GET["action"] ) {
				case "view":
					include 'templates/freeboard/view.php';
					break;	
		
				case "delete":
					include 'templates/freeboard/delete.php';
					break;	
		
				case "allow":
					include 'templates/freeboard/allow.php';
					break;	
		
				case "del-comment":
					include 'templates/freeboard/del-comment.php';
					break;	
		
				default:
					include 'templates/freeboard/list.php';
			}
		}
?>
	</div>
</div>
