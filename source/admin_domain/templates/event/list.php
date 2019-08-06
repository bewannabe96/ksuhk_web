<?php

	include 'classes/EventDBManager.php';
	$event_db = EventDBManager::createEventDBManager();

	$row_limit = 10;
	$epage = isset( $_GET["epage"] ) ? $_GET["epage"] : 1;

	if( $event_db !== EventDBManager::$CONNECT_ERROR ) {
		switch( $status ) {
			case "ongoing":
				$events_array = $event_db->select_events_bystatus( 1, $row_limit, ($epage-1)*$row_limit );
				$num_events = $event_db->get_num_events_bystatus( 1 );
				break;

			case "closed":
				$events_array = $event_db->select_events_bystatus( 2, $row_limit, ($epage-1)*$row_limit );
				$num_events = $event_db->get_num_events_bystatus( 2 );
				break;

			default:
				$events_array = $event_db->select_events_bystatus( 0, $row_limit, ($epage-1)*$row_limit );
				$num_events = $event_db->get_num_events_bystatus( 0 );
		}
	} else {
		echo '<script>alert( "데이터베이스를 조회할 수 없습니다." );</script>';
	}

?>

<table class="table table-hover" style="font-size: 0.5rem;">
	<thead class="thead-inverse">
		<tr>
			<th style="width:4rem">상황</th>
			<th>제목</th>
			<th style="width:6.5rem">게시일</th>
			<th style="width:6.5rem">수정일</th>
			<th style="width:4rem"></th>
			<th style="width:5rem" class="text-right">
				<a href="/?page=event&status=<?=$status?>&action=new">
					<span class="fa fa-plus" aria-hidden="true"></span> 글쓰기
				</a>
			</th>
		</tr>
	</thead>
	<tbody>
<?php
	if( !$events_array ) {
		echo '<tr><td colspan="6" class="text-center">검색결과가 없습니다.</td></tr>';

	} else {
		foreach( $events_array as $row ) {
?>
		<tr onclick="location.href = '/?page=event&status=<?=$status?>&action=view&id=<?=$row["EventID"]?>'">
<?php
			switch( $row["EventStatus"] ) {
				case 1 :
					echo '<td scope="row"><span class="badge badge-success">진행중</span></td>';
					break;
				default :
					echo '<td scope="row"><span class="badge badge-default">종료</span></td>';
			}
?>
			<th><a href="/?page=event&status=<?=$status?>&action=view&id=<?=$row["EventID"]?>"><?=$row["EventTitle"]?></a></th>
			<td><?=$row["EventPostdate"]?></td>
			<td><?=$row["EventUpdate"]?></td>
			<td class="text-right">
				<a href="/?page=event&status=<?=$status?>&action=edit&id=<?=$row["EventID"]?>">
					<span class="fa fa-pencil" aria-hidden="true"></span> 수정
				</a>
			</td>
			<td class="text-right">
				<a href="/?page=event&status=<?=$status?>&action=delete&id=<?=$row["EventID"]?>">
					<span class="fa fa-trash" aria-hidden="true"></span> 삭제
				</a>
			</td>
		</tr>
<?php
		}
	}
?>
	</tbody>
</table>
<?php
	include 'templates/comp/pagination.php';
	CREATE_PAGINATION( $num_events, $row_limit, $epage, "/?page=event&status=$status", "epage" );
?>
