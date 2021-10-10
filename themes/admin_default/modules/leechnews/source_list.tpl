<!-- BEGIN: main -->
<!-- BEGIN: no_curl -->
	<div class="alert alert-warning"><strong>{LANG.not_curl}</strong></div>
<!-- END: no_curl -->

<a href="{SOURCE_UPDATE}" class="btn btn-success" style="margin-bottom: 15px;">{LANG.add_source}</a>
<div id="module_show_source">
	{MAIN_SOURCE_LIST}
</div>
<div id="leechnews_result" class="row info info-success"></div>
<script>
var source_cat_id = '{catid}';
$('button.task_close').click(function () {
	$('#running').fadeOut(200, "linear");
});
</script>

<div id="running">
	<p class="inside">
		<i class="fa fa-4x fa-spinner fa-spin" aria-hidden="true"></i>
	</p>
	<button class="task_close">X</button>
</div>
<!-- END: main -->
