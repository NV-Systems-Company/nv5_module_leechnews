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

$catid = $nv_Request->get_int( 'catid', 'post', 0 );
$checkss = $nv_Request->get_string( 'checkss', 'post', '' );
$listcatid = $nv_Request->get_string( 'listcatid', 'post', '' );
$contents = 'ERR_' . $catid;

if( $listcatid != '' and NV_CHECK_SESSION == $checkss )
{
	$del_array = array_map( 'intval', explode( ',', $listcatid ) );
}
elseif( md5( $catid . NV_CHECK_SESSION ) == $checkss )
{
	$del_array = array( $catid );
}
if( !empty( $del_array ) )
{
	$sql = 'SELECT catid, title FROM ' . NV_PREFIXLANG . '_' . $module_data . '_source_cat WHERE catid IN (' . implode( ',', $del_array ) . ')';
	$result = $db->query( $sql );
	$del_array = $no_del_array = array( );
	$artitle = array( );
	while( list( $catid, $title ) = $result->fetch( 3 ) )
	{
		$check_permission = false;
		if( defined( 'NV_IS_MODADMIN' ) )
		{
			$check_permission = true;
		}

		if( $check_permission > 0 )
		{
			$contents = nv_del_source_cat( $catid );
			$artitle[] = $title;
			$del_array[] = $catid;
		}
		else
		{
			$no_del_array[] = $catid;
		}
	}
	$count = sizeof( $del_array );
	if( $count )
	{
		nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['del_sourcecatandsource'], implode( ', ', $artitle ), $admin_info['userid'] );
	}
	if( !empty( $no_del_array ) )
	{
		$contents = 'ERR_' . $lang_module['error_permission'] . ': ' . implode( ', ', $no_del_array );
	}
}

include NV_ROOTDIR . '/includes/header.php';
echo $contents;
include NV_ROOTDIR . '/includes/footer.php';
