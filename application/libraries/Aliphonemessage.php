<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;
class Aliphonemessage {
	public $appid='';
	public $sercert='';
	private $voice_tmp='TTS_165115667';//语音模板
	private $valid_code_tmp='SMS_164740167';//短信模板
	private $sign_name='江西省高速集团';//签名
	public function __construct($config = array()){
		empty($config) OR $this->initialize($config);
	}
	public function initialize($config = array())
	{
		foreach ($config as $key => $val)
		{
			if (isset($this->$key))
			{
				$this->$key = $val.'';
			}
		}
		AlibabaCloud::accessKeyClient($this->appid, $this->sercert)
                        ->regionId('cn-hangzhou') // replace regionId as you need
                        ->asGlobalClient();
	}
	/**
	 * 发送调派语音提醒
	 *
	 * @return void
	 */
	public function sendPhoneVoice($phone,$tmp,$params){
		try {
			$result = AlibabaCloud::rpcRequest()
			->product('Dyvmsapi')
			// ->scheme('https') // https | http
			->version('2017-05-25')
			->action('SingleCallByTts')
			->method('POST')
			->options([
				'query' => [
					'CalledShowNumber' => '079182326276',
					'CalledNumber' => $phone,
					'TtsCode' => $tmp,
				],
			])->request();
			$res=$result->toArray();
			echo json_encode($res);
			if($res['Code']=='OK'){
				return array('status'=>true,'msg'=>'发送成功');
			}else{
				return array('status'=>false,'msg'=>json_encode($res));
			}
		} catch (ClientException $e) {
			// echo $e->getErrorMessage() . PHP_EOL;
			return array('status'=>false,'msg'=>$e->getErrorMessage() . PHP_EOL);
		} catch (ServerException $e) {
			// echo $e->getErrorMessage() . PHP_EOL;
			return array('status'=>false,'msg'=>$e->getErrorMessage() . PHP_EOL);
		}
	}
	/**
	 * /**
	 * 发送手机短信
	 *
	 * @param [type] $templatecode 模板code
	 * @param [type] $phone 手机号
	 * @param [type] $params 发送参数
	 * @return void
	 */
	public function sendPhoneMsg($templatecode,$phone,$params){
		try {
			$result = AlibabaCloud::rpcRequest()
			->product('Dysmsapi')
			// ->scheme('https') // https | http
			->version('2017-05-25')
			->action('SendSms')
			->method('POST')
			->options([
					'query' => [
					'PhoneNumbers' => $phone,
					'SignName' => $this->sign_name,
					'TemplateCode' => $templatecode,
					'TemplateParam' => $params,
				],
			])
			->request();
			$res=$result->toArray();
			if($res['Code']=='OK'){
				return array('status'=>true,'msg'=>'发送成功');
			}else{
				return array('status'=>false,'msg'=>json_encode($res));
			}
		} catch (ClientException $e) {
			// echo $e->getErrorMessage() . PHP_EOL;
			return array('status'=>false,'msg'=>$e->getErrorMessage() . PHP_EOL);
		} catch (ServerException $e) {
			// echo $e->getErrorMessage() . PHP_EOL;
			return array('status'=>false,'msg'=>$e->getErrorMessage() . PHP_EOL);
		}
	}
	/**
	 * 发送手机验证码
	 *
	 * @return void
	 */
	public function sendPhoneValidCode($phone,$code){
		AlibabaCloud::accessKeyClient($this->appid, $this->sercert)
                        ->regionId('cn-hangzhou') // replace regionId as you need
                        ->asGlobalClient();

		try {
			$result = AlibabaCloud::rpcRequest()
			->product('Dysmsapi')
			// ->scheme('https') // https | http
			->version('2017-05-25')
			->action('SendSms')
			->method('POST')
			->options([
				'query' => [
				'PhoneNumbers' => $phone,
				'SignName' => $this->sign_name,
				'TemplateCode' => $this->valid_code_tmp,
				'TemplateParam' => json_encode(array('code'=>$code)),
				],
			])
			->request();
			$res=$result->toArray();
			if($res['Code']=='OK'){
				return array('status'=>true,'msg'=>'发送成功');
			}else{
				return array('status'=>false,'msg'=>$res['Message']);
			}
		} catch (ClientException $e) {
			// echo $e->getErrorMessage() . PHP_EOL;
			return array('status'=>false,'msg'=>$e->getErrorMessage() . PHP_EOL);
		} catch (ServerException $e) {
			// echo $e->getErrorMessage() . PHP_EOL;
			return array('status'=>false,'msg'=>$e->getErrorMessage() . PHP_EOL);
		}
	}
}
