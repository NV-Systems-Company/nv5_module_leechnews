<!-- BEGIN: main -->
<link rel="stylesheet" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.css">
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.js"></script>
<!-- BEGIN: generate_cron -->
<div class="row">
	<div class="alert alert-info">
		<p><strong>{LANG.setting_cron}</strong></p>
		<ul>
			<li>{LANG.setting_cronfile_status}: <strong>{CRONFILE_STATUS}</strong><!-- BEGIN: note1 -->&nbsp;<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="bottom" title="{LANG.setting_create_cron_note1}"></i><!-- END: note1 --> </li>
			<li>{LANG.setting_cronjob_status}: <strong>{CRON_STATUS}</strong><!-- BEGIN: note2 -->&nbsp;<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="bottom" title="{LANG.setting_create_cron_note2}"></i><!-- END: note2 --> </li>
		</ul>
		<hr/>
		<span><a class="btn btn-primary" id="setting_create_cron" name="setting_create_cron" onclick="leechnews_cronfile('1');"/>{LANG.setting_create_cron}</a></span>&nbsp;
		<span><a class="btn btn-primary" id="setting_create_cron_submit" name="setting_create_cron_submit" onclick="leechnews_cronsql('1');"/>{LANG.setting_create_cron_submit}</a></span>
	</div>
</div>
<!-- END: generate_cron -->
<div class="row">
	<form class="form-inline" role="form" action="{NV_BASE_ADMINURL}index.php" method="post">
		<input type="hidden" name ="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
		<input type="hidden" name ="{NV_OP_VARIABLE}" value="{OP}" />
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover">
				<tbody>
					<tr>
						<th>{LANG.setting_st_links}</th>
						<td>
						<select class="form-control" id="st_links" class="select2" name="st_links">
							<option value="" disabled="disabled">-----</option>
							<!-- BEGIN: st_links -->
							<option value="{ST_LINKS.key}"{ST_LINKS.selected}>{ST_LINKS.title}</option>
							<!-- END: st_links -->
						</select>
						</td>
					</tr>
					<tr>
						<th>{LANG.setting_thumb_position}</th>
						<td>
							<select class="form-control" name="thumb_position">
								<!-- BEGIN: looppos -->
								<option value="{imgpos.value}" {imgpos.selected}>{imgpos.title}</option>
								<!-- END: looppos -->
							</select>
						</td>
					</tr>
					<tr>
						<th>{LANG.setting_post_type}</th>
						<td>
							<select class="form-control" name="post_type">
								<!-- BEGIN: post_type -->
								<option value="{POST_TYPE.key}"{POST_TYPE.selected}>{POST_TYPE.title}</option>
								<!-- END: post_type -->
							</select>
						</td>
					</tr>
					<tr>
						<th>{LANG.setting_hometext}</th>
						<td>
							<input class="form-control" name="hometext" value="{HOMETEXT}">
						</td>
					</tr>
					<tr>
						<th>{LANG.setting_prune_records}</th>
						<td>
							<input class="form-control" name="prune_records" value="{PRUNE_RECORDS}">&nbsp;{LANG.setting_save_day}&nbsp;<em>{LANG.setting_save_note}<em>
						</td>
					</tr>
					<tr>
						<th>{LANG.setting_cron_user}{LANG.assign_cron}</th>
						<td>
							<select class="form-control" id="cron_user" class="select2" name="cron_user">
								<option value="" disabled="disabled">{LANG.pick_user}</option>
								<!-- BEGIN: cron_user -->
								<option value="{USER.userid}"{USER.selected}>{USER.username}</option>
								<!-- END: cron_user -->
							</select>
							&nbsp;<span class="btn btn-primary" onclick="$('#cron_user').val('{USER_ID}').trigger('change');">{LANG.get_my_ID}</span>
						</td>
					</tr>
					<!-- BEGIN: set_time_limit -->
					<tr>
						<th>{LANG.setting_set_time_limit}</th>
						<td>
							<input class="form-control" type="checkbox" name="force_set_time_limit" value="1" {FORCE_SET_TIME_LIMIT}>
							<input class="form-control" name="set_time_limit" value="{SET_TIME_LIMIT}">&nbsp;{LANG.setting_minute}&nbsp;<em>{LANG.setting_set_time_limit_note}<em>
						</td>
					</tr>
					<!-- END: set_time_limit -->
					<tr>
						<th>{LANG.setting_using_proxy}</th>
						<td>
							<input class="form-control" type="checkbox" name="using_proxy" value="1" {USING_PROXY}>&nbsp;<em>({LANG.setting_using_proxy_note})</em>
						</td>
					</tr>
					<tr>
						<th>{LANG.setting_proxy_limit_port}&nbsp;<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="bottom" title="{LANG.setting_proxy_limit_port_note_1}"></i></th>
						<td>
							<input class="form-control" name="proxy_limit_port" value="{PROXY_LIMIT_PORT}">
						</td>
					</tr>
					<tr>
						<th>{LANG.setting_set_sleep_timer}&nbsp;<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="bottom" title="{LANG.setting_set_sleep_timer_note_1}"></i></th>
						<td>
							<input class="form-control" name="sleep_timer" value="{SLEEP_TIMER}">&nbsp;{LANG.setting_second}&nbsp;<em>{LANG.setting_set_sleep_timer_note_2}</em>
						</td>
					</tr>
					<tr>
						<th>{LANG.setting_logo}:</th>
						<td>
							<div class="form-inline">
							<div class="form-group fixgroup" style="margin-left: 0px;">
								<input class="form-control fixlogo" name="module_logo" id="module_logo" value="{MODULE_LOGO}" maxlength="255" type="text" />
							</div>
							&nbsp;<span class="btn btn-primary fixprimary" value="{GLANG.browse_image}" name="selectimg" type="button"><i class="fa fa-file-image-o" aria-hidden="true"></i></span>
							</div>
						</td>
					</tr>
					
					<tr>
						<th>{LANG.setting_autologosize1}:</th>
						<td>
							<span class="text-middle pull-left"> {LANG.setting_autologowidth} &nbsp;</span>
							<input type="text" class="form-control w50 pull-left" value="{DATA.autologosize1}" maxlength="2" name="autologosize1"><span class="text-middle">&nbsp; %  </span>
						</td>
					</tr>
					<tr>
						<th>{LANG.setting_autologosize2}:</th>
						<td>
							<span class="text-middle pull-left"> {LANG.setting_autologowidth} &nbsp;</span>
							<input type="text" class="form-control pull-left w50" value="{DATA.autologosize2}" maxlength="2" name="autologosize2"/><span class="text-middle pull-left">&nbsp; %  </span>
						</td>
					</tr>
					<tr>
						<th>{LANG.setting_autologosize3}:</th>
						<td>
							<span class="text-middle pull-left"> {LANG.setting_autologosample}&nbsp;</span>
							<input type="text" class="form-control pull-left w50" value="{DATA.autologosize3}" maxlength="2" name="autologosize3"/>&nbsp;<span class="text-middle pull-left">&nbsp; %  </span>
						</td>
					</tr>
					<tr>
						<th>{LANG.upload_logo_pos}</th>
						<td>
							<select class="form-control" name="logo_position">
								<!-- BEGIN: logopos -->
								<option value="{posl.value}" {posl.selected}>{posl.title}</option>
								<!-- END: logopos -->
							</select>
						</td>
					</tr>
				</tbody>
				<tfoot>
					<tr>
						<td class="text-center" colspan="2">
							<input class="btn btn-primary" type="submit" value="{LANG.save}" name="Submit1" />
							<input type="hidden" value="1" name="savesetting" />
						</td>
					</tr>
				</tfoot>
			</table>
		</div>
	</form>
</div>
<link rel="stylesheet" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.css">
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.js"></script>

<script type="text/javascript">
//<![CDATA[
$(document).ready(function() {
	$("#cron_user").select2();
	$("#st_links").select2();
});
//]]>
</script>
<script type="text/javascript">
$("span[name=selectimg]").click(function(){
	var area = "module_logo";
	var type= "image";
	var path= "{PATH}";
	var currentpath= "{CURRENTPATH}";
	nv_open_browse("{NV_BASE_ADMINURL}index.php?" + nv_name_variable + "=upload&popup=1&area=" + area + "&path=" + path + "&type=" + type + "&currentpath=" + currentpath, "NVImg", "850", "420","resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
	return false;
});
</script>
<!-- END: main -->