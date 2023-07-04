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
$page_title = $lang_module['add_source'];

$rowcontent = array(
	'catid' => 0,
	'title' => '',
	'source_url' => '',
	'target_module' => '',
	'target_catid' => '',
	'target_listcatid' => '',
	'target_block_id' => '',
	'source_numget' => 5,
	'source_moderate' => '',
	'source_jump_url' => '',
	'source_jump_from' => '',
	'source_jump_to' => '',
	'source_clearpage_jump' => '',
	'source_autohometext' => '',
	'source_hometext_limit' => '60',
	'source_getthumb' => '',
	'source_getimage' => '',
	'source_img_stamp' => '',
	'source_gettags' => '',
	'source_getkeywords' => '',
	'source_getkeywords_des' => '',
	'source_getthumbs' => '',
	'source_clearlinks' => '',
	'source_keep_source' => '',
	'source_cleanup_html' => '',
    'source_cleanup_img' => '',
	'cron_set' => 1,
	'cron_lastrun' => 0,
	'cron_schedule' => '',
	'status' => 1,
	'date_create' => NV_CURRENTTIME,
	'date_modify' => NV_CURRENTTIME,
	'mode' => 'add'
);

$rowcontent['id'] = $nv_Request->get_int( 'id', 'get,post', 0 );

if( $rowcontent['id'] > 0 )
{
	$check_permission = false;
	$rowcontent = $db->query( 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_source WHERE id=' . $rowcontent['id'] )->fetch( );
	if( !empty( $rowcontent['id'] ) )
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
	$page_title = $lang_module['edit_source'];
}

if( $nv_Request->get_int( 'save', 'post' ) == 1 )
{

	$rowcontent['target_catid'] = $nv_Request->get_int( 'target_catid', 'post', 0 );
	$catids = array_unique( $nv_Request->get_typed_array( 'target_catids', 'post', 'int', array( ) ) );
	$rowcontent['target_listcatid'] = implode( ',', $catids );

	$groups = array_unique( $nv_Request->get_typed_array( 'target_block_ids', 'post', 'int', array( ) ) );
	$rowcontent['target_block_id'] = implode( ',', $groups );

	$rowcontent['catid'] = $nv_Request->get_int( 'catid', 'post', 0 );
	$rowcontent['title'] = $nv_Request->get_title( 'title', 'post', '', 1 );
	$rowcontent['source_url'] = $nv_Request->get_title( 'source_url', 'post', '', 1 );
	$rowcontent['target_module'] = $nv_Request->get_title( 'target_module', 'post' );
	$rowcontent['source_numget'] = $nv_Request->get_int( 'source_numget', 'post', 0 );
	$rowcontent['source_moderate'] = $nv_Request->get_int( 'source_moderate', 'post', 0 );
	$rowcontent['source_jump_url'] = $nv_Request->get_title( 'source_jump_url', 'post', 0 );
	$rowcontent['source_jump_from'] = $nv_Request->get_int( 'source_jump_from', 'post', 0 );
	$rowcontent['source_jump_to'] = $nv_Request->get_int( 'source_jump_to', 'post', 0 );
	$rowcontent['source_clearpage_jump'] = $nv_Request->get_int( 'source_clearpage_jump', 'post', 0 );
	$rowcontent['source_autohometext'] = $nv_Request->get_int( 'source_autohometext', 'post', 0 );
	$rowcontent['source_hometext_limit'] = $nv_Request->get_int( 'source_hometext_limit', 'post', 60 );
	$rowcontent['source_getthumb'] = $nv_Request->get_int( 'source_getthumb', 'post', 0 );
	$rowcontent['source_getimage'] = $nv_Request->get_int( 'source_getimage', 'post', 0 );
	$rowcontent['source_img_stamp'] = $nv_Request->get_int( 'source_img_stamp', 'post', 0 );
	$rowcontent['source_gettags'] = $nv_Request->get_int( 'source_gettags', 'post', 0 );
	$rowcontent['source_getkeywords'] = $nv_Request->get_int( 'source_getkeywords', 'post', 0 );
	$rowcontent['source_getkeywords_des'] = $nv_Request->get_int( 'source_getkeywords_des', 'post', 0 );
	$rowcontent['source_getthumbs'] = $nv_Request->get_int( 'source_getthumbs', 'post', 0 );
	$rowcontent['source_clearlinks'] = $nv_Request->get_int( 'source_clearlinks', 'post', 0 );
	$rowcontent['source_keep_source'] = $nv_Request->get_int( 'source_keep_source', 'post', 0 );
	$rowcontent['source_cleanup_html'] = $nv_Request->get_int( 'source_cleanup_html', 'post', 0 );
	$rowcontent['source_cleanup_img'] = $nv_Request->get_int ( 'source_cleanup_img', 'post', 0 );
	$rowcontent['cron_schedule'] = $nv_Request->get_int( 'cron_schedule', 'post', 0 );
	$rowcontent['status'] = $nv_Request->get_int( 'status', 'post', 1 );

	if( empty( $rowcontent['title'] ) )
	{
		$error[] = $lang_module['error_title'];
	}
	elseif( empty( $rowcontent['catid'] ) )
	{
		$error[] = $lang_module['error_source_catid'];
	}
	elseif( empty( $rowcontent['source_url'] ) )
	{
		$error[] = $lang_module['error_source_url'];
	}
	elseif( empty( $rowcontent['target_module'] ) )
	{
		$error[] = $lang_module['error_source_target_module'];
	}
	elseif( empty( $rowcontent['target_listcatid'] ) )
	{
		$error[] = $lang_module['error_source_target_catid'];
	}

	if( empty( $error ) )
	{
		if( !empty( $catids ) )
		{
			$rowcontent['target_catid'] = in_array( $rowcontent['target_catid'], $catids ) ? $rowcontent['target_catid'] : $catids[0];
		}
		if( $rowcontent['id'] == 0 )
		{
			$sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_source
				( catid, title, source_url, target_module, target_catid, target_listcatid, target_block_id, source_numget, source_moderate, source_jump_url, source_jump_from, source_jump_to, source_clearpage_jump, source_autohometext, source_hometext_limit, source_getthumb, source_getimage, source_img_stamp, source_gettags, source_getkeywords, source_getkeywords_des, source_getthumbs, source_clearlinks, source_keep_source, source_cleanup_html, source_cleanup_img, cron_set, cron_lastrun, cron_schedule, status, date_create, date_modify) VALUES
				 (:catid,
				 :title,
				 :source_url,
				 :target_module,
				 :target_catid,
				 :target_listcatid,
				 :target_block_id,
				 :source_numget,
				 :source_moderate,
				 :source_jump_url,
				 :source_jump_from,
				 :source_jump_to,
				 :source_clearpage_jump,
				 :source_autohometext,
				 :source_hometext_limit,
				 :source_getthumb,
				 :source_getimage,
				 :source_img_stamp,
				 :source_gettags,
				 :source_getkeywords,
				 :source_getkeywords_des,
				 :source_getthumbs,
				 :source_clearlinks,
				 :source_keep_source,
				 :source_cleanup_html,
				 :source_cleanup_img,
				 :cron_set,
				 :cron_lastrun,
				 :cron_schedule,
				 :status,
				  ' . intval( $rowcontent['date_create'] ) . ',
				  ' . intval( $rowcontent['date_modify'] ) . ')';

			$data_insert = array( );
			$data_insert['catid'] = $rowcontent['catid'];
			$data_insert['title'] = $rowcontent['title'];
			$data_insert['source_url'] = $rowcontent['source_url'];
			$data_insert['target_module'] = $rowcontent['target_module'];
			$data_insert['target_catid'] = $rowcontent['target_catid'];
			$data_insert['target_listcatid'] = $rowcontent['target_listcatid'];
			$data_insert['target_block_id'] = $rowcontent['target_block_id'];
			$data_insert['source_numget'] = $rowcontent['source_numget'];
			$data_insert['source_moderate'] = $rowcontent['source_moderate'];
			$data_insert['source_jump_url'] = $rowcontent['source_jump_url'];
			$data_insert['source_jump_from'] = $rowcontent['source_jump_from'];
			$data_insert['source_jump_to'] = $rowcontent['source_jump_to'];
			$data_insert['source_clearpage_jump'] = $rowcontent['source_clearpage_jump'];
			$data_insert['source_autohometext'] = $rowcontent['source_autohometext'];
			$data_insert['source_hometext_limit'] = $rowcontent['source_hometext_limit'];
			$data_insert['source_getthumb'] = $rowcontent['source_getthumb'];
			$data_insert['source_getimage'] = $rowcontent['source_getimage'];
			$data_insert['source_img_stamp'] = $rowcontent['source_img_stamp'];
			$data_insert['source_gettags'] = $rowcontent['source_gettags'];
			$data_insert['source_getkeywords'] = $rowcontent['source_getkeywords'];
			$data_insert['source_getkeywords_des'] = $rowcontent['source_getkeywords_des'];
			$data_insert['source_getthumbs'] = $rowcontent['source_getthumbs'];
			$data_insert['source_clearlinks'] = $rowcontent['source_clearlinks'];
			$data_insert['source_keep_source'] = $rowcontent['source_keep_source'];
			$data_insert['source_cleanup_html'] = $rowcontent['source_cleanup_html'];
			$data_insert['source_cleanup_img'] = $rowcontent['source_cleanup_img'];
			$data_insert['cron_set'] = $rowcontent['cron_set'];
			$data_insert['cron_lastrun'] = intval( $rowcontent['cron_lastrun'] );
			$data_insert['cron_schedule'] = $rowcontent['cron_schedule'];
			$data_insert['status'] = $rowcontent['status'];

			$rowcontent['id'] = $db->insert_id( $sql, 'id', $data_insert );
			if( $rowcontent['id'] > 0 )
			{
				// $nv_Cache->delMod($module_name);
				nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['add_source'], $rowcontent['title'], $admin_info['userid'] );
				$url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=source_list';
				$msg1 = $lang_module['source_added'];
				$msg2 = $lang_module['source_back'] . ' ' . $module_info['custom_title'];
				redirect( $msg1, $msg2, $url );
			}
			else
			{
				$error[] = $lang_module['errorsave'];
			}
		}
		else
		{
			$sth = $db->prepare( 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_source SET
				 catid=:catid,
				 title=:title,
				 source_url=:source_url,
				 target_module=:target_module,
				 target_catid=:target_catid,
				 target_listcatid=:target_listcatid,
				 target_block_id=:target_block_id,
				 source_numget=:source_numget,
				 source_moderate=:source_moderate,
				 source_jump_url=:source_jump_url,
				 source_jump_from=:source_jump_from,
				 source_jump_to=:source_jump_to,
				 source_clearpage_jump=:source_clearpage_jump,
				 source_autohometext=:source_autohometext,
				 source_hometext_limit=:source_hometext_limit,
				 source_getthumb=:source_getthumb,
				 source_getimage=:source_getimage,
				 source_img_stamp=:source_img_stamp,
				 source_gettags=:source_gettags,
				 source_getkeywords=:source_getkeywords,
				 source_getkeywords_des=:source_getkeywords_des,
				 source_getthumbs=:source_getthumbs,
				 source_clearlinks=:source_clearlinks,
				 source_keep_source=:source_keep_source,
				 source_cleanup_html=:source_cleanup_html,
				 source_cleanup_img=:source_cleanup_img,
				 cron_set=:cron_set,
				 cron_schedule=:cron_schedule,
				 status=:status,
				 date_modify=' . NV_CURRENTTIME . '
				WHERE id =' . $rowcontent['id'] );

			$sth->bindParam( ':catid', $rowcontent['catid'], PDO::PARAM_STR );
			$sth->bindParam( ':title', $rowcontent['title'], PDO::PARAM_STR );
			$sth->bindParam( ':source_url', $rowcontent['source_url'], PDO::PARAM_STR );
			$sth->bindParam( ':target_module', $rowcontent['target_module'], PDO::PARAM_STR );
			$sth->bindParam( ':target_catid', $rowcontent['target_catid'], PDO::PARAM_STR );
			$sth->bindParam( ':target_listcatid', $rowcontent['target_listcatid'], PDO::PARAM_STR );
			$sth->bindParam( ':target_block_id', $rowcontent['target_block_id'], PDO::PARAM_STR );
			$sth->bindParam( ':source_numget', $rowcontent['source_numget'], PDO::PARAM_STR );
			$sth->bindParam( ':source_moderate', $rowcontent['source_moderate'], PDO::PARAM_STR );
			$sth->bindParam( ':source_jump_url', $rowcontent['source_jump_url'], PDO::PARAM_STR );
			$sth->bindParam( ':source_jump_from', $rowcontent['source_jump_from'], PDO::PARAM_STR );
			$sth->bindParam( ':source_jump_to', $rowcontent['source_jump_to'], PDO::PARAM_STR );
			$sth->bindParam( ':source_clearpage_jump', $rowcontent['source_clearpage_jump'], PDO::PARAM_STR );
			$sth->bindParam( ':source_autohometext', $rowcontent['source_autohometext'], PDO::PARAM_STR );
			$sth->bindParam( ':source_hometext_limit', $rowcontent['source_hometext_limit'], PDO::PARAM_STR );
			$sth->bindParam( ':source_getthumb', $rowcontent['source_getthumb'], PDO::PARAM_STR );
			$sth->bindParam( ':source_getimage', $rowcontent['source_getimage'], PDO::PARAM_STR );
			$sth->bindParam( ':source_img_stamp', $rowcontent['source_img_stamp'], PDO::PARAM_STR );
			$sth->bindParam( ':source_gettags', $rowcontent['source_gettags'], PDO::PARAM_STR );
			$sth->bindParam( ':source_getkeywords', $rowcontent['source_getkeywords'], PDO::PARAM_STR );
			$sth->bindParam( ':source_getkeywords_des', $rowcontent['source_getkeywords_des'], PDO::PARAM_STR );
			$sth->bindParam( ':source_getthumbs', $rowcontent['source_getthumbs'], PDO::PARAM_STR );
			$sth->bindParam( ':source_clearlinks', $rowcontent['source_clearlinks'], PDO::PARAM_STR );
			$sth->bindParam( ':source_keep_source', $rowcontent['source_keep_source'], PDO::PARAM_STR );
			$sth->bindParam( ':source_cleanup_html', $rowcontent['source_cleanup_html'], PDO::PARAM_STR );
			$sth->bindParam( ':source_cleanup_img', $rowcontent['source_cleanup_img'], PDO::PARAM_STR );
			$sth->bindParam( ':cron_set', $rowcontent['cron_set'], PDO::PARAM_STR );
			$sth->bindParam( ':cron_schedule', $rowcontent['cron_schedule'], PDO::PARAM_STR );
			$sth->bindParam( ':status', $rowcontent['status'], PDO::PARAM_STR );

			if( $sth->execute( ) )
			{
				$nv_Cache->delMod( $module_name );
				nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['edit_source'], $rowcontent['title'], $admin_info['userid'] );
				$url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=source_list';
				$msg1 = $lang_module['source_updated'] . ' ' . $rowcontent['title'];
				$msg2 = $lang_module['source_back'] . ' ' . $module_info['custom_title'];
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

if( empty( $global_array_cat ) )
{
	$url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=source_cat';
	$msg1 = $lang_module['no_sourcecat'];
	$msg2 = $lang_module['no_sourcecat_back'];
	redirect( $msg1, $msg2, $url );
	die( );
}

$xtpl = new XTemplate( $op . ".tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'ITEM', $rowcontent );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'OP', $op );

if( !empty( $error ) )
{
	$xtpl->assign( 'error', implode( '<br />', $error ) );
	$xtpl->parse( 'main.error' );
}

foreach( $global_array_cat as $catid_i => $array_value )
{
	$temp = array(
		'catid' => $catid_i,
		'title' => $array_value['title'],
		'status' => ($array_value['status'] == 1) ? '' : 'disabled="disabled"',
		'selected' => ($catid_i == $rowcontent['catid']) ? ' selected="selected"' : ''
	);
	$xtpl->assign( 'CATS', $temp );
	$xtpl->parse( 'main.catid' );
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

foreach( $array_status as $key => $val )
{
	$xtpl->assign( 'CRON_SET', array(
		'key' => $key,
		'title' => $val,
		'selected' => $key == $rowcontent['cron_set'] ? ' selected="selected"' : ''
	) );
	$xtpl->parse( 'main.cron_set' );
}

for( $sh = 1; $sh <= 72; $sh++ )
{
	$xtpl->assign( 'CRON_SCHEDULE', array(
		'key' => $sh,
		'title' => $sh,
		'selected' => $sh == $rowcontent['cron_schedule'] ? ' selected="selected"' : ''
	) );
	$xtpl->parse( 'main.cron_schedule' );
}

// Get all module
$array_mod_news = array( );
$_query = $db->query( 'SELECT title, module_data, custom_title FROM ' . NV_PREFIXLANG . '_modules WHERE act=1 AND module_file=' . $db->quote( 'news' ) );
while( list( $_modt, $_modd, $_modc ) = $_query->fetch( PDO::FETCH_NUM ) )
{
	$_cat = $db->query( 'SELECT catid, parentid, title, weight, lev FROM ' . NV_PREFIXLANG . '_' . $_modd . '_cat ORDER BY sort ASC' )->fetchAll( );
	$_groups = $db->query( 'SELECT bid, title FROM ' . NV_PREFIXLANG . '_' . $_modd . '_block_cat ORDER BY weight ASC' )->fetchAll( );
	$array_mod_news[] = array(
		'module_title' => $_modt,
		'module_data' => $_modd,
		'custom_title' => $_modc,
		'category' => $_cat,
		'groups' => $_groups
	);
}

foreach( $array_mod_news as $mod_news )
{
	$xtpl->assign( 'SOURCE_MOD', array(
		'mod_title' => $mod_news['module_title'],
		'mod_name' => $mod_news['custom_title'],
		'mod_data' => $mod_news['module_data'],
		'selected' => $rowcontent['target_module'] == $mod_news['module_data'] ? ' selected="selected"' : ''
	) );
	$xtpl->parse( 'main.source_mod' );
}

$array_moderate = array(
	$lang_module['source_post_0'] => 0,
	$lang_module['source_post_1'] => 1,
	$lang_module['source_post_3'] => 3
);

foreach( $array_moderate as $key )
{
	$xtpl->assign( 'SOURCE_MODERATE', array(
		'key' => $key,
		'title' => $lang_module['source_post_' . $key],
		'selected' => $key == $rowcontent['source_moderate'] ? ' selected="selected"' : ''
	) );
	$xtpl->parse( 'main.source_moderate' );
}

$xtpl->assign( 'source_clearpage_jump', $rowcontent['source_clearpage_jump'] ? ' checked="checked"' : '' );
$xtpl->assign( 'source_autohometext', $rowcontent['source_autohometext'] ? ' checked="checked"' : '' );
$xtpl->assign( 'source_getthumb', $rowcontent['source_getthumb'] ? ' checked="checked"' : '' );
$xtpl->assign( 'source_getimage', $rowcontent['source_getimage'] ? ' checked="checked"' : '' );
$xtpl->assign( 'source_img_stamp', $rowcontent['source_img_stamp'] ? ' checked="checked"' : '' );
$xtpl->assign( 'source_gettags', $rowcontent['source_gettags'] ? ' checked="checked"' : '' );
$xtpl->assign( 'source_getkeywords', $rowcontent['source_getkeywords'] ? ' checked="checked"' : '' );
$xtpl->assign( 'source_getkeywords_des', $rowcontent['source_getkeywords_des'] ? ' checked="checked"' : '' );
$xtpl->assign( 'source_getthumbs', $rowcontent['source_getthumbs'] ? ' checked="checked"' : '' );
$xtpl->assign( 'source_clearlinks', $rowcontent['source_clearlinks'] ? ' checked="checked"' : '' );
$xtpl->assign( 'source_keep_source', $rowcontent['source_keep_source'] ? ' checked="checked"' : '' );
$xtpl->assign( 'source_cleanup_html', $rowcontent['source_cleanup_html'] ? ' checked="checked"' : '' );
$xtpl->assign( 'source_cleanup_img', $rowcontent['source_cleanup_img'] ? ' checked="checked"' : '' );

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );
if( $rowcontent['id'] > 0 )
{
	$op = '';
}

include (NV_ROOTDIR . "/includes/header.php");
echo nv_admin_theme( $contents );
include (NV_ROOTDIR . "/includes/footer.php");
