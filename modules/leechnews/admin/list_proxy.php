<?php

/**
 * @Project leechHouse
 * @Author KENNYNGUYEN (nguyentiendat713@gmail.com)
 *
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-10-2010 18:49
 */
if( !defined( 'NV_IS_FILE_ADMIN' ) )
{
	die( 'Stop!!!' );
}
if( !defined( 'NV_IS_AJAX' ) )
{
	die( 'Wrong URL' );
}

$page = $nv_Request->get_int( 'page', 'get', 1 );
$page = (intval( $page ) > 0) ? $page : 1;

$contents = nv_show_proxy_list( $page );

include NV_ROOTDIR . '/includes/header.php';
echo $contents;
include NV_ROOTDIR . '/includes/footer.php';
