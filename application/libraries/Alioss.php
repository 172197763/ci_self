<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use OSS\OssClient;
use OSS\Core\OssException;
/**
 * 阿里云oss
 */
class Alioss {
	public $appid='';
    public $sercert='';
    private $endpoint='http://oss-cn-hangzhou.aliyuncs.com';
    private $ossClient;
    // 存储空间名称
    private $bucket='jxgsjy';
    private $url_pre='http://jxgsjy.oss-cn-hangzhou.aliyuncs.com';
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
		$this->ossClient = new OssClient($this->appid, $this->sercert, $this->endpoint);
    }
    /**
     * 上传文件
     *
     * @param [type] $filepath oss上路径+文件名
     * @param [type] $content 文件路径
     * @return void
     */
	public function uploadFile($filepath,$content){
        try{
            $this->ossClient->putObject($this->bucket, $filepath, $content);
        } catch(OssException $e) {
            $res=array(
                'status'=>false,
                'msg'=>$e->getMessage(),
            );
            return $res;
        }
        $res=array(
            'status'=>true,
            'data'=>$this->url_pre.'/'.$filepath,
        );
        return $res;
        // print(__FUNCTION__ . ": OK" . "\n");
    }
    /**
     * 上传base64图片
     *
     * @param [type] $filepath oss上路径+文件名
     * @param [type] $content base64编码
     * @return void
     */
	public function uploadBase64Img($filepath,$content){
        try{
            $this->ossClient->putObject($this->bucket, $filepath, base64_decode($content));
        } catch(OssException $e) {
            // printf(__FUNCTION__ . ": FAILED\n");
            // printf($e->getMessage() . "\n");
            // return;
            $res=array(
                'status'=>false,
                'msg'=>$e->getMessage(),
            );
            return $res;
        }
        $res=array(
            'status'=>true,
            'data'=>$this->url_pre.'/'.$filepath,
        );
        return $res;
        // print(__FUNCTION__ . ": OK" . "\n");
    }
}
