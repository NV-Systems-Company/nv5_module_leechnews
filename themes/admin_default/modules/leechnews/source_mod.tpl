<!-- BEGIN: main -->
<!-- BEGIN: catid -->
<div class="form-group row">
	<label class="col-sm-4 control-label"><strong>Chuyên mục Lưu tin</strong></label>
	<div class="col-lg-12 col-md-12 col-sm-12 sel_target_cat">
		<table class="table table-striped table-bordered table-hover">
			<thead>
				<th>Chuyên mục</th>
				<th>Chuyên mục chính</th>
			</thead>
			<tbody>
				<!-- BEGIN: loop -->
				<tr>
					<td><input style="margin-left: {CATS.space}px;" type="checkbox" value="{CATS.catid}" name="target_catids[]" class="news_checkbox" {CATS.checked} {CATS.disabled}> {CATS.title} </td>
					<td><input id="catright_{CATS.catid}" style="{CATS.catiddisplay}" type="radio" name="target_catid" title="{LANG.content_checkcat}" value="{CATS.catid}" {CATS.catidchecked}/></td>
				</tr>
			<!-- END: loop -->
			</tbody>
		</table>
	</div>
</div>
<!-- END: catid -->

<!-- BEGIN: groups -->
<div class="form-group row">
	<label class="col-sm-4 control-label"><strong>Chọn nhóm tin</strong></label>
	<div class="col-lg-20 col-md-20 col-sm-20">
		<!-- BEGIN: loop -->
			<p><input type="checkbox" value="{BLOCK.bid}" name="target_block_ids[]" class="groups_id" {BLOCK.checked}> {BLOCK.title} </p>
		<!-- END: loop -->
	</div>
</div>
<!-- END: groups -->

<script>
// Select Cat and MultiCat
$(document).ready(function() {
	$("input[name='target_catids[]']").click(function() {
		var catid = $("input:radio[name=target_catid]:checked").val();
		var radios_catid = $("input:radio[name=target_catid]");
		var target_catids = [];
		$("input[name='target_catids[]']").each(function() {
			if ($(this).prop('checked')) {
				$("#catright_" + $(this).val()).show();
				target_catids.push($(this).val());
			} else {
				$("#catright_" + $(this).val()).hide();
				if ($(this).val() == catid) {
					radios_catid.filter("[value=" + catid + "]").prop("checked", false);
				}
			}
		});

		if (target_catids.length > 1) {
			for ( i = 0; i < target_catids.length; i++) {
				$("#catright_" + target_catids[i]).show();
			};
			catid = parseInt($("input:radio[name=catid]:checked").val() + "");
			if (!catid) {
				radios_catid.filter("[value=" + target_catids[0] + "]").prop("checked", true);
			}
		}
	});
});
</script>
<!-- END: main -->