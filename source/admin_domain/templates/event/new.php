<?php

	if( isset( $_POST["submit"] ) ) {
		include 'classes/EventDBManager.php';
		$event_db = EventDBManager::createEventDBManager();

		if( $event_db !== EventDBManager::$CONNECT_ERROR ) {
			$cost = $_POST["EventCurrency"]==0 ? 0 : $_POST["EventCost"];
			$max_invite = $_POST["maxInvite"]==1 ? $_POST["EventMaxInvite"] : 0;
			$invite = isset( $_POST["inviteEnable"] ) ? 2 : 1;

			$result = $event_db->insert_new_event( $_POST["EventTitle"], "$_POST[EventStartDate] $_POST[EventStartTime]",
					"$_POST[EventEndDate] $_POST[EventEndTime]", $_POST["EventAddress"], $_POST["EventLatitude"], $_POST["EventLongitude"],
					$_POST["EventCurrency"], $cost, $max_invite, $invite, $_POST["EventContent"], $_FILES["EventImage"] );

			switch( $result ) {
				case EventDBManager::$INSERT_ERROR :
					echo '<script>alert( "게시글 게시 중 오류가 발생했습니다." );</script>';
					break;
				case EventDBManager::$CONNECT_ERROR :
					echo '<script>alert( "데이터베이스를 연결할 수 없습니다." );</script>';
					echo "<script>location.href = '/?page=event&status=$status'</script>";
					break;
				case EventDBManager::$INVALID_SIZE :
					echo '<script>alert( "파일 크기를 확인해 주세요." );</script>';
					break;
				case EventDBManager::$INVALID_TYPE :
					echo '<script>alert( "파일 종류를 확인해 주세요." );</script>';
					break;
				default :
					echo '<script>alert( "게시글이 성공적으로 게시되었습니다." );</script>';
					echo "<script>location.href = '/?page=event&status=$status'</script>";
			}
		} else {
			echo '<script>alert( "데이터베이스를 연결할 수 없습니다." );</script>';
			echo "<script>location.href = '/?page=event&status=$status'</script>";
		}
	}

?>

<form id="event-form" method="post" enctype="multipart/form-data">
	<div class="form-group row mt-5">
		<label class="col-2 col-form-label text-right">제목</label>
		<div class="col-10">
			<input class="form-control" type="text" placeholder="제목" id="title-input" name="EventTitle" value="<?php if(isset($_POST["EventTitle"])){echo $_POST["EventTitle"];} ?>" maxlength="60" autocomplete="off">
		</div>
	</div>

	<div class="form-group row">
		<label class="col-2 col-form-label text-right">일정</label>
		<label class="col-2 col-form-label text-right text-muted">시작</label>
		<div class="col-4">
			<input class="form-control" type="date" name="EventStartDate" value="<?php if(isset($_POST["EventStartDate"])){echo $_POST["EventStartDate"];} ?>">
		</div>
		<div class="col-4">
			<input class="form-control" type="time" name="EventStartTime" value="<?php if(isset($_POST["EventStartTime"])){echo $_POST["EventStartTime"];}else{echo "00:00";} ?>">
		</div>
	</div>

	<div class="form-group row">
		<label class="col-2 offset-2 col-form-label text-right text-muted">종료</label>
		<div class="col-4">
			<input class="form-control" type="date" name="EventEndDate" value="<?php if(isset($_POST["EventEndDate"])){echo $_POST["EventEndDate"];} ?>">
		</div>
		<div class="col-4">
			<input class="form-control" type="time" name="EventEndTime" value="<?php if(isset($_POST["EventEndTime"])){echo $_POST["EventEndTime"];}else{echo "00:00";} ?>">
		</div>
	</div>

	<div class="form-group row">
		<label class="col-2 col-form-label text-right">장소</label>
		<div class="col-10">
			<div class="input-group">
				<input class="form-control" type="text" placeholder="주소" id="addr-input" name="EventAddress" value="<?php if(isset($_POST["EventAddress"])){echo $_POST["EventAddress"];} ?>" autocomplete="off">
				<input type="text" id="lat-input" name="EventLatitude" value="<?php if(isset($_POST["EventLatitude"])){echo $_POST["EventLatitude"];} ?>" hidden>
				<input type="text" id="lng-input" name="EventLongitude" value="<?php if(isset($_POST["EventLongitude"])){echo $_POST["EventLongitude"];} ?>" hidden>
				<span class="input-group-btn">
					<button class="btn btn-info" id="addr-search-btn" type="button">검색</button>
				</span>
			</div>
		</div>
	</div>

	<div class="form-group row">
		<div class="col-10 offset-2">
			<div id="map" class="card card-block rounded" style="height:25rem;"></div>
			<small class="form-text text-muted">마커를 클릭해서 정확한 주소를 가져오세요.</small>
		</div>
	</div>

	<div class="form-group row">
		<label class="col-2 col-form-label text-right">참가비</label>
		<div class="col-2">
			<div class="input-group w-100">
				<span class="input-group-addon"><input type="radio" name="EventCurrency" value="0" checked></span>
				<span class="input-group-addon">무료</span>
			</div>
		</div>
		<div class="col-5">
			<div class="input-group">
				<span class="input-group-addon"><input type="radio" name="EventCurrency" value="1" <?php if(isset($_POST["EventCurrency"])&&$_POST["EventCurrency"]==1){echo "checked";} ?>></span>
				<span class="input-group-addon"><span class="fa fa-dollar" aria-hidden="true"></span></span>
				<span class="input-group-addon"><input type="radio" name="EventCurrency" value="2" <?php if(isset($_POST["EventCurrency"])&&$_POST["EventCurrency"]==2){echo "checked";} ?>></span>
				<span class="input-group-addon"><span class="fa fa-krw" aria-hidden="true"></span></span>
				<input class="form-control" type="number" id="cost-input-krw" name="EventCost" value="<?php if(isset($_POST["EventCost"])){echo $_POST["EventCost"];} ?>">
			</div>
		</div>
	</div>

	<div class="form-group row">
		<label class="col-2 col-form-label text-right">정원</label>
		<div class="col-2">
			<div class="input-group w-100">
				<span class="input-group-addon"><input type="radio" name="maxInvite" value="0" checked></span>
				<span class="input-group-addon">없음</span>
			</div>
		</div>
		<div class="col-4">
			<div class="input-group">
				<span class="input-group-addon"><input type="radio" name="maxInvite" value="1" <?php if(isset($_POST["maxInvite"])&&$_POST["maxInvite"]==1){echo "checked";} ?>></span>
				<input class="form-control" type="number" id="maxinvite-input" name="EventMaxInvite" value="<?php if(isset($_POST["EventMaxInvite"])){echo $_POST["EventMaxInvite"];} ?>">
				<span class="input-group-addon">명</span>
			</div>
		</div>
		<div class="col-4 text-muted">
			<label class="form-check-label">
				<input type="checkbox" class="form-check-input" name="inviteEnable"> 온라인 신청 받기
			</label>
		</div>
	</div>

	<div class="form-group row">
		<label class="col-2 col-form-label text-right">사진</label>
		<div class="col-10">
			<input id="file-input" type="file" class="form-control" name="EventImage">
			<small class="form-text text-muted">2MB 이하의 이미지 파일(PNG/JPG/JPEG)</small>
		</div>
	</div>

	<div class="form-group row">
		<label class="col-2 col-form-label text-right">본문</label>
		<div class="col-10">
			<textarea class="form-control" id="content-textarea" name="EventContent" rows="15"><?php if(isset($_POST["EventContent"])){echo $_POST["EventContent"];} ?></textarea>
		</div>
	</div>


	<div class="form-group row mb-5">
		<div class="col-10 offset-2 text-right">
			<div class="btn-group">
				<button type="button" class="btn btn-secondary" onclick="location.href = '/?page=event&status=<?=$status?>'">취소</button>
				<button type="submit" name="submit" class="btn btn-primary">작성</button>
			</div>
		</div>
	</div>
</form>

<script>

	var map;
	function initMap() {
		map = new google.maps.Map(document.getElementById('map'), {
			center: {lat: 22.33, lng: 114.107},
			zoom: 10,
			clickableIcons: false,
			mapTypeControl: false,
			streetViewControl: false
		});

		var searchInput = document.getElementById('addr-input');
		var latInput = document.getElementById('lat-input');
		var lngInput = document.getElementById('lng-input');
		var searchBtn = document.getElementById('addr-search-btn');

		var markers = [];
		var bounds;
		var marker;

<?php
		if( isset( $_POST["EventLatitude"] ) ) {
?>
			var viewCenter = {lat: <?=$_POST["EventLatitude"]?>, lng: <?=$_POST["EventLongitude"]?>};
			marker = new google.maps.Marker({
				map: map,
				position: viewCenter
			});
			map.setCenter(viewCenter);
			map.setZoom(16);
<?php
		}
?>

		// create markers from places
		function createMarker(places) {
			if (places.length == 0) { return; }

			markers.forEach( function(marker) { marker.setMap(null); });
			markers = [];

			bounds = new google.maps.LatLngBounds();
			marker;
			places.forEach( function(place) {
				marker = new google.maps.Marker({
					map: map,
					position: place.geometry.location
				});

				marker.addListener('click', function() {
					searchInput.value = place.formatted_address;
					latInput.value = place.geometry.location.lat();
					lngInput.value = place.geometry.location.lng();
				});

				markers.push(marker);

				if (place.geometry.viewport)
					bounds.union(place.geometry.viewport);
				else
					bounds.extend(place.geometry.location);

			});

			map.fitBounds(bounds);
			if(places.length == 1) {
				map.setZoom(16);
				map.setCenter(places[0].geometry.location);
			}
		}
			

		// search event listen
		service = new google.maps.places.PlacesService(map);
		google.maps.event.addDomListener(searchBtn, 'click', function() {
			var request = {
				query: searchInput.value
			};

			function searchCallback(places, status) {
				if (status == google.maps.places.PlacesServiceStatus.OK) {
					createMarker(places);
				}
			}

			service.textSearch(request, searchCallback);
		});

		// place changed event listen
		var searchBox = new google.maps.places.SearchBox(searchInput);
		searchBox.addListener('places_changed', function() {
			createMarker(searchBox.getPlaces());
		});

	}


</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDxxKlLaGarkklQArn8j_Ihif5z5jwZRu0&language=en&libraries=places&callback=initMap" async defer></script>
<script>
	document.getElementById('event-form').onsubmit = function() {
		var files = document.getElementById('file-input').files;
		if(files.length!=0) {
			if(files[0].size > 2097152) {
				alert('파일 용량이 2MB보다 큽니다.');
				return false;
			}
		}
		return confirm("저장하시겠습니까?");
	}
</script>
