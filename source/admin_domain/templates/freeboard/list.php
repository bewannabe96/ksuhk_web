<?php

	include 'classes/FreeboardDBManager.php';
	$freeboard_db = FreeboardDBManager::createFreeboardDBManager();

	$row_limit = 10;
	$fpage = isset( $_GET["fpage"] ) ? $_GET["fpage"] : 1;

	if( $freeboard_db !== FreeboardDBManager::$CONNECT_ERROR ) {
		if( $status == "report" ) {
			$posts_array = $freeboard_db->select_report_posts( $row_limit, ($fpage-1)*$row_limit );
			$num_posts = $freeboard_db->get_num_report_posts();
		} else {
			$posts_array = $freeboard_db->select_posts( $row_limit, ($fpage-1)*$row_limit );
			$num_posts = $freeboard_db->get_num_posts();
		}
	} else {
		echo '<script>alert( "데이터베이스를 조회할 수 없습니다." );</script>';
	}

?>

<table class="table table-hover" style="font-size: 0.5rem;">
	<thead class="thead-inverse">
		<tr>
			<th>제목</th>
			<th style="width:10rem">게시자</th>
			<th style="width:8rem">게시일</th>
			</th>
		</tr>
	</thead>
	<tbody>
<?php
	if( !$posts_array ) {
		echo '<tr><td colspan="3" class="text-center">검색결과가 없습니다.</td></tr>';

	} else {
		foreach( $posts_array as $row ) {
?>
		<tr onclick="location.href = '/?page=freeboard&action=view&id=<?=$row["PostID"]?>'">
			<td scope="row">
				<a href="/?page=freeboard&action=view&id=<?=$row["PostID"]?>"><?=$row["PostTitle"]?></a>
			</td>
			<td>
				<?=$row["UserName"]?> (<?=$row["UserUsername"]?>)
			</td>
			<td><?=$row["PostPostdate"]?></td>
		</tr>
<?php
		}
	}
?>
	</tbody>
</table>
<?php
	include 'templates/comp/pagination.php';
	CREATE_PAGINATION( $num_posts, $row_limit, $fpage, "/?page=freeboard&status=$status", "fpage" );
?>
