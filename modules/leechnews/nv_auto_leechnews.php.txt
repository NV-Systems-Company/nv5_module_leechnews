<?php

/**
 * @Project LEECHNEWS ON NUKEVIET 4.x
 * @Author KENNYNGUYEN (nguyentiendat713@gmail.com)
 * @License GNU/GPL version 2 or any later version
 * @Createdate 19 July 2016 16:00 GMT+7
 */
if (! defined('NV_MAINFILE') or ! defined('NV_IS_CRON')) {
    die('Stop!!!');
}

function cron_auto_leechnews()
{
	global $db, $db_config;
	
	if (! defined('LEECHNEWS_4X___CRON')) {
		define ('LEECHNEWS_4X___CRON', true);
	}
	
	$modlang_leechnews = array();
	// Get all language that installed
	$result = $db->query("SELECT lang FROM " . $db_config['prefix'] . "_setup_language WHERE setup=1");
	while (list ($_tmp) = $result->fetch(PDO::FETCH_NUM)) {
		$modlang_leechnews[$_tmp] = array(
			"lang" => $_tmp,
			"mod" => array()
		);
		
		// Get all module
		$result1 = $db->query("SELECT title, module_data, module_file, module_upload FROM " . $db_config['prefix'] . "_" . $_tmp . "_modules WHERE module_file='leechnews' AND act=1;");
		while (list ($module_title, $module_data, $module_file, $module_upload) = $result1->fetch(PDO::FETCH_NUM)) {
			$modlang_leechnews[$_tmp]['mod'][] = array(
				"module_title" => $module_title,
				"module_file" => $module_file,
				"module_data" => $module_data,
				"module_upload" => $module_upload
			);
		}
	}
	
	foreach ($modlang_leechnews as $lang => $array_mod) {
		foreach ($array_mod['mod'] as $module_info) {
			$prefix = $db_config['prefix'] . "_" . $lang;
			$table_prefix = $prefix . "_" . $module_info['module_data'];
			$_source_sch = $db->query("SELECT id, cron_lastrun, cron_schedule FROM " . $db_config['prefix'] . "_" . $lang . "_" . $module_info['module_data'] . "_source WHERE cron_set=1 AND status=1 AND cron_schedule > 0 ORDER BY cron_lastrun ASC LIMIT 0,1;");
			while (list ($id, $cron_lastrun, $cron_schedule) = $_source_sch->fetch(PDO::FETCH_NUM)) {
				if ($id > 0 AND $cron_schedule > 0){
					$to_run = $cron_lastrun + ($cron_schedule * 3600);
					if( $to_run < NV_CURRENTTIME){
						require_once (NV_ROOTDIR . "/modules/" . $module_info['module_file'] . "/global.functions.php");
						nv_auto_leechnews($id, $table_prefix, $prefix, $module_info['module_upload'], $module_info['module_file'], $module_info['module_title'], $lang);
					}
				}
			}
		}
	}
    return true;
}