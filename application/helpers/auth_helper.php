<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
if ( ! function_exists('checkSource')) {
	function checkSource(){
		ini_set("display_errors",1);
		$ci = & get_instance();
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH'])&&$_SERVER['HTTP_X_REQUESTED_WITH']=='XMLHttpRequest')
		{
			return true;
		}
		if($ci->config->item('is_debug')){
			return true;
		}
		else if(isset($_SERVER['HTTP_WITHUROAD'])&&$_SERVER['HTTP_WITHUROAD']=='1')
		{
			return true;
		}
		else
		{
			$errorMsg = "传入参数错误";
			ob_start();
			$ci->load->view('jsonerror', array('errorMsg' => $errorMsg));
			ob_end_flush();
			exit;
		}
	}
}


if ( ! function_exists('checkParams')) {
	function checkParams(){
		$ci = & get_instance();
		if (func_num_args() > 0) {
			$args = func_get_args();
			foreach ($args as $param) { 
				if ($param===""||$param==NULL) { 
					$errorMsg = "传入参数错误";
					ob_start();
					$ci->load->view('jsonerror', array('errorMsg' => $errorMsg));
					ob_end_flush();
					exit();
				}
			}
		}
		else {
			$errorMsg = "传入参数错误";
			ob_start();
			$ci->load->view('jsonerror', array('errorMsg' => $errorMsg));
			ob_end_flush();
			exit;
		}
		
	}
}

if ( ! function_exists('checkSign')) {
    function checkSign($data, $sign) {
        $KEY = 'LKYWa19a01hafoh2';
        $strToSign = '';
        foreach ($data as $key => $value) {
            if($key == 'sign')break;
            $strToSign .= $key.'='.$value.'&';
        }

        $strToSign = $strToSign.$KEY;

//        var_dump($strToSign);exit();

//        $strToSign = 'IncidentUID=23ca0c9d-5877-4cdf-9d9f-b02660d9312d&IncidentDetail=测试事件&OccurTime=2017/1/10 16:44:48&BeginPile=K2&BeginPileDistance=200&EndPile=K2&EndPileDistance=217&Longitude=123.123456&Latitude=33.456789&LKYWa19a01hafoh2';
        $strEncrypt = hash('sha256', $strToSign);

//        var_dump($strEncrypt);exit();
        $sign1 = strtoupper($sign);
        $sign2 = strtoupper($strEncrypt);
        $result = ($sign1 == $sign2);
        if(!$result){
            $ci = &get_instance();
            ob_start();
//            $ci->load->view('jsonerror', array('errorMsg' => '非法调用！strToSign:'.$strEncrypt));
            $ci->load->view('jsonerror', array('errorMsg' => '非法调用！Sign错误'.$strEncrypt));//.$strEncrypt
            ob_end_flush();
            exit;
        }
        return $result;
    }
}
