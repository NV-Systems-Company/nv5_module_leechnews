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

$catid = $nv_Request->get_int( 'catid', 'post,get', 0 );
$mod = $nv_Request->get_title( 'mod', 'post,get', '' );
$filename = $nv_Request->get_title( 'filename', 'post,get', '' );
$new_vid = $nv_Request->get_int( 'new_vid', 'post', 0 );
$content = 'NO_' . $catid;

if( !empty( $filename ) and $mod == 'download_template' )
{
	if( file_exists( NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/ini/' . $filename ) )
	{
		header( nv_leech_news_downloadtempl( NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/ini/' . $filename, NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/ini/', $filename ) );
		die( );
	}
}
elseif( !empty( $filename ) and $mod == 'setup_template' and defined( 'NV_IS_MODADMIN' ) )
{
	$file_ini = NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/ini/' . $filename;
	if( file_exists( $file_ini ) )
	{
		$content = nv_setup_template( $file_ini );
	}
}

$catid = $db->query( 'SELECT catid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_source_cat WHERE catid=' . $catid )->fetchColumn( );
if( $catid > 0 )
{
	if( $mod == 'weight' and $new_vid > 0 and (defined( 'NV_IS_MODADMIN' )) )
	{
		$sql = 'SELECT catid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_source_cat WHERE catid!=' . $catid . ' ORDER BY weight ASC';
		$result = $db->query( $sql );

		$weight = 0;
		while( $row = $result->fetch( ) )
		{
			++$weight;
			if( $weight == $new_vid )
			{
				++$weight;
			}
			$sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_source_cat SET weight=' . $weight . ' WHERE catid=' . $row['catid'];
			$db->query( $sql );
		}

		$sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_source_cat SET weight=' . $new_vid . ' WHERE catid=' . $catid;
		$db->query( $sql );
		nv_fix_source_cat( );
		$content = 'OK_' . $catid;
	}
	elseif( defined( 'NV_IS_MODADMIN' ) )
	{
		if( $mod == 'status' and ($new_vid == 0 or $new_vid == 1) )
		{
			$sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_source_cat SET status=' . $new_vid . ' WHERE catid=' . $catid;
			$db->query( $sql );
			$content = 'OK_' . $catid;
		}
		elseif( $mod == 'export_templ' )
		{
			header( nv_leech_news_export_func( $catid, 1 ) );
			die( );
		}
	}
	$nv_Cache->delMod( $module_name );
}
elseif( $catid == 0 AND $mod == 'export_templ_all' )
{
	header( nv_leech_news_export_func( 0, 2 ) );
	die( );
}

include NV_ROOTDIR . '/includes/header.php';
echo $content;
include NV_ROOTDIR . '/includes/footer.php';
