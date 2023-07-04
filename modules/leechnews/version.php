<?php

/**
 * @Project LEECHNEWS ON NUKEVIET 4.x
 * @Author AnvH(anvh.ceo@gmail.com)
 * @License GNU/GPL version 2 or any later version
 * @Createdate Wed, 07 April 2019 07:00:00 GMT
 */
if( !defined( 'NV_MAINFILE' ) )
	die( 'Stop!!!' );

$module_version = array( //
	"name" => "Leechnews", //
	"modfuncs" => "", //
	"submenu" => "", //
	"is_sysmod" => 0, //
	"virtual" => 1, //
	"version" => "1.2.04", //
	"date" => "Wed, 07 April 2021 07:00:00 GMT", //
	"author" => "Developed by AnvH(anvh.ceo@gmail.com)", //
	"uploads_dir" => array(
		$module_name,
		$module_name . '/logo/',
		$module_name . '/ini/'
	), //
	"files_dir" => array( $module_upload . '/tmp/' ),
	"note" => "Lấy tin từ Website khác"
);
//
