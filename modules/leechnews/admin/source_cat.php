<?php

/**
 * @Project FEEDNEWS ON NUKEVIET 4.x
 * @Author KENNYNGUYEN (nguyentiendat713@gmail.com)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 08/25/2015 10:27
 */
if( !defined( 'NV_IS_FILE_ADMIN' ) )
	die( 'Stop!!!' );
$page_title = $lang_module['update_source_cat'];
$error = array( );
$rowcontent = array(
	'catid' => '',
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
	'detail_author_replace' => '',
	'status' => 1,
	'weight' => 0,
	'date_create' => NV_CURRENTTIME,
	'date_modify' => NV_CURRENTTIME,
	'mode' => 'add'
);

$rowcontent['catid'] = $nv_Request->get_int( 'catid', 'get,post', 0 );

$array_list_action = array(
	'delete' => $lang_global['delete'],
	'admin_leechnews' => $lang_module['admin_leechnews']
);

if( $rowcontent['catid'] > 0 )
{
	$check_permission = false;
	$rowcontent = $db->query( 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_source_cat WHERE catid=' . $rowcontent['catid'] )->fetch( );
	if( !empty( $rowcontent['catid'] ) )
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
	$page_title = $lang_module['edit_source_cat'];
}

if( $nv_Request->get_int( 'save', 'post' ) == 1 )
{
	$rowcontent['title'] = $nv_Request->get_title( 'title', 'post', '', 1 );
	$rowcontent['host_url'] = $nv_Request->get_title( 'host_url', 'post' );
	$rowcontent['logo'] = $nv_Request->get_title( 'logo', 'post' );
	$rowcontent['block_pattern'] = $nv_Request->get_title( 'block_pattern', 'post' );
	$rowcontent['block_title'] = $nv_Request->get_title( 'block_title', 'post' );
	$rowcontent['block_title_remove'] = $nv_Request->get_title( 'block_title_remove', 'post', '', 1 );
	$rowcontent['block_title_replace'] = $nv_Request->get_title( 'block_title_replace', 'post', '', 1 );
	$rowcontent['block_url'] = $nv_Request->get_title( 'block_url', 'post', '', 1 );
	$rowcontent['block_hometext'] = $nv_Request->get_title( 'block_hometext', 'post', '', 1 );
	$rowcontent['block_hometext_remove'] = $nv_Request->get_title( 'block_hometext_remove', 'post', '', 1 );
	$rowcontent['block_hometext_replace'] = $nv_Request->get_title( 'block_hometext_replace', 'post', '', 1 );
	$rowcontent['block_thumb'] = $nv_Request->get_title( 'block_thumb', 'post', '', 1 );
	$rowcontent['block_thumb_alt'] = $nv_Request->get_title( 'block_thumb_alt', 'post', '', 1 );
	$rowcontent['block_thumb_attribute'] = $nv_Request->get_title( 'block_thumb_attribute', 'post', '', 1 );
	$rowcontent['detail_bodyhtml'] = $nv_Request->get_title( 'detail_bodyhtml', 'post', '', 1 );
	$rowcontent['detail_bodyhtml_remove'] = $nv_Request->get_title( 'detail_bodyhtml_remove', 'post', '', 1 );
	$rowcontent['detail_bodyhtml_replace'] = $nv_Request->get_title( 'detail_bodyhtml_replace', 'post', '', 1 );
	$rowcontent['detail_bodyhtml_attribute'] = $nv_Request->get_title( 'detail_bodyhtml_attribute', 'post', '', 1 );
	$rowcontent['detail_hometext'] = $nv_Request->get_title( 'detail_hometext', 'post', '', 1 );
	$rowcontent['detail_hometext_remove'] = $nv_Request->get_title( 'detail_hometext_remove', 'post', '', 1 );
	$rowcontent['detail_hometext_replace'] = $nv_Request->get_title( 'detail_hometext_replace', 'post', '', 1 );
	$rowcontent['detail_author'] = $nv_Request->get_title( 'detail_author', 'post', '', 1 );
	$rowcontent['detail_author_replace'] = $nv_Request->get_title( 'detail_author_replace', 'post', '', 1 );
	$rowcontent['status'] = $nv_Request->get_int( 'status', 'post', 1 );

	if( !nv_is_url( $rowcontent['host_url'] ) )
	{
		$rowcontent['host_url'] = '';
		$error[] = $lang_module['error_wronghost_url'];
	}
	elseif( empty( $rowcontent['title'] ) )
	{
		$error[] = $lang_module['error_title'];
	}
	elseif( empty( $rowcontent['host_url'] ) )
	{
		$error[] = $lang_module['error_host_url'];
	}

	if( empty( $error ) )
	{
		if( $rowcontent['catid'] == 0 )
		{
			$sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_source_cat
				( title, host_url, logo, block_pattern, block_title, block_title_remove, block_title_replace, block_url, block_hometext, block_hometext_remove, block_hometext_replace, block_thumb, block_thumb_alt, block_thumb_attribute, detail_bodyhtml, detail_bodyhtml_remove, detail_bodyhtml_replace, detail_bodyhtml_attribute, detail_hometext, detail_hometext_remove, detail_hometext_replace, detail_author, detail_author_replace, status, weight, date_create, date_modify) VALUES
				 (:title,
				 :host_url,
				 :logo,
				 :block_pattern,
				 :block_title,
				 :block_title_remove,
				 :block_title_replace,
				 :block_url,
				 :block_hometext,
				 :block_hometext_remove,
				 :block_hometext_replace,
				 :block_thumb,
				 :block_thumb_alt,
				 :block_thumb_attribute,
				 :detail_bodyhtml,
				 :detail_bodyhtml_remove,
				 :detail_bodyhtml_replace,
				 :detail_bodyhtml_attribute,
				 :detail_hometext,
				 :detail_hometext_remove,
				 :detail_hometext_replace,
				 :detail_author,
				 :detail_author_replace,
				 :status,
				  ' . intval( $rowcontent['weight'] ) . ',
				  ' . intval( $rowcontent['date_create'] ) . ',
				  ' . intval( $rowcontent['date_modify'] ) . ')';

			$data_insert = array( );
			$data_insert['title'] = $rowcontent['title'];
			$data_insert['host_url'] = $rowcontent['host_url'];
			$data_insert['logo'] = $rowcontent['logo'];
			$data_insert['block_pattern'] = $rowcontent['block_pattern'];
			$data_insert['block_title'] = $rowcontent['block_title'];
			$data_insert['block_title_remove'] = $rowcontent['block_title_remove'];
			$data_insert['block_title_replace'] = $rowcontent['block_title_replace'];
			$data_insert['block_url'] = $rowcontent['block_url'];
			$data_insert['block_hometext'] = $rowcontent['block_hometext'];
			$data_insert['block_hometext_remove'] = $rowcontent['block_hometext_remove'];
			$data_insert['block_hometext_replace'] = $rowcontent['block_hometext_replace'];
			$data_insert['block_thumb'] = $rowcontent['block_thumb'];
			$data_insert['block_thumb_alt'] = $rowcontent['block_thumb_alt'];
			$data_insert['block_thumb_attribute'] = $rowcontent['block_thumb_attribute'];
			$data_insert['detail_bodyhtml'] = $rowcontent['detail_bodyhtml'];
			$data_insert['detail_bodyhtml_remove'] = $rowcontent['detail_bodyhtml_remove'];
			$data_insert['detail_bodyhtml_replace'] = $rowcontent['detail_bodyhtml_replace'];
			$data_insert['detail_bodyhtml_attribute'] = $rowcontent['detail_bodyhtml_attribute'];
			$data_insert['detail_hometext'] = $rowcontent['detail_hometext'];
			$data_insert['detail_hometext_remove'] = $rowcontent['detail_hometext_remove'];
			$data_insert['detail_hometext_replace'] = $rowcontent['detail_hometext_replace'];
			$data_insert['detail_author'] = $rowcontent['detail_author'];
			$data_insert['detail_author_replace'] = $rowcontent['detail_author_replace'];
			$data_insert['status'] = $rowcontent['status'];

			$rowcontent['catid'] = $db->insert_id( $sql, 'catid', $data_insert );
			if( $rowcontent['catid'] > 0 )
			{
				nv_fix_source_cat( );
				nv_leech_news_export_func( $rowcontent['catid'], 0 );
				$nv_Cache->delMod( $module_name );
				nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['add_source_cat'], $rowcontent['title'], $admin_info['userid'] );
				$url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name;
				$msg1 = $lang_module['source_cat_added'];
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
			$sth = $db->prepare( 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_source_cat SET
				 title=:title,
				 host_url=:host_url,
				 logo=:logo,
				 block_pattern=:block_pattern,
				 block_title=:block_title,
				 block_title_remove=:block_title_remove,
				 block_title_replace=:block_title_replace,
				 block_url=:block_url,
				 block_hometext=:block_hometext,
				 block_hometext_remove=:block_hometext_remove,
				 block_hometext_replace=:block_hometext_replace,
				 block_thumb=:block_thumb,
				 block_thumb_alt=:block_thumb_alt,
				 block_thumb_attribute=:block_thumb_attribute,
				 detail_bodyhtml=:detail_bodyhtml,
				 detail_bodyhtml_remove=:detail_bodyhtml_remove,
				 detail_bodyhtml_replace=:detail_bodyhtml_replace,
				 detail_bodyhtml_attribute=:detail_bodyhtml_attribute,
				 detail_hometext=:detail_hometext,
				 detail_hometext_remove=:detail_hometext_remove,
				 detail_hometext_replace=:detail_hometext_replace,
				 detail_author=:detail_author,
				 detail_author_replace=:detail_author_replace,
				 status=:status,
				 date_modify=' . NV_CURRENTTIME . '
				WHERE catid =' . $rowcontent['catid'] );

			$sth->bindParam( ':title', $rowcontent['title'], PDO::PARAM_STR );
			$sth->bindParam( ':host_url', $rowcontent['host_url'], PDO::PARAM_STR );
			$sth->bindParam( ':logo', $rowcontent['logo'], PDO::PARAM_STR );
			$sth->bindParam( ':block_pattern', $rowcontent['block_pattern'], PDO::PARAM_STR );
			$sth->bindParam( ':block_title', $rowcontent['block_title'], PDO::PARAM_STR );
			$sth->bindParam( ':block_title_remove', $rowcontent['block_title_remove'], PDO::PARAM_STR );
			$sth->bindParam( ':block_title_replace', $rowcontent['block_title_replace'], PDO::PARAM_STR );
			$sth->bindParam( ':block_url', $rowcontent['block_url'], PDO::PARAM_STR );
			$sth->bindParam( ':block_hometext', $rowcontent['block_hometext'], PDO::PARAM_STR );
			$sth->bindParam( ':block_hometext_remove', $rowcontent['block_hometext_remove'], PDO::PARAM_STR );
			$sth->bindParam( ':block_hometext_replace', $rowcontent['block_hometext_replace'], PDO::PARAM_STR );
			$sth->bindParam( ':block_thumb', $rowcontent['block_thumb'], PDO::PARAM_STR );
			$sth->bindParam( ':block_thumb_alt', $rowcontent['block_thumb_alt'], PDO::PARAM_STR );
			$sth->bindParam( ':block_thumb_attribute', $rowcontent['block_thumb_attribute'], PDO::PARAM_STR );
			$sth->bindParam( ':detail_bodyhtml', $rowcontent['detail_bodyhtml'], PDO::PARAM_STR );
			$sth->bindParam( ':detail_bodyhtml_remove', $rowcontent['detail_bodyhtml_remove'], PDO::PARAM_STR );
			$sth->bindParam( ':detail_bodyhtml_replace', $rowcontent['detail_bodyhtml_replace'], PDO::PARAM_STR );
			$sth->bindParam( ':detail_bodyhtml_attribute', $rowcontent['detail_bodyhtml_attribute'], PDO::PARAM_STR );
			$sth->bindParam( ':detail_hometext', $rowcontent['detail_hometext'], PDO::PARAM_STR );
			$sth->bindParam( ':detail_hometext_remove', $rowcontent['detail_hometext_remove'], PDO::PARAM_STR );
			$sth->bindParam( ':detail_hometext_replace', $rowcontent['detail_hometext_replace'], PDO::PARAM_STR );
			$sth->bindParam( ':detail_author', $rowcontent['detail_author'], PDO::PARAM_STR );
			$sth->bindParam( ':detail_author_replace', $rowcontent['detail_author_replace'], PDO::PARAM_STR );
			$sth->bindParam( ':status', $rowcontent['status'], PDO::PARAM_STR );

			if( $sth->execute( ) )
			{
				nv_leech_news_export_func( $rowcontent['catid'], 0 );
				$nv_Cache->delMod( $module_name );
				nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['edit_source_cat'], $rowcontent['title'], $admin_info['userid'] );
				$url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name;
				$msg1 = $lang_module['source_cat_updated'] . ' ' . $rowcontent['title'];
				$msg2 = $lang_module['source_cat_back'] . ' ' . $lang_module['source_cat'];
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
$xtpl->assign( 'SOURCECAT_LIST', nv_show_sourcecat_list( ) );

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

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );
if( $rowcontent['catid'] > 0 )
{
	$op = '';
}

include (NV_ROOTDIR . "/includes/header.php");
echo nv_admin_theme( $contents );
include (NV_ROOTDIR . "/includes/footer.php");
