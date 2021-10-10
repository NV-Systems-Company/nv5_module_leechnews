<!-- BEGIN: main -->
<div id="module_show_proxy">
	{PROXY_LIST}
</div>
<!-- BEGIN: error -->
<div class="alert alert-danger">{ERROR}</div>
<!-- END: error -->

<div id="content">
	<a name="edit"></a>
	<form class="source_form" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" enctype="multipart/form-data" method="post">
		<div class="row">
			<div class="form-group row clear">
				<label class="col-sm-4 control-label"><strong>{LANG.proxy_ip}</strong></label>
				<div class="col-lg-18 col-md-18 col-sm-18"><input name="proxy_ip" type="text" id="proxy_ip" class="form-control" value="{ITEM.proxy_ip}"/></div>
			</div>
			<div class="form-group row">
				<label class="col-sm-4 control-label"><strong>{LANG.proxy_port}</strong></label>
				<div class="col-lg-18 col-md-18 col-sm-18"><input name="proxy_port" type="text" id="proxy_port" class="form-control" value="{ITEM.proxy_port}"/></div>
			</div>
			<div class="form-group row">
				<label class="col-sm-4 control-label"><strong>{LANG.proxy_username}</strong></label>
				<div class="col-lg-18 col-md-18 col-sm-18"><input name="proxy_username" type="text" id="proxy_username" class="form-control" value="{ITEM.proxy_username}"/></div>
			</div>
			<div class="form-group row">
				<label class="col-sm-4 control-label"><strong>{LANG.proxy_password}</strong></label>
				<div class="col-lg-18 col-md-18 col-sm-18"><input name="proxy_password" type="text" id="proxy_password" class="form-control" value="{ITEM.proxy_password}"/></div>
			</div>
			<div class="form-group row">
				<label class="col-sm-4 control-label"><strong>{LANG.proxy_type}</strong></label>
				<div class="col-lg-18 col-md-18 col-sm-18"><input name="proxy_type" type="text" id="proxy_type" class="form-control" value="{ITEM.proxy_type}" {no_edit}/></div>
			</div>
			<div class="form-group row">
				<label class="col-sm-4 control-label"><strong>{LANG.proxy_country}</strong></label>
				<div class="col-lg-18 col-md-18 col-sm-18"><input name="proxy_country" type="text" id="proxy_country" class="form-control" value="{ITEM.proxy_country}" {no_edit}/></div>
			</div>
			
			<div class="form-group row">
				<label class="col-sm-4 control-label"><strong>Trạng thái kích hoạt</strong></label>
				<div class="col-lg-3 col-md-3 col-sm-3">
				<select class="form-control" id="status" name="status">
					<!-- BEGIN: status -->
					<option value="{STATUS.key}" {STATUS.selected}>{STATUS.title}</option>
					<!-- END: status -->
				</select>
				</div>
			</div>
		</div>
		<div class="text-center">
			<br/>
			<input type="hidden" value="1" name="save" />
			<input type="hidden" value="{ITEM.proxy_id}" name="proxy_id" />
			<input class="btn btn-primary submit-post" name="statussave" type="submit" value="{LANG.save}" />
		</div>
	</form>
</div>
<!-- END: main -->
