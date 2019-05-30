<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('base64_to_png')) {
	/**
	 * base64转jpg
	 *
	 * @param [type] $url
	 * @param [type] $data
	 * @return void
	 */
	function base64_to_png($img_parent_path, $base64)
	{
		$baseurl=base_url();
		$cc="img/$img_parent_path/".date('Ymd');
		if(!file_exists($cc)){
			mkdir($cc,0777,true);
		}
		$jpg_data=array();
		$img = base64_decode($base64);
		$filename=date('YmdHis').rand(100000,999999).'.png';
		$targetName = $cc."/".$filename;
		$a = file_put_contents($targetName, $img);
		$jpg_url=base_url().$targetName;
		return $a===false?false:$jpg_url;
	}	
}