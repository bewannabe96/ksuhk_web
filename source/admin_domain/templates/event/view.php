<?php

	include 'classes/EventDBManager.php';
	$event_db = EventDBManager::createEventDBManager();

	if( $event_db !== EventDBManager::$CONNECT_ERROR ) {
		$event = $event_db->select_event_byid( $_GET["id"] );
		if( !$event ) {
			echo '<script>alert( "게시글을 불러오는 중 오류가 발생했습니다." );</script>';
			echo "<script>location.href = '/?page=event&status=$status'</script>";
			die;
		}

	} else {
		echo '<script>alert( "데이터베이스를 연결할 수 없습니다." );</script>';
		die;
	}

?>

<form>
	<div class="form-group row">
		<label class="col-2 col-form-label text-right">제목</label>
		<div class="col-10">
			<p class="form-control-static"><strong><?=$event["EventTitle"]?></strong></p>
		</div>
	</div>

	<div class="form-group row">
		<label class="col-2 col-form-label text-right">게시일</label>
		<div class="col-10">
			<p class="form-control-static"><?=$event["EventPostdate"]?></p>
		</div>
	</div>

	<div class="form-group row">
		<label class="col-2 col-form-label text-right">수정일</label>
		<div class="col-10">
			<p class="form-control-static"><?=$event["EventUpdate"]?></p>
		</div>
	</div>

<?php
	if( isset( $event["EventImage"] ) && $event["EventImage"] != "" ) {
?>
	<div class="form-group row">
		<div class="col-10 offset-2">
			<img src="src/image.php?hash=<?=$event["EventImage"]?>" class="w-50 rounded">
		</div>
	</div>
<?php
	}
?>

	<div class="form-group row">
		<label class="col-2 col-form-label text-right">본문</label>
		<div class="col-10">
			<blockquote class="blockquote" style="font-size:1rem;">
				<p class="form-control-static"><?php echo nl2br( $event["EventContent"] ); ?></p>
			</blockquote>
		</div>
	</div>

	<div class="form-group row">
		<label class="col-2 col-form-label text-right">일정</label>
		<div class="col-10">
			<p class="form-control-static"><?=$event["EventStart"]?> ~ <?=$event["EventEnd"]?></p>
		</div>
	</div>

	<div class="form-group row">
		<label class="col-2 col-form-label text-right">장소</label>
		<div class="col-10">
			<p class="form-control-static"><?=$event["EventAddress"]?></p>
			<div id="map" class="card card-block rounded" style="height:25rem;"></div>
		</div>
	</div>

	<div class="form-group row">
		<label class="col-2 col-form-label text-right">참가비</label>
		<div class="col-10">
			<p class="form-control-static">
<?php
			switch( $event["EventCurrency"] ) {
				case 1 :
					echo 'HKD<span class="fa fa-dollar"></span> ' . $event["EventCost"];
					break;
				case 2 :
					echo 'KRW<span class="fa fa-krw"></span> ' . $event["EventCost"];
					break;
				default :
					echo "무료";
			}
?>
			</p>
		</div>
	</div>

	<div class="form-group row">
		<label class="col-2 col-form-label text-right">정원</label>
		<div class="col-10">
			<p class="form-control-static">
<?php
			if( $event["EventMaxInvite"] == 0 )
				echo "없음";

			else
				echo $event["EventMaxInvite"] . "명";
			if( $event["EventInviteEnable"] == 1 )
				echo '<span class="text-muted ml-4">(온라인 신청 받지않음)</span>';
			if( $event["EventInviteEnable"] == 3 )
				echo '<span class="text-muted ml-4">(온라인 신청 마감됨)</span>';
?>
			</p>
<?php
		if( $event["EventInviteEnable"] != 1 && $event["EventStatus"] != 2 ) {
?>
			<table class="table table-bordered" style="font-size: 0.7rem;">
				<thead class="thead-inverse">
					<tr>
						<th style="width:5rem">이름</th>
						<th style="width:8rem">아이디</th>
						<th class="text-center" style="width:3rem">학교</th>
						<th style="width:7rem">연락처</th>
						<th>이메일</th>
						<th style="width:9rem">카카오톡</th>
					</tr>
				</thead>
				<tbody>
<?php
				$row_limit = 10;
				$jpage = isset( $_GET["jpage"] ) ? $_GET["jpage"] : 1;

				$joins = $event_db->select_joins( $_GET["id"], $row_limit, ($jpage-1)*$row_limit );
				$num_joins = $event_db->get_num_joins_byid( $_GET["id"] );
				if( !$joins ) {
					echo '<tr><td colspan="6" class="text-center">신청자가 없습니다.</td></tr>';
				} else {
					if ( is_array( $joins ) ) {
						foreach( $joins as $row ) {
?>
					<tr>
						<th scope="row"><?=$row["UserName"]?></th>
						<td><?=$row["UserUsername"]?></td>
						<td class="text-center"><img src="/src/school-logo/<?=$row["UserSchool"]?>.png" style="width:1.2rem;"></td>
						<td><?=$row["UserPhoneNo"]?></td>
						<td><?=$row["UserEmail"]?></td>
						<td><?=$row["UserKakaoID"]?></td>
					</tr>
<?php
						}
					}
				}
?>
				<tbody>
			</table>
<?php
			include 'templates/comp/pagination.php';
			CREATE_PAGINATION( $num_joins, $row_limit, $jpage, "/?page=event&status=$status&action=view&id=$_GET[id]", "jpage" );

		}
?>
		</div>
	</div>

	<div class="form-group row">
		<div class="col-10 offset-2">
			<div class="btn-group float-right">
				<a href="/?page=event&status=<?=$status?>&action=edit&id=<?=$event["EventID"]?>" class="btn btn-secondary">수정</a>
<?php
				if( $event["EventInviteEnable"] == 2 )
					echo "<a href='/?page=event&status=$status&action=closeinvite&id=$event[EventID]' class='btn btn-info'>신청마감</a>";
				if( $event["EventStatus"] == 1 )
					echo "<a href='/?page=event&status=$status&action=close&id=$event[EventID]' class='btn btn-warning'>이벤트종료</a>";
?>
				<a href="/?page=event&status=<?=$status?>&action=delete&id=<?=$event["EventID"]?>" class="btn btn-danger">삭제</a>
			</div>
		</div>
	</div>
</form>
<script>

	var map;
	function initMap() {
		var centerLatLng = {lat: <?=$event["EventLatitude"]?>, lng: <?=$event["EventLongitude"]?>};

		map = new google.maps.Map(document.getElementById('map'), {
			center: centerLatLng,
			zoom: 17,
			clickableIcons: false,
			mapTypeControl: false,
			streetViewControl: false
		});

		marker = new google.maps.Marker({
			map: map,
			position: centerLatLng
		});

	}

</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDxxKlLaGarkklQArn8j_Ihif5z5jwZRu0&language=en&callback=initMap" async defer></script>
