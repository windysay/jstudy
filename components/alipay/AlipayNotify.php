<?php
namespace app\components\alipay;
/**
 * 支付宝支付通知主要类
 */
 
class  AlipayNotify extends AlipayPub{
 
	const SERVICE = 'notify_verify';//接口名称
	const HTTP_VERIFY_URL = 'http://notify.alipay.com/trade/notify_query.do?';//HTTP形式消息验证地址
 
	public $response;//支付宝返回的响应
	public $result;//返回参数，类型为关联数组
 
	public function __construct() {
   	      $this->parameters['service']=self::SERVICE;
	}
	
	public function ceshi() {
        return $this->parameters;
	}

  
	/**
	 * 针对notify_url验证消息是否是支付宝发出的合法消息
	 * @return 验证结果
	 */
	public function verifyNotify($data){
	    	if(empty($data)) {//判断$data来的数组是否为空
	  	      	return false;
	    	}
	    	ksort($data);//排序
			//生成签名结果
			$isSign = $this->getSignVeryfy($data, $data["sign"]);
			//return $isSign;
			//获取支付宝远程服务器ATN结果（验证是否是支付宝发来的消息）
			$responseTxt = 'true';
			if (!empty($data["notify_id"])){
				$responseTxt = $this->getResponse($data["notify_id"]);
				if (preg_match("/true$/i",$responseTxt)) {
				  $responseTxt=true;
	            } else {
				  $responseTxt=false;
			    }
			}
			if($responseTxt&& $isSign){
				return true;
			}else {
				return false;
			}
	}
	
	
	/**
	 * 获取返回时的签名验证结果
	 * @param $para_temp 通知返回来的参数数组
	 * @param $sign 返回的签名结果
	 * @return 签名验证结果
	 */
	public function getSignVeryfy($para_temp, $sign) {
		//除去待签名参数数组中的空值和签名参数
		$para_filter =$this->paraFilter($para_temp);
		//把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
		$prestr =$this->formatBizQueryParaMap($para_filter,false);
		$isSgin = false;
		switch(self::SIGN_TYPE) {
			case "MD5" :
				$isSgin = $this->md5Verify($prestr,$sign);
				break;
			default :
				$isSgin = false;
		}
		return $isSgin;
	}
	
	/**
	 * 验证签名
	 * @param $prestr 需要签名的字符串
	 * @param $sign 签名结果
	 * @param $key 私钥
	 * return 签名结果
	 */
	public function md5Verify($prestr, $sign) {
		$prestr = $prestr . self::KEY;
		$mysgin = md5($prestr);
		if($mysgin == $sign) {
			return true;
		}else {
			return false;
		}
	}
	

	/**
	 * 获取远程服务器ATN结果,验证返回URL
	 * @param $notify_id 通知校验ID
	 * @return 服务器ATN结果
	 * 验证结果集：
	 * invalid命令参数不对 出现这个错误，请检测返回处理中partner和key是否为空
	 * true 返回正确信息
	 * false 请检查防火墙或者是服务器阻止端口问题以及验证时间是否超过一分钟
	 */
	public function getResponse($notify_id) {
		$partner = self::PARTNER;
		$veryfy_url = self::HTTP_VERIFY_URL;
		$veryfy_url = $veryfy_url."partner=" . $partner . "&notify_id=" . $notify_id;
		$responseTxt = $this->getHttpResponseGET($veryfy_url);
		return $responseTxt;
	}
	
	public function getHttpResponseGET($url,$cacert_url=null) {//http  get请求
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_HEADER, 0 ); // 过滤HTTP头
		curl_setopt($curl,CURLOPT_RETURNTRANSFER, 1);// 显示输出结果
		//curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);//SSL证书认证
		//curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);//严格认证
		//curl_setopt($curl, CURLOPT_CAINFO,$cacert_url);//证书地址
		$responseText = curl_exec($curl);
		//var_dump( curl_error($curl) );//如果执行curl过程中出现异常，可打开此开关，以便查看异常内容
		curl_close($curl);
		return $responseText;
	}
		
 
}
?>