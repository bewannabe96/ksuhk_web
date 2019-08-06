<?php

	$section = isset( $_GET["section"] ) ? $_GET["section"] : "news";

?>

<div class="row">
	<div class="col-3" style="border-right: 1px solid #BBBBBB;">
		<nav class="nav nav-pills flex-column">
			<a class="nav-link <?php if($section==='news'){ ?>active<?php } ?>" href="/?page=notice&section=news">공지사항</a>
			<a class="nav-link <?php if($section==='recruit'){ ?>active<?php } ?>" href="/?page=notice&section=recruit">채용</a>
			<a class="nav-link <?php if($section==='lecture'){ ?>active<?php } ?>" href="/?page=notice&section=lecture">강연</a>
		</nav>
	</div>
	<div class="col-9">
<?php
		if( !isset( $_GET["action"] ) ) {
			include 'templates/notice/list.php';

		} else {
			switch( $_GET["action"] ) {
				case "view":
					include 'templates/notice/view.php';
					break;	
		
				case "new":
					include 'templates/notice/new.php';
					break;	
		
				case "edit":
					include 'templates/notice/edit.php';
					break;	
		
				case "delete":
					include 'templates/notice/delete.php';
					break;	
		
				default:
					include 'templates/notice/list.php';
			}
		}
?>
	</div>
</div>
