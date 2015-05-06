<?php

namespace MattLeff;

class Helper
{
	
	public static function safeAccess($key, $array, $fallback_value = null)
	{
		$is_key_set = false;
		if(is_array($array)) {
			$is_key_set = array_key_exists($key, $array);
		} elseif(is_object( $array ) && $array instanceof ArrayAccess) {
			$is_key_set = isset($array[$key]);
		}
		return $is_key_set ? $array[$key] : $fallback_value;
	}
	
}
