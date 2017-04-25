<?php
namespace common\extensions\qiniu;

use Yii;
require_once("io.php");
require_once("rs.php");

/**
 * 上传图片至七牛云
 * $fileName 图片名
 * $path 图片地址
 * @author Administrator
 *
 */
class QiniuConfig{
	public $domain;
	public $upToken;
	public function __construct($fileName,$path){
		global $QINIU_ACCESS_KEY;
		global $QINIU_SECRET_KEY;
		global $QINIU_BUCKET;
		global $QINIU_DOMAIN;
		/** 七牛服务器连接配置 */
		$this->domain=$QINIU_DOMAIN;//七牛空间域名
		Qiniu_SetKeys($QINIU_ACCESS_KEY, $QINIU_SECRET_KEY);
		$client = new \Qiniu_MacHttpClient(null);
		/** 服务端生成 上传凭证 */
		$putPolicy = new \Qiniu_RS_PutPolicy($QINIU_BUCKET);
		$this->upToken = $putPolicy->Token(null);
		$putExtra = new \Qiniu_PutExtra();
		$putExtra->Crc32 = 1;
		
		Qiniu_PutFile($this->upToken, $fileName, $path, $putExtra);
		
	}
}