<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
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
$mod_data = $nv_Request->get_string( 'mod_data', 'get,post', '' );
$id = $nv_Request->get_int( 'id', 'get,post', 0 );

$contents = nv_show_source_mod( $mod_data, $id );

include NV_ROOTDIR . '/includes/header.php';
echo $contents;
include NV_ROOTDIR . '/includes/footer.php';
