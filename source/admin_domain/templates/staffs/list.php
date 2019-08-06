<?php

	include 'classes/StaffDBManager.php';
	$staff_db = StaffDBManager::createStaffDBManager();

	if( $staff_db !== StaffDBManager::$CONNECT_ERROR ) {
		$staffs_array = $staff_db->select_all_staffs();
		$num_staffs = $staff_db->get_num_staffs();
	} else {
		echo '<script>alert( "데이터베이스를 조회할 수 없습니다." );</script>';
	}

?>

<table class="table table-hover" width="100%" style="font-size: 0.7rem;">
	<thead class="thead-inverse">
		<tr>
			<th style="width:6rem"></th>
			<th style="width:5rem">이름</th>
			<th style="width:8rem">영문이름</th>
			<th>직책</th>
			<th class="text-center" style="width:3rem">학교</th>
			<th style="width:6rem">연락처</th>
			<th style="width:13rem">이메일</th>
			<th class="text-right" colspan="2">
				<a href="/?page=staffs&action=new&staffsnum=<?=$num_staffs?>"><span class="fa fa-plus" aria-hidden="true"></span> 추가</a>
			</th>
		</tr>
	</thead>
	<tbody>

<?php
	if( !$staffs_array ) {
		echo '<tr><td colspan="8" class="text-center">임원진 데이터가 없습니다.</td></tr>';

	} else {
		foreach( $staffs_array as $row ) {
?>
			<tr style="height:5rem">
				<th scope="row">
<?php
				if( isset( $row["StaffImage"] ) )
					echo '<img src="/src/image.php?hash=' . $row["StaffImage"] . '" class="rounded-circle" style="width:5rem; height:5rem;">';
				else
					echo '<img src="/src/placeholder.jpeg" class="rounded-circle" style="width:5rem; height:5rem;">';
?>
				</th>
				<th class="align-middle"><?=$row["StaffName"]?></th>
				<td class="align-middle"><?=$row["StaffEngName"]?></td>
				<td class="align-middle"><?=$row["StaffPosition"]?></td>
				<td class="align-middle text-center">
					<img src="/src/school-logo/<?=$row["StaffSchool"]?>.png" style="width:2rem;">
				</td>
				<td class="align-middle"><?=$row["StaffPhoneNo"]?></td>
				<td class="align-middle"><?=$row["StaffEmail"]?></td>
				<th style="width:9rem" class="text-center align-middle">
					<a href="/?page=staffs&action=edit&id=<?=$row["StaffID"]?>" class="mr-3">
						<span class="fa fa-pencil" aria-hidden="true"></span> 수정
					</a>
					<a href="/?page=staffs&action=delete&id=<?=$row["StaffID"]?>&image=<?=$row["StaffImage"]?>&priority=<?=$row["priority"]?>">
						<span class="fa fa-trash" aria-hidden="true"></span> 삭제
					</a>
				</th>
				<th style="width:1rem" class="text-right align-middle">
					<a <?php if($row["priority"]!=1) { ?>href="/?page=staffs&action=move&direction=up&priority=<?=$row["priority"]?>"<?php } ?>>
						<span class="fa fa-chevron-up fa-lg mb-3" aria-hidden="true"></span>
					</a>
					<a <?php if($row["priority"]!=$num_staffs) { ?>href="/?page=staffs&action=move&direction=down&priority=<?=$row["priority"]?>"<?php } ?>>
						<span class="fa fa-chevron-down fa-lg" aria-hidden="true"></span>
					</a>
				</th>
			</tr>
<?php
		}
	}
?>
	</tbody>
</table>
