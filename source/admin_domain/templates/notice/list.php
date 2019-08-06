<?php

	include 'classes/NoticeDBManager.php';
	$notice_db = NoticeDBManager::createNoticeDBManager();

	$row_limit = 10;
	$npage = isset( $_GET["npage"] ) ? $_GET["npage"] : 1;

	if( $notice_db !== NoticeDBManager::$CONNECT_ERROR ) {
		switch( $section ) {
			case "recruit":
				$posts_array = $notice_db->select_posts_bycategory( 2, $row_limit, ($npage-1)*$row_limit );
				$num_posts = $notice_db->get_num_posts_bycategory( 2 );
				break;

			case "lecture":
				$posts_array = $notice_db->select_posts_bycategory( 3, $row_limit, ($npage-1)*$row_limit );
				$num_posts = $notice_db->get_num_posts_bycategory( 3 );
				break;

			default:
				$posts_array = $notice_db->select_posts_bycategory( 1, $row_limit, ($npage-1)*$row_limit );
				$num_posts = $notice_db->get_num_posts_bycategory( 1 );
		}
	} else {
		echo '<script>alert( "데이터베이스를 조회할 수 없습니다." );</script>';
	}

?>

<table class="table table-hover" style="font-size: 0.5rem;">
	<thead class="thead-inverse">
		<tr>
			<th>제목</th>
			<th style="width:6.5rem">게시일</th>
			<th style="width:6.5rem">수정일</th>
			<th style="width:4rem"></th>
			<th style="width:5rem" class="text-right">
				<a href="/?page=notice&section=<?=$section?>&action=new">
					<span class="fa fa-plus" aria-hidden="true"></span> 글쓰기
				</a>
			</th>
		</tr>
	</thead>
	<tbody>
<?php
	if( !$posts_array ) {
		echo '<tr><td colspan="5" class="text-center">검색결과가 없습니다.</td></tr>';

	} else {
		foreach( $posts_array as $row ) {
?>
		<tr onclick="location.href = '/?page=notice&section=<?=$section?>&action=view&id=<?=$row["PostID"]?>'">
			<td scope="row">
				<a href="/?page=notice&section=<?=$section?>&action=view&id=<?=$row["PostID"]?>"><?=$row["PostTitle"]?></a>
			</td>
			<td><?=$row["PostPostdate"]?></td>
			<td><?=$row["PostUpdate"]?></td>
			<td class="text-right">
				<a href="/?page=notice&section=<?=$section?>&action=edit&id=<?=$row["PostID"]?>">
					<span class="fa fa-pencil" aria-hidden="true"></span> 수정
				</a>
			</td>
			<td class="text-right">
				<a href="/?page=notice&section=<?=$section?>&action=delete&id=<?=$row["PostID"]?>">
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
	CREATE_PAGINATION( $num_posts, $row_limit, $npage, "/?page=notice&section=$section", "npage" );
?>
