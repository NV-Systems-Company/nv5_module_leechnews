<!-- BEGIN: main -->

<form class="navbar-form" name="block_list" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}=source_cat">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<thead>
				<tr>
					<th class="text-center"><input name="check_all[]" type="checkbox" value="yes" onclick="nv_checkAll(this.form, 'idcheck[]', 'check_all[]',this.checked);" /></th>
					<th class="text-center">{LANG.title}</th>
					<th class="text-center">{LANG.sample_block}</th>
					<th class="text-center">{LANG.logs_added_time}</th>
					<th class="text-center">{LANG.logs_job_status}</th>
				</tr>
			</thead>
			<tbody>
				<!-- BEGIN: loop -->
				<tr {ROW.class}>
					<td class="text-center"><input type="checkbox" onclick="nv_UncheckAll(this.form, 'idcheck[]', 'check_all[]', this.checked);" value="{ROW.id}" name="idcheck[]" /></td>
					<td>
						<strong>{ROW.title}</strong>&nbsp;
						<a href="{ROW.news_url}" title="{LANG.article_status_source}" target="_blank"><i class="fa fa-external-link" aria-hidden="true"></i></a>&nbsp;-
						<a href="{ROW.preview_link}" title="{LANG.preview_report}" class="ls-modal"><i class="fa fa-eye"></i></a>
					</td>
					<td class="text-center">{ROW.source_cat_title}</td>
					<td class="text-center">{ROW.addtime}</td>
					<td class="text-center">{ROW.status}</td>
				</tr>
				<!-- END: loop -->
			</tbody>
			<tfoot>
				<tr class="text-left">
					<td colspan="12">
						<select class="form-control" name="action" id="action">
							<!-- BEGIN: action -->
							<option value="{ACTION.value}">{ACTION.title}</option>
							<!-- END: action -->
						</select>
						<input type="button" class="btn btn-primary" onclick="nv_reports_action(this.form, '{NV_CHECK_SESSION}', '{LANG.msgnocheck}')" value="{LANG.action}" />
					</td>
				</tr>
			</tfoot>
		</table>
	</div>
</form>
<!-- BEGIN: generate_page -->
<div class="text-center">
	{GENERATE_PAGE}
</div>
<!-- END: generate_page -->
<script>
var curr_page = "{CUR_PAGE}";
var nv_moderate_confirm = "{LANG.moderate_confirm}";
</script>

<!-- END: main -->
<!-- BEGIN: no_data -->
<div class="row alert alert-info">{LANG.no_reports}</div>
<!-- END: no_data -->
