<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if ( ! function_exists('http')) {
    function http($url, $data = '', $method = 'GET')
    {
        $curl = curl_init(); // 启动一个CURL会话
        curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 对认证证书来源的检查
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false); // 从证书中检查SSL加密算法是否存在
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
        if ($method == 'POST') {
            curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
            if ($data != '') {
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data); // Post提交的数据包
            }
        }
        curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
        curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
        $tmpInfo = curl_exec($curl); // 执行操作
        curl_close($curl); // 关闭CURL会话
        return $tmpInfo; // 返回数据
    }
}
if ( ! function_exists('network_getclientip')) {
	function network_getclientip()
	{
	    $ipaddress = '';
	    if (isset($_SERVER['HTTP_CLIENT_IP']))
	        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
	    else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
	        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
	    else if(isset($_SERVER['X_FORWARDED_FOR']))
	        $ipaddress = $_SERVER['X_FORWARDED_FOR'];
	    else if(isset($_SERVER['HTTP_X_FORWARDED']))
	        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
	    else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
	        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
	    else if(isset($_SERVER['HTTP_FORWARDED']))
	        $ipaddress = $_SERVER['HTTP_FORWARDED'];
	    else if(isset($_SERVER['REMOTE_ADDR']))
	        $ipaddress = $_SERVER['REMOTE_ADDR'];
	    else
	        $ipaddress = 'UNKNOWN';
	 
	    return $ipaddress;
	}
}

if ( ! function_exists('network_get')) {
	function network_get($url, $content = null, $ishttps = false)
	{
		if (function_exists("curl_init")) {
			$curl = curl_init();
            $data='';
			if (is_array($content)) {
				$data = http_build_query($content);
			}

			if (is_string($data)&&!empty($data)) {
				curl_setopt($curl, CURLOPT_URL, $url."?".$data);
			} else {
				curl_setopt($curl, CURLOPT_URL, $url);
			}
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_HEADER, false);
			curl_setopt($curl, CURLOPT_TIMEOUT, 60); //seconds
			
			// https verify
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, $ishttps);
			//curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);

			$ret_data = curl_exec($curl);

			if (curl_errno($curl)) {
				curl_close($curl);
				return false;
			}
			else {
				curl_close($curl);
				return $ret_data;
			}
		} else {
			throw new Exception("[PHP] curl module is required");
		}
	}	
}

if ( ! function_exists('network_post')) {
	function network_post($url, $content = null, $ishttps = false)
	{
		if (function_exists("curl_init")) {
			$headers=array();
			// $headers[]="Content-type: text/xml";
			
			$curl = curl_init();

			if (is_array($content)) {
				$data = http_build_query($content);
			}

			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $content);
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_HEADER, false);
			curl_setopt($curl, CURLOPT_TIMEOUT, 60); //seconds
			
			// https verify
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, $ishttps);
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, $ishttps);
			curl_setopt($curl, CURLOPT_HTTPHEADER,$headers);
			$ret_data = curl_exec($curl);

			if (curl_errno($curl)) {
				curl_close($curl);
				return false;
			}
			else {
				curl_close($curl);
				return $ret_data;
			}
		} else {
			throw new Exception("[PHP] curl module is required");
		}
	}	
}
if ( ! function_exists('network_postforxml')) {
	function network_postforxml($url, $content = null, $ishttps = false)
	{
		if (function_exists("curl_init")) {
			$curl = curl_init();
			$header[] = "Content-type: text/xml";//定义content-type为xml 
			curl_setopt($curl, CURLOPT_URL, $url); //定义表单提交地址 
			curl_setopt($curl, CURLOPT_POST, 1);   //定义提交类型 1：POST ；0：GET 
			curl_setopt($curl, CURLOPT_HEADER, 0); //定义是否显示状态头 1：显示 ； 0：不显示 
			curl_setopt($curl, CURLOPT_HTTPHEADER, $header);//定义请求类型 
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);//定义是否直接输出返回流 
			curl_setopt($curl, CURLOPT_POSTFIELDS, $content); //定义提交的数据，这里是XML文件 

			$ret_data = curl_exec($curl);

			if (curl_errno($curl)) {
				curl_close($curl);
				return false;
			}
			else {
				curl_close($curl);
				return $ret_data;
			}
		} else {
			throw new Exception("[PHP] curl module is required");
		}
	}	
}
if ( ! function_exists('network_postforjson')) {
	function network_postforjson($url, $content = null, $ishttps = false)
	{
		if (function_exists("curl_init")) {
			$curl = curl_init();

			if (is_array($content)) {
				$content = json_encode($content);
			}
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $content);
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-type:application/json", "Accept:application/json"));
			curl_setopt($curl, CURLOPT_TIMEOUT, 60); //seconds
			
			// https verify
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, $ishttps);
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, $ishttps);

			$ret_data = curl_exec($curl);

			if (curl_errno($curl)) {
				curl_close($curl);
				return false;
			}
			else {
				curl_close($curl);
				return $ret_data;
			}
		} else {
			throw new Exception("[PHP] curl module is required");
		}
	}	
}

if ( ! function_exists('network_postreturnstatuscode')) {
	function network_postreturnstatuscode($url, $content = null, $ishttps = false)
	{
		if (function_exists("curl_init")) {
			$curl = curl_init();

			if (is_array($content)) {
				$content = json_encode($content);
			}

			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $content);
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-type:application/json",
													     "Accept:application/json"));
			curl_setopt($curl, CURLOPT_TIMEOUT, 60); //seconds
			
			// https verify
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, $ishttps);
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, $ishttps);

			$ret_data = curl_exec($curl);
			$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

			if (curl_errno($curl)) {
				curl_close($curl);
				return false;
			}
			else {
				curl_close($curl);
				return $httpCode;
			}
		} else {
			throw new Exception("[PHP] curl module is required");
		}
	}	
}

if ( ! function_exists('postforxintaiprotocal')) {

	function postforxintaiprotocal($baseurl, $interfacename, $systemno, $password, $clientid, $bodycontent)
	{
		if (function_exists("curl_init")) {

			$curl = curl_init();

			date_default_timezone_set('Asia/Shanghai');
			$datetime = date("YmdHis");
			$date = date("Ymd");
			$transNo = $interfacename.$date."00000000";

			$signInfo = strtoupper(md5(strtoupper(md5($systemno.$transNo.$datetime)).$password));

			$postString = array("time" => $datetime,
								"body" => $bodycontent,
								"signInfo" => $signInfo,
								"systemNo" => $systemno,
								"id" => $transNo,
								"clientId" => $clientid);

			$content = json_encode($postString);

			$url = $baseurl.$interfacename."/";

			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $content);
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-type:application/json",
													     "Accept:application/json"));
			curl_setopt($curl, CURLOPT_TIMEOUT, 60); //seconds
			
			// https verify
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);

			$ret_data = curl_exec($curl);

			if (curl_errno($curl)) {
				curl_close($curl);
				return false;
			}
			else {
				curl_close($curl);
				if ($ret_data) {
				 	$response = json_decode($ret_data, true);

				 	if ($response['returnCode'] == "1") {
				 		return array("status" => "1",
				 					 "data" => $response['body']);
				 	} else {
				 		return array("status" => "0",
				 					 "msg" => $response['returnMessage']);
				 	}

				} else {
					return false;
				}
			}
		} else {
			throw new Exception("[PHP] curl module is required");
		}
	}	
}
if ( ! function_exists('network_xwwwformurlencoded_post')) {

	function network_xwwwformurlencoded_post($url, $data)
	{
		
		$postdata = http_build_query ( $data );

		$opts = array (
				'http' => array (
						'method' => 'POST',
						'header' => 'Content-type: application/x-www-form-urlencoded',
						'content' => $postdata 
				) 
		);
		
		
		$context = stream_context_create ( $opts );
		
		$result = file_get_contents ( $url, false, $context );
		
		return $result;

	}	
}