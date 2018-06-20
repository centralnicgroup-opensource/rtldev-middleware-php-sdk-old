<?php 
declare(strict_types=1);
/**
 * HEXONET
 * Copyright © HEXONET
 */

namespace HEXONET;

/**
 * HEXONET Util
 *
 * @package HEXONET
 */
class Util {

	/**
	 * Encode the command array in a command-string
	 *
	 * @param array $commandarray Command array
	 * @return string Encoded command as a string
	 */
	public static function command_encode($commandarray) {
		if (!is_array($commandarray)) return $commandarray;
		$command = "";
		foreach ( $commandarray as $key => $value ) {
			if(is_array($value)){
				if(Util::is_associative($value)){
					$zero = 0;
					foreach($value as $k => $v){
						$command .= "$key$zero$k=$v\n";
					}
				}else{
					foreach($value as $k => $v){
						$command .= "$key$k=$v\n";
					}
				}
				
			}else{
				$command .= "$key=$value\n";
			}
		}
		return $command;
	}
	
	/**
	 * Returns true if associative array
	 * 
	 * @param array $arr An array
	 * @return boolean True if associative array
	 */
	public static function is_associative($arr){
		return array_keys($arr) !== range(0, count($arr) - 1);
	}
	
	/**
	 * Convert the response string as a hash
	 * 
	 * @param string $response
	 * @return array The response as a hash
	 */
	public static function response_to_hash ($response) {
		if (is_array($response)) return $response;
		$hash = array(
				"PROPERTY" => array(),
				"CODE" => "423",
				"DESCRIPTION" => "Connection failed - Verify URL (For Windows users, replace HTTPS with HTTP or verify the CA cert.)"
		);
		if (!$response) return $hash;
		$rlist = explode( "\n", $response );
		foreach ( $rlist as $item ) {
			if ( preg_match("/^([^\=]*[^\t\= ])[\t ]*=[\t ]*(.*)$/", $item, $m) ) {
				$attr = $m[1];
				$value = $m[2];
				$value = preg_replace( "/[\t ]*$/", "", $value );
				if ( preg_match( "/^property\[([^\]]*)\]/i", $attr, $m) ) {
					$prop = strtoupper($m[1]);
					$prop = preg_replace( "/\s/", "", $prop );
					if ( in_array($prop, array_keys($hash["PROPERTY"])) ) {
						array_push($hash["PROPERTY"][$prop], $value);
					}
					else {
						$hash["PROPERTY"][$prop] = array($value);
					}
				}
				else {
					$hash[strtoupper($attr)] = $value;
				}
			}
		}
		if ( (!$hash["CODE"]) || (!$hash["DESCRIPTION"]) ) {
			$hash = array(
					"PROPERTY" => array(),
					"CODE" => "423",
					"DESCRIPTION" => "Invalid response from API"
			);
		}
		return $hash;
	}
	
	/**
	 * Convert the response string as a list hash
	 *
	 * @param string $response
	 * @return array The response as a list hash
	 */
	public static function response_to_list_hash ($response) {

		
		$list = array(
				"FIRST" => 0,
				"PAGE" => 1,
				"LAST" => -1,
				"LIMIT" => 1,
				"ITEMS" => array(),
				"COLUMNS" => array(),
				"QUEUETIME" => null,
				"TOTAL" => 0,
				"DESCRIPTION" => $response["DESCRIPTION"],
				"PAGES" => 0,
				"COUNT" => 0,
				"RUNTIME" => null,
				"CODE" => $response["CODE"],
				"LASTPAGEFIRST" => -1,
		);
		foreach ( $response["PROPERTY"] as $property => $values ) {
			if ( preg_match('/^(FIRST|LAST|COUNT|LIMIT|TOTAL|ITEMS|COLUMN)$/', $property) ) {
				$list[$property] = $response["PROPERTY"][$property][0];
			}
			else {
				foreach ( $values as $index => $value ) {
					$list["ITEMS"][$index][$property] = $value;
					if(!in_array($property, $list["COLUMNS"]))
						array_push($list["COLUMNS"], $property);
				}
			}
		}
		if ( isset($list["FIRST"]) && isset($list["LIMIT"]) ) {
			$list["PAGE"] = floor($list["FIRST"] / $list["LIMIT"]) + 1;
			if ( $list["PAGE"] > 1 ) {
				$list["PREVPAGE"] = $list["PAGE"] - 1;
				$list["PREVPAGEFIRST"] = ($list["PREVPAGE"]-1) * $list["LIMIT"];
			}
			$list["NEXTPAGE"] = $list["PAGE"] + 1;
			$list["NEXTPAGEFIRST"] = ($list["NEXTPAGE"]-1) * $list["LIMIT"];
		}
		if ( isset($list["TOTAL"]) && isset($list["LIMIT"]) ) {
			$list["PAGES"] = floor(($list["TOTAL"] + $list["LIMIT"] - 1) / $list["LIMIT"]);
			$list["LASTPAGEFIRST"] = ($list["PAGES"] - 1) * $list["LIMIT"];
			if ( isset($list["NEXTPAGE"]) && ($list["NEXTPAGE"] > $list["PAGES"]) ) {
				unset($list["NEXTPAGE"]);
				unset($list["NEXTPAGEFIRST"]);
			}
		}
		
		if(isset($response["RUNTIME"]))
			$list["RUNTIME"] = $response["RUNTIME"];

		if(isset($response["QUEUETIME"]))
			$list["QUEUETIME"] = $response["QUEUETIME"];
		
		return $list;
	}
	
	/**
	 * Convert the Unix-Timestamp to a SQL datetime
	 * If no timestamp given, returns the current datetime
	 *
	 * @param string|int $timestamp A Unix-Timestamp or nothing
	 * @return datetime The SQL datetime 
	 */
	public static function sqltime($timestamp=null){
		if(!isset($timestamp)){
			$timestamp = time();
		}
		return gmdate("Y-m-d H:i:s", $timestamp);	
	}
	
	/**
	 * Convert the SQL datetime to Unix-Timestamp
	 * 
	 * @param datetime $sqldatetime The SQL datetime
	 * @return int A Unix-Timestamp
	 */
	public static function timesql($sqldatetime){
		$datetime = new DateTime($sqldatetime);
		return $datetime->format('U');
	}
	
	/**
	 * URL-encodes string
	 * This function is convenient when encoding a string to be used in a query part of a URL
	 * 
	 * @param string $string The string
	 * @return string The encoded url string
	 */
	public static function url_encode($string){
		return rawurlencode($string);
	}
	
	/**
	 * Decodes URL-encoded string
	 * Decodes any %## encoding in the given string.
	 *
	 * @param string $string The encoded URL string
	 * @return string The decoded string
	 */
	public static function url_decode($string){
		return rawurldecode($string);
	}
	
	
	/**
	 * Encodes data with MIME base64
	 * This encoding is designed to make binary data survive transport through transport layers that are not 8-bit clean, such as mail bodies. 
	 *
	 * @param string $string The string
	 * @return string The encoded base64 string
	 */
	public static function base64_decode($string){
		return base64_decode($string);
	}
	
	/**
	 * Decodes data encoded with MIME base64
	 *
	 * @param string $string The encoded base64 string
	 * @return string The decoded string
	 */
	public static function base64_encode($string){
		return base64_encode($string);
	}
	

}
?>