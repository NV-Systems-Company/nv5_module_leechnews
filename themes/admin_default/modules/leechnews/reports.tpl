<!-- BEGIN: main -->
<div id="show_reports">
{SHOW_REPORTS}
</div>
<div id="running">
	<p class="inside">
		<i class="fa fa-4x fa-spinner fa-spin" aria-hidden="true"></i>
	</p>
	<button class="task_close">X</button>
</div>

<div id="previewModal" class="modal fade">
<div class="modal-dialog">
    <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><strong>{LANG.preview_report}</strong></h4>
            </div>
            <div class="modal-body">
                <p>Loading...</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{LANG.preview_report_close}</button>
            </div>
    </div>
</div>
<script>
$('.ls-modal').on('click', function(e){
  e.preventDefault();
  $('#previewModal').modal('show').find('.modal-body').load($(this).attr('href'));
});
$('button.task_close').click(function () {
	$('#running').fadeOut(200, "linear");
});
</script>
<!-- END: main -->