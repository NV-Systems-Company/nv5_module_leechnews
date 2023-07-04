<!-- BEGIN: main -->
<!-- BEGIN: show_not_installed -->
<a class="btn btn-info" href="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}=sourcecat_temp" title="{LANG.all_sample}"><em class="fa fa-download fa-lg">&nbsp;</em><strong>{LANG.all_sample}</strong></a>&nbsp;
<!-- END: show_not_installed -->
<!-- BEGIN: show_all -->
<a class="btn btn-primary" href="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}=sourcecat_temp&sort=not_installed" title="{LANG.sample_source} {LANG.temp_not_installed}"><em class="fa fa-cogs fa-lg">&nbsp;</em><strong>{LANG.sample_source} {LANG.temp_not_installed}</strong></a>&nbsp;
<!-- END: show_all -->
<a class="btn btn-success" href="#" title="{LANG.temp_download_all}" onclick="nv_download_source_cat('0', 'export_templ_all');"><em class="fa fa-download fa-lg">&nbsp;</em><strong>{LANG.temp_download_all}</strong></a>
<form class="navbar-form" name="block_list" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}=source_cat">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<thead>
				<tr>
					<th class="text-center">{LANG.stt}</th>
					<th class="text-center"><a href="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}=sourcecat_temp&sort=title">{LANG.title}</a></th>
					<th class="text-center">{LANG.host_url}</th>
					<th class="text-center">{LANG.functional}</th>
					<th class="text-center"><a href="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}=sourcecat_temp&sort=install_status">{LANG.install_status}</a></th>
				</tr>
			</thead>
			<tbody>
				<!-- BEGIN: loop -->
				<tr>
					<td class="text-center">
						<!-- BEGIN: stt -->
						<strong>{STT}</strong>
						<!-- END: stt -->
					</td>
					<td><strong>{INI.title}</strong>&nbsp;<em>({INI.file_name})</em></td>
					<td><a href="{INI.host_url}">{INI.host_url}</a></td>
					<td class="text-center">{INI.adminfuncs}</td>
					<td class="text-center">{INI.install_status}</td>
				</tr>
				<!-- END: loop -->
			</tbody>
		</table>
	</div>
</form>
<!-- END: main -->
<!-- BEGIN: no_data -->
<div class="row alert alert-info">{LANG.no_ini_files}</div>
<!-- END: no_data -->