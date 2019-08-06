<style>
	.img-name {
		text-overflow:ellipsis;
		overflow:hidden;
		white-space:nowrap;
	}
</style>
<div class="card">
	<div class="form-group card-header p-0 p-sm-3">
		<div class="row px-4 py-2 py-lg-0">
			<div class="col-sm-9 col-md-10 p-0">
				<input type="file" id="file-select-input" class="form-control-file">
				<small class="form-text text-muted">
					사진은 <?=$BAS_MAX_IMG_UPLOAD?>장까지 업로드 가능합니다. (장당 최대 2MB)
				</small>
			</div>
			<button type="button" id="upload-btn" class="btn btn-info col-sm-3 col-md-2 mt-2 mt-sm-0 disabled">업로드</button>
		</div>
	</div>
	<div class="progress">
		<div id="progressbar" class="progress-bar progress-bar-striped bg-info progress-bar-animated" style="width:0;"></div>
	</div>
	<div class="card-block p-0 pr-3">
		<div id="uploaded-img-div" class="d-flex" style="overflow-x:scroll;"></div>
		<label id="no-img-label" class="w-100 text-center p-3">업로드한 사진이 없습니다.</label>
	</div>
	<div class="card-footer text-right">
		<label class="m-0">총 <span id="img-count" class="text-info mx-1">0</span>장</label>
	</div>
</div>
<?php include $WEB_ROOT.'/templates/popup-message.php'; ?>
<script>
	var imgCount = 0;
	var isSubmit = false;
	var fileSelect = document.getElementById('file-select-input');
	var progressBar = document.getElementById('progressbar');

	fileSelect.onchange = function() {
		var file = fileSelect.files[0];

		progressBar.style.width = '0%';
		progressBar.innerHTML = '';
		document.getElementById('upload-btn').className = 'btn btn-info col-sm-3 col-md-2 mt-2 mt-sm-0';

		if(file.size >= 2097152) {
			createAlert('파일의 크기를 다시 확인해주세요.');
			fileSelect.value = '';
		} else if(!fileSelect.files[0].type.match('image')) {
			createAlert('파일의 종류를 다시 확인해주세요.');
			fileSelect.value = '';
		}
	}

	window.onbeforeunload = function(){
		if(!isSubmit) {
			//confirm("작성된 내용과 업로드된 사진은 모두 지워집니다. 나가시겠습니까?")
			var formData = new FormData();
			formData.append('UserID', <?=$_SESSION["user_id"]?>);

			var xhttp = new XMLHttpRequest();
			xhttp.open("POST", "/buy-and-sell/write/upload-image.php?action=delete&scope=all", true);
			xhttp.send(formData);
		}
	};

	function deleteImage(image_hash) {
		progressBar.style.width = '0%';
		progressBar.innerHTML = '';

		var formData = new FormData();
		var actionUrl = "/buy-and-sell/write/upload-image.php?action=delete&scope=hash&hash=" + image_hash;
		formData.append('UserID', <?=$_SESSION["user_id"]?>);

		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function() {
			if (this.readyState == 4) {
				if (this.status == 200) {
					if(this.responseText == 1) {
						document.getElementById(image_hash).remove();
						imgCount -= 1;
						document.getElementById('img-count').innerHTML = imgCount;
						if(imgCount <= 0)
							document.getElementById('no-img-label').style.display = '';
						progressBar.style.width = '100%';
						progressBar.className = 'progress-bar progress-bar-striped bg-warning';
						progressBar.innerHTML = '삭제됨';
					} else {
						createAlert('사진을 삭제하는 중 요류가 발생했습니다.');
					}
				} else {
					createAlert('사진을 삭제하는 중 요류가 발생했습니다.');
				}
			}
		};
		xhttp.open("POST", actionUrl, true);
		xhttp.send(formData);
	}

	document.getElementById('upload-btn').onclick = function() {
		var uploadBtn = this;

		if(imgCount >= <?=$BAS_MAX_IMG_UPLOAD?>) {
			createAlert('이미지는 최대 <?=$BAS_MAX_IMG_UPLOAD?>장까지 업로드가 가능합니다.');
			uploadBtn.className = 'btn btn-info col-sm-3 col-md-2 mt-2 mt-sm-0 disabled';
			fileSelect.value = '';

		} else if(fileSelect.files.length == 1) {
			var file = fileSelect.files[0];

			fileSelect.value = '';
			progressBar.className = 'progress-bar progress-bar-striped bg-danger';

			var formData = new FormData();
			formData.append('UserID', <?=$_SESSION["user_id"]?>);
			formData.append('TempImage', file);

			var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function() {
				var imgCardHTML = '';
				if (this.readyState == 4) {
					if (this.status == 200) {
						switch(this.responseText) {
							case '-1':
							case '-2':
							case '-4':
							case '-5':
								if(this.responseText == -1)
									createAlert('이미지는 최대 <?=$BAS_MAX_IMG_UPLOAD?>장까지 업로드가 가능합니다.');
								else if(this.responseText == -4)
									createAlert('파일의 크기 및 종류를 다시 확인해주세요.');
								else
									createAlert('사진을 업로드하는 중 요류가 발생했습니다.');
								progressBar.className = 'progress-bar progress-bar-striped bg-danger';
								progressBar.innerHTML = '업로드 실패 (' + file.name + ')';
								break;
							default:
								imgCardHTML += '<div id="' + this.responseText + '" ';
								imgCardHTML += 'class="card m-3">';
								imgCardHTML += '<input name="images[]" value="' + this.responseText + '" hidden>';
								imgCardHTML += '<div class="card-header d-flex py-2">';
								imgCardHTML += '<label class="img-name m-0">' + file.name + '</label></div>';
								imgCardHTML += '<img class="card-img-bottom" style="height:15rem" src="';
								imgCardHTML += '/src/buy-and-sell-image.php?basid=temp&hash=';
								imgCardHTML += this.responseText + '" style="height:10rem;"/>';
								imgCardHTML += '<div class="card-footer text-right py-2">';
								imgCardHTML += '<span class="fa fa-check text-success mr-3"></span>';
								imgCardHTML += '<button type="button" class="btn btn-link p-0" onclick="deleteImage(\'';
								imgCardHTML += this.responseText + '\')"><span class="fa fa-trash"></span>';
								imgCardHTML += '</button>';
								imgCardHTML += '</div></div>';
								document.getElementById('uploaded-img-div').innerHTML += imgCardHTML;
								imgCount += 1;
								document.getElementById('img-count').innerHTML = imgCount;
								if(imgCount > 0)
									document.getElementById('no-img-label').style.display = 'none';
								progressBar.className = 'progress-bar progress-bar-striped bg-success';
								progressBar.innerHTML = '업로드 완료 (' + file.name + ')';
						}
					} else {
						createAlert('사진을 업로드하는 중 요류가 발생했습니다.');
					}
				}

			};

			xhttp.upload.addEventListener("progress", function(e) {
				progressBar.style.width = Math.round((e.loaded / e.total) * 100) + '%';
				progressBar.innerHTML = Math.round((e.loaded / e.total) * 100) + '%';
				uploadBtn.className = 'btn btn-info col-sm-3 col-md-2 mt-2 mt-sm-0 disabled';

			}, false);

			xhttp.addEventListener("load", function(e) {
				progressBar.style.width = '100%';
			}, false);

			xhttp.addEventListener("error", function(e) {
				progressBar.style.width = '100%';
				progressBar.className = 'progress-bar progress-bar-striped bg-danger';
				progressBar.innerHTML = '업로드 실패 (' + file.name + ')';
			}, false);

			xhttp.open("POST", "/buy-and-sell/write/upload-image.php", true);
			xhttp.send(formData);
		}
	}
</script>