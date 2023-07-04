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

$proxy_id = $nv_Request->get_int( 'proxy_id', 'post,get', 0 );
$mod = $nv_Request->get_string( 'mod', 'post,get', '' );
$new_vid = $nv_Request->get_int( 'new_vid', 'post', 0 );
$content = 'NO_' . $proxy_id;

$proxy_id = $db->query( 'SELECT proxy_id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_proxy WHERE proxy_id=' . $proxy_id )->fetchColumn( );
if( $proxy_id > 0 )
{
	if( $mod == 'weight' and $new_vid > 0 and (defined( 'NV_IS_MODADMIN' )) )
	{
		$sql = 'SELECT proxy_id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_proxy WHERE proxy_id!=' . $proxy_id . ' ORDER BY weight ASC';
		$result = $db->query( $sql );

		$weight = 0;
		while( $row = $result->fetch( ) )
		{
			++$weight;
			if( $weight == $new_vid )
			{
				++$weight;
			}
			$sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_proxy SET weight=' . $weight . ' WHERE proxy_id=' . $row['proxy_id'];
			$db->query( $sql );
		}

		$sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_proxy SET weight=' . $new_vid . ' WHERE proxy_id=' . $proxy_id;
		$db->query( $sql );
		nv_fix_proxy( );
		$content = 'OK_' . $proxy_id;
	}
	elseif( defined( 'NV_IS_MODADMIN' ) )
	{
		if( $mod == 'status' and ($new_vid == 0 or $new_vid == 1) )
		{
			$sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_proxy SET status=' . $new_vid . ' WHERE proxy_id=' . $proxy_id;
			$db->query( $sql );
			$content = 'OK_' . $proxy_id;
		}
	}
	$nv_Cache->delMod( $module_name );
}

include NV_ROOTDIR . '/includes/header.php';
echo $content;
include NV_ROOTDIR . '/includes/footer.php';
