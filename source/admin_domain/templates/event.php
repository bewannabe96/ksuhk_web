<?php

	$status = isset( $_GET["status"] ) ? $_GET["status"] : "all";

?>

<div class="row">
	<div class="col-3" style="border-right: 1px solid #BBBBBB;">
		<nav class="nav nav-pills flex-column">
			<a class="nav-link <?php if($status==='all'){ ?>active<?php } ?>" href="/?page=event&status=all">전체</a>
			<a class="nav-link <?php if($status==='ongoing'){ ?>active<?php } ?>" href="/?page=event&status=ongoing">진행중</a>
			<a class="nav-link <?php if($status==='closed'){ ?>active<?php } ?>" href="/?page=event&status=closed">종료</a>
		</nav>
	</div>
	<div class="col-9">
<?php
	if( !isset( $_GET["action"] ) ) {
		include 'templates/event/list.php';

	} else {
		switch( $_GET["action"] ) {
			case "view":
				include 'templates/event/view.php';
				break;	
	
			case "new":
				include 'templates/event/new.php';
				break;	
	
			case "edit":
				include 'templates/event/edit.php';
				break;	
	
			case "delete":
				include 'templates/event/delete.php';
				break;	
	
			case "closeinvite":
				include 'templates/event/close-invite.php';
				break;	

			case "close":
				include 'templates/event/close.php';
				break;	
	
			default:
				include 'templates/event/list.php';
		}
	}
?>
	</div>
</div>
