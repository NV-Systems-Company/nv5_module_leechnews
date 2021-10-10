<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-10-2010 18:49
 */
if( !defined( 'NV_IS_FILE_ADMIN' ) )
	die( 'Stop!!!' );

$id = $nv_Request->get_int( 'id', 'post', 0 );
$checkss = $nv_Request->get_string( 'checkss', 'post', '' );
$listid = $nv_Request->get_string( 'listid', 'post', '' );
$mod = $nv_Request->get_string( 'mod', 'post', '' );
$contents = 'ERR_' . $id;

if( $listid != '' and NV_CHECK_SESSION == $checkss )
{
	$id_array = array_map( 'intval', explode( ',', $listid ) );
}
if( $mod == 'approve' )
{
	if( !empty( $id_array ) )
	{
		require (NV_ROOTDIR . "/modules/" . $module_file . "/simple_html_dom.php");
		$sql = 'SELECT id, title FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id IN (' . implode( ',', $id_array ) . ')';
		$result = $db->query( $sql );
		$id_array = $no_approve_array = array( );
		$artitle = array( );
		while( list( $id, $title ) = $result->fetch( 3 ) )
		{
			$check_permission = false;
			if( defined( 'NV_IS_MODADMIN' ) )
			{
				$check_permission = true;
			}

			if( $check_permission > 0 )
			{
				$contents = nv_approve_news( $id );
				$artitle[] = $title;
				$id_array[] = $id;
			}
			else
			{
				$no_approve_array[] = $id;
			}
		}
		$count = sizeof( $id_array );
		if( $count )
		{
			nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['approve_report'], implode( ',', $artitle ), $admin_info['userid'] );
		}
		if( !empty( $no_approve_array ) )
		{
			$contents = 'ERR_' . $lang_module['error_permission'] . ': ' . implode( ',', $no_approve_array );
		}
	}
}
elseif( $mod == 'deny' )
{
	if( !empty( $id_array ) )
	{
		require (NV_ROOTDIR . "/modules/" . $module_file . "/simple_html_dom.php");
		$sql = 'SELECT id, title FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id IN (' . implode( ',', $id_array ) . ')';
		$result = $db->query( $sql );
		$id_array = $no_approve_array = array( );
		$artitle = array( );
		while( list( $id, $title ) = $result->fetch( 3 ) )
		{
			$check_permission = false;
			if( defined( 'NV_IS_MODADMIN' ) )
			{
				$check_permission = true;
			}

			if( $check_permission > 0 )
			{
				$contents = nv_deny_news( $id );
				$artitle[] = $title;
				$id_array[] = $id;
			}
			else
			{
				$no_approve_array[] = $id;
			}
		}
		$count = sizeof( $id_array );
		if( $count )
		{
			nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['deny_report'], implode( ',', $artitle ), $admin_info['userid'] );
		}
		if( !empty( $no_approve_array ) )
		{
			$contents = 'ERR_' . $lang_module['error_permission'] . ': ' . implode( ',', $no_approve_array );
		}
	}
}

include NV_ROOTDIR . '/includes/header.php';
echo $contents;
include NV_ROOTDIR . '/includes/footer.php';
