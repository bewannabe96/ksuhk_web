<?php
	function CREATE_PAGINATION( $num_items, $item_per_page, $current, $location, $get_name ) {
		$total_pages = (int)( ($num_items - 1) / $item_per_page ) + 1;
		$link = $location . '&' . $get_name . '=';

		$partition = (int)( ( $current - 1 ) / 5 );
		$total_partitions = (int)( ( $total_pages - 1 ) / 5 );

		$min_page = $partition*5+1;
		$max_page = $partition*5+5;
		$max_page = $max_page > $total_pages ? $total_pages : $max_page;
?>
		<nav class="d-flex justify-content-end">
			<ul class="pagination pagination-sm my-0">
				<li class="page-item <?php if($partition==0) { echo "disabled"; } ?>">
					<?php echo '<a class="page-link" href="' . $link . ($min_page-5) . '">이전</a>'; ?>
				</li>
<?php
				for( $i = $min_page; $i <= $max_page; $i++ ) {
					echo '<li class="page-item ';
					if( $i == $current ) { echo 'active'; }
					echo '"><a class="page-link" href="' . $link . $i . '">' . $i . '</a></li>';
				}
?>
				<li class="page-item <?php if($partition==$total_partitions) { echo "disabled"; } ?>">
					<?php echo '<a class="page-link" href="' . $link . ($max_page+1) . '">다음</a>'; ?>
				</li>
			</ul>
		</nav>
<?php
	}
?>
