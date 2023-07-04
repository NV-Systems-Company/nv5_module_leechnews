<!-- BEGIN: main -->
<!-- BEGIN: error -->
<div class="alert alert-danger">{ERROR}</div>
<!-- END: error -->
<div id="content">
	<form class="source_form" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" enctype="multipart/form-data" method="post">
		<div class="row">

			<div class="form-group row">
				<label class="col-sm-4 control-label"><strong>{LANG.title}</strong></label>
				<div class="col-lg-16 col-md-16 col-sm-16">
					<input name="title" type="text" id="title" class="form-control" value="{ITEM.title}"/>
				</div>
			</div>

			<div class="form-group row">
				<label class="col-sm-4 control-label"><strong>{LANG.host_url}</strong></label>
				<div class="col-lg-16 col-md-16 col-sm-16">
					<input name="source_url" type="text" id="source_url" class="form-control" value="{ITEM.source_url}"/>
				</div>
			</div>

			<div class="form-group row">
				<label class="col-sm-4 control-label"><strong>{LANG.sample_sourcecat}</strong></label>
				<div class="col-lg-6 col-md-6 col-sm-6">
					<select class="form-control" id="catid" name="catid">
					<option value="">{LANG.pick_source_catid}</option>
					<!-- BEGIN: catid -->
					<option value="{CATS.catid}" {CATS.selected}>{CATS.title}</option>
					<!-- END: catid -->
					</select>
				</div>
			</div>

			<div class="form-group row">
				<label class="col-sm-4 control-label"><strong>{LANG.sample_module}</strong></label>
				<div class="col-lg-6 col-md-6 col-sm-6">
					<select class="form-control" id="target_module" name="target_module" onchange="nv_feednews_change_mod('{ITEM.id}');">
					<option value="">{LANG.pick_source_target_module}</option>
					<!-- BEGIN: source_mod -->
					<option value="{SOURCE_MOD.mod_data}" {SOURCE_MOD.selected}>{SOURCE_MOD.mod_name}</option>
					<!-- END: source_mod -->
					</select>
				</div>
			</div>
			<div id="target_cat_select"></div>
			
			<div class="form-group row jump_url">
				<label class="col-sm-4 control-label"><strong>{LANG.jump_structure}</strong></label>
				<div class="row col-lg-8 col-md-8 col-sm-8">
					<input name="source_jump_url" type="text" id="source_jump_url" class="form-control" value="{ITEM.source_jump_url}" placeholder="http://domain.com.vn/chuyen-muc/page-[PAGE_NUM].html"/>
				</div>
			</div>
			
			<div class="form-group row otherimage_limit">
				<label class="col-sm-4 control-label"><strong>{LANG.jump_limit}</strong></label>
				<div class="row col-lg-4 col-md-4 col-sm-4">
					<input name="source_jump_from" type="text" id="source_jump_from" class="form-control" value="{ITEM.source_jump_from}" placeholder="{LANG.jump_limit_from}"/>
				</div>
				
				<div class="row col-lg-4 col-md-4 col-sm-4 col-md-push-1">
					<input name="source_jump_to" type="text" id="source_jump_to" class="form-control" value="{ITEM.source_jump_to}" placeholder="{LANG.jump_limit_to}"/>
				</div>
			</div>
			
			<div class="form-group row">
				<label class="col-sm-4 control-label"><strong>{LANG.jump_auto_remove}</strong></label>
				<div class="col-lg-20 col-md-20 col-sm-20">
					<input name="source_clearpage_jump" type="checkbox" value="1" id="source_clearpage_jump" class="form-control" {source_clearpage_jump}/>
				</div>
			</div>
			
			<div class="form-group row">
				<label class="col-sm-4 control-label"><strong>{LANG.news_count}</strong></label>
				<div class="col-lg-4 col-md-4 col-sm-4">
					<input name="source_numget" type="number" id="source_numget" class="form-control" value="{ITEM.source_numget}"  />
				</div>
			</div>

			<div class="form-group row">
				<label class="col-sm-4 control-label"><strong>{LANG.job_leecher}</strong></label>
				<div class="col-lg-4 col-md-4 col-sm-4">
					<select class="form-control" id="cron_set" name="cron_set">
					<!-- BEGIN: cron_set -->
					<option value="{CRON_SET.key}" {CRON_SET.selected}>{CRON_SET.title}</option>
					<!-- END: cron_set -->
					</select>
				</div>
			</div>
			
			<div class="form-group row">
				<label class="col-sm-4 control-label"><strong>{LANG.sample_schedule_set}</strong></label>
				<div class="col-lg-4 col-md-4 col-sm-4">
					<select class="form-control" id="cron_schedule" name="cron_schedule">
					<!-- BEGIN: cron_schedule -->
					<option value="{CRON_SCHEDULE.key}" {CRON_SCHEDULE.selected}>{CRON_SCHEDULE.title} {LANG.source_hour}</option>
					<!-- END: cron_schedule -->
					</select>
				</div>
			</div>
			
			<div class="form-group row">
				<label class="col-sm-4 control-label"><strong>{LANG.status}</strong></label>
				<div class="col-lg-4 col-md-4 col-sm-4">
					<select class="form-control" id="status" name="status">
					<!-- BEGIN: status -->
					<option value="{STATUS.key}" {STATUS.selected}>{STATUS.title}</option>
					<!-- END: status -->
					</select>
				</div>
			</div>

			<div class="form-group row">
				<label class="col-sm-4 control-label"><strong>{LANG.source_moderate}</strong></label>
				<div class="col-lg-4 col-md-4 col-sm-4">
					<select class="form-control" id="source_moderate" name="source_moderate">
					<!-- BEGIN: source_moderate -->
					<option value="{SOURCE_MODERATE.key}" {SOURCE_MODERATE.selected}>{SOURCE_MODERATE.title}</option>
					<!-- END: source_moderate -->
					</select>
				</div>
			</div>

			<div class="form-group row">
				<label class="col-sm-4 control-label"><strong>{LANG.sample_generate_hometext}</strong></label>
				<div class="col-lg-4 col-md-4 col-sm-4">
					<input name="source_autohometext" type="checkbox" value="1" id="source_autohometext" class="form-control" {source_autohometext}/>
				</div>
			</div>
			
			<div class="form-group row hometext_limit">
				<label class="col-sm-4 control-label"><strong>{LANG.setting_hometext}</strong></label>
				<div class="col-lg-4 col-md-4 col-sm-4">
					<input name="source_hometext_limit" type="text" id="source_hometext_limit" class="form-control" value="{ITEM.source_hometext_limit}"/>
				</div>
			</div>
			
			<div class="form-group row">
				<label class="col-sm-4 control-label"><strong>{LANG.save_thumbnail}</strong></label>
				<div class="col-lg-20 col-md-20 col-sm-20">
					<input name="source_getthumb" type="checkbox" value="1" id="source_getthumb" class="form-control" {source_getthumb}/>
				</div>
			</div>
			
			<div class="form-group row">
				<label class="col-sm-4 control-label"><strong>{LANG.save_image}</strong></label>
				<div class="col-lg-20 col-md-20 col-sm-20">
					<input name="source_getimage" type="checkbox" value="1" id="source_getimage" class="form-control" {source_getimage}/>
				</div>
			</div>

			<div class="form-group row">
				<label class="col-sm-4 control-label"><strong>{LANG.source_active_logo}</strong>&nbsp;<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="bottom" title="{LANG.source_active_logo_note}"></i></label>
				<div class="col-lg-20 col-md-20 col-sm-20">
					<input name="source_img_stamp" type="checkbox" value="1" id="source_img_stamp" class="form-control" {source_img_stamp}/>
				</div>
			</div>

			<div class="form-group row">
				<label class="col-sm-4 control-label"><strong>{LANG.sample_get_keywords}</strong></label>
				<div class="col-lg-20 col-md-20 col-sm-20">
					<input name="source_gettags" type="checkbox" value="1" id="source_gettags" class="form-control" {source_gettags}/>
				</div>
			</div>

			<div class="form-group row">
				<label class="col-sm-4 control-label"><strong>{LANG.sample_generate_keywords}</strong></label>
				<div class="col-lg-20 col-md-20 col-sm-20">
					<input name="source_getkeywords" type="checkbox" value="1" id="source_getkeywords" class="form-control" {source_getkeywords}/>
				</div>
			</div>

			<div class="form-group row">
				<label class="col-sm-4 control-label"><strong>{LANG.sample_get_keywords_des}</strong></label>
				<div class="col-lg-20 col-md-20 col-sm-20">
					<input name="source_getkeywords_des" type="checkbox" value="1" id="source_getkeywords_des" class="form-control" {source_getkeywords_des}/>
				</div>
			</div>

			<div class="form-group row">
				<label class="col-sm-4 control-label"><strong>{LANG.sample_generate_thumb}</strong></label>
				<div class="col-lg-20 col-md-20 col-sm-20">
					<input name="source_getthumbs" type="checkbox" value="1" id="source_getthumbs" class="form-control" {source_getthumbs}/>
				</div>
			</div>

			<div class="form-group row">
				<label class="col-sm-4 control-label"><strong>{LANG.sample_anchor_only}</strong></label>
				<div class="col-lg-20 col-md-20 col-sm-20">
					<input name="source_clearlinks" type="checkbox" value="1" id="source_clearlinks" class="form-control" {source_clearlinks}/>
				</div>
			</div>
			
			<div class="form-group row">
				<label class="col-sm-4 control-label"><strong>{LANG.sample_keep_source}</strong></label>
				<div class="col-lg-20 col-md-20 col-sm-20">
					<input name="source_keep_source" type="checkbox" value="1" id="source_keep_source" class="form-control" {source_keep_source}/>
				</div>
			</div>
			
			<div class="form-group row">
				<label class="col-sm-4 control-label"><strong>{LANG.sample_cleanall_HTML}</strong></label>
				<div class="col-lg-20 col-md-20 col-sm-20">
					<input name="source_cleanup_html" type="checkbox" value="1" id="source_cleanup_html" class="form-control" {source_cleanup_html}/> <em>{LANG.sample_cleanall_HTML_note}</em>
				</div>
			</div>
            
			<div class="form-group row">
				<label class="col-sm-4 control-label"><strong>{LANG.sample_cleanall_IMG}</strong></label>
				<div class="col-lg-20 col-md-20 col-sm-20">
					<input name="source_cleanup_img" type="checkbox" value="1" id="source_cleanup_img" class="form-control" {source_cleanup_img}/> <em>{LANG.sample_cleanall_IMG_note}</em>
				</div>
			</div>

		</div>
		<div class="text-center">
			<br/>
			<input type="hidden" value="1" name="save" />
			<input type="hidden" value="{ITEM.id}" name="id" />
			<input class="btn btn-primary submit-post" name="statussave" type="submit" value="{LANG.save}" />
		</div>
	</form>
</div>
<script>
$(document).ready(function(){
	var id = '{ITEM.id}';
	var mod_data = $('#target_module').val();
	if (document.getElementById('target_cat_select')) {
		$('#target_cat_select').load(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=source_mod&mod_data=' + mod_data + '&id=' + id + '&nocache=' + new Date().getTime() );
	}
});
</script>
<!-- END: main -->
