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

$id = $nv_Request->get_int( 'id', 'post,get', 0 );
$mod = $nv_Request->get_string( 'mod', 'post,get', '' );
$new_vid = $nv_Request->get_int( 'new_vid', 'post', 0 );
$content = 'NO_' . $id;

if( $mod == 'cron_create' )
{
	$create_cronfile = nv_leech_news_createcron( 1 );
	if( $create_cronfile )
	{
		$content = 'OK_' . $lang_module['setting_create_cron_success'];
	}
	else
	{
		$content = 'ERR_' . $lang_module['setting_create_cron_failed'];
	}
}
elseif( $mod == 'cron_setup' )
{
	$check_sql = nv_leech_news_checkcron( );
	if( $check_sql )
	{
		$content = 'OK_' . $lang_module['setting_create_cron_success'];
	}
	else
	{
		$create_cronsql = nv_leech_news_createcron_sql( 1 );
		if( $create_cronsql )
		{
			$content = 'OK_' . $lang_module['setting_create_cron_success'];
		}
		else
		{
			$content = 'ERR_' . $lang_module['setting_create_cron_failed'];
		}
	}
}
else
{
	$id = $db->query( 'SELECT id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_source WHERE id=' . $id )->fetchColumn( );
	if( $id > 0 )
	{
		if( $mod == 'weight' and $new_vid > 0 and (defined( 'NV_IS_MODADMIN' )) )
		{
			$sql = 'SELECT id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_source WHERE id!=' . $id . ' ORDER BY weight ASC';
			$result = $db->query( $sql );

			$weight = 0;
			while( $row = $result->fetch( ) )
			{
				++$weight;
				if( $weight == $new_vid )
				{
					++$weight;
				}
				$sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_source SET weight=' . $weight . ' WHERE id=' . $row['id'];
				$db->query( $sql );
			}

			$sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_source SET weight=' . $new_vid . ' WHERE id=' . $id;
			$db->query( $sql );

			$content = 'OK_' . $id;
		}
		elseif( defined( 'NV_IS_MODADMIN' ) and $mod == 'status' and ($new_vid == 0 or $new_vid == 1) )
		{
			$sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_source SET status=' . $new_vid . ' WHERE id=' . $id;
			$db->query( $sql );
			$content = 'OK_' . $id;
		}
		elseif( defined( 'NV_IS_MODADMIN' ) and $mod == 'cron_set' and ($new_vid == 0 or $new_vid == 1) )
		{
			$sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_source SET cron_set=' . $new_vid . ' WHERE id=' . $id;
			$db->query( $sql );
			$content = 'OK_' . $id;
		}
		elseif( defined( 'NV_IS_MODADMIN' ) and $mod == 'cron_schedule' and ($new_vid >= 1 or $new_vid <= 72) )
		{
			$sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_source SET cron_schedule=' . $new_vid . ' WHERE id=' . $id;
			$db->query( $sql );
			$content = 'OK_' . $id;
		}
		$nv_Cache->delMod( $module_name );
	}
}
include NV_ROOTDIR . '/includes/header.php';
echo $content;
include NV_ROOTDIR . '/includes/footer.php';
