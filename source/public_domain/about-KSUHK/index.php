<?php

	$env_page = "page_aboutKSUHK";

	include $_SERVER["DOCUMENT_ROOT"].'/config.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>KSUHK - KSUHK 소개 / 인사말</title>
	<!-- HTML Header -->
	<?php include $WEB_ROOT.'/templates/html-header.php'; ?>
</head>
<body class="bg-faded">

<!-- Navigator -->
<?php include $WEB_ROOT.'/templates/navigator.php'; ?>

<div class="container my-0 my-sm-5 p-0">
	<div class="row w-100 m-0">
		<div class="col-lg-3 p-0 p-sm-3">
			<div class="card">
				<h5 class="card-header">KSUHK 소개</h5>
				<div class="list-group list-group-flush">
					<a href="/about-KSUHK" class="list-group-item list-group-item-action active">인사말</a>
					<a href="/about-KSUHK/staff" class="list-group-item list-group-item-action">임원진 소개</a>
				</div>
			</div>
		</div>
		<div class="col-lg-9 mt-0 mt-sm-3 mt-lg-0 p-0 p-sm-3">
			<ol class="breadcrumb mb-0 mb-sm-3">
				<li class="breadcrumb-item active">KSUHK 소개</li>
				<li class="breadcrumb-item active">인사말</li>
			</ol>
			<div class="card card-block">
				<div class="row mt-4">
					<div class="col-lg-4">
						<img src="/src/president.jpg" class="rounded w-100">
					</div>
					<div class="col-lg-8 mt-5 mt-lg-0">
						<h2 class="text-info">친애하는 재홍콩 유학생 여러분!</h2><br>
						<p class="text-justify">
							&ensp;&ensp;&ensp;&ensp;&ensp;
							2015년 출범한 홍콩한인유학생 총학생회가 어느새 설립 2주년을 맞이하였습니다.
							총학생회는 과기대, 시립대, 이공대, 중문대, 홍콩대 등 다섯 개 대학교 한인학생회들의 효율적인
							발전과 협력을 도모하고 학생들의 성장과 이익 대변을 위하여 설립되었습니다.<br>
							&ensp;&ensp;&ensp;&ensp;&ensp;
							2016년을 기준으로 홍콩 내 한인 대학생의 수가 최초로 1,000명을 넘어선 것은 비단 홍콩이
							우수한 교육 시설을 갖춘 곳이란 것뿐만이 아니라 그만큼 홍콩 내에서 한인 학생들의 영향력도
							크게 증가하고 있다는 것을 의미합니다.
							하지만 명부기실(名符其實)이라는 말처럼 명성에 맞는 실력을 갖추어야 합니다.
							이에 앞장서 총학생회에서는 조직체계를 더욱 굳건히 하겠으며 외부 기관들과 신뢰를
							쌓고 학생들을 위한 새로운 기회도 더 많이 창출하도록 혼신의 노력을 기울이겠습니다.<br>
							&ensp;&ensp;&ensp;&ensp;&ensp;
							저희는 현재까지 약 10개월의 임기 동안 다양한 도전과 난관을 직면하였습니다.
							하지만 각 학교를 대표하는 학생회 및 총학생회 임원들과 한데 힘을 모아 어려움을 극복해왔습니다.
							또한 많은 학생들이 총학생회에 관심을 가지고 힘을 실어주었습니다.
							그 누구도 강제로 시키지 않았지만 학생회 임원진들은 본인의 소중한 시간과 에너지를 할애하며
							다른 학생들을 위하여 봉사하였습니다. 홍콩 내 모든 한인 학생들을 대표하여 감사의 뜻을 전하며
							앞으로도 계속 성장해 나아갈 총학생회의 모습을 기대합니다. 모두 큰 관심을 가지고 지켜봐 주십시오.<br><br>
							감사합니다.<br><br>
						</p>
						<div class="text-right">
							제2대 홍콩한인유학생 총학생회장<br>
							<strong>황상필 올림</strong><br> 
							<img src="/src/sign.png" class="my-4" style="width:7rem;">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Footer -->
<?php include $WEB_ROOT.'/templates/footer.php'; ?>

</body>
</html>
