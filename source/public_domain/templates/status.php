<?php
	function ERROR_PAGE( $msg ) {
?>
		<div class="card">
			<div class="media card-block text-danger p-5">
				<div class="media-left mr-3"><h1><span class="fa fa-times-circle-o fa-2x"></span></h1></div>
				<div class="media-body align-bottom">
					<h2>페이지 에러</h2>
					<p class="text-muted"><?=$msg?></p>
				</div>
			</div>
		</div>
<?php
	}

	function READY_PAGE() {
?>
		<div class="card">
			<div class="media card-block text-warning p-5">
				<div class="media-left mr-3"><h1><span class="fa fa-warning fa-2x"></span></h1></div>
				<div class="media-body align-bottom">
					<h2>죄송합니다</h2>
					<p class="text-muted">페이지가 준비중 입니다.</p>
				</div>
			</div>
		</div>
<?php
	}
?>
