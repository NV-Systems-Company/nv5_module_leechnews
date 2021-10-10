<!-- BEGIN: main -->
<!-- BEGIN: data -->
<form class="navbar-form" name="block_list" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}=source_cat">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<thead>
				<tr>
					<th class="text-center"><input name="check_all[]" type="checkbox" value="yes" onclick="nv_checkAll(this.form, 'proxy_idcheck[]', 'check_all[]',this.checked);" /></th>
					<th class="text-center">{LANG.stt}</th>
					<th class="text-center">{LANG.weight}</th>
					<th class="text-center">{LANG.proxy_ip}</th>
					<th class="text-center">{LANG.proxy_port}</th>
					<th class="text-center">{LANG.status}</th>
					<th class="text-center">{LANG.functional}</th>
				</tr>
			</thead>
			<tbody>
				<!-- BEGIN: loop -->
				<tr>
					<td class="text-center"><input type="checkbox" onclick="nv_UncheckAll(this.form, 'proxy_idcheck[]', 'check_all[]', this.checked);" value="{ROW.proxy_id}" name="proxy_idcheck[]" /></td>
					<td class="text-center">
					<!-- BEGIN: stt -->
					<strong>{STT}</strong>
					<!-- END: stt -->
					</td>
					<td class="text-center">
					<!-- BEGIN: weight -->
					<select class="form-control" id="id_weight_{ROW.proxy_id}" onchange="nv_change_proxy('{ROW.proxy_id}','weight');">
						<!-- BEGIN: loop -->
						<option value="{WEIGHT.key}" {WEIGHT.selected}>{WEIGHT.title}</option>
						<!-- END: loop -->
					</select>
					<!-- END: weight -->
					</td>
					
					<td><strong>{ROW.proxy_ip}</strong></td>
					
					<td>{ROW.proxy_port}</td>
					
					<td class="text-center">
					<!-- BEGIN: status -->
					<select class="form-control" id="id_status_{ROW.proxy_id}" onchange="nv_change_proxy('{ROW.proxy_id}','status');">
						<!-- BEGIN: loop -->
						<option value="{STATUS.key}" {STATUS.selected}>{STATUS.title}</option>
						<!-- END: loop -->
					</select>
					<!-- END: status -->
					</td>
					
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
						<input type="button" class="btn btn-primary" onclick="nv_proxy_action(this.form, '{NV_CHECK_SESSION}', '{LANG.msgnocheck}')" value="{LANG.action}" />
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
<div class="row alert alert-info">Chưa có Nhóm nguồn tin nào</div>
<!-- END: no_data -->

<!-- END: main -->