<?php
/**
 * @copyright	Copyright (c) 2009-2010 TIG Corporation (http://www.tig.vn)
 * @license		http://www.gnu.org/licenses/gpl-2.0.txt GNU GENERAL PUBLIC LICENSE Version 2
 * @version 	$Id: String.php 4032 2010-07-27 01:44:26Z leha $
 * @since		2.0.0
 */

class Sky_Utility_String 
{
	public static function normalizeUri($uri) 
	{
		if ($uri == null) {
			return null;
		}
		$uri = ltrim($uri, '/');
		return rtrim($uri, '/');
	}
	
	public static function removeSign($string, $seperator = '-', $allowANSIOnly = false) 
	{
		$pattern = array(
						"a" => "á|à|ạ|ả|ã|Á|À|Ạ|Ả|Ã|ă|ắ|ằ|ặ|ẳ|ẵ|Ă|Ắ|Ằ|Ặ|Ẳ|Ẵ|â|ấ|ầ|ậ|ẩ|ẫ|Â|Ấ|Ầ|Ậ|Ẩ|Ẫ",
						"o" => "ó|ò|ọ|ỏ|õ|Ó|Ò|Ọ|Ỏ|Õ|ô|ố|ồ|ộ|ổ|ỗ|Ô|Ố|Ồ|Ộ|Ổ|Ỗ|ơ|ớ|ờ|ợ|ở|ỡ|Ơ|Ớ|Ờ|Ợ|Ở|Ỡ",
						"e" => "é|è|ẹ|ẻ|ẽ|É|È|Ẹ|Ẻ|Ẽ|ê|ế|ề|ệ|ể|ễ|Ê|Ế|Ề|Ệ|Ể|Ễ",
						"u" => "ú|ù|ụ|ủ|ũ|Ú|Ù|Ụ|Ủ|Ũ|ư|ứ|ừ|ự|ử|ữ|Ư|Ứ|Ừ|Ự|Ử|Ữ",
						"i" => "í|ì|ị|ỉ|ĩ|Í|Ì|Ị|Ỉ|Ĩ",
						"y" => "ý|ỳ|ỵ|ỷ|ỹ|Ý|Ỳ|Ỵ|Ỷ|Ỹ",
						"d" => "đ|Đ",
						"c" => "ç",
					);
		while (list($key, $value) = each($pattern)) {
			$string = preg_replace('/' . $value . '/i', $key, $string);	
		}
		if ($allowANSIOnly) {
			$string = strtolower($string);
			$string = preg_replace("/(\w*)(\W+)/i", "$1".$seperator, $string);
		}
		return $string;
	}
}
