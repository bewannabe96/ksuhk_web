<div id="alert-div" class="col-sm-8 col-lg-6 offset-sm-2 offset-lg-3 fixed-bottom mb-5"></div>
<script>
	function createAlert(msg, heading) {
		heading = heading || '경고!';
		document.getElementById('alert-div').innerHTML = '<div class="alert alert-danger alert-dismissible fade show" role="alert">'
				+ '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
				+ '<span class="fa fa-times" aria-hidden="true"></span>'
				+ '</button>'
				+ '<strong><span class="fa fa-warning"></span> ' + heading + '</strong> ' + msg
				+ '</div>';
	}

	function createAttention(msg, heading) {
		heading = heading || '주의!';
		document.getElementById('alert-div').innerHTML = '<div class="alert alert-warning alert-dismissible fade show" role="alert">'
				+ '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
				+ '<span class="fa fa-times" aria-hidden="true"></span>'
				+ '</button>'
				+ '<strong><span class="fa fa-warning"></span> ' + heading + '</strong> ' + msg
				+ '</div>';
	}

	function createInfo(msg, heading) {
		heading = heading || '알림!';
		document.getElementById('alert-div').innerHTML = '<div class="alert alert-info alert-dismissible fade show" role="alert">'
				+ '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
				+ '<span class="fa fa-times" aria-hidden="true"></span>'
				+ '</button>'
				+ '<strong><span class="fa fa-lightbulb-o"></span> ' + heading + '</strong> ' + msg
				+ '</div>';
	}
</script>
