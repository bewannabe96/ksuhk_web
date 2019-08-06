<?php

	include 'classes/BuyandsellDBManager.php';
	$buyandsell_db = BuyandsellDBManager::createBuyandsellDBManager();

	$category = isset( $_GET["category"] ) ? $_GET["category"] : 0;
	$query = isset( $_GET["query"] ) ? $_GET["query"] : "";

	$row_limit = 10;
	$bpage = isset( $_GET["bpage"] ) ? $_GET["bpage"] : 1;

	if( $buyandsell_db !== BuyandsellDBManager::$CONNECT_ERROR ) {
		if( $status == "report" ) {
			$bases_array = $buyandsell_db->select_report_bases( $row_limit, ($bpage-1)*$row_limit );
			$num_bases = $buyandsell_db->get_num_report_bases();
		} else {
			$search_result = $buyandsell_db->search_bases( $category, $query, $row_limit, ($bpage-1)*$row_limit );
			$num_bases = $search_result[0];
			$bases_array = $search_result[1];
		}
	} else {
		echo '<script>alert( "데이터베이스를 조회할 수 없습니다." );</script>';
	}

?>
<div class="d-flex align-items-center flex-row-reverse m-3 mx-sm-0">
<?php
	if( $status != "report" ) {
?>
		<form id="search-form" class="d-flex" method="get">
			<select class="form-control mr-1" name="category">
				<option value="0">분류</option>
				<option value="2" <?php if(isset($_GET["category"])&&$_GET["category"]==2){echo "selected";} ?>>의류</option>
				<option value="3" <?php if(isset($_GET["category"])&&$_GET["category"]==3){echo "selected";} ?>>전자제품</option>
				<option value="4" <?php if(isset($_GET["category"])&&$_GET["category"]==4){echo "selected";} ?>>생활용품</option>
				<option value="5" <?php if(isset($_GET["category"])&&$_GET["category"]==5){echo "selected";} ?>>학용품/사무용품</option>
				<option value="6" <?php if(isset($_GET["category"])&&$_GET["category"]==6){echo "selected";} ?>>운동용품/악기</option>
				<option value="7" <?php if(isset($_GET["category"])&&$_GET["category"]==7){echo "selected";} ?>>재능나눔</option>
				<option value="1" <?php if(isset($_GET["category"])&&$_GET["category"]==1){echo "selected";} ?>>기타</option>
			</select>
			<input name="page" value="buy-and-sell" hidden>
			<input id="search-word" name="query" hidden>
			<input id="search-word-temp" name="search" class="form-control mr-1" type="text" placeholder="검색" value="<?php if(isset($_GET["search"])){echo $_GET["search"];} ?>">
			<button class="btn btn-outline-success" type="submit">검색</button>
		</form>
<?php
	}
?>
	<script>
		document.getElementById('search-form').onsubmit = function() {
			document.getElementById('search-word').value
				= '%' + document.getElementById('search-word-temp').value.split('').join('%') + '%';
		}
	</script>
</div>
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
	if( !$bases_array ) {
		echo '<tr><td colspan="3" class="text-center">검색결과가 없습니다.</td></tr>';

	} else {
		foreach( $bases_array as $row ) {
?>
		<tr onclick="location.href = '/?page=buy-and-sell&action=view&id=<?=$row["BASID"]?>'">
			<td scope="row">
				<a href="/?page=buy-and-sell&action=view&id=<?=$row["BASID"]?>"><?=$row["BASTitle"]?></a>
			</td>
			<td>
				<?=$row["UserName"]?> (<?=$row["UserUsername"]?>)
			</td>
			<td><?=$row["BASPostdate"]?></td>
		</tr>
<?php
		}
	}
?>
	</tbody>
</table>
<?php
	include 'templates/comp/pagination.php';
	CREATE_PAGINATION( $num_bases, $row_limit, $bpage, "/?page=buy-and-sell&status=$status", "bpage" );
?>
