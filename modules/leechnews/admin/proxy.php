<?php

/**
 * @Project leechHouse ON NUKEVIET 4.x
 * @Author KENNYNGUYEN (nguyentiendat713@gmail.com)
 *
 * @License GNU/GPL version 2 or any later version
 * @Createdate 08/25/2015 10:27
 */
if( !defined( 'NV_IS_FILE_ADMIN' ) )
	die( 'Stop!!!' );
$page_title = $lang_module['update_proxy'];
$error = array( );
$rowcontent = array(
	'proxy_id' => '',
	'proxy_city' => '',
	'proxy_country' => '',
	'proxy_ip' => '',
	'proxy_port' => '',
	'proxy_last_update' => '',
	'proxy_refs' => '',
	'proxy_state' => '',
	'proxy_status' => '',
	'proxy_time' => '',
	'proxy_type' => '',
	'proxy_uid' => '',
	'proxy_uptimeld' => '',
	'proxy_username' => '',
	'proxy_password' => '',
	'status' => '',
	'date_added' => '',
	'date_modified' => '',
	'weight' => 0,
	'mode' => 'add'
);

$rowcontent['proxy_id'] = $nv_Request->get_int( 'proxy_id', 'get,post', 0 );

$array_list_action = array(
	'delete' => $lang_global['delete'],
	'admin_leechnews' => $lang_module['admin_leechnews']
);

if( $rowcontent['proxy_id'] > 0 )
{
	$check_permission = false;
	$rowcontent = $db->query( 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_proxy WHERE proxy_id=' . $rowcontent['proxy_id'] )->fetch( );
	if( !empty( $rowcontent['proxy_id'] ) )
	{
		$rowcontent['mode'] = 'edit';
		if( defined( 'NV_IS_MODADMIN' ) )
		{
			$check_permission = true;
		}
	}

	if( !$check_permission )
	{
		Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name );
		die( );
	}
	$page_title = $lang_module['edit_proxy'];
}

if( $nv_Request->get_int( 'save', 'post' ) == 1 )
{
	$rowcontent['proxy_ip'] = $nv_Request->get_title( 'proxy_ip', 'post', '', 1 );
	$rowcontent['proxy_city'] = $nv_Request->get_title( 'proxy_city', 'post' );
	$rowcontent['proxy_country'] = $nv_Request->get_title( 'proxy_country', 'post' );
	$rowcontent['proxy_port'] = $nv_Request->get_title( 'proxy_port', 'post' );
	$rowcontent['proxy_last_update'] = $nv_Request->get_title( 'proxy_last_update', 'post' );
	$rowcontent['proxy_refs'] = $nv_Request->get_title( 'proxy_refs', 'post', '', 1 );
	$rowcontent['proxy_state'] = $nv_Request->get_title( 'proxy_state', 'post', '', 1 );
	$rowcontent['proxy_status'] = $nv_Request->get_title( 'proxy_status', 'post', '', 1 );
	$rowcontent['proxy_time'] = $nv_Request->get_title( 'proxy_time', 'post', '', 1 );
	$rowcontent['proxy_type'] = $nv_Request->get_title( 'proxy_type', 'post', '', 1 );
	$rowcontent['proxy_uid'] = $nv_Request->get_title( 'proxy_uid', 'post', '', 1 );
	$rowcontent['proxy_uptimeld'] = $nv_Request->get_title( 'proxy_uptimeld', 'post', '', 1 );
	$rowcontent['proxy_username'] = $nv_Request->get_title( 'proxy_username', 'post', '', 1 );
	$rowcontent['proxy_password'] = $nv_Request->get_title( 'proxy_password', 'post', '', 1 );
	$rowcontent['status'] = $nv_Request->get_int( 'status', 'post', '', 1 );
	$rowcontent['weight'] = 0;

	if( empty( $rowcontent['proxy_ip'] ) )
	{
		$error[] = $lang_module['error_proxy_ip'];
	}
	elseif( empty( $rowcontent['proxy_port'] ) )
	{
		$error[] = $lang_module['error_proxy_port'];
	}

	if( empty( $error ) )
	{
		if( $rowcontent['proxy_id'] == 0 )
		{
			$sql = 'INSERT IGNORE INTO ' . NV_PREFIXLANG . '_' . $module_data . '_proxy
				( proxy_city, proxy_country, proxy_ip, proxy_port, proxy_last_update, proxy_refs, proxy_state, proxy_status, proxy_time, proxy_type, proxy_uid, proxy_uptimeld, proxy_username, proxy_password, status, date_added, date_modified, weight) 
				VALUES
				( :proxy_city,
				:proxy_country,
				:proxy_ip,
				:proxy_port,
				:proxy_last_update,
				:proxy_refs,
				:proxy_state,
				:proxy_status,
				:proxy_time,
				:proxy_type,
				:proxy_uid,
				:proxy_uptimeld,
				:proxy_username,
				:proxy_password,
				:status,
				:date_added,
				:date_modified,
				0 )';

			$data_insert = array( );
			$data_insert['proxy_city'] = $rowcontent['proxy_city'];
			$data_insert['proxy_country'] = $rowcontent['proxy_country'];
			$data_insert['proxy_ip'] = $rowcontent['proxy_ip'];
			$data_insert['proxy_port'] = $rowcontent['proxy_port'];
			$data_insert['proxy_last_update'] = $rowcontent['proxy_last_update'];
			$data_insert['proxy_refs'] = $rowcontent['proxy_refs'];
			$data_insert['proxy_state'] = $rowcontent['proxy_state'];
			$data_insert['proxy_status'] = $rowcontent['proxy_status'];
			$data_insert['proxy_time'] = $rowcontent['proxy_time'];
			$data_insert['proxy_type'] = $rowcontent['proxy_type'];
			$data_insert['proxy_uid'] = $rowcontent['proxy_uid'];
			$data_insert['proxy_uptimeld'] = $rowcontent['proxy_uptimeld'];
			$data_insert['proxy_username'] = $rowcontent['proxy_username'];
			$data_insert['proxy_password'] = $rowcontent['proxy_password'];
			$data_insert['status'] = $rowcontent['status'];
			$data_insert['date_added'] = NV_CURRENTTIME;
			$data_insert['date_modified'] = NV_CURRENTTIME;

			$rowcontent['proxy_id'] = $db->insert_id( $sql, 'proxy_id', $data_insert );
			if( $rowcontent['proxy_id'] > 0 )
			{
				nv_fix_proxy( );
				$nv_Cache->delMod( $module_name );
				nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['update_proxy'], $rowcontent['proxy_ip'], $admin_info['userid'] );
				$url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&op=' . $op;
				$msg1 = $lang_module['proxy_added'];
				$msg2 = $lang_module['source_cat_back'] . ' ' . $module_info['custom_title'];
				redirect( $msg1, $msg2, $url );
			}
			else
			{
				$error[] = $lang_module['errorsave'];
			}
		}
		else
		{
			$sth = $db->prepare( 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_proxy SET
				proxy_city=:proxy_city,
				proxy_country=:proxy_country,
				proxy_ip=:proxy_ip,
				proxy_port=:proxy_port,
				proxy_last_update=:proxy_last_update,
				proxy_refs=:proxy_refs,
				proxy_state=:proxy_state,
				proxy_status=:proxy_status,
				proxy_time=:proxy_time,
				proxy_type=:proxy_type,
				proxy_uid=:proxy_uid,
				proxy_uptimeld=:proxy_uptimeld,
				proxy_username=:proxy_username,
				proxy_password=:proxy_password,
				status=:status,
				date_modified=' . NV_CURRENTTIME . '
				WHERE proxy_id =' . $rowcontent['proxy_id'] );

			$sth->bindParam( ':proxy_city', $rowcontent['proxy_city'], PDO::PARAM_STR );
			$sth->bindParam( ':proxy_country', $rowcontent['proxy_country'], PDO::PARAM_STR );
			$sth->bindParam( ':proxy_ip', $rowcontent['proxy_ip'], PDO::PARAM_STR );
			$sth->bindParam( ':proxy_port', $rowcontent['proxy_port'], PDO::PARAM_STR );
			$sth->bindParam( ':proxy_last_update', $rowcontent['proxy_last_update'], PDO::PARAM_STR );
			$sth->bindParam( ':proxy_refs', $rowcontent['proxy_refs'], PDO::PARAM_STR );
			$sth->bindParam( ':proxy_state', $rowcontent['proxy_state'], PDO::PARAM_STR );
			$sth->bindParam( ':proxy_status', $rowcontent['proxy_status'], PDO::PARAM_STR );
			$sth->bindParam( ':proxy_time', $rowcontent['proxy_time'], PDO::PARAM_STR );
			$sth->bindParam( ':proxy_type', $rowcontent['proxy_type'], PDO::PARAM_STR );
			$sth->bindParam( ':proxy_uid', $rowcontent['proxy_uid'], PDO::PARAM_STR );
			$sth->bindParam( ':proxy_uptimeld', $rowcontent['proxy_uptimeld'], PDO::PARAM_STR );
			$sth->bindParam( ':proxy_username', $rowcontent['proxy_username'], PDO::PARAM_STR );
			$sth->bindParam( ':proxy_password', $rowcontent['proxy_password'], PDO::PARAM_STR );
			$sth->bindParam( ':status', $rowcontent['status'], PDO::PARAM_STR );

			if( $sth->execute( ) )
			{
				nv_fix_proxy( );
				$nv_Cache->delMod( $module_name );
				nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['update_proxy'], $rowcontent['proxy_ip'], $admin_info['userid'] );
				$url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&op=' . $op;
				$msg1 = $lang_module['proxy_updated'] . ' ' . $rowcontent['proxy_ip'];
				$msg2 = $lang_module['source_cat_back'] . ' ' . $lang_module['proxy'];
				redirect( $msg1, $msg2, $url );
			}
			else
			{
				$error[] = $lang_module['errorsave'];
			}
		}
	}
	else
	{
		$url = 'javascript: history.go(-1)';
		$msg1 = implode( '<br />', $error );
		$msg2 = $lang_module['content_back'];
		redirect( $msg1, $msg2, $url, 'back' );
	}
}

$xtpl = new XTemplate( $op . ".tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'ITEM', $rowcontent );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'UPLOAD_PATH', NV_UPLOADS_DIR . '/' . $module_upload );
$xtpl->assign( 'UPLOAD_PATH', NV_UPLOADS_DIR . '/' . $module_upload . '/logo/' );
$xtpl->assign( 'OP', $op );
$xtpl->assign( 'PROXY_LIST', nv_show_proxy_list( ) );

if( !empty( $error ) )
{
	$xtpl->assign( 'error', implode( '<br />', $error ) );
	$xtpl->parse( 'main.error' );
}

$array_status = array(
	$lang_global['no'],
	$lang_global['yes']
);

foreach( $array_status as $key => $val )
{
	$xtpl->assign( 'STATUS', array(
		'key' => $key,
		'title' => $val,
		'selected' => $key == $rowcontent['status'] ? ' selected="selected"' : ''
	) );
	$xtpl->parse( 'main.status' );
}

foreach ($array_list_action as $key => $val) {
    if( defined( 'NV_IS_MODADMIN' ) )
    {
        $xtpl->assign('ACTION', array(
            'value' => $key,
            'title' => $val,
        ));
        $xtpl->parse( 'main.action' );
    }
}


if( $rowcontent['proxy_id'] > 0 )
{
	$op = '';
	$xtpl->assign( 'no_edit', 'readonly="readonly" disabled="disabled"' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include (NV_ROOTDIR . "/includes/header.php");
echo nv_admin_theme( $contents );
include (NV_ROOTDIR . "/includes/footer.php");
