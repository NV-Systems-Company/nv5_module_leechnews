<?php

/**
 * @Project FEEDNEWS ON NUKEVIET 4.x
 * @Author KENNYNGUYEN (nguyentiendat713@gmail.com)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 07/30/2013 10:27
 */
if( !defined( 'NV_ADMIN' ) )
	die( 'Stop!!!' );

$allow_func = array(
	'main',
	'source',
	'source_cat',
	'source_list',
	'source_mod',
	'get',
	'list_source_cat',
	'list_proxy',
	'list_source',
	'sourcecat_temp',
	'logs',
	'moderate_news',
	'del_log',
	'list_logs',
	'reports',
	'del_report',
	'list_reports',
	'setting',
	'proxy'
);

$submenu['source_cat'] = $lang_module['source_cat'];
$submenu['source_list'] = $lang_module['source_list'];
$submenu['source'] = $lang_module['add_source'];
$submenu['logs'] = $lang_module['logs'];
$submenu['reports'] = $lang_module['reports'];
$submenu['sourcecat_temp'] = $lang_module['sourcecat_temp'];
$submenu['proxy'] = $lang_module['proxy'];
$submenu['setting'] = $lang_module['setting'];
if( defined( 'NV_IS_MODADMIN' ) )
{
	$allow_func[] = 'change_source_cat';
	$allow_func[] = 'change_proxy';
	$allow_func[] = 'change_source';
	$allow_func[] = 'del_source_cat';
	$allow_func[] = 'del_proxy';
	$allow_func[] = 'del_source';
}
