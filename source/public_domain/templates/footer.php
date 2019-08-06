<div id="footer" class="jumbotron jumbotron-fluid bg-inverse text-muted mb-0">
	<div class="container">
		<div class="row">
			<div class="col-6 col-md-3 col-lg-2">
				<p>
					KSUHK 소개<br>
					<a href="/about-KSUHK">인사말</a><br>
					<a href="/about-KSUHK/staff">임원진 소개</a>
				</p>	
			</div>
			<div class="col-6 col-md-3 col-lg-2">
				<p>
					알림게시판<br>
					<a href="/notice/?section=news">공지사항</a><br>
					<a href="/notice/?section=recruit">채용</a><br>
					<a href="/notice/?section=lecture">강연</a>
				</p>	
			</div>
			<div class="col-6 col-md-3 col-lg-2">
				<p>
					<a href="/event" class="text-muted">행사/이벤트</a><br>
					<a href="/freeboard" class="text-muted">자유게시판</a><br>
					<a href="/buy-and-sell" class="text-muted">벼룩시장</a>
				</p>	
			</div>
			<div class="col-6 col-md-3 col-lg-2">
				<p>
<?php
				if( isset( $_SESSION["auth"] ) && $_SESSION["auth"] >= 1 ) {
?>
					<a href="/memberinfo" class="text-muted">회원정보수정</a><br>
					<a href="/login/logout.php" class="text-muted">로그아웃</a><br>
<?php
				} else {
?>
					<a href="/login" class="text-muted">로그인</a><br>
					<a href="/signup" class="text-muted">회원가입</a><br>
<?php
				}
?>
				</p>	
			</div>
			<div class="col-lg-4">
				<div class="d-flex justify-content-end">
					<a href="https://www.facebook.com/ksuhk/" class="text-muted"><span class="fa fa-facebook-square fa-2x"></span></a>
					<a href="https://www.instagram.com/ksuhk15/" class="text-muted ml-3"><span class="fa fa-instagram fa-2x"></span></a>
				</div>
			</div>
		</div>
		<hr class="bg-faded my-4">
		<p>
			Copyright <span class="fa fa-copyright"></span> 2016 Korean Studnets' Union in Hong Kong. All Rights reserved.<br>
			<span class="fa fa-envelope"></span> ksu.hongkong@gmail.com<br>
			<span class="fa fa-envelope"></span> (HKUST) ksa.hkust@gmai.com<br>
			<span class="fa fa-envelope"></span> (CITYU) cityu.kascu@gmail.com<br>
			<span class="fa fa-envelope"></span> (POLYU) polyu.ksa@gmail.com<br>
			<span class="fa fa-envelope"></span> (CUHK) cuksa.cuhk@gmail.com<br>
			<span class="fa fa-envelope"></span> (HKU) hkuksa.hku@gmail.com<br>
			<span class="fa fa-phone"></span> (852)6312 5830
		</p>
	</div>
</div>
