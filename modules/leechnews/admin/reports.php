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
$page_title = $lang_module['reports'];
$quick_view = $nv_Request->isset_request( 'quick_view', 'get, post' );
$view_id = $nv_Request->get_int( 'view_id', 'get, post', 0 );

if( $quick_view and $view_id > 0 )
{
	$xtpl = new XTemplate( "reports_view.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'GLANG', $lang_global );
	$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
	$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
	$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
	$xtpl->assign( 'MODULE_NAME', $module_name );
	$xtpl->assign( 'OP', $op );

	$query = $db->query( 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id = ' . $view_id );
	$news_contents = $query->fetch( );
	$xtpl->assign( 'CONTENT', $news_contents );

	$xtpl->parse( 'preview' );
	$contents = $xtpl->text( 'preview' );

	include (NV_ROOTDIR . "/includes/header.php");
	echo $contents;
	include (NV_ROOTDIR . "/includes/footer.php");
}
else
{
	$xtpl = new XTemplate( $op . ".tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'GLANG', $lang_global );
	$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
	$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
	$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
	$xtpl->assign( 'MODULE_NAME', $module_name );
	$xtpl->assign( 'OP', $op );
	$xtpl->assign( 'SHOW_REPORTS', nv_show_reports( ) );

	$array_status = array(
		$lang_global['no'],
		$lang_global['yes']
	);

	$xtpl->parse( 'main' );
	$contents = $xtpl->text( 'main' );

	include (NV_ROOTDIR . "/includes/header.php");
	echo nv_admin_theme( $contents );
	include (NV_ROOTDIR . "/includes/footer.php");
}
