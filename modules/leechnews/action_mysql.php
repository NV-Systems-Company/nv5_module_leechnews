<?php

/**
 * @Project FEEDNEWS ON NUKEVIET 4.x
 * @Author KENNYNGUYEN (nguyentiendat713@gmail.com)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 07/30/2013 10:27
 */

if( !defined( 'NV_MAINFILE' ) )
	die( 'Stop!!!' );

$sql_drop_module = array( );
$array_table = array(
	'source_cat',
	'source',
	'rows',
	'logs',
	'proxy'
);
$table = $db_config['prefix'] . '_' . $lang . '_' . $module_data;
$result = $db->query( 'SHOW TABLE STATUS LIKE ' . $db->quote( $table . '_%' ) );
while( $item = $result->fetch( ) )
{
	$name = substr( $item['name'], strlen( $table ) + 1 );
	if( preg_match( '/^' . $db_config['prefix'] . '\_' . $lang . '\_' . $module_data . '\_/', $item['name'] ) and (preg_match( '/^([0-9]+)$/', $name ) or in_array( $name, $array_table ) or preg_match( '/^bodyhtml\_([0-9]+)$/', $name )) )
	{
		$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $item['name'];
	}
}

// Xoa bo tien trinh tu dong
$run_file = 'nv_auto_leechnews.php';
$run_func = 'cron_auto_leechnews';
$db->query( "DELETE FROM " . $db_config['prefix'] . '_cronjobs' . " WHERE run_file=" . $db->quote( $run_file ) . " AND run_func=" . $db->quote( $run_func ) );
// Xoa bo file Cron
@nv_deletefile( NV_ROOTDIR . '/includes/cronjobs/nv_auto_leechnews.php' );

$sql_create_module = $sql_drop_module;

$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_source_cat (
	catid int(11) NOT NULL AUTO_INCREMENT,
	title varchar(250) NOT NULL DEFAULT '',
	host_url varchar(255) NOT NULL DEFAULT '',
	logo varchar(250) NOT NULL DEFAULT '',
	block_pattern text NOT NULL,
	block_title text NOT NULL,
	block_title_remove text NOT NULL,
	block_title_replace text NOT NULL,
	block_url text NOT NULL,
	block_hometext text NOT NULL,
	block_hometext_remove text NOT NULL,
	block_hometext_replace text NOT NULL,
	block_thumb text NOT NULL,
	block_thumb_alt text NOT NULL,
	block_thumb_attribute text NOT NULL,
	detail_bodyhtml text NOT NULL,
	detail_bodyhtml_remove text NOT NULL,
	detail_bodyhtml_replace text NOT NULL,
	detail_bodyhtml_attribute text NOT NULL,
	detail_hometext text NOT NULL,
	detail_hometext_remove text NOT NULL,
	detail_hometext_replace text NOT NULL,
	detail_author text NOT NULL,
	detail_author_replace text NOT NULL,
	status int(11) NOT NULL DEFAULT '1',
	weight int(11) NOT NULL DEFAULT '0',
	date_create int(11) NOT NULL,
	date_modify int(11) NOT NULL,
	PRIMARY KEY (catid)
) ENGINE=MyISAM;";

$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_source (
	id int(11) NOT NULL AUTO_INCREMENT,
	catid int(11) NOT NULL,
	title varchar(250) NOT NULL DEFAULT '',
	source_url varchar(255) NOT NULL DEFAULT '',
	target_module varchar(250) NOT NULL DEFAULT '',
	target_catid int(11) NOT NULL,
	target_listcatid varchar(255) NOT NULL default '',
	target_block_id varchar(255) NOT NULL default '',
	source_numget int(11) NOT NULL DEFAULT '5',
	source_moderate int(11) NOT NULL DEFAULT '1',
	source_jump_url text NOT NULL,
	source_jump_from int(11) NOT NULL DEFAULT '0',
	source_jump_to int(11) NOT NULL DEFAULT '0',
	source_clearpage_jump int(11) NOT NULL DEFAULT '0',
	source_autohometext int(11) NOT NULL DEFAULT '0',
	source_hometext_limit int(11) NOT NULL DEFAULT '100',
	source_getthumb int(11) NOT NULL DEFAULT '0',
	source_getimage int(11) NOT NULL DEFAULT '0',
	source_img_stamp int(11) NOT NULL DEFAULT '0',
	source_gettags int(11) NOT NULL DEFAULT '0',
	source_getkeywords int(11) NOT NULL DEFAULT '0',
	source_getkeywords_des int(11) NOT NULL DEFAULT '0',
	source_getthumbs int(11) NOT NULL DEFAULT '0',
	source_clearlinks int(11) NOT NULL DEFAULT '0',
	source_keep_source int(11) NOT NULL DEFAULT '0',
	source_cleanup_html int(11) NOT NULL DEFAULT '0',
	source_cleanup_img int(11) NOT NULL DEFAULT '0',
	cron_set int(11) NOT NULL DEFAULT '0',
	cron_lastrun int(11) NOT NULL DEFAULT '0',
	cron_schedule int(11) NOT NULL DEFAULT '0',
	status int(11) NOT NULL DEFAULT '1',
	date_create int(11) NOT NULL,
	date_modify int(11) NOT NULL,
	PRIMARY KEY (id),
	KEY catid (catid)
) ENGINE=MyISAM;";

$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_rows (
	 id int(11) unsigned NOT NULL auto_increment,
	 news_id smallint(5) unsigned NOT NULL default '0',
	 sid smallint(5) unsigned NOT NULL default '0',
	 scatid smallint(5) unsigned NOT NULL default '0',
	 addtime int(11) unsigned NOT NULL default '0',
	 status tinyint(4) NOT NULL default '0',
	 title varchar(250) NOT NULL default '',
	 news_url varchar(255) NOT NULL default '',
	 alias varchar(250) NOT NULL default '',
	 hometext text,
	 homeimgalt varchar(255) default '',
	 thumb varchar(250) NOT NULL default '',
	 keywords text,
	 author varchar(250) default '',
	 bodyhtml text,
	 PRIMARY KEY (id)
	) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_logs (
	 id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
	 sid mediumint(8) NOT NULL DEFAULT '0',
	 scatid mediumint(8) NOT NULL DEFAULT '0',
	 userid mediumint(8) unsigned NOT NULL DEFAULT '0',
	 status tinyint(4) NOT NULL DEFAULT '0',
	 note text NOT NULL,
	 set_time int(11) unsigned NOT NULL DEFAULT '0',
	 PRIMARY KEY (id),
	 KEY sid (sid),
	 KEY userid (userid)
) ENGINE=MyISAM";
$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_proxy (
	 proxy_id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
	 proxy_city varchar(250) default '',
	 proxy_country varchar(250) default '',
	 proxy_ip varchar(250) default '',
	 proxy_port varchar(250) default '',
	 proxy_last_update varchar(250) default '',
	 proxy_refs varchar(250) default '',
	 proxy_state varchar(250) default '',
	 proxy_status varchar(250) default '',
	 proxy_time varchar(250) default '',
	 proxy_type varchar(250) default '',
	 proxy_uid varchar(250) default '',
	 proxy_uptimeld varchar(250) default '',
	 proxy_username varchar(250) default '',
	 proxy_password varchar(250) default '',
	 status tinyint(4) NOT NULL DEFAULT '0',
	 date_added int(11) unsigned NOT NULL DEFAULT '0',
	 date_modified int(11) unsigned NOT NULL DEFAULT '0',
	 weight int(11) NOT NULL DEFAULT '0',
	 PRIMARY KEY (proxy_id)
) ENGINE=MyISAM";

$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'post_type', 'direct')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'thumb_position', '1')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'st_links', '10')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'prune_records', '10')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'hometext', '160')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'cron_user', '1')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'force_set_time_limit', '1')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'using_proxy', '0')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'proxy_limit_port', '80,443')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'proxy_limit_country', '')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'sleep_timer', '5')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'set_time_limit', '3')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'autologosize1', '50')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'autologosize2', '40')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'autologosize3', '30')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'module_logo', 'images/logo.png')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'logo_position', 'bottom_right')";
