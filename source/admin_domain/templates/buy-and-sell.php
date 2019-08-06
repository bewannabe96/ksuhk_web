<?php

	$status = isset( $_GET["status"] ) ? $_GET["status"] : "all";

?>

<div class="row">
	<div class="col-3" style="border-right: 1px solid #BBBBBB;">
		<nav class="nav nav-pills flex-column">
			<a class="nav-link <?php if($status==='all'){ ?>active<?php } ?>" href="/?page=buy-and-sell&status=all">전체</a>
			<a class="nav-link <?php if($status==='report'){ ?>active<?php } ?>" href="/?page=buy-and-sell&status=report">신고접수</a>
		</nav>
	</div>
	<div class="col-9">
<?php
		if( !isset( $_GET["action"] ) ) {
			include 'templates/buy-and-sell/list.php';

		} else {
			switch( $_GET["action"] ) {
				case "view":
					include 'templates/buy-and-sell/view.php';
					break;	
		
				case "delete":
					include 'templates/buy-and-sell/delete.php';
					break;	
		
				case "allow":
					include 'templates/buy-and-sell/allow.php';
					break;
		
				default:
					include 'templates/buy-and-sell/list.php';
			}
		}
?>
	</div>
</div>
