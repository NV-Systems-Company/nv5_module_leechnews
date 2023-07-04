<?php

/**
 * @Project FEEDNEWS ON NUKEVIET 4.x
 * @Author KENNYNGUYEN (nguyentiendat713@gmail.com)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 08/25/2015 10:27
 */
if( !defined( 'NV_IS_FILE_ADMIN' ) )
	die( 'Stop!!!' );

$page_title = $lang_module['setting'];

$savesetting = $nv_Request->get_int( 'savesetting', 'post', 0 );
if( !empty( $savesetting ) )
{
	$array_config = array( );
	$array_config['post_type'] = $nv_Request->get_title( 'post_type', 'post', 'direct', 1 );
	$array_config['thumb_position'] = $nv_Request->get_int( 'thumb_position', 'post', 2 );
	$array_config['logo_position'] = $nv_Request->get_title( 'logo_position', 'post' );
	$array_config['st_links'] = $nv_Request->get_int( 'st_links', 'post', 10 );
	if( $array_config['st_links'] == 0 )
	{
		$array_config['st_links'] = 20;
	}
	$array_config['hometext'] = $nv_Request->get_int( 'hometext', 'post', 100 );
	if( $array_config['hometext'] == 0 )
	{
		$array_config['hometext'] = 100;
	}
	$array_config['prune_records'] = $nv_Request->get_int( 'prune_records', 'post', 10 );
	$array_config['force_set_time_limit'] = $nv_Request->get_int( 'force_set_time_limit', 'post', 0 );
	$array_config['using_proxy'] = $nv_Request->get_int( 'using_proxy', 'post', 0 );
	$array_config['proxy_limit_port'] = $nv_Request->get_title( 'proxy_limit_port', 'post', 0 );
	$array_config['proxy_limit_country'] = $nv_Request->get_title( 'proxy_limit_country', 'post', 0 );
	$array_config['sleep_timer'] = $nv_Request->get_int( 'sleep_timer', 'post', 0 );
	$array_config['set_time_limit'] = $nv_Request->get_int( 'set_time_limit', 'post', 3 );
	$array_config['cron_user'] = $nv_Request->get_int( 'cron_user', 'post', 1 );
	if( $array_config['cron_user'] == 0 )
	{
		$array_config['cron_user'] = 1;
	}

	$array_config['module_logo'] = $nv_Request->get_title( 'module_logo', 'post', '', 0 );
	$array_config['autologosize1'] = $nv_Request->get_int( 'autologosize1', 'post', 50 );
	$array_config['autologosize2'] = $nv_Request->get_int( 'autologosize2', 'post', 40 );
	$array_config['autologosize3'] = $nv_Request->get_int( 'autologosize3', 'post', 30 );

	if( !nv_is_url( $array_config['module_logo'] ) and file_exists( NV_DOCUMENT_ROOT . $array_config['module_logo'] ) )
	{
		$lu = strlen( NV_BASE_SITEURL );
		$array_config['module_logo'] = substr( $array_config['module_logo'], $lu );
	}
	elseif( !nv_is_url( $array_config['module_logo'] ) )
	{
		$array_config['module_logo'] = $global_config['site_logo'];
	}

	$sth = $db->prepare( "UPDATE " . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = '" . NV_LANG_DATA . "' AND module = :module_name AND config_name = :config_name" );
	$sth->bindParam( ':module_name', $module_name, PDO::PARAM_STR );
	foreach( $array_config as $config_name => $config_value )
	{
		$sth->bindParam( ':config_name', $config_name, PDO::PARAM_STR );
		$sth->bindParam( ':config_value', $config_value, PDO::PARAM_STR );
		$sth->execute( );
	}

	$nv_Cache->delMod( 'settings' );
	$nv_Cache->delMod( $module_name );
	Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&rand=' . nv_genpass( ) );
	die( );
}

$xtpl = new XTemplate( "setting.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'OP', $op );

$module_logo = (isset( $module_config[$module_name]['module_logo'] )) ? $module_config[$module_name]['module_logo'] : '';
$module_logo = (!nv_is_url( $module_logo ) && !empty( $module_config[$module_name]['module_logo'] )) ? NV_BASE_SITEURL . $module_logo : $module_logo;

$xtpl->assign( 'DATA', $module_config[$module_name] );
$xtpl->assign( 'MODULE_LOGO', $module_logo );
$xtpl->assign( 'PATH', !defined( 'NV_IS_SPADMIN' ) ? '' : NV_UPLOADS_DIR . '/' . $module_upload );
$xtpl->assign( 'CURRENTPATH', !defined( 'NV_IS_SPADMIN' ) ? 'images' : NV_UPLOADS_DIR . '/' . $module_upload );

if( $module_config[$module_name]['force_set_time_limit'] > 0 )
{
	$xtpl->assign( 'FORCE_SET_TIME_LIMIT', 'checked="checked"' );
}

if( $module_config[$module_name]['using_proxy'] > 0 )
{
	$xtpl->assign( 'USING_PROXY', 'checked="checked"' );
}

$xtpl->assign( 'SLEEP_TIMER', $module_config[$module_name]['sleep_timer'] );
$xtpl->assign( 'PROXY_LIMIT_PORT', $module_config[$module_name]['proxy_limit_port'] );
$xtpl->assign( 'SET_TIME_LIMIT', $module_config[$module_name]['set_time_limit'] );
$xtpl->assign( 'PRUNE_RECORDS', $module_config[$module_name]['prune_records'] );
$xtpl->assign( 'HOMETEXT', $module_config[$module_name]['hometext'] );

$array_post_type = array(
	1 => $lang_module['post_type_1'],
	2 => $lang_module['post_type_2']
);
foreach( $array_post_type as $key => $val )
{
	$xtpl->assign( 'POST_TYPE', array(
		'key' => $key,
		'title' => $val,
		'selected' => $key == $module_config[$module_name]['post_type'] ? ' selected="selected"' : ''
	) );
	$xtpl->parse( 'main.post_type' );
}

$array_imgposition = array(
	0 => $lang_module['imgposition_0'],
	1 => $lang_module['imgposition_1'],
	2 => $lang_module['imgposition_2']
);

// Vi tri anh minh hoa
foreach ($array_imgposition as $key => $val) {
        $xtpl->assign('imgpos', array(
            'value' => $key,
            'title' => $val,
            'selected' => $key == $module_config[$module_name]['thumb_position'] ? ' selected="selected"' : ''
        ));
        $xtpl->parse( 'main.looppos' );
}


$array_logoposition = array(
	'' => $lang_module['upload_logo_pos'],
	'bottom_right' => $lang_module['logoposbottomright'],
	'bottom_left' => $lang_module['logoposbottomleft'],
	'bottom_center' => $lang_module['logoposbottomcenter'],
	'center_right' => $lang_module['logoposcenterright'],
	'center_left' => $lang_module['logoposcenterleft'],
	'center_center' => $lang_module['logoposcentercenter'],
	'top_right' => $lang_module['logopostopright'],
	'top_left' => $lang_module['logopostopleft'],
	'top_center' => $lang_module['logopostopcenter']
);

// Vi tri Logo
foreach ($array_logoposition as $key => $val) {
        $xtpl->assign('posl', array(
            'value' => $key,
            'title' => $val,
            'selected' => $key == $module_config[$module_name]['logo_position'] ? ' selected="selected"' : ''
        ));
        $xtpl->parse( 'main.logopos' );
}

// Bai viet chi hien thi link
for( $i = 1; $i <= 100; ++$i )
{
	$xtpl->assign( 'ST_LINKS', array(
		'key' => $i,
		'title' => $i,
		'selected' => $i == $module_config[$module_name]['st_links'] ? ' selected="selected"' : ''
	) );
	$xtpl->parse( 'main.st_links' );
}
// User assign lay tin tu dong
$db_slave->sqlreset( )->select( 'userid, username' )->from( NV_USERS_GLOBALTABLE )->where( 'active=1' );
$array_userid = array( );
$result = $db_slave->query( $db_slave->sql( ) );
$array_users = $result->fetchAll( );
foreach( $array_users as $user_i )
{
	if( $user_i['userid'] == $module_config[$module_name]['cron_user'] )
	{
		$user_i['selected'] = 'selected="selected"';
	}
	else
	{
		$user_i['selected'] = '';
	}
	$xtpl->assign( 'USER', $user_i );
	$xtpl->parse( 'main.cron_user' );
}
if( $sys_info['allowed_set_time_limit'] )
{
	$xtpl->parse( 'main.set_time_limit' );
}

if( file_exists( NV_ROOTDIR . '/modules/' . $module_file . '/nv_auto_leechnews.php.txt' ) )
{

	$cronfile_status = $cron_status = $lang_module['setting_cron_notexisted'];
	if( file_exists( NV_ROOTDIR . '/includes/cronjobs/nv_auto_leechnews.php' ) )
	{
		//
		$cronfile_status = $lang_module['setting_cron_existed'];
		$xtpl->parse( 'main.generate_cron.note1' );
	}

	$check_cronsql = nv_leech_news_checkcron( );
	if( $check_cronsql )
	{
		$cron_status = $lang_module['setting_cron_existed'];
		$xtpl->parse( 'main.generate_cron.note2' );
	}
	$xtpl->assign( 'CRONFILE_STATUS', $cronfile_status );
	$xtpl->assign( 'CRON_STATUS', $cron_status );
	$xtpl->parse( 'main.generate_cron' );
}
$xtpl->assign( 'USER_ID', $admin_info['userid'] );

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );
include (NV_ROOTDIR . "/includes/header.php");
echo nv_admin_theme( $contents );
include (NV_ROOTDIR . "/includes/footer.php");
