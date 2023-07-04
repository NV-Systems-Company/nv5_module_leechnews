<?php

/**
 * @Project LEECHNEWS ON NUKEVIET 4.x
 * @Author KENNYNGUYEN (nguyentiendat713@gmail.com)
 * @License GNU/GPL version 2 or any later version
 * @Createdate 19 July 2016 16:00 GMT+7
 */
if( !defined( 'NV_IS_FILE_ADMIN' ) )
	die( 'Stop!!!' );

$leech_id = $nv_Request->get_int( 'id', 'post', 0 );
$checkss = $nv_Request->get_string( 'checkss', 'post', 0 );
$listid = $nv_Request->get_string( 'listid', 'post', '' );
$contents = "No ID";
$leech_array = array();

if( $listid != '' and NV_CHECK_SESSION == $checkss )
{
	$leech_array = array_map( 'intval', explode( ',', $listid ) );
}
elseif( md5( $leech_id . NV_CHECK_SESSION ) == $checkss )
{
	$leech_array = array( $leech_id );
}
$prefix = $db_config['prefix'] . "_" . NV_LANG_DATA;
$table_prefix = $prefix . "_" . $module_info['module_data'];

foreach( $leech_array as $id )
{
	if( $id > 0 )
	{
		$contents = nv_auto_leechnews( $id, $table_prefix, $prefix, $module_upload, $module_file, $module_name, NV_LANG_DATA, $admin_info['userid'] );
	}
}

include NV_ROOTDIR . '/includes/header.php';
echo $contents;
include NV_ROOTDIR . '/includes/footer.php';
