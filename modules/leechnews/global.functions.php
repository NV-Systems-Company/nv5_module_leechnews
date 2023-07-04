<?php

/**
 * @Project LEECHNEWS ON NUKEVIET 4.x
 * @Author AnvH(anvh.ceo@gmail.com)
 * @License GNU/GPL version 2 or any later version
 * @Createdate 19 July 2016 16:00 GMT+7
 */
if( !defined( 'NV_MAINFILE' ) )
{
	die( 'Stop!!!' );
}

define( 'LEECHNEWS_4X___DOM', true );

/**
 * nv_news_get_bodytext()
 *
 * @param mixed $bodytext
 * @return
 *
 */
function nv_news_get_bodytext( $bodytext )
{
	// Get image tags
	if( preg_match_all( "/\<img[^\>]*src=\"([^\"]*)\"[^\>]*\>/is", $bodytext, $match ) )
	{
		foreach( $match [0] as $key => $_m )
		{
			$textimg = "";
			if( strpos( $match[1][$key], 'data:image/png;base64' ) === false )
			{
				$textimg = " " . $match[1][$key];
			}
			if( preg_match_all( "/\<img[^\>]*alt=\"([^\"]+)\"[^\>]*\>/is", $_m, $m_alt ) )
			{
				$textimg .= " " . $m_alt[1][0];
			}
			$bodytext = str_replace( $_m, $textimg, $bodytext );
		}
	}
	// Get link tags
	if( preg_match_all( "/\<a[^\>]*href=\"([^\"]+)\"[^\>]*\>(.*)\<\/a\>/isU", $bodytext, $match ) )
	{
		foreach( $match [0] as $key => $_m )
		{
			$bodytext = str_replace( $_m, $match[1][$key] . " " . $match[2][$key], $bodytext );
		}
	}

	$bodytext = nv_unhtmlspecialchars( strip_tags( $bodytext ) );
	$bodytext = str_replace( "&nbsp;", " ", $bodytext );
	return preg_replace( "/[ ]+/", " ", $bodytext );
}

/**
 * origin_img()
 *
 * @param mixed $html
 * @return
 *
 */
function origin_img( $html )
{
	preg_match_all( '/<img[^>]+>/i', $html, $result );
	if( isset( $result ) )
	{
		foreach( $result [0] as $tagimg )
		{
			$preg = preg_match_all( '#<\s*img [^\>]*src\s*=\s*(["\'])(.*?)\1#im', $tagimg, $matches );
			$newtag = '<img src="' . $matches[2][0] . '" />';
			$html = str_replace( $tagimg, $newtag, $html );
		}
	}
	return $html;
}

/**
 * nv_clean_html()
 *
 * @param mixed $html
 * @return
 *
 */
function nv_clean_html( $html )
{
	$html = preg_replace( '#<script(.*?)>(.*?)</script>#is', '', $html );
	$html = preg_replace( "/<\s* input [^>]+ >/xi", "", $html );
	$html = convert_style( $html );
	return $html;
}

/**
 * convert_style()
 *
 * @param mixed $html
 * @return
 *
 */
function convert_style( $html )
{
	preg_match_all( '/(\s)+style="(.*)"(\s)*/U', $html, $result, PREG_PATTERN_ORDER );
	foreach( $result [0] as $css )
	{
		$html = str_replace( $css, strtolower( $css ), $html );
	}
	return $html;
}

/**
 * curl()
 *
 * @param mixed $url
 * @return
 *
 */
function curl( $url, $proxy = false, $proxy_array = '' )
{
	$userAgents = array(
		'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.1) Gecko/20090624 Firefox/3.5 (.NET CLR 3.5.30729)', //
		'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)', //
		'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0)', //
		'Mozilla/4.8 [en] (Windows NT 6.0; U)', //
		'Opera/9.25 (Windows NT 6.0; U; en)'
	);
	srand( ( float )microtime( ) * 10000000 );
	$rand = array_rand( $userAgents );
	$agent = $userAgents[$rand];

	$ch = @curl_init( );
	curl_setopt( $ch, CURLOPT_URL, $url );
	$head[] = "Connection: keep-alive";
	$head[] = "Keep-Alive: 300";
	$head[] = "Cache-Control: no-cache";
	$head[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
	$head[] = "Accept-Language: en-us,en;q=0.5";
	curl_setopt( $ch, CURLOPT_USERAGENT, $agent );
	curl_setopt( $ch, CURLOPT_HTTPHEADER, $head );

	$safe_mode = (ini_get( 'safe_mode' ) == '1' || strtolower( ini_get( 'safe_mode' ) ) == 'on') ? 1 : 0;
	$open_basedir = @ini_get( 'open_basedir' ) ? true : false;
	if( !$safe_mode and !$open_basedir )
	{
		curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1 );
		curl_setopt( $ch, CURLOPT_MAXREDIRS, 10 );
	}
	if( $proxy AND isset( $proxy_array ) )
	{
		if( !empty( $proxy_array['proxy_ip'] ) and !empty( $proxy_array['proxy_port'] ) )
		{
			curl_setopt( $ch, CURLOPT_PROXY, $proxy_array['proxy_ip'] . ':' . $proxy_array['proxy_port'] );
		}
		if( !empty( $proxy_array['proxy_username'] ) and !empty( $proxy_array['proxy_password'] ) )
		{
			curl_setopt( $ch, CURLOPT_PROXYUSERPWD, $proxy_array['proxy_username'] . ':' . $proxy_array['proxy_password'] );
		}
	}

	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
	curl_setopt( $ch, CURLOPT_ENCODING, "gzip" );
	curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, FALSE );
	curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, FALSE );
	curl_setopt( $ch, CURLOPT_TIMEOUT, 60 );
	curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 60 );

	curl_setopt( $ch, CURLOPT_HTTPHEADER, array( 'Expect:' ) );
	$page = curl_exec( $ch );
	curl_close( $ch );
	return $page;
}

/**
 * getAbsoluteImageUrl()
 *
 * @param mixed $pageUrl
 * @param mixed $imgSrc
 * @return
 *
 */
function getAbsoluteImageUrl( $pageUrl, $imgSrc )
{
	$imgInfo = parse_url( $imgSrc );
	if( !empty( $imgInfo['host'] ) )
	{
		// img src is already an absolute URL
		return $imgSrc;
	}
	else
	{
		$urlInfo = parse_url( $pageUrl );
		$base = $urlInfo['scheme'] . '//' . $urlInfo['host'];
		if( substr( $imgSrc, 0, 1 ) == '/' )
		{
			// img src is relative from the root URL
			return $base . $imgSrc;
		}
		else
		{
			// img src is relative from the current directory
			return $base . substr( $urlInfo['path'], 0, strrpos( $urlInfo['path'], '/' ) ) . '/' . $imgSrc;
		}
	}
}

/**
 * html_no_comment()
 *
 * @param mixed $url
 * @return
 *
 */
function html_no_comment( $url )
{
	$url = _urlencode( $url );
	// create HTML DOM
	$check_curl = function_exists( 'curl_version' );
	if( !$html = file_get_html( $url ) )
	{
		if( !$html = str_get_html( file_get_contents_curl( $url ) ) or !$check_curl )
		{
			return false;
		}
	}
	// remove all comment elements
	foreach( $html->find ( 'comment' ) as $e )
		$e->outertext = '';

	$ret = $html->save( );

	// clean up memory
	$html->clear( );
	unset( $html );
	return $ret;
}

/**
 * dom_html_file()
 *
 * @param mixed $url
 * @param mixed $module_upload
 * @return
 *
 */
function dom_html_file( $url, $module_upload, $origin_img = false, $proxy = false, $proxy_array = '' )
{
	$check_curl = function_exists( 'curl_version' );
	if( !empty( $url ) and $check_curl )
	{
		$file = NV_ROOTDIR . "/" . NV_ASSETS_DIR . '/' . $module_upload . '/tmp/' . md5( $url ) . '.html';
		$current = curl( $url, $proxy, $proxy_array );

		$check = parse_url( $url );

		$current = nv_clean_html( $current, $check['host'] );
		if( $origin_img )
		{
			$current = origin_img( $current );
		}
		file_put_contents( $file, $current );
		$html = file_get_html( $file );
		// unlink( $file );
		return $html;
	}
	else
	{
		return false;
	}
}

/**
 * nv_get_firstimage()
 *
 * @param mixed $contents
 * @return
 *
 */
function nv_get_firstimage( $contents )
{
	if( preg_match( '/< *img[^>]*src *= *["\']?([^"\']*)/i', $contents, $img ) )
	{
		return $img[1];
	}
	else
	{
		return '';
	}
}

/**
 * nv_custom_check_url()
 *
 * @param mixed $url
 * @return
 *
 */
function nv_custom_check_url( $url )
{
	$check = parse_url( $url );
	$status = isset( $check['host'] ) ? true : false;
	return $status;
}

/**
 * is_404()
 *
 * @param mixed $url
 * @return
 *
 */
function is_404( $url )
{
	$handle = curl_init( $url );
	curl_setopt( $handle, CURLOPT_RETURNTRANSFER, TRUE );
	$response = curl_exec( $handle );
	$httpCode = curl_getinfo( $handle, CURLINFO_HTTP_CODE );
	curl_close( $handle );
	if( $httpCode >= 200 && $httpCode < 300 )
	{
		return false;
	}
	else
	{
		return true;
	}
}

/**
 * nv_check_link()
 *
 * @param mixed $url
 * @param mixed $host
 * @return
 *
 */
function nv_check_link( $host = '', $url )
{
	if( (nv_custom_check_url( $url ) === false) and (preg_match_all( '/^(http|https)\:\/\/(.*)\.([a-z]+)/', $host, $matches, PREG_SET_ORDER )) )
	{
		/*while( $url{0} == '/' )
		{
			$url = substr( $url, 1 );
		}
		if( $matches[0][0]{strlen( $matches[0][0] ) - 1} != '/' )
		{
			$matches[0][0] = $matches[0][0] . '/';
		}
		$url = $matches[0][0] . $url;*/
	}
	return $url;
}

/**
 * nv_remove_emptytag()
 *
 * @param mixed $html
 * @return
 *
 */
function nv_remove_emptytag( $html )
{
	$html = preg_replace( '/<[^\/>]*>([\s]?)*<\/[^>]*>/', ' ', $html );
	return $html;
}

/**
 * nv_anchortext_only()
 *
 * @param mixed $string
 * @return
 *
 */
function nv_anchortext_only( $string )
{
	$result = preg_replace( '/<a(.*?)>(.*?)<\/a>/', "\\2", $string );
	return $result;
}

/**
 * nv_phpuri()
 *
 * @param mixed $module_file
 * @param mixed $host
 * @param mixed $href
 * @return
 *
 */
function nv_phpuri( $module_file, $host, $href )
{
	require_once (NV_ROOTDIR . "/modules/" . $module_file . "/phpuri.php");
	$url = phpUri::parse( $host )->join( $href );
	return $url;
}

/**
 * nv_purifier()
 *
 * @param mixed $html
 * @param mixed $module_file
 * @return
 *
 */
function nv_purifier( $module_file, $html )
{
	require_once (NV_ROOTDIR . "/modules/" . $module_file . '/purifier/HTMLPurifier.auto.php');
	$config = HTMLPurifier_Config::createDefault( );
	$purifier = new HTMLPurifier( $config );
	$clean_html = $purifier->purify( $html );
	return $clean_html;
}

/**
 * nv_save_thumb()
 *
 * @param mixed $url
 * @param mixed $dir
 * @return
 *
 */
function nv_save_thumb( $url, $dir, $module_name, $add_logo = 0 )
{
	global $global_config, $module_config, $module_upload;
	$dir = str_replace( '_', '-', $dir );

	$href = $url;
	$extension = pathinfo( $url, PATHINFO_EXTENSION );
	$imgext = array(
		'jpg' => 'jpg',
		'jpeg' => 'jpeg',
		'gif' => 'gif',
		'png' => 'png',
		'bmp' => 'bmp'
	);
	if( !in_array( $extension, $imgext ) )
	{
		$extension = 'jpg';
	}

	$filename = pathinfo( $url, PATHINFO_FILENAME );
	$basename = strtolower( change_alias( $filename ) ) . '.' . $extension;
	$url = str_replace( pathinfo( $url, PATHINFO_FILENAME ), rawurlencode( pathinfo( $url, PATHINFO_FILENAME ) ), $url );
	if( !empty( $basename ) )
	{
		$folder_upload = NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $dir . "/" . date( 'Y_m' );
		if( !is_dir( $folder_upload ) )
		{
			@mkdir( $folder_upload, 0755, true );

			$_index = fopen( $folder_upload . "/" . "index.html", "a+" );
			fclose( $_index );
		}
		$_i = $basename;
		// Thu muc Upload chua anh cua bai viet
		if( file_exists( $folder_upload . '/' . $basename ) )
		{
			$dest = $folder_upload . '/' . md5( $basename . NV_CURRENTTIME ) . '_' . $basename;
			$_i = md5( $basename . NV_CURRENTTIME ) . '_' . $basename;
		}
		else
		{
			$dest = $folder_upload . '/' . $basename;
		}

		// Khoi tao hinh anh moi
		$image = new NukeViet\Files\Image( $url, NV_MAX_WIDTH, NV_MAX_HEIGHT );
		$image->save( NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $dir . "/" . date( 'Y_m' ), strtolower( $_i ) );
		if( $image->is_destroy !== FALSE )
		{
			$href = date( 'Y_m' ) . "/" . $_i;
		}
	}

	$logo_pos = '';
	if( !empty( $module_config[$module_name]['module_logo'] ) and file_exists( NV_ROOTDIR . '/' . $module_config[$module_name]['module_logo'] ) and $add_logo == 1 )
	{
		$logo_pos = explode( '_', $module_config[$module_name]['logo_position'] );
		$image_info = nv_is_image( NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $dir . "/" . date( 'Y_m' ) . "/" . $_i );
		$upload_logo = $module_config[$module_name]['module_logo'];
		$logo_size = getimagesize( NV_ROOTDIR . '/' . $upload_logo );

		if( $image_info['width'] <= 150 )
		{
			$w = ceil( $logo_size[0] * $module_config[$module_name]['autologosize1'] / 100 );
		}
		elseif( $image_info['width'] < 350 )
		{
			$w = ceil( $logo_size[0] * $module_config[$module_name]['autologosize2'] / 100 );
		}
		else
		{
			if( ceil( $image_info['width'] * $module_config[$module_name]['autologosize3'] / 100 ) > $logo_size[0] )
			{
				$w = $logo_size[0];
			}
			else
			{
				$w = ceil( $image_info['width'] * $module_config[$module_name]['autologosize3'] / 100 );
			}
		}
		$h = ceil( $w * $logo_size[1] / $logo_size[0] );
		$x = $image_info['width'] - $w - 5;
		$y = $image_info['height'] - $h - 5;

		$config_logo = array( );
		$config_logo['w'] = $w;
		$config_logo['h'] = $h;

		$config_logo['x'] = $image_info['width'] - $w - 5;
		// Horizontal: Right
		$config_logo['y'] = $image_info['height'] - $h - 5;
		// Vertical: Bottom

		// Logo vertical
		if( $logo_pos[0] == "top" )
		{
			$config_logo['y'] = 5;
		}
		elseif( $logo_pos[0] == "center" )
		{
			$config_logo['y'] = round( ($image_info['height'] / 2) - ($h / 2) );
		}

		// Logo horizontal
		if( $logo_pos[1] == "left" )
		{
			$config_logo['x'] = 5;
		}
		elseif( $logo_pos[1] == "center" )
		{
			$config_logo['x'] = round( ($image_info['width'] / 2) - ($w / 2) );
		}

		$createImage = new NukeViet\Files\Image( NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $dir . "/" . date( 'Y_m' ) . "/" . $_i, NV_MAX_WIDTH, NV_MAX_HEIGHT );
		$createImage->addlogo( NV_ROOTDIR . '/' . $upload_logo, '', '', $config_logo );
		$createImage->save( NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $dir . "/" . date( 'Y_m' ), strtolower( $_i ) );
		$rename = $createImage->is_destroy;
	}
	return $href;
}

/**
 * nv_save_img()
 *
 * @param mixed $url
 * @param mixed $dir
 * @param mixed $id
 * @return
 *
 */
function nv_save_img( $url, $dir, $id, $module_name, $add_logo = 0 )
{
	global $global_config, $module_config, $module_upload;
	$dir = str_replace( '_', '-', $dir );
	$href = $url;

	$extension = pathinfo( $url, PATHINFO_EXTENSION );
	$imgext = array(
		'jpg' => 'jpg',
		'jpeg' => 'jpeg',
		'gif' => 'gif',
		'png' => 'png',
		'bmp' => 'bmp'
	);
	if( !in_array( $extension, $imgext ) )
	{
		$extension = 'jpg';
	}
	$filename = pathinfo( $url, PATHINFO_FILENAME );
	$basename = strtolower( change_alias( $filename ) ) . '.' . $extension;
	$url = str_replace( pathinfo( $url, PATHINFO_FILENAME ), rawurlencode( pathinfo( $url, PATHINFO_FILENAME ) ), $url );

	$folder_upload = NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $dir . "/" . date( 'Y_m' );
	if( !is_dir( $folder_upload ) )
	{
		@mkdir( $folder_upload, 0755, true );

		$_index = fopen( $folder_upload . "/" . "index.html", "a+" );
		fclose( $_index );
	}

	$_i = $basename;
	// Thu muc Upload chua anh cua bai viet
	if( file_exists( $folder_upload . '/' . $basename ) )
	{
		$dest = $folder_upload . '/' . md5( $id . NV_CURRENTTIME ) . '_' . $basename;
		$_i = md5( $id . NV_CURRENTTIME ) . '_' . $basename;
	}
	else
	{
		$dest = $folder_upload . '/' . $basename;
	}

	// Khoi tao hinh anh moi
	$image = new NukeViet\Files\Image( $url, NV_MAX_WIDTH, NV_MAX_HEIGHT );
	$image->save( NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $dir . "/" . date( 'Y_m' ), strtolower( $_i ) );
	if( $image->is_destroy !== FALSE )
	{
		$href = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $dir . "/" . date( 'Y_m' ) . "/" . $_i;
	}

	$logo_pos = '';
	if( !empty( $module_config[$module_name]['module_logo'] ) and file_exists( NV_ROOTDIR . '/' . $module_config[$module_name]['module_logo'] ) and $add_logo == 1 )
	{
		$logo_pos = explode( '_', $module_config[$module_name]['logo_position'] );
		$image_info = nv_is_image( NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $dir . "/" . date( 'Y_m' ) . "/" . $_i );
		$upload_logo = $module_config[$module_name]['module_logo'];
		$logo_size = getimagesize( NV_ROOTDIR . '/' . $upload_logo );

		if( $image_info['width'] <= 150 )
		{
			$w = ceil( $logo_size[0] * $module_config[$module_name]['autologosize1'] / 100 );
		}
		elseif( $image_info['width'] < 350 )
		{
			$w = ceil( $logo_size[0] * $module_config[$module_name]['autologosize2'] / 100 );
		}
		else
		{
			if( ceil( $image_info['width'] * $module_config[$module_name]['autologosize3'] / 100 ) > $logo_size[0] )
			{
				$w = $logo_size[0];
			}
			else
			{
				$w = ceil( $image_info['width'] * $module_config[$module_name]['autologosize3'] / 100 );
			}
		}
		$h = ceil( $w * $logo_size[1] / $logo_size[0] );
		$x = $image_info['width'] - $w - 5;
		$y = $image_info['height'] - $h - 5;

		$config_logo = array( );
		$config_logo['w'] = $w;
		$config_logo['h'] = $h;

		$config_logo['x'] = $image_info['width'] - $w - 5;
		// Horizontal: Right
		$config_logo['y'] = $image_info['height'] - $h - 5;
		// Vertical: Bottom

		// Logo vertical
		if( $logo_pos[0] == "top" )
		{
			$config_logo['y'] = 5;
		}
		elseif( $logo_pos[0] == "center" )
		{
			$config_logo['y'] = round( ($image_info['height'] / 2) - ($h / 2) );
		}

		// Logo horizontal
		if( $logo_pos[1] == "left" )
		{
			$config_logo['x'] = 5;
		}
		elseif( $logo_pos[1] == "center" )
		{
			$config_logo['x'] = round( ($image_info['width'] / 2) - ($w / 2) );
		}

		$createImage = new NukeViet\Files\Image( NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $dir . "/" . date( 'Y_m' ) . "/" . $_i, NV_MAX_WIDTH, NV_MAX_HEIGHT );
		$createImage->addlogo( NV_ROOTDIR . '/' . $upload_logo, '', '', $config_logo );
		$createImage->save( NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $dir . "/" . date( 'Y_m' ), strtolower( $_i ) );
		$rename = $createImage->is_destroy;
	}

	return $href;
}

/**
 * nv_leech_record()
 *
 * @param mixed $sid
 * @param mixed $scatid
 * @param mixed $userid
 * @param mixed $status
 * @param mixed $note
 * @param mixed $set_time
 * @param mixed $table_prefix
 * @return
 *
 */
function nv_leech_record( $sid = '', $scatid = '', $userid = '', $status = '', $note = '', $set_time = 0, $table_prefix )
{
	global $db;

	$sth = $db->prepare( 'INSERT INTO ' . $table_prefix . '_logs
		(sid, scatid, userid, status, note, set_time) VALUES
		(:sid, :scatid, :userid, :status, :note, :set_time )' );
	$sth->bindParam( ':sid', $sid, PDO::PARAM_STR );
	$sth->bindParam( ':scatid', $scatid, PDO::PARAM_STR );
	$sth->bindParam( ':userid', $userid, PDO::PARAM_STR );
	$sth->bindParam( ':status', $status, PDO::PARAM_STR );
	$sth->bindParam( ':note', $note, PDO::PARAM_STR, strlen( $note ) );
	$sth->bindParam( ':set_time', $set_time, PDO::PARAM_STR );
	if( $sth->execute( ) )
	{
		return true;
	}
	else
	{
		return false;
	}
}

/**
 * nv_prune_logs()
 *
 * @param mixed $table_prefix
 * @param mixed $module_name
 * @return
 *
 */
function nv_prune_records( $table_prefix, $module_name )
{
	global $db, $module_config;
	if( $module_config[$module_name]['prune_records'] > 0 )
	{
		$query = 'DELETE FROM ' . $table_prefix . '_rows WHERE addtime < ' . (NV_CURRENTTIME - (86400 * $module_config[$module_name]['prune_records']));
		if( $db->query( $query ) )
		{
			return true;
		}
		else
		{
			return false;
		}
	}
}

/**
 * nv_auto_leechnews()
 *
 * @param mixed $id
 * @param mixed $table_prefix
 * @param mixed $module_upload
 * @param mixed $module_file
 * @param mixed $module_name
 * @param mixed $lang
 * @param mixed $userid
 * @return
 *
 */
function nv_auto_leechnews( $id = 0, $table_prefix = '', $prefix = '', $module_upload, $module_file, $module_name, $lang, $userid = 0 )
{
	global $db, $db_config, $db_slave, $lang_global, $module_config, $global_config, $nv_Cache, $sys_info, $lang_module;
	$mod_config = $module_config[$module_name];
	// Tang thoi luong phien lam viec
	if( $sys_info['allowed_set_time_limit'] and $mod_config['force_set_time_limit'] > 0 )
	{
		set_time_limit( $mod_config['set_time_limit'] * 3600 );
	}

	$sleep_timer = 5;
	if( isset( $mod_config['sleep_timer'] ) and $mod_config['sleep_timer'] > 0 )
	{
		$sleep_timer = $mod_config['sleep_timer'];
	}
	define( 'SLEEP_TIMER', $sleep_timer );

	$use_proxy = false;
	$proxy_array = $get_proxy_array = array( );
	if( $mod_config['using_proxy'] > 0 )
	{
		$use_proxy = true;
	}

	$contents = $error = '';
	if( !defined( 'LEECHSHOPS_4X___DOM' ) )
	{
		require_once (NV_ROOTDIR . "/modules/" . $module_file . "/simple_html_dom.php");
	}
	if( defined( 'LEECHNEWS_4X___CRON' ) )
	{
		require NV_ROOTDIR . "/modules/" . $module_file . "/language/" . $lang . ".php";
	}
	if( defined( 'leechHouse_4X___CRON' ) )
	{
		require NV_ROOTDIR . "/modules/" . $module_file . "/language/" . $lang . ".php";
	}

	$global_array_cat = array( );
	$sql = 'SELECT * FROM ' . $table_prefix . '_source_cat ORDER BY weight ASC';
	$result = $db->query( $sql );
	while( $row = $result->fetch( ) )
	{
		$global_array_cat[$row['catid']] = $row;
	}
	if( function_exists( 'curl_version' ) )
	{
		if( $id > 0 )
		{
			$query = $db_slave->query( 'SELECT * FROM ' . $table_prefix . '_source WHERE id = ' . $id );
			$rows = $query->fetch( );
			if( $rows['id'] > 0 )
			{
				$source_cat = $global_array_cat[$rows['catid']];

				// Host
				$host = html_entity_decode( $source_cat['host_url'] );
				$url_array = array( );

				// Link den chuyen muc lay tin
				$rows['source_url'] = html_entity_decode( $rows['source_url'] );
				// Link cau truc nhay trang
				$rows['source_jump_url'] = html_entity_decode( $rows['source_jump_url'] );

				// Link den chuyen muc lay tin
				$url_array[1] = $rows['source_url'];

				// Xu ly nhay trang
				if( isset( $rows['source_jump_url'] ) and !empty( $rows['source_jump_url'] ) and ($rows['source_jump_to'] >= $rows['source_jump_from']) and ($rows['source_jump_to'] > 0 and $rows['source_jump_from'] > 0) )
				{
					$_jump_step = '';
					for( $_jump_step = $rows['source_jump_from']; $_jump_step <= $rows['source_jump_to']; ++$_jump_step )
					{

						// $_jump_url = $rows ['source_url'] . $str_jumpstyle . $_jump_step;

						$_jump_url = preg_replace( '/\[PAGE_NUM\]/', $_jump_step, $rows['source_jump_url'] );
						if( $_jump_step > 0 and $_jump_step != 1 and $_jump_url != $rows['source_url'] )
						{
							$url_array[$_jump_step] = $_jump_url;
						}
					}
				}

				$numget = $rows['source_numget'];
				// So tin get

				$_body_imgAttribute = $_thumb_imgAttribute = 'src';
				if( !empty( $source_cat['block_thumb_attribute'] ) )
				{
					$_thumb_imgAttribute = html_entity_decode( $source_cat['block_thumb_attribute'] );
				}

				if( !empty( $source_cat['detail_bodyhtml_attribute'] ) )
				{
					$_body_imgAttribute = html_entity_decode( $source_cat['detail_bodyhtml_attribute'] );
				}

				$rows['source_cleanup_img'] = false;
				if( $rows['source_cleanup_img'] == 1 )
				{
					$rows['source_cleanup_img'] = true;
				}

				$url_list = array( );
				$_array_remove = $total = array( );
				$leech_runtime = NV_CURRENTTIME;
				// Export category id
				$catids = explode( ',', $rows['target_listcatid'] );
				$_num_table = sizeof( $catids );
				// Prepare to check if category is exist
				$_check_table = '';
				$_count_table = 0;
				foreach( $catids as $check_catid_i )
				{
					$_check_table = $db_slave->query( 'SHOW TABLES LIKE ' . $db->quote( $prefix . '_' . $rows['target_module'] . '_' . $check_catid_i ) )->rowCount( );
					if( $_check_table > 0 )
					{
						$_count_table++;
					}
				}
				// If existing tables not equal to setted up tables -> die
				if( $_count_table < $_num_table )
				{
					nv_leech_record( $rows['id'], $rows['catid'], $userid, 19, $lang_module['leech_news_task_noCATID'], $leech_runtime, $table_prefix );
					echo $lang_module['leech_news_task_noCATID'];
					die( );
				}

				// Remove from content
				$_array_remove = explode( ',', $source_cat['detail_bodyhtml_remove'] );
				$record_status = 1;
				$record_note = $source_cat['title'] . '_' . $rows['title'];
				if( isset( $url_array ) and !empty( $url_array ) )
				{
					foreach( $url_array as $sort => $url )
					{
						if( !empty( $url ) and ($numget > 0) )
						{

							if( $use_proxy )
							{
								$where_proxy = '';
								if( !empty( $mod_config['proxy_limit_port'] ) )
								{
									$where_proxy .= ' proxy_port IN ( ' . $mod_config['proxy_limit_port'] . ' )';
								}
								$sql = 'SELECT * FROM ' . $table_prefix . '_proxy WHERE proxy_id > 0 AND ' . $where_proxy . ' ORDER BY proxy_id LIMIT 0,50';
								$result = $db->query( $sql );
								while( $row = $result->fetch( ) )
								{
									$get_proxy_array[] = $row;
								}
								if( isset( $get_proxy_array ) and !empty( $get_proxy_array ) )
								{
									$random_keys = array_rand( $get_proxy_array, 1 );
									$proxy_array = $get_proxy_array[$random_keys];
								}
							}

							$html = dom_html_file( $url, $module_upload, $rows['source_cleanup_img'], $use_proxy, $proxy_array );
							if( $html )
							{
								$_c = 0;
								if( $html->find( html_entity_decode( $source_cat['block_pattern'] ) ) )
								{
									$this_url = '';

									foreach( $html->find ( html_entity_decode ( $source_cat ['block_pattern'] ) ) as $ul )
									{

										// Reset proxy
										if( $use_proxy and isset( $get_proxy_array ) and !empty( $get_proxy_array ) )
										{
											// Do not need to SELECT proxy again from DB.
											$random_keys = array_rand( $get_proxy_array, 1 );
											$proxy_array = $get_proxy_array[$random_keys];
										}
										// Thu lay URL tu khoi bao ngoai ( Khoi bao ngoai la tag a)
										$this_url = $ul->href;

										// Kiem tra xem co phan tich duoc khoi bao ngoai khong
										// Tieu de
										$url_list[$_c]['alias'] = $url_list[$_c]['title'] = '';
										$title = $ul->find( html_entity_decode( $source_cat['block_title'] ), 0 );
										if( $title )
										{
											if( !empty( $source_cat['block_title_remove'] ) )
											{
												$title_pattern = explode( ',', $source_cat['block_title_remove'] );
												foreach( $title_pattern as $title_pattern_i )
												{
													foreach( $title->find ( html_entity_decode ( $title_pattern_i ) ) as $title_pattern_ii )
													{
														$title_pattern_ii->innertext = '';
													}
												}
											}
											$title = $title->plaintext;
										}
										if( !empty( $title ) )
										{
											if( $source_cat['block_title_replace'] )
											{
												$array_pattern = explode( ',', $source_cat['block_title_replace'] );
												foreach( $array_pattern as $pattern )
												{
													$title = str_replace( html_entity_decode( $pattern, ENT_QUOTES ), '', $title );
												}
											}

											$title = str_replace( '&nbsp;', ' ', $title );
											$title = html_entity_decode( $title, ENT_QUOTES );
											$title = nv_htmlspecialchars( str_replace( '\'', '"', strip_tags( $title ) ) );
											$title = trim( $title );
											$alias = strtolower( change_alias( $title ) );
											if( !empty( $alias ) )
											{
												$url_list[$_c]['alias'] = $alias;
											}
											$url_list[$_c]['title'] = $title;
										}
										else
										{
											$record_status = 30;
											// Khong lay duoc tieu de
											$record_note .= "<br/>" . $lang_module['job_status_14'];
										}
										// Lien ket bai viet
										$url_list[$_c]['href'] = '';
										$href = $ul->find( html_entity_decode( $source_cat['block_url'] ), 0 );
										if( $href )
										{
											$url_list[$_c]['href'] = $href->href;
										}
										if( !empty( $url_list[$_c]['href'] ) )
										{
											$url_list[$_c]['href'] = nv_phpuri( $module_file, $host, $url_list[$_c]['href'] );
										}
										else
										{
											$record_status = 30;
											// Khong lay duoc link bai viet chi tiet
											$record_note .= "<br/>" . $lang_module['job_status_15'];
										}
										// Kiem tra khong trung tin
										$duplicate = false;
										$query = "SELECT id FROM " . $prefix . "_" . $rows['target_module'] . "_rows WHERE alias=" . $db->quote( $url_list[$_c]['alias'] );
										$query_id = $db->query( $query );
										if( $query_id->fetch( 3 ) )
										{
											$duplicate = true;
										}

										$ignore = $duplicate_rows = false;
										$_query = "SELECT id, status, alias, news_url FROM " . $table_prefix . "_rows WHERE alias=" . $db->quote( $url_list[$_c]['alias'] ) . " AND news_url=" . $db->quote( $url_list[$_c]['href'] );
										$_rows_data = $db->query( $_query )->fetch( );
										if( $_rows_data['id'] > 0 )
										{
											if( $_rows_data['status'] == 99 )
											{
												// Tin bi ignore
												$ignore = true;
											}
											elseif( $_rows_data['alias'] == $url_list[$_c]['alias'] and $_rows_data['news_url'] == $url_list[$_c]['href'] )
											{
												// Tin da co trong module leecher
												$duplicate_rows = true;
											}
										}

										if( !$duplicate and !$ignore )
										{
											if( !empty( $url_list[$_c]['href'] ) and nv_is_url( $url_list[$_c]['href'] ) )
											{
												// Body html ( Xoa bo, ghi de noi dung, chinh sua link anh trong bai, tu tao homethumb)

												// Delay xx seconds for each url - 5 is default
												sleep( SLEEP_TIMER );

												$body_content = dom_html_file( $url_list[$_c]['href'], $module_upload, $rows['source_cleanup_img'], $use_proxy, $proxy_array );
												$_body_contents = ($body_content) ? $body_content->find( html_entity_decode( $source_cat['detail_bodyhtml'] ) ) : false;

												$url_list[$_c]['keywords'] = $keywords = $bodyhtml = '';
												if( $_body_contents )
												{
													foreach( $_body_contents as $_body_content )
													{
														if( !empty( $_array_remove ) and isset( $_array_remove ) )
														{
															foreach( $_array_remove as $_array_remove_i )
															{
																foreach( $_body_content->find ( html_entity_decode ( $_array_remove_i ) ) as $_array_remove_ii )
																{
																	$_array_remove_ii->outertext = '';
																}
															}
														}

														foreach( $_body_content->find ( 'a' ) as $_href_i )
														{
															$_link = $_href_i->href;
															$_href_i->href = nv_phpuri( $module_file, $host, $_link );
														}

														$_m = 0;
														foreach( $_body_content->find ( 'img' ) as $_img_i )
														{
															$_m++;
															$img = $_img_i->getAttribute( $_body_imgAttribute );
															$img = nv_phpuri( $module_file, $host, $img );
															$_img_i->src = urldecode( $img );

															// Xu ly alt
															$alt = $_img_i->alt;
															$_alt = $url_list[$_c]['title'];
															if( $_m > 1 )
															{
																$_alt .= ' - ' . $lang_module['homeimg'] . ' ' . $_m;
															}
															if( empty( $alt ) )
															{
																$_img_i->alt = $_alt;
															}
														}
														$bodyhtml .= $_body_content->innertext;
													}
												}
												$url_list[$_c]['bodyhtml'] = '';
												if( !empty( $bodyhtml ) )
												{
													$bodyhtml = nv_clean_html( $bodyhtml );
													if( $source_cat['detail_bodyhtml_replace'] )
													{
														$array_pattern = explode( ',', $source_cat['detail_bodyhtml_replace'] );
														foreach( $array_pattern as $pattern )
														{
															$pattern = html_entity_decode( $pattern );
															$bodyhtml = str_replace( $pattern, '', $bodyhtml );
														}
													}
													if( $rows['source_clearlinks'] == 1 )
													{
														$bodyhtml = nv_anchortext_only( $bodyhtml );
													}
													$url_list[$_c]['bodyhtml'] = html_entity_decode( $bodyhtml );
													// Xac dinh tu khoa
													if( $rows['source_gettags'] == 1 )
													{
														$keywords = $body_content->find( 'meta[name=keywords]', 0 );
														if( $keywords )
														{
															$url_list[$_c]['keywords'] = $keywords->content;
															$url_list[$_c]['keywords'] = str_replace( '&nbsp;', ' ', $url_list[$_c]['keywords'] );
															$url_list[$_c]['keywords'] = html_entity_decode( $url_list[$_c]['keywords'], ENT_QUOTES );
															$url_list[$_c]['keywords'] = nv_htmlspecialchars( str_replace( '\'', '"', strip_tags( $url_list[$_c]['keywords'] ) ) );
															$url_list[$_c]['keywords'] = trim( $url_list[$_c]['keywords'] );
														}
														else
														{
															$record_status = 16;
															// Khong lay duoc tu khoa
															$record_note .= "<br/>" . $lang_module['job_status_16'] . '( ' . sprintf( $lang_module['job_status_sourcelink'], $url_list[$_c]['href'], $lang_module['article_status_source'], $lang_module['article_status_source'] ) . ' )';
														}
													}
												}
												else
												{
													$record_status = 18;
													// Khong lay duoc noi dung chi tiet
													$record_note .= "<br/>" . $lang_module['job_status_18'] . '( ' . sprintf( $lang_module['job_status_sourcelink'], $url_list[$_c]['href'], $lang_module['article_status_source'], $lang_module['article_status_source'] ) . ' )';
												}

												// Hometext
												$url_list[$_c]['hometext'] = '';
												$_hometext_pattern = $_hometext_remove = $_hometext_replace = '';
												if( !empty( $source_cat['block_hometext'] ) )
												{
													$_hometext_pattern = $source_cat['block_hometext'];
													$_hometext_remove = $source_cat['block_hometext_remove'];
													$_hometext_replace = $source_cat['block_hometext_replace'];
													$hometext = $ul->find( html_entity_decode( $_hometext_pattern ), 0 );
												}
												elseif( !empty( $source_cat['detail_hometext'] ) )
												{
													$_hometext_pattern = $source_cat['detail_hometext'];
													$_hometext_remove = $source_cat['detail_hometext_remove'];
													$_hometext_replace = $source_cat['detail_hometext_replace'];
													$hometext = $body_content->find( html_entity_decode( $_hometext_pattern ), 0 );
												}

												if( !empty( $_hometext_pattern ) )
												{
													if( $hometext )
													{
														if( !empty( $_hometext_remove ) )
														{
															$hometext_pattern = explode( ',', $_hometext_remove );
															foreach( $hometext_pattern as $hometext_pattern_i )
															{
																foreach( $hometext->find ( html_entity_decode ( $hometext_pattern_i ) ) as $hometext_pattern_ii )
																{
																	$hometext_pattern_ii->innertext = '';
																}
															}
														}

														$hometext = $hometext->plaintext;
														if( !empty( $hometext ) )
														{
															if( $_hometext_replace )
															{
																$array_pattern = explode( ',', $_hometext_replace );
																foreach( $array_pattern as $pattern )
																{
																	$hometext = str_replace( html_entity_decode( $pattern, ENT_QUOTES ), '', $hometext );
																}
															}
															$hometext = str_replace( '&nbsp;', ' ', $hometext );
															$hometext = html_entity_decode( $hometext, ENT_QUOTES );
															$url_list[$_c]['hometext'] = nv_htmlspecialchars( str_replace( '\'', '"', strip_tags( $hometext ) ) );
															$url_list[$_c]['hometext'] = trim( $url_list[$_c]['hometext'] );
														}
													}
													else
													{
														$record_status = 22;
														// Khong lay duoc gioi thieu ngan
														$record_note .= "<br/>" . $lang_module['job_status_22'] . '( ' . sprintf( $lang_module['job_status_sourcelink'], $url_list[$_c]['href'], $lang_module['article_status_source'], $lang_module['article_status_source'] ) . ' )';
													}
												}

												// Auto generate hometext
												if( !empty( $bodyhtml ) and empty( $url_list[$_c]['hometext'] ) and $rows['source_autohometext'] == 1 )
												{
													$url_list[$_c]['hometext'] = str_replace( '&nbsp;', ' ', $url_list[$_c]['hometext'] );
													$url_list[$_c]['hometext'] = html_entity_decode( $url_list[$_c]['hometext'], ENT_QUOTES );
													$url_list[$_c]['hometext'] = nv_htmlspecialchars( str_replace( '\'', '"', strip_tags( $url_list[$_c]['hometext'] ) ) );
													$url_list[$_c]['hometext'] = nv_clean60( strip_tags( $bodyhtml ), $rows['source_hometext_limit'] );
													$url_list[$_c]['hometext'] = trim( $url_list[$_c]['hometext'] );
												}

												// Anh thumb
												$url_list[$_c]['img'] = $_origin_thumb = '';
												if( !empty( $source_cat['block_thumb'] ) )
												{
													$img = $ul->find( html_entity_decode( $source_cat['block_thumb'] ), 0 );
													if( $img )
													{
														$img = $img->getAttribute( $_thumb_imgAttribute );
														if( !empty( $img ) )
														{
															$img = nv_phpuri( $module_file, $host, $img );
															$_origin_thumb = $img;
															$url_list[$_c]['img'] = urldecode( $img );
														}
													}
												}
												// Auto generate thumbnail
												if( !empty( $bodyhtml ) and !empty( $body_content ) and $rows['source_getthumbs'] == 1 )
												{
													$_meta_img = $body_content->find( 'meta[property="og:image"]', 0 );
													if( $_meta_img )
													{
														$url_list[$_c]['img'] = $_meta_img->content;
														$url_list[$_c]['img'] = nv_phpuri( $module_file, $host, $url_list[$_c]['img'] );
														$_origin_thumb = urldecode( $url_list[$_c]['img'] );
													}
													else
													{
														$url_list[$_c]['img'] = nv_get_firstimage( $bodyhtml );
														if( !empty( $url_list[$_c]['img'] ) )
														{
															$url_list[$_c]['img'] = nv_phpuri( $module_file, $host, $url_list[$_c]['img'] );
															$_origin_thumb = urldecode( $url_list[$_c]['img'] );
														}
													}
												}
												// Alt cua Anh thumb
												$url_list[$_c]['alt_thumb'] = '';
												if( !empty( $source_cat['block_thumb_alt'] ) )
												{
													$alt_thumb = $ul->find( html_entity_decode( $source_cat['block_thumb_alt'] ), 0 );
													if( $alt_thumb )
													{
														$alt_thumb = $alt_thumb->plaintext;
													}
												}
												else
												{
													$alt_thumb = $ul->find( html_entity_decode( $source_cat['block_thumb'] ), 0 );
													if( $alt_thumb )
													{
														$alt_thumb = $alt_thumb->alt;
													}
												}
												if( !empty( $alt_thumb ) )
												{
													$url_list[$_c]['alt_thumb'] = $alt_thumb;
													$url_list[$_c]['alt_thumb'] = str_replace( '&nbsp;', ' ', $url_list[$_c]['alt_thumb'] );
													$url_list[$_c]['alt_thumb'] = html_entity_decode( $url_list[$_c]['alt_thumb'], ENT_QUOTES );
													$url_list[$_c]['alt_thumb'] = nv_htmlspecialchars( str_replace( '\'', '"', strip_tags( $url_list[$_c]['alt_thumb'] ) ) );
													$url_list[$_c]['alt_thumb'] = trim( $url_list[$_c]['alt_thumb'] );
												}

												// Author of Article
												$url_list[$_c]['author'] = '';
												if( !empty( $source_cat['detail_author'] ) )
												{
													$author = $body_content->find( html_entity_decode( $source_cat['detail_author'] ), 0 );
													if( $author )
													{
														$author = $author->plaintext;
													}
												}
												if( !empty( $author ) )
												{
													$url_list[$_c]['author'] = $author;
													$url_list[$_c]['author'] = str_replace( '&nbsp;', ' ', $url_list[$_c]['author'] );
													$url_list[$_c]['author'] = html_entity_decode( $url_list[$_c]['author'], ENT_QUOTES );
													$url_list[$_c]['author'] = nv_htmlspecialchars( str_replace( '\'', '"', strip_tags( $url_list[$_c]['author'] ) ) );
													$url_list[$_c]['author'] = trim( $url_list[$_c]['author'] );
													// Replace data from Author
													if( $source_cat['detail_author_replace'] )
													{
														$array_pattern = explode( ',', $source_cat['detail_author_replace'] );
														foreach( $array_pattern as $pattern )
														{
															$url_list[$_c]['author'] = str_replace( html_entity_decode( $pattern, ENT_QUOTES ), '', $url_list[$_c]['author'] );
														}
													}
												}

												$rowcontent = array( );
												// Neu day du du lieu bat buoc thi chen tin vao DB
												if( (!empty( $title ) and !empty( $alias ) and !empty( $url_list[$_c]['bodyhtml'] )) and $rows['source_moderate'] != 3 )
												{
													$rowcontent['status'] = 1;
													if( $rows['source_moderate'] == 1 )
													{
														$rowcontent['status'] = 5;
													}
													$rowcontent['exptime'] = $rowcontent['topicid'] = $rowcontent['sourceid'] = 0;
													$rowcontent['sourcetext'] = '';

													// Giu link bai viet goc
													if( $rows['source_keep_source'] == 1 )
													{
														$rowcontent['sourcetext'] = $url_list[$_c]['href'];
														$url_info = @parse_url( $rowcontent['sourcetext'] );
														// Xu ly nguon tin
														if( isset( $url_info['scheme'] ) and isset( $url_info['host'] ) )
														{
															$sourceid_link = $url_info['scheme'] . '://' . $url_info['host'];
															$stmt = $db->prepare( 'SELECT sourceid FROM ' . $prefix . '_' . $rows['target_module'] . '_sources WHERE link= :link' );
															$stmt->bindParam( ':link', $sourceid_link, PDO::PARAM_STR );
															$stmt->execute( );
															$rowcontent['sourceid'] = $stmt->fetchColumn( );

															if( empty( $rowcontent['sourceid'] ) )
															{
																$weight = $db->query( 'SELECT max(weight) FROM ' . $prefix . '_' . $rows['target_module'] . '_sources' )->fetchColumn( );
																$weight = intval( $weight ) + 1;
																$_sql = "INSERT INTO " . $prefix . "_" . $rows['target_module'] . "_sources (title, link, logo, weight, add_time, edit_time) VALUES ( :title ,:sourceid_link, '', :weight, " . NV_CURRENTTIME . ", " . NV_CURRENTTIME . ")";

																$data_insert = array( );
																$data_insert['title'] = $url_info['host'];
																$data_insert['sourceid_link'] = $sourceid_link;
																$data_insert['weight'] = $weight;

																$rowcontent['sourceid'] = $db->insert_id( $_sql, 'sourceid', $data_insert );
															}
														}
														else
														{
															$stmt = $db->prepare( 'SELECT sourceid FROM ' . $prefix . '_' . $rows['target_module'] . '_sources WHERE title= :title' );
															$stmt->bindParam( ':title', $rowcontent['sourcetext'], PDO::PARAM_STR );
															$stmt->execute( );
															$rowcontent['sourceid'] = $stmt->fetchColumn( );

															if( empty( $rowcontent['sourceid'] ) )
															{
																$weight = $db->query( 'SELECT max(weight) FROM ' . $prefix . '_' . $rows['target_module'] . '_sources' )->fetchColumn( );
																$weight = intval( $weight ) + 1;
																$_sql = "INSERT INTO " . $prefix . "_" . $rows['target_module'] . "_sources (title, link, logo, weight, add_time, edit_time) VALUES ( :title, '', '', " . $weight . " , " . NV_CURRENTTIME . ", " . NV_CURRENTTIME . ")";
																$data_insert = array( );
																$data_insert['title'] = $rowcontent['sourcetext'];

																$rowcontent['sourceid'] = $db->insert_id( $_sql, 'sourceid', $data_insert );
															}
														}
													}

													$rowcontent['homeimgthumb'] = 3;
													if( empty( $url_list[$_c]['img'] ) )
													{
														$rowcontent['homeimgthumb'] = '';
													}
													if( $rows['source_getthumb'] == 1 )
													{
														if( !empty( $url_list[$_c]['img'] ) )
														{
															$_href = nv_save_thumb( urldecode( $url_list[$_c]['img'] ), $rows['target_module'], $module_name, $rows['source_img_stamp'] );
															$url_list[$_c]['img'] = $_href;
															if( nv_is_url( $url_list[$_c]['img'] ) )
															{
																$rowcontent['homeimgthumb'] = 3;
															}
															else
															{
																$rowcontent['homeimgthumb'] = 2;
															}
														}
													}
													$save_time = NV_CURRENTTIME - rand( 60, 120 );
													$sql = 'INSERT INTO ' . $prefix . '_' . $rows['target_module'] . '_rows
														(catid, listcatid, topicid, admin_id, author, sourceid, addtime, edittime, status, publtime, exptime, archive, title, alias, hometext, homeimgfile, homeimgalt, homeimgthumb, inhome, allowed_comm, allowed_rating, external_link, hitstotal, hitscm, total_rating, click_rating, instant_active, instant_template, instant_creatauto) VALUES
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
														 ' . intval( $rowcontent['source_keep_source'] ) . ',
														 1,
														 0,
														 0,
														 0,
                                                         0,
                                                         0,
                                                         0)';

													$data_insert = array( );
													$data_insert['listcatid'] = $rows['target_listcatid'];
													$data_insert['author'] = $url_list[$_c]['author'];
													$data_insert['title'] = $url_list[$_c]['title'];
													$data_insert['alias'] = $url_list[$_c]['alias'];
													$data_insert['hometext'] = $url_list[$_c]['hometext'];
													$data_insert['homeimgfile'] = $url_list[$_c]['img'];
													$data_insert['homeimgalt'] = $url_list[$_c]['alt_thumb'];
													$data_insert['homeimgthumb'] = intval( $rowcontent['homeimgthumb'] );
													$rowcontent['id'] = $db->insert_id( $sql, 'id', $data_insert );
													
													$log_status = 0;
													if( $rowcontent['id'] > 0 )
													{
														nv_insert_logs( NV_LANG_DATA, $rows['target_module'], $lang_module['target_content_add'], $url_list[$_c]['title'], $module_config[$module_name]['cron_user'] );
														$url_list[$_c]['bodyhtml'] = str_get_html( $url_list[$_c]['bodyhtml'] );
														// Luu anh bai viet ve host
														if( $rows['source_getimage'] == 1 )
														{
															$_m = 0;
															foreach( $url_list [$_c] ['bodyhtml']->find ( 'img' ) as $_img_i )
															{
																$_m++;
																$img = $_img_i->src;
																$_img_i->src = nv_save_img( urldecode( $img ), $rows['target_module'], $rowcontent['id'] . $_m, $module_name, $rows['source_img_stamp'] );
															}
														}
														$url_list[$_c]['bodyhtml'] = $url_list[$_c]['bodyhtml']->outertext;

														// Lam sach style bodyhtml
														if( $rows['source_cleanup_html'] == 1 )
														{
															$url_list[$_c]['bodyhtml'] = preg_replace( '/(<[^>]+) style=".*?"/i', '$1', $url_list[$_c]['bodyhtml'] );
															$url_list[$_c]['bodyhtml'] = preg_replace( '/(\s)+(id|class|rel|type)="(.*)"(\s)*/U', ' ', $url_list[$_c]['bodyhtml'] );
															$url_list[$_c]['bodyhtml'] = nv_remove_emptytag( $url_list[$_c]['bodyhtml'] );
															$url_list[$_c]['bodyhtml'] = nv_purifier( $module_file, $url_list[$_c]['bodyhtml'] );
														}
														$ct_query = array( );
                                                        $url_list[$_c]['files'] = $url_list[$_c]['layout_func'] = '';
                                                        
														// Tu dong xac dinh keywords
														$keywords = '';
														// Tao keywords tu noi dung bai viet
														if( $rows['source_getkeywords'] == 1 )
														{
															$keywords .= ($url_list[$_c]['hometext'] != '') ? $url_list[$_c]['hometext'] : $url_list[$_c]['bodyhtml'];
															$keywords = nv_get_keywords( $keywords, 100 );
														}
														$keywords = explode( ',', $keywords );

														// Uu tien loc tu khoa theo cac tu khoa da co trong tags thay vi doc tu tu dien
														$keywords_return = array( );
														foreach( $keywords as $keyword_i )
														{
															$sth = $db->prepare( 'SELECT COUNT(*) FROM ' . $prefix . '_' . $rows['target_module'] . '_tags_id where keyword = :keyword' );
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

														$rowcontent['keywords'] = implode( ',', $keywords_return ) . ',' . $url_list[$_c]['keywords'];
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
																$keyword_des = '';
																if( $rows['source_getkeywords_des'] == 1 )
																{
																	$keyword_des = $keyword;
																}
																$sth = $db->prepare( 'SELECT tid, alias, description, keywords FROM ' . $prefix . '_' . $rows['target_module'] . '_tags where alias= :alias OR FIND_IN_SET(:keyword, keywords)>0' );
																$sth->bindParam( ':alias', $alias_i, PDO::PARAM_STR );
																$sth->bindParam( ':keyword', $keyword, PDO::PARAM_STR );
																$sth->execute( );

																list( $tid, $alias, $keywords_i ) = $sth->fetch( 3 );
																if( empty( $tid ) )
																{
																	$array_insert = array( );
																	$array_insert['alias'] = $alias_i;
																	$array_insert['keyword'] = $keyword;
																	$array_insert['description'] = $keyword_des;

																	$tid = $db->insert_id( "INSERT INTO " . $prefix . "_" . $rows['target_module'] . "_tags (numnews, alias, description, image, keywords) VALUES (1, :alias, :description, '', :keyword)", "tid", $array_insert );
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
																			$sth = $db->prepare( 'UPDATE ' . $prefix . '_' . $rows['target_module'] . '_tags SET keywords= :keywords WHERE tid =' . $tid );
																			$sth->bindParam( ':keywords', $keywords_i2, PDO::PARAM_STR );
																			$sth->execute( );
																		}
																	}
																	$db->query( 'UPDATE ' . $prefix . '_' . $rows['target_module'] . '_tags SET numnews = numnews+1 WHERE tid = ' . $tid );
																}

																// insert keyword for table _tags_id
																try
																{
																	$sth = $db->prepare( 'INSERT INTO ' . $prefix . '_' . $rows['target_module'] . '_tags_id (id, tid, keyword) VALUES (' . $rowcontent['id'] . ', ' . intval( $tid ) . ', :keyword)' );
																	$sth->bindParam( ':keyword', $keyword, PDO::PARAM_STR );
																	$sth->execute( );
																}
																catch ( PDOException $e )
																{
																	$sth = $db->prepare( 'UPDATE ' . $prefix . '_' . $rows['target_module'] . '_tags_id SET keyword = :keyword WHERE id = ' . $rowcontent['id'] . ' AND tid=' . intval( $tid ) );
																	$sth->bindParam( ':keyword', $keyword, PDO::PARAM_STR );
																	$sth->execute( );
																}
															}
														}
                                                        
														if( $global_config['version'] == '4.0.29' )
														{
															$stmt = $db->prepare( 'INSERT INTO ' . $prefix . '_' . $rows['target_module'] . '_detail VALUES
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
															$stmt->bindParam( ':bodyhtml', $url_list[$_c]['bodyhtml'], PDO::PARAM_STR, strlen( $url_list[$_c]['bodyhtml'] ) );
															$stmt->bindParam( ':sourcetext', $rowcontent['sourcetext'], PDO::PARAM_STR, strlen( $rowcontent['sourcetext'] ) );
															$ct_query[] = ( int )$stmt->execute( );
														}elseif( $global_config['version'] == '4.5.03' )
														{
															$stmt = $db->prepare( 'INSERT INTO ' . $prefix . '_' . $rows['target_module'] . '_detail VALUES
																(' . $rowcontent['id'] . ',
																 :titlesite,
																 :description,
																 :bodyhtml,
																 :keywords,
																 "",
																 :sourcetext,
                                                                 :files,
																 ' . intval( $module_config[$module_name]['thumb_position'] ) . ',
                                                                 :layout_func,
																 0,
																 1,
																 1,
																 1
																 )' );
															$stmt->bindParam( ':titlesite', $url_list[$_c]['title'], PDO::PARAM_STR, strlen( $url_list[$_c]['title'] ) );
															$stmt->bindParam( ':description', $url_list[$_c]['hometext'], PDO::PARAM_STR, strlen( $url_list[$_c]['hometext'] ) );
															$stmt->bindParam( ':bodyhtml', $url_list[$_c]['bodyhtml'], PDO::PARAM_STR, strlen( $url_list[$_c]['bodyhtml'] ) );
															$stmt->bindParam( ':keywords', $url_list[$_c]['keywords'], PDO::PARAM_STR, strlen( $url_list[$_c]['bodyhtml'] ) );
															$stmt->bindParam( ':sourcetext', $rowcontent['sourcetext'], PDO::PARAM_STR, strlen( $rowcontent['sourcetext'] ) );
															$stmt->bindParam( ':files', $url_list[$_c]['files'], PDO::PARAM_STR, strlen( $url_list[$_c]['files'] ) );
															$stmt->bindParam( ':layout_func', $rowcontent['layout_func'], PDO::PARAM_STR, strlen( $rowcontent['layout_func'] ) );
															$ct_query[] = ( int )$stmt->execute( );
														}
														else
														{
															$stmt = $db->prepare( 'INSERT INTO ' . $prefix . '_' . $rows['target_module'] . '_detail VALUES
																(' . $rowcontent['id'] . ',
																 :titlesite,
																 :description,
																 :bodyhtml,
																 :keywords,
																 :sourcetext,
                                                                 :files,
																 ' . intval( $module_config[$module_name]['thumb_position'] ) . ',
                                                                 :layout_func,
																 0,
																 1,
																 1,
																 1
																 )' );
															$stmt->bindParam( ':titlesite', $url_list[$_c]['title'], PDO::PARAM_STR, strlen( $url_list[$_c]['title'] ) );
															$stmt->bindParam( ':description', $url_list[$_c]['hometext'], PDO::PARAM_STR, strlen( $url_list[$_c]['hometext'] ) );
															$stmt->bindParam( ':bodyhtml', $url_list[$_c]['bodyhtml'], PDO::PARAM_STR, strlen( $url_list[$_c]['bodyhtml'] ) );
															$stmt->bindParam( ':keywords', $url_list[$_c]['keywords'], PDO::PARAM_STR, strlen( $url_list[$_c]['bodyhtml'] ) );
															$stmt->bindParam( ':sourcetext', $rowcontent['sourcetext'], PDO::PARAM_STR, strlen( $rowcontent['sourcetext'] ) );
															$stmt->bindParam( ':files', $url_list[$_c]['files'], PDO::PARAM_STR, strlen( $url_list[$_c]['files'] ) );
															$stmt->bindParam( ':layout_func', $rowcontent['layout_func'], PDO::PARAM_STR, strlen( $rowcontent['layout_func'] ) );
															$ct_query[] = ( int )$stmt->execute( );
														}
														// Cac chuyen muc
														foreach( $catids as $catid )
														{
															$ct_query[] = ( int )$db->exec( 'INSERT INTO ' . $prefix . '_' . $rows['target_module'] . '_' . $catid . ' SELECT * FROM ' . $prefix . '_' . $rows['target_module'] . '_rows WHERE id=' . $rowcontent['id'] );
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
																$db->query( 'INSERT INTO ' . $prefix . '_' . $rows['target_module'] . '_block (bid, id, weight) VALUES (' . $bid_i . ', ' . $rowcontent['id'] . ', 0)' );
															}
														}

														if( !$duplicate_rows )
														{
															$log_status = 1;
															if( $rows['source_moderate'] == 1 )
															{
																$log_status = 3;
																$record_status = 2;
																$record_note .= "<br/>" . $lang_module['job_status_2'];
															}
															// Luu noi dung vao rows cua leecher
															$sql = 'INSERT INTO ' . $table_prefix . '_rows
															( news_id, sid, scatid, addtime, status, title, news_url, alias, hometext, homeimgalt, thumb, keywords, author, bodyhtml) VALUES
															(:news_id,
															:sid,
															:scatid,
															:addtime,
															:status,
															:title,
															:news_url,
															:alias,
															:hometext,
															:homeimgalt,
															:thumb,
															:keywords,
															:author,
															:bodyhtml)';

															$data_insert = array( );
															$data_insert['news_id'] = $rowcontent['id'];
															$data_insert['sid'] = $rows['id'];
															$data_insert['scatid'] = $rows['catid'];
															$data_insert['addtime'] = NV_CURRENTTIME;
															$data_insert['status'] = $log_status;
															$data_insert['title'] = $url_list[$_c]['title'];
															$data_insert['news_url'] = $url_list[$_c]['href'];
															$data_insert['alias'] = $url_list[$_c]['alias'];
															$data_insert['hometext'] = $url_list[$_c]['hometext'];
															$data_insert['homeimgalt'] = $url_list[$_c]['alt_thumb'];
															$data_insert['thumb'] = $_origin_thumb;
															$data_insert['keywords'] = $url_list[$_c]['keywords'];
															$data_insert['author'] = $url_list[$_c]['author'];
															$data_insert['bodyhtml'] = $bodyhtml;
															$news_id = $db->insert_id( $sql, 'id', $data_insert );
															if( $news_id > 0 )
															{
																$nv_Cache->delMod( $module_name );
															}
														}
													}
													else
													{
														$record_note .= '<br/>' . $lang_module['errorsave'];
													}
													$_c++;
												}
												elseif( $rows['source_moderate'] == 3 and !$duplicate_rows and !empty( $bodyhtml ) )
												{
													$record_status = 3;
													// Hoan thanh - Dang cho duyet
													$record_note .= "<br/>" . $lang_module['job_status_3'];
													$log_status = 5;
													// Bai viet chuyen sang cho duyet
													// Luu noi dung vao rows cua leecher
													$sql = 'INSERT INTO ' . $table_prefix . '_rows
													( news_id, sid, scatid, addtime, status, title, news_url, alias, hometext, homeimgalt, thumb, keywords, author, bodyhtml) VALUES
													(:news_id,
													:sid,
													:scatid,
													:addtime,
													:status,
													:title,
													:news_url,
													:alias,
													:hometext,
													:homeimgalt,
													:thumb,
													:keywords,
													:author,
													:bodyhtml)';

													$data_insert = array( );
													$data_insert['news_id'] = 0;
													$data_insert['sid'] = $rows['id'];
													$data_insert['scatid'] = $rows['catid'];
													$data_insert['addtime'] = $leech_runtime;
													$data_insert['status'] = $log_status;
													$data_insert['title'] = $url_list[$_c]['title'];
													$data_insert['news_url'] = $url_list[$_c]['href'];
													$data_insert['alias'] = $url_list[$_c]['alias'];
													$data_insert['hometext'] = $url_list[$_c]['hometext'];
													$data_insert['homeimgalt'] = $url_list[$_c]['alt_thumb'];
													$data_insert['thumb'] = $_origin_thumb;
													$data_insert['keywords'] = $url_list[$_c]['keywords'];
													$data_insert['author'] = $url_list[$_c]['author'];
													$data_insert['bodyhtml'] = $bodyhtml;
													$news_id = $db->insert_id( $sql, 'id', $data_insert );
													if( $news_id > 0 )
													{
														$nv_Cache->delMod( $module_name );
													}
													$_c++;
												}
												else
												{
													if( !$duplicate_rows )
													{
														$record_status = 30;
														// Khong lay duoc noi dung trang chi tiet
														$record_note .= "<br/>" . $lang_module['job_status_18'] . '( ' . sprintf( $lang_module['job_status_sourcelink'], $url_list[$_c]['href'], $lang_module['article_status_source'], $lang_module['article_status_source'] ) . ' )';
														$log_status = 99;
														// Ignore link nay trong tuong lai
														// Luu noi dung vao rows cua leecher
														$sql = 'INSERT INTO ' . $table_prefix . '_rows
														( news_id, sid, scatid, addtime, status, title, news_url, alias, hometext, homeimgalt, thumb, keywords, author, bodyhtml) VALUES
														(:news_id,
														:sid,
														:scatid,
														:addtime,
														:status,
														:title,
														:news_url,
														:alias,
														:hometext,
														:homeimgalt,
														:thumb,
														:keywords,
														:author,
														:bodyhtml)';

														$data_insert = array( );
														$data_insert['news_id'] = 0;
														$data_insert['sid'] = $rows['id'];
														$data_insert['scatid'] = $rows['catid'];
														$data_insert['addtime'] = $leech_runtime;
														$data_insert['status'] = $log_status;
														$data_insert['title'] = $url_list[$_c]['title'];
														$data_insert['news_url'] = $url_list[$_c]['href'];
														$data_insert['alias'] = $url_list[$_c]['alias'];
														$data_insert['hometext'] = $url_list[$_c]['hometext'];
														$data_insert['homeimgalt'] = $url_list[$_c]['alt_thumb'];
														$data_insert['thumb'] = $_origin_thumb;
														$data_insert['keywords'] = $url_list[$_c]['keywords'];
														$data_insert['author'] = $url_list[$_c]['author'];
														$data_insert['bodyhtml'] = $bodyhtml;
														$news_id = $db->insert_id( $sql, 'id', $data_insert );
														if( $news_id > 0 )
														{
															$nv_Cache->delMod( $module_name );
														}
													}
												}
												// End luu tin
											}
											else
											{
												$record_status = 30;
												// Khong lay duoc link bai viet chi tiet
												$record_note .= "<br/>" . $lang_module['job_status_20'];
											}
										}
										// Dung khi toi gioi han lay tin
										if( $_c == $numget )
										{
											break;
										}
									}
									$_count = sizeof( $url_list );
									$html->clear;
								}
								else
								{
									$record_status = 30;
									// Error_10 Khong phan tich duoc Khoi bao ngoai nhom tin
									$record_note .= "<br/>" . $lang_module['job_status_10'];
								}
							}
							else
							{
								$record_status = 30;
								// Error_12 Khong phan tich duoc Nguon tin
								$record_note .= "<br/>" . $lang_module['job_status_12'];
							}
						}// End of check URL
						else
						{
							$record_status = 11;
							// Chua khai bao day du nhom tin/ Nguon tin.
							$record_note .= "<br/>" . $lang_module['job_status_11'];
						}
						// Delay xx seconds for each url - 5 is default
						sleep( SLEEP_TIMER );
					}
				}
				$db->query( 'UPDATE ' . $table_prefix . '_source SET cron_lastrun = ' . $leech_runtime . ' WHERE id = ' . $rows['id'] );
				nv_leech_record( $rows['id'], $rows['catid'], $userid, $record_status, $record_note, $leech_runtime, $table_prefix );
			}
			elseif( $rows['status'] == 0 )
			{
				return $lang_module['leech_news_task_disabled'];
			}
			elseif( empty( $rows['id'] ) )
			{
				return $lang_module['leech_news_task_noID'];
			}
		}
		else
		{
			return $lang_module['leech_news_task_noID'];
		}
	}
	else
	{
		return $lang_module['not_curl'];
	}

	nv_prune_records( $table_prefix, $module_name );
	$nv_Cache->delMod( $rows['target_module'] );

	return $lang_module['leech_news_task_completed'];
	unset( $url_list, $html );
}
