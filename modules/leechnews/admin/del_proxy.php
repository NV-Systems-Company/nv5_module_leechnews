<?php

/**
 * @Project leechHouse
 * @Author KENNYNGUYEN (nguyentiendat713@gmail.com)
 *
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-10-2010 18:49
 */
if( !defined( 'NV_IS_FILE_ADMIN' ) )
	die( 'Stop!!!' );

$proxy_id = $nv_Request->get_int( 'proxy_id', 'post', 0 );
$checkss = $nv_Request->get_string( 'checkss', 'post', '' );
$listproxy_id = $nv_Request->get_string( 'listproxy_id', 'post', '' );
$contents = 'ERR_' . $proxy_id;

if( $listproxy_id != '' and NV_CHECK_SESSION == $checkss )
{
	$del_array = array_map( 'intval', explode( ',', $listproxy_id ) );
}
elseif( md5( $proxy_id . NV_CHECK_SESSION ) == $checkss )
{
	$del_array = array( $proxy_id );
}
if( !empty( $del_array ) )
{
	$sql = 'SELECT proxy_id, proxy_ip FROM ' . NV_PREFIXLANG . '_' . $module_data . '_proxy WHERE proxy_id IN (' . implode( ',', $del_array ) . ')';
	$result = $db->query( $sql );
	$del_array = $no_del_array = array( );
	$artitle = array( );
	while( list( $proxy_id, $proxy_ip ) = $result->fetch( 3 ) )
	{
		$check_permission = false;
		if( defined( 'NV_IS_MODADMIN' ) )
		{
			$check_permission = true;
		}

		if( $check_permission > 0 )
		{
			$contents = nv_del_proxy( $proxy_id );
			$artitle[] = $proxy_ip;
			$del_array[] = $proxy_id;
		}
		else
		{
			$no_del_array[] = $proxy_id;
		}
	}
	$count = sizeof( $del_array );
	if( $count )
	{
		nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['del_proxy'], implode( ', ', $artitle ), $admin_info['userid'] );
	}
	if( !empty( $no_del_array ) )
	{
		$contents = 'ERR_' . $lang_module['error_permission'] . ': ' . implode( ', ', $no_del_array );
	}
}

include NV_ROOTDIR . '/includes/header.php';
echo $contents;
include NV_ROOTDIR . '/includes/footer.php';
