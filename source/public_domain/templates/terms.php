<div class="row">
	<div class="col-lg-10 offset-lg-1">
		<form id="terms-form" method="post" action=".">
		<div class="card">
			<h4 class="card-header">이용약관</h4>
			<div class="card-block pa-2" style="height: 13rem; overflow-y: scroll;">
			</div>
			<div class="card-footer">
				<label class="custom-control custom-checkbox">
					<input type="checkbox" class="custom-control-input" id="box-1" name="terms[]">
					<span class="custom-control-indicator"></span>
					<span class="custom-control-description">위의 '이용약관'에 동의합니다.</span>
				</label>
			</div>
		</div>
		<div class="card mt-3">
			<h4 class="card-header">개인정보보호정책</h4>
			<div class="card-block pa-2" style="height: 13rem; overflow-y: scroll;">
			</div>
			<div class="card-footer">
				<label class="custom-control custom-checkbox">
					<input type="checkbox" class="custom-control-input" id="box-2" name="terms[]">
					<span class="custom-control-indicator"></span>
					<span class="custom-control-description">위의 '개인정보보호취급방침'에 동의합니다.</span>
				</label>
			</div>
		</div>
		<button type="submit" class="btn btn-primary col-xs-4 offset-xs-8 col-sm-3 offset-sm-9 mt-2">다음</button>
		</form>
	</div>
</div>

<script>
	document.getElementById('terms-form').onsubmit = function() {
		if(!document.getElementById('box-1').checked) {
			document.getElementById('box-1').select();
			createAlert("'이용약관'에 동의해 주세요.");
			return false;
		} else if(!document.getElementById('box-2').checked) {
			document.getElementById('box-2').select();
			createAlert("'개인정보보호정책'에 동의해 주세요.");
			return false;
		}
	}
</script>
