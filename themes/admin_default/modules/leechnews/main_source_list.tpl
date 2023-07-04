<!-- BEGIN: main -->
<!-- BEGIN: data -->
<form class="navbar-form" name="block_list" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<thead>
				<tr>
					<th class="text-center"><input name="check_all[]" type="checkbox" value="yes" onclick="nv_checkAll(this.form, 'idcheck[]', 'check_all[]',this.checked);" /></th>
					<th class="text-center">{LANG.stt}</th>
					<th class="text-center">{LANG.sourcecat_name}</th>
					<th class="text-center">{LANG.sample_sourcecat}</th>
					<th class="text-center">{LANG.status}</th>
					<th class="text-center">{LANG.sample_schedule}</th>
					<th class="text-center">{LANG.sample_schedule_set}</th>
					<th class="text-center">{LANG.sample_schedule_lastrun}</th>
					<th class="text-center">{LANG.functional}</th>
				</tr>
			</thead>
			<tbody>
				<!-- BEGIN: loop -->
				<tr>
					<td class="text-center"><input type="checkbox" onclick="nv_UncheckAll(this.form, 'idcheck[]', 'check_all[]', this.checked);" value="{ROW.id}" name="idcheck[]" /></td>
					<td class="text-center">
						<!-- BEGIN: stt -->
						<strong>{STT}</strong>
						<!-- END: stt -->
					</td>
					<td><strong>{ROW.title}</strong></td>
					<td class="text-center"><strong><a href="{ROW.bycat_link}" title="{ROW.cat_title}">{ROW.cat_title}</a></strong></td>
					<td class="text-center">
					<select class="form-control" id="id_status_{ROW.id}" onchange="nv_change_source('{ROW.id}','status');">
						<!-- BEGIN: status -->
						<option value="{STATUS.key}" {STATUS.selected}>{STATUS.title}</option>
						<!-- END: status -->
					</select>
					</td>
					<td class="text-center">
					<select class="form-control" id="id_cron_set_{ROW.id}" onchange="nv_change_source('{ROW.id}','cron_set');">
						<!-- BEGIN: cron_set -->
						<option value="{CRON_SET.key}" {CRON_SET.selected}>{CRON_SET.title}</option>
						<!-- END: cron_set -->
					</select>
					</td>
					<td class="text-center">
					<select class="form-control" id="id_cron_schedule_{ROW.id}" onchange="nv_change_source('{ROW.id}','cron_schedule');">
						<!-- BEGIN: cron_schedule -->
						<option value="{CRON_SCHEDULE.key}" {CRON_SCHEDULE.selected}>{CRON_SCHEDULE.title}</option>
						<!-- END: cron_schedule -->
					</select>
					</td>
					<td class="text-center">{ROW.cron_lastrun}</td>
					<td class="text-center">{ROW.adminfuncs}</td>
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
						<input type="button" class="btn btn-primary" onclick="nv_sourcelist_action(this.form, '{NV_CHECK_SESSION}', '{LANG.msgnocheck}')" value="{LANG.action}" />
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
</script>
<!-- END: data -->

<!-- BEGIN: no_data -->
<div class="row alert alert-info">{LANG.no_sample}</div>
<!-- END: no_data -->

<!-- BEGIN: show_all -->
<a href="{SHOWALL}" class="btn btn-info">{LANG.all_sample}</a>
<!-- END: show_all -->
<!-- END: main -->