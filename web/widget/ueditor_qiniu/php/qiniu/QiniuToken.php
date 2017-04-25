<?php
namespace common\extensions\qiniu;

use Yii;
require_once("io.php");
require_once("rs.php");

/**
 * 获取上传凭证
 * @author Administrator
 *
 */
class QiniuToken{
	public $domain; 
	public $upToken; //上传凭证
	public function __construct(){
		global $QINIU_ACCESS_KEY;
		global $QINIU_SECRET_KEY;
		global $QINIU_BUCKET;
		global $QINIU_DOMAIN;
		/** 七牛服务器连接配置 */
		$this->domain=$QINIU_DOMAIN;
		Qiniu_SetKeys($QINIU_ACCESS_KEY, $QINIU_SECRET_KEY);
		$client = new \Qiniu_MacHttpClient(null);
		/** 服务端生成 上传凭证 */
		$putPolicy = new \Qiniu_RS_PutPolicy($QINIU_BUCKET);
		$this->upToken = $putPolicy->Token(null);
		
	}
}