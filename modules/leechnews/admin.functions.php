<?php

/**
 * @Project LEECHNEWS ON NUKEVIET 4.x
 * @Author KENNYNGUYEN (nguyentiendat713@gmail.com)
 * @License GNU/GPL version 2 or any later version
 * @Createdate 19 July 2016 16:00 GMT+7
 */

if( !defined( 'NV_ADMIN' ) or !defined( 'NV_MAINFILE' ) or !defined( 'NV_IS_MODADMIN' ) )
	die( 'Stop!!!' );
define( 'NV_IS_FILE_ADMIN', true );
require (NV_ROOTDIR . "/modules/" . $module_file . "/global.functions.php");
$per_page = $module_config[$module_name]['st_links'];

/**
 * Khai bao cau truc bien de su dung
 * cho toan bo Module
 * $ini_construct
 */
$ini_construct = array(
    'title' => '',
    'host_url' => '',
    'logo' => '',
    'block_pattern' => '',
    'block_title' => '',
    'block_title_remove' => '',
    'block_title_replace' => '',
    'block_url' => '',
    'block_hometext' => '',
    'block_hometext_remove' => '',
    'block_hometext_replace' => '',
    'block_thumb' => '',
    'block_thumb_alt' => '',
    'block_thumb_attribute' => '',
    'detail_bodyhtml' => '',
    'detail_bodyhtml_remove' => '',
    'detail_bodyhtml_replace' => '',
    'detail_bodyhtml_attribute' => '',
    'detail_hometext' => '',
    'detail_hometext_remove' => '',
    'detail_hometext_replace' => '',
    'detail_author' => '',
    'detail_author_replace' => ''
);


/**
 * $global_array_cat
 */
global $global_array_cat;
$global_array_cat = array( );
$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_source_cat ORDER BY weight ASC';
$result = $db->query( $sql );
while( $row = $result->fetch( ) )
{
	$global_array_cat[$row['catid']] = $row;
}

/**
 * nv_del_content_source()
 *
 * @param mixed $id
 * @return
 *
 */
function nv_del_content_source( $id )
{
	global $db, $module_name, $module_data, $title, $lang_module, $admin_info, $nv_Cache;
	$contents = "ERR_" . $id;
	$title = '';
	list( $id, $title ) = $db->query( 'SELECT id, title FROM ' . NV_PREFIXLANG . '_' . $module_data . '_source WHERE id=' . intval( $id ) )->fetch( 3 );
	if( $id > 0 )
	{
		$db->query( "DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_source WHERE id=" . $id );
		$db->query( "DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_rows WHERE sid=" . $id );
		$db->query( "DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_logs WHERE sid=" . $id );
		$nv_Cache->delMod( $module_name );
		$contents = "OK_" . $id;
	}
	return $contents;
}

/**
 * nv_del_source_cat()
 *
 * @param mixed $id
 * @return
 *
 */
function nv_del_source_cat( $catid )
{
	global $db, $module_name, $module_data, $title, $lang_module, $admin_info, $nv_Cache;
	$contents = "ERR_" . $catid;
	$title = '';
	list( $catid, $title ) = $db->query( 'SELECT catid, title FROM ' . NV_PREFIXLANG . '_' . $module_data . '_source_cat WHERE catid=' . $catid )->fetch( 3 );
	if( $catid > 0 )
	{
		if( (defined( 'NV_IS_MODADMIN' )) )
		{
			$db->query( "DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_source_cat WHERE catid=" . $catid );
			$db->query( "DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_source WHERE catid=" . $catid );
			$db->query( "DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_rows WHERE scatid=" . $catid );
			$db->query( "DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_logs WHERE scatid=" . $catid );
			nv_fix_source_cat( );
			$nv_Cache->delMod( $module_name );
			$contents = "OK_" . $catid;
		}
		else
		{
			$contents = "ERR_" . $lang_module['error_permission'];
		}
	}
	else
	{
		$contents = "ERR_" . $lang_module['error_catid'];
	}
	return $contents;
}

/**
 * nv_del_proxy()
 *
 * @param mixed $id
 * @return
 *
 */
function nv_del_proxy( $proxy_id )
{
	global $db, $module_name, $module_data, $lang_module, $nv_Cache;
	$contents = "ERR_" . $proxy_id;
	$proxy_ip = '';
	list( $proxy_id, $proxy_ip ) = $db->query( 'SELECT proxy_id, proxy_ip FROM ' . NV_PREFIXLANG . '_' . $module_data . '_proxy WHERE proxy_id=' . $proxy_id )->fetch( 3 );
	if( $proxy_id > 0 )
	{
		if( (defined( 'NV_IS_MODADMIN' )) )
		{
			$db->query( "DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_proxy WHERE proxy_id=" . $proxy_id );
			nv_fix_proxy( );
			$nv_Cache->delMod( $module_name );
			$contents = "OK_" . $proxy_id;
		}
		else
		{
			$contents = "ERR_" . $lang_module['error_permission'];
		}
	}
	else
	{
		$contents = "ERR_" . $lang_module['error_catid'];
	}
	return $contents;
}

/**
 * nv_show_sourcecat_list()
 *
 * @return
 *
 */
function nv_show_sourcecat_list( $page = 1 )
{
	global $db, $lang_module, $lang_global, $module_name, $module_data, $global_array_cat, $admin_id, $global_config, $module_file, $per_page, $nv_Request;

	$base_url = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . '&' . NV_OP_VARIABLE . '=source_cat';
	$page = $nv_Request->get_int( 'page', 'get', 1 );

	$xtpl = new XTemplate( 'sourcecat_list.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'GLANG', $lang_global );
	$xtpl->assign( 'CUR_PAGE', $page );
	$all_page = $db->query( 'SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_source_cat ORDER BY weight ASC' )->fetchColumn( );

	$sql = 'SELECT catid, title, weight, host_url, status FROM ' . NV_PREFIXLANG . '_' . $module_data . '_source_cat ORDER BY weight ASC LIMIT ' . ($page - 1) * $per_page . ', ' . $per_page;
	$rowall = $db->query( $sql )->fetchAll( 3 );

	$num = sizeof( $rowall );
	$a = 1;
	if( $page > 1 )
		$a = 1 + (($page - 1) * $per_page);

	$array_status = array(
		$lang_global['no'],
		$lang_global['yes']
	);

	foreach( $rowall as $row )
	{
		list( $catid, $title, $weight, $host_url, $status ) = $row;

		if( defined( 'NV_IS_MODADMIN' ) )
		{
			$check_show = 1;
		}

		if( !empty( $check_show ) )
		{

			$admin_funcs = array( );
			$weight_disabled = $func_cat_disabled = true;

			if( defined( 'NV_IS_MODADMIN' ) )
			{
				$weight_disabled = false;
				$admin_funcs[] = "<em class=\"fa fa-download fa-lg\">&nbsp;</em> <a href=\"javascript:void(0);\" onclick=\"nv_download_source_cat('" . $catid . "', 'export_templ')\">" . $lang_module['source_cat_export'] . "</a>";
			}
			if( defined( 'NV_IS_MODADMIN' ) )
			{
				$func_cat_disabled = false;
				$admin_funcs[] = "<em class=\"fa fa-edit fa-lg\">&nbsp;</em> <a class=\"\" href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=source_cat&amp;catid=" . $catid . "#edit\">" . $lang_global['edit'] . "</a>\n";
			}
			if( defined( 'NV_IS_MODADMIN' ) )
			{
				$weight_disabled = false;
				$admin_funcs[] = "<em class=\"fa fa-trash-o fa-lg\">&nbsp;</em> <a href=\"javascript:void(0);\" onclick=\"nv_del_source_cat('" . $catid . "', '" . md5( $catid . NV_CHECK_SESSION ) . "')\">" . $lang_global['delete'] . "</a>";
			}

			$xtpl->assign( 'ROW', array(
				'catid' => $catid,
				'host_url' => $host_url,
				'link' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=source&catid=' . $catid,
				'title' => $title,
				'adminfuncs' => implode( '&nbsp;-&nbsp;', $admin_funcs )
			) );

			$xtpl->assign( 'STT', $a );
			$xtpl->parse( 'main.data.loop.stt' );

			for( $i = 1; $i <= $all_page; ++$i )
			{
				$xtpl->assign( 'WEIGHT', array(
					'key' => $i,
					'title' => $i,
					'selected' => $i == $weight ? ' selected="selected"' : ''
				) );
				$xtpl->parse( 'main.data.loop.weight.loop' );
			}
			$xtpl->parse( 'main.data.loop.weight' );

			foreach( $array_status as $key => $val )
			{
				$xtpl->assign( 'STATUS', array(
					'key' => $key,
					'title' => $val,
					'selected' => $key == $status ? ' selected="selected"' : ''
				) );
				$xtpl->parse( 'main.data.loop.status.loop' );
			}
			$xtpl->parse( 'main.data.loop.status' );

			$xtpl->parse( 'main.data.loop' );
			++$a;
		}
	}
	$array_list_action = array(
		'' => $lang_module['sel_action'],
		'delete' => $lang_global['delete']
	);
    
    foreach ($array_list_action as $key => $val) {
		if( defined( 'NV_IS_MODADMIN' ) )
		{
            $xtpl->assign('ACTION', array(
                'value' => $key,
                'title' => $val,
            ));
            $xtpl->parse( 'main.data.action' );
		}
    }

	$generate_page = nv_generate_page( $base_url, $all_page, $per_page, $page );
	if( !empty( $generate_page ) )
	{
		$xtpl->assign( 'GENERATE_PAGE', $generate_page );
		$xtpl->parse( 'main.data.generate_page' );
	}

	if( $num > 0 )
	{
		$xtpl->parse( 'main.data' );
	}
	elseif( $page > 1 )
	{
		header( 'Location:' . $base_url );
	}
	else
	{
		$xtpl->parse( 'main.no_data' );
	}

	$xtpl->parse( 'main' );
	$contents = $xtpl->text( 'main' );

	return $contents;
}

/**
 * nv_show_proxy_list()
 *
 * @return
 *
 */
function nv_show_proxy_list( $page = 1 )
{
	global $db, $lang_module, $lang_global, $module_name, $module_data, $global_array_cat, $admin_id, $global_config, $module_file, $nv_Request;
	$per_page = 50;
	$base_url = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . '&' . NV_OP_VARIABLE . '=proxy';
	$page = $nv_Request->get_int( 'page', 'get', 1 );
	$xtpl = new XTemplate( 'proxy_list.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'GLANG', $lang_global );
	$xtpl->assign( 'CUR_PAGE', $page );
	$all_page = $db->query( 'SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_proxy ORDER BY weight ASC' )->fetchColumn( );
	$sql = 'SELECT proxy_id, proxy_ip, proxy_port, proxy_last_update, weight, status FROM ' . NV_PREFIXLANG . '_' . $module_data . '_proxy ORDER BY weight ASC LIMIT ' . ($page - 1) * $per_page . ', ' . $per_page;
	$rowall = $db->query( $sql )->fetchAll( 3 );
	$num = sizeof( $rowall );
	$a = 1;
	if( $page > 1 )
		$a = 1 + (($page - 1) * $per_page);
	$array_status = array(
		$lang_global['no'],
		$lang_global['yes']
	);
	foreach( $rowall as $row )
	{
		list( $proxy_id, $proxy_ip, $proxy_port, $proxy_last_update, $weight, $status ) = $row;
		if( defined( 'NV_IS_MODADMIN' ) )
		{
			$check_show = 1;
		}
		if( !empty( $check_show ) )
		{
			$admin_funcs = array( );
			$weight_disabled = $func_cat_disabled = true;

			if( defined( 'NV_IS_MODADMIN' ) )
			{
				$func_cat_disabled = false;
				$admin_funcs[] = "<em class=\"fa fa-edit fa-lg\">&nbsp;</em> <a class=\"\" href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=proxy&amp;proxy_id=" . $proxy_id . "#edit\">" . $lang_global['edit'] . "</a>\n";
			}
			if( defined( 'NV_IS_MODADMIN' ) )
			{
				$weight_disabled = false;
				$admin_funcs[] = "<em class=\"fa fa-trash-o fa-lg\">&nbsp;</em> <a href=\"javascript:void(0);\" onclick=\"nv_del_proxy('" . $proxy_id . "', '" . md5( $proxy_id . NV_CHECK_SESSION ) . "')\">" . $lang_global['delete'] . "</a>";
			}
			$xtpl->assign( 'ROW', array(
				'proxy_id' => $proxy_id,
				'proxy_ip' => $proxy_ip,
				'proxy_port' => $proxy_port,
				'proxy_last_update' => $proxy_last_update,
				'adminfuncs' => implode( '&nbsp;-&nbsp;', $admin_funcs )
			) );
			$xtpl->assign( 'STT', $a );
			$xtpl->parse( 'main.data.loop.stt' );
			for( $i = 1; $i <= $all_page; ++$i )
			{
				$xtpl->assign( 'WEIGHT', array(
					'key' => $i,
					'title' => $i,
					'selected' => $i == $weight ? ' selected="selected"' : ''
				) );
				$xtpl->parse( 'main.data.loop.weight.loop' );
			}
			$xtpl->parse( 'main.data.loop.weight' );

			foreach( $array_status as $key => $val )
			{
				$xtpl->assign( 'STATUS', array(
					'key' => $key,
					'title' => $val,
					'selected' => $key == $status ? ' selected="selected"' : ''
				) );
				$xtpl->parse( 'main.data.loop.status.loop' );
			}
			$xtpl->parse( 'main.data.loop.status' );
			$xtpl->parse( 'main.data.loop' );
			++$a;
		}
	}
	$array_list_action = array(
		'' => $lang_module['sel_action'],
		'delete' => $lang_global['delete']
	);
    foreach ($array_list_action as $key => $val) {
		if( defined( 'NV_IS_MODADMIN' ) )
		{
            $xtpl->assign('ACTION', array(
                'value' => $key,
                'title' => $val,
            ));
            $xtpl->parse( 'main.data.action' );
		}
    }
	$generate_page = nv_generate_page( $base_url, $all_page, $per_page, $page );
	if( !empty( $generate_page ) )
	{
		$xtpl->assign( 'GENERATE_PAGE', $generate_page );
		$xtpl->parse( 'main.data.generate_page' );
	}
	if( $num > 0 )
	{
		$xtpl->parse( 'main.data' );
	}
	elseif( $page > 1 )
	{
		header( 'Location:' . $base_url );
	}
	else
	{
		$xtpl->parse( 'main.no_data' );
	}
	$xtpl->parse( 'main' );
	$contents = $xtpl->text( 'main' );
	return $contents;
}

/**
 * nv_fix_proxy()
 *
 * @return
 *
 */
function nv_fix_proxy( )
{
	global $db, $module_data;
	$sql = 'SELECT proxy_id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_proxy ORDER BY weight ASC';
	$result = $db->query( $sql );
	$weight = 0;
	while( $row = $result->fetch( ) )
	{
		++$weight;
		$sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_proxy SET weight=' . $weight . ' WHERE proxy_id=' . intval( $row['proxy_id'] );
		$db->query( $sql );
	}
	$result->closeCursor( );
}

/**
 * nv_show_reports()
 *
 * @return
 *
 */
function nv_show_reports( $page = 1 )
{
	global $db, $lang_module, $lang_global, $module_name, $module_data, $global_array_cat, $admin_id, $global_config, $module_file, $per_page, $nv_Request;

	$base_url = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . '&' . NV_OP_VARIABLE . '=reports';
	$page = $nv_Request->get_int( 'page', 'get', 1 );

	$xtpl = new XTemplate( 'reports_list.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'GLANG', $lang_global );
	$xtpl->assign( 'CUR_PAGE', $page );
	$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
	$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
	$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
	$xtpl->assign( 'MODULE_NAME', $module_name );

	$all_page = $db->query( 'SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows ORDER BY addtime DESC' )->fetchColumn( );
	$sql = 'SELECT id, sid, scatid, addtime, title, news_url, status FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows ORDER BY addtime DESC LIMIT ' . ($page - 1) * $per_page . ', ' . $per_page;
	$rowall = $db->query( $sql )->fetchAll( 3 );
	$num = sizeof( $rowall );
	$a = 1;
	$array_status = array(
		$lang_global['no'],
		$lang_global['yes']
	);

	foreach( $rowall as $row )
	{
		list( $id, $sid, $scatid, $addtime, $title, $news_url, $status ) = $row;

		if( defined( 'NV_IS_MODADMIN' ) )
		{
			$check_show = 1;
		}

		if( !empty( $check_show ) )
		{
			if( $status == 3 )
			{
				$class = 'class="success"';
			}
			elseif( $status == 5 )
			{
				$class = 'class="warning"';
			}
			elseif( $status == 7 )
			{
				$class = 'class="success"';
			}
			elseif( $status == 99 )
			{
				$class = 'class="danger"';
			}
			elseif( $status == 1 )
			{
				$class = 'class="success"';
			}
			else
			{
				$class = '';
			}
			$xtpl->assign( 'ROW', array(
				'id' => $id,
				'addtime' => nv_date( 'H:i - d/m/Y', $addtime ),
				'source_cat_title' => $global_array_cat[$scatid]['title'],
				'news_url' => $news_url,
				'title' => $title,
				'class' => $class,
				'status' => $lang_module['article_status_' . $status],
				'preview_link' => NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . '&' . NV_OP_VARIABLE . '=reports&quick_view=1&view_id=' . $id
			) );

			$xtpl->assign( 'STT', $a );
			$xtpl->parse( 'main.loop.stt' );

			foreach( $array_status as $key => $val )
			{
				$xtpl->assign( 'STATUS', array(
					'key' => $key,
					'title' => $val,
					'selected' => $key == $status ? ' selected="selected"' : ''
				) );
				$xtpl->parse( 'main.loop.status.loop' );
			}
			$xtpl->parse( 'main.loop.status' );

			$xtpl->parse( 'main.loop' );
			++$a;
		}
	}
	$array_list_action = array(
		'' => $lang_module['sel_action'],
		'delete' => $lang_global['delete'],
		'approve' => $lang_module['approve_report'],
		'deny' => $lang_module['deny_report']
	);

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

	$generate_page = nv_generate_page( $base_url, $all_page, $per_page, $page );
	if( !empty( $generate_page ) )
	{
		$xtpl->assign( 'GENERATE_PAGE', $generate_page );
		$xtpl->parse( 'main.generate_page' );
	}

	if( $num > 0 )
	{
		$xtpl->parse( 'main' );
		$contents = $xtpl->text( 'main' );
	}
	elseif( $page > 1 )
	{
		header( 'Location:' . $base_url );
		die( );
	}
	else
	{
		$xtpl->parse( 'no_data' );
		$contents = $xtpl->text( 'no_data' );
	}
	return $contents;
}

/**
 * nv_del_reports()
 *
 * @param mixed $id
 * @return
 *
 */
function nv_del_reports( $id )
{
	global $db, $module_name, $module_data, $lang_module, $admin_info, $nv_Cache;
	$contents = "ERR_" . $id;
	list( $id, $title ) = $db->query( 'SELECT id, title FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id=' . intval( $id ) )->fetch( 3 );
	if( $id > 0 )
	{
		$db->query( "DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_rows WHERE id=" . $id );
		$nv_Cache->delMod( $module_name );
		$contents = "OK_" . $id;
	}
	return $contents;
}

/**
 * nv_show_logs()
 *
 * @return
 *
 */
function nv_show_logs( $page = 1 )
{
	global $db, $lang_module, $lang_global, $module_name, $module_data, $global_array_cat, $admin_id, $global_config, $module_file, $nv_Request, $per_page;

	$base_url = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . '&' . NV_OP_VARIABLE . '=logs';
	$page = $nv_Request->get_int( 'page', 'get', 1 );

	$xtpl = new XTemplate( 'logs_list.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'GLANG', $lang_global );
	$xtpl->assign( 'CUR_PAGE', $page );
	$all_page = $db->query( 'SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_logs ORDER BY set_time DESC' )->fetchColumn( );

	$sql = 'SELECT l.id, l.sid, l.scatid, l.userid, u.username, l.note, l.set_time, l.status FROM ' . NV_PREFIXLANG . '_' . $module_data . '_logs l LEFT JOIN ' . NV_USERS_GLOBALTABLE . ' u ON l.userid=u.userid ORDER BY set_time DESC LIMIT ' . ($page - 1) * $per_page . ', ' . $per_page;
	$rowall = $db->query( $sql )->fetchAll( 3 );
	$num = sizeof( $rowall );
	$a = 1;
	if( $page > 1 )
		$a = 1 + (($page - 1) * $per_page);
	$array_status = array(
		$lang_global['no'],
		$lang_global['yes']
	);

	foreach( $rowall as $row )
	{
		list( $id, $sid, $scatid, $userid, $username, $note, $set_time, $status ) = $row;

		$query = $db->query( 'SELECT title FROM ' . NV_PREFIXLANG . '_' . $module_data . '_source WHERE id = ' . $sid );
		$source_title = $query->fetchColumn( );

		if( defined( 'NV_IS_MODADMIN' ) )
		{
			$check_show = 1;
		}
		if( $userid == 0 )
		{
			$username = $lang_module['job_leecher'];
		}
		if( !empty( $check_show ) )
		{

			$xtpl->assign( 'ROW', array(
				'id' => $id,
				'source_title' => $source_title,
				'addtime' => nv_date( 'H:i - d/m/Y', $set_time ),
				'source_cat_title' => $global_array_cat[$scatid]['title'],
				'username' => $username,
				'note' => $note,
				'note_cut' => nv_clean60( strip_tags( $note, '<br>' ), 80 ),
				'status' => $lang_module['job_status_' . $status]
			) );

			$xtpl->assign( 'STT', $a );
			$xtpl->parse( 'main.loop.stt' );

			foreach( $array_status as $key => $val )
			{
				$xtpl->assign( 'STATUS', array(
					'key' => $key,
					'title' => $val,
					'selected' => $key == $status ? ' selected="selected"' : ''
				) );
				$xtpl->parse( 'main.loop.status.loop' );
			}
			$xtpl->parse( 'main.loop.status' );

			$xtpl->parse( 'main.loop' );
			++$a;
		}
	}
	$array_list_action = array(
		'' => $lang_module['sel_action'],
		'delete' => $lang_global['delete']
	);

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
	$generate_page = nv_generate_page( $base_url, $all_page, $per_page, $page );
	if( !empty( $generate_page ) )
	{
		$xtpl->assign( 'GENERATE_PAGE', $generate_page );
		$xtpl->parse( 'main.generate_page' );
	}

	if( $num > 0 )
	{
		$xtpl->parse( 'main' );
		$contents = $xtpl->text( 'main' );
	}
	elseif( $page > 1 )
	{
		header( 'Location:' . $base_url );
		die( );
	}
	else
	{
		$xtpl->parse( 'no_data' );
		$contents = $xtpl->text( 'no_data' );
	}

	return $contents;
}

/**
 * nv_del_logs()
 *
 * @param mixed $id
 * @return
 *
 */
function nv_del_logs( $id )
{
	global $db, $module_name, $module_data, $lang_module, $admin_info, $nv_Cache;
	$contents = "ERR_" . $id;
	list( $id, $sid ) = $db->query( 'SELECT id, sid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_logs WHERE id=' . intval( $id ) )->fetch( 3 );
	if( $id > 0 )
	{
		$db->query( "DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_logs WHERE id=" . $id );
		$nv_Cache->delMod( $module_name );
		$contents = "OK_" . $id;
	}
	return $contents;
}

/**
 * nv_show_sourcecat_template( $sort = '' )
 *
 * @return
 *
 */
function nv_show_sourcecat_template( $sort = '' )
{
	global $db, $lang_module, $lang_global, $module_name, $module_data, $module_upload, $global_array_cat, $admin_id, $global_config, $module_file;

	$xtpl = new XTemplate( 'sourcecat_temp_list.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'GLANG', $lang_global );
	$xtpl->assign( 'MODULE_NAME', $module_name );

	if( !empty( $sort ) and $sort == 'not_installed' )
	{
		unset( $ini );
		$xtpl->parse( 'main.show_not_installed' );
	}
	else
	{
		$xtpl->parse( 'main.show_all' );
	}

	$files = array( );
	foreach( glob ( NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $module_upload . '/ini/*.ini' ) as $file )
	{
		$files[] = $file;
	}
	$a = 0;
	$contents = '';
	$me = array( );
	if( !empty( $files ) )
	{
		foreach( $files as $key => $file )
		{
			$_ini = nv_object2array( simplexml_load_file( $file ) );
			$_ini['file_name'] = basename( $file );

			$_ini['install_status'] = false;
            
			$_check_host = $db->query( 'SELECT catid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_source_cat WHERE host_url LIKE ' . $db->quote( $_ini['host_url'] ) )->fetchColumn( );
			if( $_check_host > 0 )
			{
				$_ini['install_status'] = true;
			}

			$admin_funcs = array( );
			if( $_ini['install_status'] === false )
			{
				$admin_funcs[] = "<em class=\"fa fa-cogs fa-lg\">&nbsp;</em> <a href=\"javascript:void(0);\" onclick=\"nv_setup_source_cat('" . $_ini['file_name'] . "', 'setup_template')\">" . $lang_module['source_cat_setup'] . "</a>";
			}

			if( defined( 'NV_IS_MODADMIN' ) )
			{
				$admin_funcs[] = "<em class=\"fa fa-download fa-lg\">&nbsp;</em> <a href=\"javascript:void(0);\" onclick=\"nv_download_template('" . $_ini['file_name'] . "', 'download_template')\">" . $lang_module['source_cat_export'] . "</a>";
			}
			$me[] = array(
				'title' => $_ini['title'],
				'file_name' => $_ini['file_name'],
				'host_url' => $_ini['host_url'],
				'install_status' => $_ini['install_status'],
				'adminfuncs' => $admin_funcs
			);
		}

		$_sort = array( );
		foreach( $me as $key => $key_sort )
		{
			if( $sort == 'title' or $sort == 'install_status' )
			{
				$_sort[$key] = $key_sort[$sort];
			}
			else
			{
				$_sort[$key] = $key_sort['title'];
			}
		}
		array_multisort( $_sort, SORT_ASC, $me );

		foreach( $me as $ini )
		{
			if( !empty( $sort ) and $sort == 'not_installed' and $ini['install_status'] )
				unset( $ini );

			if( isset( $ini ) and !empty( $ini ) )
			{
				$xtpl->assign( 'INI', array(
					'title' => $ini['title'],
					'file_name' => $ini['file_name'],
					'host_url' => $ini['host_url'],
					'install_status' => $ini['install_status'] ? $lang_module['temp_installed'] : $lang_module['temp_not_installed'],
					'adminfuncs' => implode( '&nbsp;-&nbsp;', $ini['adminfuncs'] )
				) );

				$a++;
				$xtpl->assign( 'STT', $a );
				$xtpl->parse( 'main.loop.stt' );
				$xtpl->parse( 'main.loop' );
			}
		}

		$xtpl->parse( 'main' );
		$contents = $xtpl->text( 'main' );
	}
	else
	{
		$xtpl->parse( 'no_data' );
		$contents = $xtpl->text( 'no_data' );
	}
	return $contents;
}

/**
 * nv_setup_template($file_ini)
 * $catid
 *
 * @return
 *
 */

function nv_setup_template( $file_ini )
{
	global $db, $module_data, $lang_global, $lang_module, $module_name, $admin_info, $nv_Cache, $ini_construct;
	$contents = $lang_module['errorsave'];

    $rowcontent = $ini_construct;
    $row_insert = implode( ',', $ini_construct);

    $_new_content = nv_object2array( simplexml_load_file( $file_ini ) );
	$_installed = false;
	$_check_host = $db->query( 'SELECT catid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_source_cat WHERE host_url LIKE ' . $db->quote( $_new_content['host_url'] ) )->fetchColumn( );
	if( $_check_host > 0 )
	{
		$_installed = true;
	}
	// Kiem tra ini hop le va chua cai vao he thong
	if( !empty( $_new_content ) and $_installed === false )
	{
        // build query...
        $sql  = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_source_cat';
        // implode keys of $array...
        $sql .= ' ('.implode(', ', array_keys($ini_construct)).', status, weight, date_create, date_modify )';
        // implode values of $array...
        $sql .= ' VALUES (:'.implode(', :', array_keys($ini_construct)).', 1, 1, ' . NV_CURRENTTIME . ', ' . NV_CURRENTTIME . ')';
        
        //Du lieu de insert vao DB
        $data_insert = array();
        foreach ( $_new_content as $construct => $value){
            $data_insert[$construct]= (isset( $value ) and !empty( $value )) ? $value : '';
        }

		$rowcontent['catid'] = $db->insert_id( $sql, 'catid', $data_insert );
		if( $rowcontent['catid'] > 0 ) {
			nv_fix_source_cat( );
			$nv_Cache->delMod( $module_name );
			nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['add_source_cat'], $rowcontent['title'], $admin_info['userid'] );
			$contents = $lang_module['source_cat_setup_ok'];
		}
		else
		{
			$contents = $lang_module['errorsave'];
		}
	}
	else
	{
		$contents = $lang_module['sourcecat_temp_installed'];
	}
	return $contents;
}

/**
 * nv_leech_news_downloadtempl($file_src, $directory, $file_basename, $is_resume = false, $max_speed = 512)
 * $catid
 *
 * @return
 *
 */
function nv_leech_news_downloadtempl( $file_src, $directory, $file_basename, $is_resume = false, $max_speed = 512 )
{
	$download = new NukeViet\Files\Download( $file_src, $directory, $file_basename, $is_resume, $max_speed );
	$download->download_file( );
	exit( );
}

/**
 * nv_leech_news_export_func($catid)
 * $catid
 *
 * @return
 *
 */
function nv_leech_news_export_func( $catid = 0, $download = 0 )
{
	global $db, $lang_module, $lang_global, $module_name, $module_upload, $module_data, $global_array_cat, $global_config, $module_file, $ini_construct;
	$contents = $file_ini = '';
	if( $download == 1 )
	{
		if( $catid > 0 )
		{
			$_cat = $db->query( 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_source_cat WHERE catid=' . $catid . ' ORDER BY weight ASC' )->fetch( );
			if( sizeof( $_cat ) > 0 )
			{
				$_host = parse_url( $_cat['host_url'] );
				$_host = $_host['host'];
				$file_ini = NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/ini/' . $_host . '.ini';

				$contents .= '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
				$contents .= '	<config>' . "\n";
                // Xu ly mang de xua ra file ini
                // Luu y bien $ini_construct
                foreach ( array_keys ( $ini_construct ) as $construct ) {
                    $contents .= '		<' . $construct . '>' . html_entity_decode( $_cat[$construct] ) . '</' . $construct . '>' . "\n";
                }			
                $contents .= '	</config>' . "\n";
				file_put_contents( $file_ini, $contents );
			}
		}
		$contents = nv_leech_news_downloadtempl( $file_ini, NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/ini/', $_host . '.ini' );
	}
	elseif( $download == 2 )
	{
		$_ini = '/([a-zA-Z0-9\-\_]+)\.ini$/';
		$file_src = NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . NV_TEMPNAM_PREFIX . 'template_' . $module_name . '_' . md5( nv_genpass( 10 ) . NV_CHECK_SESSION ) . '.zip';
		if( file_exists( $file_src ) )
		{
			@nv_deletefile( $file_src );
		}

		$files_folders = NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/ini/';
		$zip = new PclZip( $file_src );
		$zip->add( $files_folders, PCLZIP_OPT_REMOVE_PATH, $files_folders );
		$zip->delete( PCLZIP_OPT_BY_NAME, 'index.html' );
		$filesize = @filesize( $file_src );

		if( $filesize > 0 )
		{
			$contents = nv_leech_news_downloadtempl( $file_src, NV_ROOTDIR . '/' . NV_TEMP_DIR, NV_TEMPNAM_PREFIX . $module_name . '_ini_' . nv_date( 'd_m_Y_H_i', NV_CURRENTTIME ) );
		}
	}
	return $contents;
}

/**
 * nv_leech_news_createcron($create)
 * $create
 *
 * @return
 *
 */
function nv_leech_news_createcron( $create = 0 )
{
	global $module_file;
	// Khoi tao bien rong
	$return = $contents = $file_cron = '';
	// Kiem tra lenh truyen vao va xem co ton tai file mau hay khong
	if( $create > 0 and file_exists( NV_ROOTDIR . '/modules/' . $module_file . '/nv_auto_leechnews.php.txt' ) )
	{
		// Xac dinh ten cua cron
		$file_cron = NV_ROOTDIR . '/includes/cronjobs/nv_auto_leechnews.php';
		// Lay noi dung tu file txt
		$contents .= file_get_contents( NV_ROOTDIR . '/modules/' . $module_file . '/nv_auto_leechnews.php.txt' );
		// Luu file cron
		$_write_cron = file_put_contents( $file_cron, $contents );
		if( $_write_cron !== FALSE )
		{
			$return = true;
		}
		else
		{
			// Neu luu file khong thanh cong, tra ve false va xuat ra phuong an xu ly cho quan tri
			return false;
		}
	}
	else
	{
		return false;
	}
	return $return;
}

/**
 * nv_leech_news_createcron_sql($create)
 * $create
 *
 * @return
 *
 */
function nv_leech_news_createcron_sql( $create = 0 )
{
	global $module_file, $db_config, $db;
	$_create_cron = "INSERT INTO " . $db_config['prefix'] . '_cronjobs' . " (start_time, inter_val, run_file, run_func, params, del, is_sys, act, last_time, last_result, vi_cron_name) VALUES (" . NV_CURRENTTIME . ", 60, 'nv_auto_leechnews.php', 'cron_auto_leechnews', '', 0, 0, 1, 0, 0, 'Auto leech News')";
	$result = $db->query( $_create_cron );
	return $result;
}

/**
 * nv_leech_news_checkcron( )
 *
 * @return
 *
 */
function nv_leech_news_checkcron( $run_file = 'nv_auto_leechnews.php', $run_func = 'cron_auto_leechnews' )
{
	global $module_file, $db_config, $db;
	$_checkcron = "SELECT COUNT(*) FROM " . $db_config['prefix'] . '_cronjobs' . " WHERE run_file=" . $db->quote( $run_file ) . " AND run_func=" . $db->quote( $run_func );
	$result = $db->query( $_checkcron )->fetchColumn( );
	if( $result > 0 )
	{
		return true;
	}
	else
	{
		return false;
	}
}

/**
 * nv_show_main_source_list()
 *
 * @return
 *
 */
function nv_show_main_source_list( $catid, $page = 1 )
{
	global $db, $lang_module, $lang_global, $module_name, $module_data, $global_array_cat, $global_config, $module_file, $per_page, $nv_Request;

	$base_url = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . '&' . NV_OP_VARIABLE . '=source_list';
	$page = $nv_Request->get_int( 'page', 'get', 1 );

	$xtpl = new XTemplate( 'main_source_list.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'GLANG', $lang_global );
	$xtpl->assign( 'OP', 'source_list' );
	$xtpl->assign( 'CUR_PAGE', $page );

	$_where = '';
	if( $catid > 0 )
	{
		$_where = ' WHERE catid=' . $catid;
		$xtpl->assign( 'SHOWALL', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=source_list" );
		$xtpl->parse( 'main.show_all' );
	}

	$all_page = $db->query( 'SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_source ' . $_where . ' ORDER BY date_create ASC' )->fetchColumn( );
	$sql = 'SELECT id, catid, title, cron_set, cron_lastrun, cron_schedule, status FROM ' . NV_PREFIXLANG . '_' . $module_data . '_source ' . $_where . ' ORDER BY date_create ASC LIMIT ' . ($page - 1) * $per_page . ', ' . $per_page;
	$rowall = $db->query( $sql )->fetchAll( 3 );
	$num = sizeof( $rowall );
	$a = 1;
	if( $page > 1 )
		$a = 1 + (($page - 1) * $per_page);

	$array_status = array(
		$lang_global['no'],
		$lang_global['yes']
	);

	foreach( $rowall as $row )
	{
		list( $id, $catid, $title, $cron_set, $cron_lastrun, $cron_schedule, $status ) = $row;

		if( defined( 'NV_IS_MODADMIN' ) )
		{
			$check_show = 1;
		}

		if( !empty( $check_show ) )
		{

			$admin_funcs = array( );
			$weight_disabled = $func_cat_disabled = true;
			if( $status == 1 )
			{
				$admin_funcs[] = "<i class=\"fa fa-refresh fa-lg\">&nbsp;</i> <a href=\"javascript:void(0);\" onclick=\"nv_admin_leechnews('" . $id . "', '" . md5( $id . NV_CHECK_SESSION ) . "');\">" . $lang_module['leech_news'] . "</a>";
			}
			if( defined( 'NV_IS_MODADMIN' ) )
			{
				$func_cat_disabled = false;
				$admin_funcs[] = "<em class=\"fa fa-edit fa-lg\">&nbsp;</em> <a class=\"\" href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=source&amp;id=" . $id . "#edit\">" . $lang_global['edit'] . "</a>\n";
			}
			if( defined( 'NV_IS_MODADMIN' ) )
			{
				$weight_disabled = false;
				$admin_funcs[] = "<em class=\"fa fa-trash-o fa-lg\">&nbsp;</em> <a href=\"javascript:void(0);\" onclick=\"nv_del_source('" . $id . "', '" . md5( $id . NV_CHECK_SESSION ) . "');\">" . $lang_global['delete'] . "</a>";
			}
			if( $cron_lastrun > 0 )
			{
				$cron_lastrun = nv_date( 'H:i d/m/Y', $cron_lastrun );
			}
			else
			{
				$cron_lastrun = $lang_module['cron_lastrun_notset'];
			}
			$xtpl->assign( 'ROW', array(
				'id' => $id,
				'catid' => $catid,
				'cat_title' => $global_array_cat[$catid]['title'],
				'bycat_link' => NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=source_list&amp;catid=" . $catid,
				'title' => $title,
				'cron_lastrun' => $cron_lastrun,
				'cron_schedule' => $cron_schedule,
				'adminfuncs' => implode( '&nbsp;-&nbsp;', $admin_funcs )
			) );

			$xtpl->assign( 'STT', $a );
			$xtpl->parse( 'main.data.loop.stt' );

			foreach( $array_status as $key => $val )
			{
				$xtpl->assign( 'STATUS', array(
					'key' => $key,
					'title' => $val,
					'selected' => $key == $status ? ' selected="selected"' : ''
				) );
				$xtpl->parse( 'main.data.loop.status' );
			}

			foreach( $array_status as $key => $val )
			{
				$xtpl->assign( 'CRON_SET', array(
					'key' => $key,
					'title' => $val,
					'selected' => $key == $cron_set ? ' selected="selected"' : ''
				) );
				$xtpl->parse( 'main.data.loop.cron_set' );
			}

			for( $sh = 1; $sh <= 72; $sh++ )
			{
				$xtpl->assign( 'CRON_SCHEDULE', array(
					'key' => $sh,
					'title' => $sh,
					'selected' => $sh == $cron_schedule ? ' selected="selected"' : ''
				) );
				$xtpl->parse( 'main.data.loop.cron_schedule' );
			}

			$xtpl->parse( 'main.data.loop' );
			++$a;
		}
	}
	$array_list_action = array(
		'' => $lang_module['sel_action'],
		'delete' => $lang_global['delete'],
		'admin_leechnews' => $lang_module['admin_leechnews']
	);
    foreach ($array_list_action as $key => $val) {
		if( defined( 'NV_IS_MODADMIN' ) )
		{
            $xtpl->assign('ACTION', array(
                'value' => $key,
                'title' => $val,
            ));
            $xtpl->parse( 'main.data.action' );
		}
    }

	$generate_page = nv_generate_page( $base_url, $all_page, $per_page, $page );
	if( !empty( $generate_page ) )
	{
		$xtpl->assign( 'GENERATE_PAGE', $generate_page );
		$xtpl->parse( 'main.data.generate_page' );
	}
	if( $num > 0 )
	{
		$xtpl->parse( 'main.data' );
	}
	else
	{
		$xtpl->parse( 'main.no_data' );
	}
	$xtpl->parse( 'main' );
	$contents = $xtpl->text( 'main' );

	return $contents;
}

/**
 * nv_fix_source_cat()
 *
 * @return
 *
 */
function nv_fix_source_cat( )
{
	global $db, $module_data;
	$sql = 'SELECT catid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_source_cat ORDER BY weight ASC';
	$result = $db->query( $sql );
	$weight = 0;
	while( $row = $result->fetch( ) )
	{
		++$weight;
		$sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_source_cat SET weight=' . $weight . ' WHERE catid=' . intval( $row['catid'] );
		$db->query( $sql );
	}
	$result->closeCursor( );
}

/**
 * redirect()
 *
 * @param string $msg1
 * @param string $msg2
 * @param mixed $nv_redirect
 * @return
 *
 */
function redirect( $msg1 = '', $msg2 = '', $nv_redirect, $go_back = '', $time_back = 5 )
{
	global $global_config, $module_file, $module_name;
	$xtpl = new XTemplate( 'redirect.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );

	if( empty( $nv_redirect ) )
	{
		$nv_redirect = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name;
	}
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
	$xtpl->assign( 'TIME_BACK', $time_back );
	$xtpl->assign( 'NV_REDIRECT', $nv_redirect );
	$xtpl->assign( 'MSG1', $msg1 );
	$xtpl->assign( 'MSG2', $msg2 );

	if( $go_back )
	{
		$xtpl->parse( 'main.go_back' );
	}
	else
	{
		$xtpl->parse( 'main.meta_refresh' );
	}

	$xtpl->parse( 'main' );
	$contents = $xtpl->text( 'main' );

	include NV_ROOTDIR . '/includes/header.php';
	echo nv_admin_theme( $contents );
	include NV_ROOTDIR . '/includes/footer.php';
}

/**
 * nv_show_source_mod()
 *
 * @return
 *
 */
function nv_show_source_mod( $mod_data, $id )
{
	global $db, $lang_module, $lang_global, $module_config, $module_name, $module_data, $global_config, $module_file;

	$xtpl = new XTemplate( 'source_mod.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'GLANG', $lang_global );
	$array_catid_in_row = $array_group_id_in_row = array( );
	$rowcontent = array(
		'catid' => 0,
		'target_catid' => 0,
		'target_listcatid' => array( ),
		'target_block_id' => array( )
	);
	$check_module = false;
	if( $id > 0 )
	{
		$rowcontent = $db->query( 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_source WHERE id=' . $id )->fetch( );
		if( $mod_data == $rowcontent['target_module'] )
		{
			$check_module = true;
		}
		$array_catid_in_row = explode( ',', $rowcontent['target_listcatid'] );
		$array_group_id_in_row = explode( ',', $rowcontent['target_block_id'] );
	}

	if( !empty( $mod_data ) )
	{
		$_cat = $db->query( 'SELECT catid, parentid, title, weight, lev FROM ' . NV_PREFIXLANG . '_' . $mod_data . '_cat ORDER BY sort ASC' )->fetchAll( );
		if( sizeof( $_cat ) > 0 )
		{
			foreach( $_cat as $key => $array_value )
			{
				$space = intval( $array_value['lev'] ) * 30;
				$catiddisplay = (sizeof( $array_catid_in_row ) > 1 and (in_array( $array_value['catid'], $array_catid_in_row )) and ($check_module)) ? '' : ' display: none;';
				$temp = array(
					'catid' => $array_value['catid'],
					'space' => $space,
					'title' => $array_value['title'],
					'catiddisplay' => $catiddisplay,
					'checked' => (in_array( $array_value['catid'], $array_catid_in_row ) and ($check_module)) ? ' checked="checked"' : '',
					'catidchecked' => ($array_value['catid'] == $rowcontent['target_catid'] and ($check_module)) ? ' checked="checked"' : ''
				);
				$xtpl->assign( 'CATS', $temp );
				$xtpl->parse( 'main.catid.loop' );
			}
			$xtpl->parse( 'main.catid' );
		}

		$_groups = $db->query( 'SELECT bid, title FROM ' . NV_PREFIXLANG . '_' . $mod_data . '_block_cat ORDER BY weight ASC' )->fetchAll( );
		if( sizeof( $_groups ) > 0 )
		{
			foreach( $_groups as $key => $group_array )
			{
				$_block = array(
					'bid' => $group_array['bid'],
					'title' => $group_array['title'],
					'checked' => (in_array( $group_array['bid'], $array_group_id_in_row ) and ($check_module)) ? ' checked="checked"' : ''
				);
				$xtpl->assign( 'BLOCK', $_block );
				$xtpl->parse( 'main.groups.loop' );
			}
			$xtpl->parse( 'main.groups' );
		}
	}
	$xtpl->parse( 'main' );
	$contents = $xtpl->text( 'main' );
	return $contents;
}

function nv_approve_news( $id )
{
	global $db, $db_slave, $lang_global, $lang_module, $module_config, $module_name, $module_data, $module_file, $global_config, $global_array_cat, $nv_Cache;

	$contents = "ERR_" . $id;

	$_sql = $db_slave->query( 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id = ' . $id );
	$rowcontent = $_sql->fetch( );

	$query = $db_slave->query( 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_source WHERE id = ' . $rowcontent['sid'] );
	$rows = $query->fetch( );

	$duplicate = false;
	$query = "SELECT id FROM " . NV_PREFIXLANG . "_" . $rows['target_module'] . "_rows WHERE alias=" . $db->quote( $rowcontent['alias'] );
	$query_id = $db->query( $query );
	if( $query_id->fetch( 3 ) )
	{
		$duplicate = true;
	}

	if( $rowcontent['id'] > 0 and !empty( $rowcontent['title'] ) and !empty( $rowcontent['bodyhtml'] ) and $rows['id'] > 0 and !$duplicate )
	{
		$host = html_entity_decode( $global_array_cat[$rows['catid']]['host_url'] );
		// Host

		$log_status = 1;
		$rowcontent['status'] = 1;
		if( $rows['source_moderate'] > 0 )
		{
			$rowcontent['status'] = 5;
			$log_status = 3;
		}
		$rowcontent['gid'] = $rowcontent['exptime'] = $rowcontent['topicid'] = $rowcontent['sourceid'] = 0;
		$rowcontent['sourcetext'] = '';

		// Giu link bai viet goc
		if( $rows['source_keep_source'] == 1 )
		{
			$rowcontent['sourcetext'] = $rowcontent['news_url'];
			$url_info = @parse_url( $rowcontent['sourcetext'] );
			// Xu ly nguon tin
			if( isset( $url_info['scheme'] ) and isset( $url_info['host'] ) )
			{
				$sourceid_link = $url_info['scheme'] . '://' . $url_info['host'];
				$stmt = $db->prepare( 'SELECT sourceid FROM ' . NV_PREFIXLANG . '_' . $rows['target_module'] . '_sources WHERE link= :link' );
				$stmt->bindParam( ':link', $sourceid_link, PDO::PARAM_STR );
				$stmt->execute( );
				$rowcontent['sourceid'] = $stmt->fetchColumn( );

				if( empty( $rowcontent['sourceid'] ) )
				{
					$weight = $db->query( 'SELECT max(weight) FROM ' . NV_PREFIXLANG . '_' . $rows['target_module'] . '_sources' )->fetchColumn( );
					$weight = intval( $weight ) + 1;
					$_sql = "INSERT INTO " . NV_PREFIXLANG . "_" . $rows['target_module'] . "_sources (title, link, logo, weight, add_time, edit_time) VALUES ( :title ,:sourceid_link, '', :weight, " . NV_CURRENTTIME . ", " . NV_CURRENTTIME . ")";

					$data_insert = array( );
					$data_insert['title'] = $url_info['host'];
					$data_insert['sourceid_link'] = $sourceid_link;
					$data_insert['weight'] = $weight;

					$rowcontent['sourceid'] = $db->insert_id( $_sql, 'sourceid', $data_insert );
				}
			}
			else
			{
				$stmt = $db->prepare( 'SELECT sourceid FROM ' . NV_PREFIXLANG . '_' . $rows['target_module'] . '_sources WHERE title= :title' );
				$stmt->bindParam( ':title', $rowcontent['sourcetext'], PDO::PARAM_STR );
				$stmt->execute( );
				$rowcontent['sourceid'] = $stmt->fetchColumn( );

				if( empty( $rowcontent['sourceid'] ) )
				{
					$weight = $db->query( 'SELECT max(weight) FROM ' . NV_PREFIXLANG . '_' . $rows['target_module'] . '_sources' )->fetchColumn( );
					$weight = intval( $weight ) + 1;
					$_sql = "INSERT INTO " . NV_PREFIXLANG . "_" . $rows['target_module'] . "_sources (title, link, logo, weight, add_time, edit_time) VALUES ( :title, '', '', " . $weight . " , " . NV_CURRENTTIME . ", " . NV_CURRENTTIME . ")";
					$data_insert = array( );
					$data_insert['title'] = $rowcontent['sourcetext'];

					$rowcontent['sourceid'] = $db->insert_id( $_sql, 'sourceid', $data_insert );
				}
			}
		}

		$rowcontent['homeimgthumb'] = 3;
		if( empty( $rowcontent['thumb'] ) )
		{
			$rowcontent['homeimgthumb'] = '';
		}
		if( $rows['source_getthumb'] == 1 )
		{
			if( !empty( $rowcontent['thumb'] ) )
			{
				$_href = nv_save_thumb( $rowcontent['thumb'], $rows['target_module'] );
				$rowcontent['thumb'] = $_href;
				$rowcontent['homeimgthumb'] = 2;
			}
		}
		$save_time = NV_CURRENTTIME - rand( 60, 120 );
		$sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $rows['target_module'] . '_rows
			(catid, listcatid, topicid, admin_id, author, sourceid, addtime, edittime, status, publtime, exptime, archive, title, alias, hometext, homeimgfile, homeimgalt, homeimgthumb, inhome, allowed_comm, allowed_rating, hitstotal, hitscm, total_rating, click_rating) VALUES
			 (' . intval( $rows['target_catid'] ) . ',
			 :listcatid,
			 ' . $rowcontent['topicid'] . ',
			 ' . intval( $module_config[$module_name]['cron_user'] ) . ',
			 :author,
			 ' . intval( $rowcontent['sourceid'] ) . ',
			 ' . $save_time . ',
			 ' . $save_time . ',
			 ' . intval( $rowcontent['status'] ) . ',
			 ' . $save_time . ',
			 ' . intval( $rowcontent['exptime'] ) . ',
			 1,
			 :title,
			 :alias,
			 :hometext,
			 :homeimgfile,
			 :homeimgalt,
			 :homeimgthumb,
			 1,
			 4,
			 1,
			 1,
			 0,
			 0,
			 0)';

		$data_insert = array( );
		$data_insert['listcatid'] = $rows['target_listcatid'];
		$data_insert['author'] = $rowcontent['author'];
		$data_insert['title'] = $rowcontent['title'];
		$data_insert['alias'] = $rowcontent['alias'];
		$data_insert['hometext'] = $rowcontent['hometext'];
		$data_insert['homeimgfile'] = $rowcontent['thumb'];
		$data_insert['homeimgalt'] = $rowcontent['homeimgalt'];
		$data_insert['homeimgthumb'] = $rowcontent['homeimgthumb'];

		$rowcontent['id'] = $db->insert_id( $sql, 'id', $data_insert );

		if( $rowcontent['id'] > 0 )
		{
			nv_insert_logs( NV_LANG_DATA, $rows['target_module'], $lang_module['target_content_add'], $rowcontent['title'], $module_config[$module_name]['cron_user'] );
			$rowcontent['bodyhtml'] = html_entity_decode( $rowcontent['bodyhtml'] );
			$rowcontent['bodyhtml'] = str_get_html( $rowcontent['bodyhtml'] );

			foreach( $rowcontent ['bodyhtml']->find ( 'a' ) as $_href_i )
			{
				$_link = $_href_i->href;
				$_href_i->href = nv_phpuri( $module_file, $host, $_link );
			}

			if( $rows['source_getimage'] == 1 )
			{
				$_m = 0;
				foreach( $rowcontent ['bodyhtml']->find ( 'img' ) as $_img_i )
				{
					$_m++;
					$img = $_img_i->src;
					$_img_i->src = nv_save_img( $img, $rows['target_module'], $rowcontent['id'] . $_m );
				}
			}
			$rowcontent['bodyhtml'] = $rowcontent['bodyhtml']->outertext;

			// Lam sach style bodyhtml
			if( $rows['source_cleanup_html'] == 1 )
			{
				$rowcontent['bodyhtml'] = nv_purifier( $module_file, $rowcontent['bodyhtml'] );
			}
			$ct_query = array( );
			if( $global_config['version'] == '4.1.00' )
			{
				$stmt = $db->prepare( 'INSERT INTO ' . NV_PREFIXLANG . '_' . $rows['target_module'] . '_detail VALUES
					(' . $rowcontent['id'] . ',
					 :titlesite,
					 :description,
					 :bodyhtml,
					 :sourcetext,
					 ' . intval( $module_config[$module_name]['thumb_position'] ) . ',
					 0,
					 1,
					 1,
					 1,
					 ' . $rowcontent['gid'] . '
					 )' );
				$stmt->bindParam( ':titlesite', $rowcontent['title'], PDO::PARAM_STR, strlen( $rowcontent['title'] ) );
				$stmt->bindParam( ':description', $rowcontent['hometext'], PDO::PARAM_STR, strlen( $rowcontent['hometext'] ) );
				$stmt->bindParam( ':bodyhtml', $rowcontent['bodyhtml'], PDO::PARAM_STR, strlen( $rowcontent['bodyhtml'] ) );
				$stmt->bindParam( ':sourcetext', $rowcontent['sourcetext'], PDO::PARAM_STR, strlen( $rowcontent['sourcetext'] ) );
				$ct_query[] = ( int )$stmt->execute( );
			}
			elseif( $global_config['version'] == '4.0.29' )
			{
				$stmt = $db->prepare( 'INSERT INTO ' . NV_PREFIXLANG . '_' . $rows['target_module'] . '_detail VALUES
				(' . $rowcontent['id'] . ',
				 :bodyhtml,
				 :sourcetext,
				 ' . intval( $module_config[$module_name]['thumb_position'] ) . ',
				 0,
				 1,
				 1,
				 1,
				 ' . $rowcontent['gid'] . '
				 )' );
				$stmt->bindParam( ':bodyhtml', $rowcontent['bodyhtml'], PDO::PARAM_STR, strlen( $rowcontent['bodyhtml'] ) );
				$stmt->bindParam( ':sourcetext', $rowcontent['sourcetext'], PDO::PARAM_STR, strlen( $rowcontent['sourcetext'] ) );
				$ct_query[] = ( int )$stmt->execute( );
			}

			// Cac chuyen muc
			$catids = explode( ',', $rows['target_listcatid'] );
			foreach( $catids as $catid )
			{
				$ct_query[] = ( int )$db->exec( 'INSERT INTO ' . NV_PREFIXLANG . '_' . $rows['target_module'] . '_' . $catid . ' SELECT * FROM ' . NV_PREFIXLANG . '_' . $rows['target_module'] . '_rows WHERE id=' . $rowcontent['id'] );
			}

			if( array_sum( $ct_query ) != sizeof( $ct_query ) )
			{
				$error .= $lang_module['errorsave'];
			}
			unset( $ct_query );

			// Nhom tin
			if( !empty( $rows['target_block_id'] ) )
			{
				$id_block_content_new = explode( ',', $rows['target_block_id'] );
				foreach( $id_block_content_new as $bid_i )
				{
					$db->query( 'INSERT INTO ' . NV_PREFIXLANG . '_' . $rows['target_module'] . '_block (bid, id, weight) VALUES (' . $bid_i . ', ' . $rowcontent['id'] . ', 0)' );
				}
			}

			// Tu dong xac dinh keywords
			$keywords = '';
			// Tao keywords tu noi dung bai viet
			if( $rows['source_getkeywords'] == 1 )
			{
				$keywords .= ($rowcontent['hometext'] != '') ? $rowcontent['hometext'] : $rowcontent['bodyhtml'];
				$keywords = nv_get_keywords( $keywords, 100 );
			}
			$keywords = explode( ',', $keywords );

			// Uu tien loc tu khoa theo cac tu khoa da co trong tags thay vi doc tu tu dien
			$keywords_return = array( );
			foreach( $keywords as $keyword_i )
			{
				$sth = $db->prepare( 'SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $rows['target_module'] . '_tags_id where keyword = :keyword' );
				$sth->bindParam( ':keyword', $keyword_i, PDO::PARAM_STR );
				$sth->execute( );
				if( $sth->fetchColumn( ) )
				{
					$keywords_return[] = $keyword_i;
					if( sizeof( $keywords_return ) > 20 )
					{
						break;
					}
				}
			}

			if( sizeof( $keywords_return ) < 20 )
			{
				foreach( $keywords as $keyword_i )
				{
					if( !in_array( $keyword_i, $keywords_return ) )
					{
						$keywords_return[] = $keyword_i;
						if( sizeof( $keywords_return ) > 20 )
						{
							break;
						}
					}
				}
			}

			$rowcontent['keywords'] = implode( ',', $keywords_return ) . ',' . $rowcontent['keywords'];
			if( $rowcontent['keywords'] != '' )
			{
				$keywords = explode( ',', $rowcontent['keywords'] );
				$keywords = array_map( 'strip_punctuation', $keywords );
				$keywords = array_map( 'trim', $keywords );
				$keywords = array_diff( $keywords, array( '' ) );
				$keywords = array_unique( $keywords );

				foreach( $keywords as $keyword )
				{
					$keyword = nv_unhtmlspecialchars( $keyword );
					$keyword = str_replace( '&', ' ', $keyword );
					$alias_i = change_alias( $keyword );
					$alias_i = nv_strtolower( $alias_i );
					$sth = $db->prepare( 'SELECT tid, alias, description, keywords FROM ' . NV_PREFIXLANG . '_' . $rows['target_module'] . '_tags where alias= :alias OR FIND_IN_SET(:keyword, keywords)>0' );
					$sth->bindParam( ':alias', $alias_i, PDO::PARAM_STR );
					$sth->bindParam( ':keyword', $keyword, PDO::PARAM_STR );
					$sth->execute( );

					list( $tid, $alias, $keywords_i ) = $sth->fetch( 3 );
					if( empty( $tid ) )
					{
						$array_insert = array( );
						$array_insert['alias'] = $alias_i;
						$array_insert['keyword'] = $keyword;

						$tid = $db->insert_id( "INSERT INTO " . NV_PREFIXLANG . "_" . $rows['target_module'] . "_tags (numnews, alias, description, image, keywords) VALUES (1, :alias, '', '', :keyword)", "tid", $array_insert );
					}
					else
					{
						if( $alias != $alias_i )
						{
							if( !empty( $keywords_i ) )
							{
								$keyword_arr = explode( ',', $keywords_i );
								$keyword_arr[] = $keyword;
								$keywords_i2 = implode( ',', array_unique( $keyword_arr ) );
							}
							else
							{
								$keywords_i2 = $keyword;
							}
							if( $keywords_i != $keywords_i2 )
							{
								$sth = $db->prepare( 'UPDATE ' . NV_PREFIXLANG . '_' . $rows['target_module'] . '_tags SET keywords= :keywords WHERE tid =' . $tid );
								$sth->bindParam( ':keywords', $keywords_i2, PDO::PARAM_STR );
								$sth->execute( );
							}
						}
						$db->query( 'UPDATE ' . NV_PREFIXLANG . '_' . $rows['target_module'] . '_tags SET numnews = numnews+1 WHERE tid = ' . $tid );
					}

					// insert keyword for table _tags_id
					try
					{
						$sth = $db->prepare( 'INSERT INTO ' . NV_PREFIXLANG . '_' . $rows['target_module'] . '_tags_id (id, tid, keyword) VALUES (' . $rowcontent['id'] . ', ' . intval( $tid ) . ', :keyword)' );
						$sth->bindParam( ':keyword', $keyword, PDO::PARAM_STR );
						$sth->execute( );
					}
					catch ( PDOException $e )
					{
						$sth = $db->prepare( 'UPDATE ' . NV_PREFIXLANG . '_' . $rows['target_module'] . '_tags_id SET keyword = :keyword WHERE id = ' . $rowcontent['id'] . ' AND tid=' . intval( $tid ) );
						$sth->bindParam( ':keyword', $keyword, PDO::PARAM_STR );
						$sth->execute( );
					}
				}
			}
			// Update status of approved news_url
			$sth = $db->prepare( 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_rows SET status = ' . $log_status . ' WHERE id = ' . $id );
			$sth->execute( );

			$nv_Cache->delMod( $rows['target_module'] );
			$contents = "OK_" . $lang_module['approve_report_ok'];
		}
	}
	else
	{
		$contents = "OK_" . $lang_module['approve_report_dup'];
	}
	unset( $rows, $rowcontent );
	return $contents;
}

function nv_deny_news( $id )
{
	global $db, $module_name, $module_data, $lang_module, $admin_info, $nv_Cache;
	$contents = "ERR_" . $id;
	list( $id, $title ) = $db->query( 'SELECT id, title FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id=' . intval( $id ) )->fetch( 3 );
	if( $id > 0 )
	{
		$db->query( "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_rows SET status=99 WHERE id=" . $id );
		$nv_Cache->delMod( $module_name );
		$contents = "OK_" . $lang_module['deny_report_ok'];
	}
	return $contents;
}
