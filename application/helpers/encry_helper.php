<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('des3Decrypt')) {
/**
	 * 3des解密
	 *
	 * @return void
	 */
	function des3Decrypt($str,$password){
		$m = "tripledes";  
		
		$iv = mcrypt_create_iv(mcrypt_get_iv_size($m,MCRYPT_MODE_ECB), MCRYPT_RAND);

		$td = mcrypt_module_open(MCRYPT_3DES, '', MCRYPT_MODE_ECB, '');  
		mcrypt_generic_init($td, $password, $iv);  
		$res  = pkcs5_unpadding(mdecrypt_generic($td, base64_decode($str)));  
		mcrypt_generic_deinit($td);  
		mcrypt_module_close($td);  
		return $res;
	}
}
if ( ! function_exists('des3Encrypt')) {
	/**
	 * 3des加密
	 *
	 * @return void
	 */
	function des3Encrypt($str,$password){
		//加密方法   
		$m = "tripledes";  
		
		$iv = mcrypt_create_iv(mcrypt_get_iv_size($m,MCRYPT_MODE_ECB), MCRYPT_RAND);
	
		$td = mcrypt_module_open(MCRYPT_3DES, '', MCRYPT_MODE_ECB, '');  
		mcrypt_generic_init($td, $password, $iv);  
		
		$str = base64_encode(mcrypt_generic($td,pkcs5_padding($str,8)));
		mcrypt_generic_deinit($td);  
		mcrypt_module_close($td);
		   
		return $str;
	}
}
if ( ! function_exists('pkcs5_padding')) {
	function pkcs5_padding($text, $blocksize)  
    {  
      $pad = $blocksize - (strlen($text) % $blocksize);  
      return $text . str_repeat(chr($pad), $pad);  
	}
}
if ( ! function_exists('pkcs5_unpadding')) {
    function pkcs5_unpadding($text)  
    {  
      $pad = ord($text{strlen($text)-1});  
      if ($pad > strlen($text))   
      {  
        return false;  
      }  
      if( strspn($text, chr($pad), strlen($text) - $pad) != $pad)  
      {  
        return false;  
      }  
      return substr($text, 0, -1 * $pad);  
	}
} 