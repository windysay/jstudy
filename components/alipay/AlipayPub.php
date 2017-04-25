<?php
namespace app\components\alipay;
/**
 * 支付宝支付主要类
 * ====================================================
 * 接口分三种类型：
 * 【请求型接口】--Wxpay_client_
 * 		统一支付接口类--UnifiedOrder
 * 		订单查询接口--OrderQuery
 * 		退款申请接口--Refund
 * 		退款查询接口--RefundQuery
 * 		对账单接口--DownloadBill
 * 		短链接转换接口--ShortUrl
 * 【响应型接口】--Wxpay_server_
 * 		通用通知接口--Notify
 * 		Native支付——请求商家获取商品信息接口--NativeCall
 * 【其他】
 * 		静态链接二维码--NativeLink
 * 		JSAPI支付--JsApi
 * =====================================================
 * 【CommonUtil】常用工具：
 * 		trimString()，设置参数时需要用到的字符处理函数
 * 		createNoncestr()，产生随机字符串，不长于32位
 * 		formatBizQueryParaMap(),格式化参数，签名过程需要用到
 * 		getSign(),生成签名
 * 		arrayToXml(),array转xml
 * 		xmlToArray(),xml转 array
 * 		postXmlCurl(),以post方式提交xml到对应的接口url
 * 		postXmlSSLCurl(),使用证书，以post方式提交xml到对应的接口url
 */
 
class  AlipayPub{//支付宝支付基类
	
 
	const SERVICE_PAY = 'create_direct_pay_by_user';//请求支付的接口名称
	const SERVICE_REFUND = 'refund_fastpay_by_platform_pwd';//请求退款的接口名称
	const PARTNER = '2088321029990065';//合作身份者id，以2088开头的16位纯数字 r日语网站
	const KEY = 'e2fko3bngcefzr4b4hsil9mm49e9b0mm';//安全检验码，以数字和字母组成的32位字符  //r日语网站
	const SIGN_TYPE='MD5';//签名方式 不需修改
	const _INPUT_CHARSET='UTF-8';//字符编码格式 目前支持 gbk 或 utf-8
	const PAYMENT_TYPE=1;//支付类型
	const NOTIFY_URL='http://www.iperapera.com/alipay/notify-url';//支付成功之后，支付宝服务器异步通知页面路径
	const RETURN_URL='http://www.iperapera.com/alipay/return-url';//支付成功之后，支付宝页面跳转同步通知页面路径
	const NOTIFY_URL_REFUND='http://www.iperapera.com/alipay/notify-url-refund';//申请退款之后，支付宝服务器异步通知页面路径
	const ALIPAY_GATEWAY_NEW='https://mapi.alipay.com/gateway.do?';//支付宝网关地址.
	const CERT_URL='d:/www/tianyuan/common/extensions/alipay/cert/cacert.pem';

	//=======【curl超时设置】===================================
	//本例程通过curl使用HTTP POST方法，此处可修改其超时时间，默认为30秒
	const CURL_TIMEOUT = 30;
	
	const SELLER_EMAIL='iperapera@hotmail.com';   //收钱账户 商家的邮箱
 
	public $parameters;//请求参数，类型为关联数组
	public $response;//支付宝返回的响应
	public $result;//返回参数，类型为关联数组
 
	public function __construct($type=null) {
        switch ($type) {
        	case 'pay':
						$this->parameters['service']=self::SERVICE_PAY;
				        $this->parameters['partner']=self::PARTNER;
				        $this->parameters['payment_type']=self::PAYMENT_TYPE;
				        $this->parameters['_input_charset']=self::_INPUT_CHARSET;
				        $this->parameters['notify_url']=self::NOTIFY_URL;
				        $this->parameters['return_url']=self::RETURN_URL;
        		  break;
        	case 'refund':
						$this->parameters['service']=self::SERVICE_REFUND;
				        $this->parameters['partner']=self::PARTNER;
				        $this->parameters['_input_charset']=self::_INPUT_CHARSET;
				        $this->parameters['notify_url']=self::NOTIFY_URL_REFUND;
                        $this->parameters['seller_email']=self::SELLER_EMAIL;
                        $this->parameters['seller_user_id']=self::PARTNER;
                        $this->parameters['sign_type']=self::SIGN_TYPE;                 
                        $this->parameters['refund_date']=date('Y-m-d H:i:s');  
                        $this->setRefundBatchNo();//生成退款批次号                   
        		  break;
        	default:
				        $this->parameters['partner']=self::PARTNER;
				        $this->parameters['_input_charset']=self::_INPUT_CHARSET;
        		  break;
        }
	}
	
	/**
	 * 	作用：设置请求参数
	 */
	public function setParameter($parameter, $parameterValue){
		$this->parameters[$this->trimString($parameter)] = $this->trimString($parameterValue);
	}

	public function setRefundBatchNo(){//生成退款批次号
	  $this->parameters['batch_no'] = date('Ymd').$this->createNoncestr(20);
	  //return  $this->parameters['batch_no'];
	}
 
	public function trimString($value){
		$ret = null;
		if (null != $value){
			$ret = $value;
			if (strlen($ret) == 0){
				$ret = null;
			}
		}
		return $ret;
	}
	/**
	 * 	作用：产生随机字符串，不长于32位
	 */
	public function createNoncestr($length = 32){
		$chars = "abcdefghijklmnopqrstuvwxyz0123456789";
		$str ="";
		for ( $i = 0; $i < $length; $i++ )  {
			$str.= substr($chars, mt_rand(0, strlen($chars)-1), 1);
		}
		return $str;
	}
	
	/**
	 * 	作用：格式化参数，签名过程需要使用
	 */
	public function formatBizQueryParaMap($paraMap,$urlencode){
		$buff = "";
		ksort($paraMap);//函数按照键名对数组排序，为数组值保留原来的键。
		foreach ($paraMap as $k => $v){
			if($urlencode){
				$v = urlencode($v);//是指针对网页url中的中文字符的一种编码转化方式
			}
			$buff .= $k . "=" . $v . "&";
		}
		$reqPar;
		if (strlen($buff) > 0){
			$reqPar = substr($buff, 0, strlen($buff)-1);
		}
		return $reqPar;
	}
 
	public function md5Sign($parameters){//md5签名
		//签名步骤一：去除 sign 和参数 sign_type
		$parameters=$this->paraFilter($parameters);
		//签名步骤一：按字典序排序参数
		ksort($parameters);
		$string = $this->formatBizQueryParaMap($parameters, false);
		//签名步骤二：在string后加入KEY
		$string = $string.self::KEY;
		//签名步骤三：MD5加密
		$result_ = md5($string);
		//echo "【string3】 ".$String."</br>";
		return $result_;
	}
 
	/**
	 * 	作用：生成签名
	 */
	public function getSign($parameters){
		    $mysign='';
			switch (strtoupper(self::SIGN_TYPE)) {
				case "MD5" :
					$mysign =$this->md5Sign($parameters);
					break;
				default :
					$mysign = "";
			}
		return $mysign;
	}
	
	/**
	 * 除去数组中的空值和签名参数
	 * @param $para 签名参数组
	 * return 去掉空值与签名参数后的新签名参数组
	 */
	public function paraFilter($para) {
		$para_filter = [];
		foreach($para as $key=>$value){
			if($key == "sign" || $key == "sign_type" || $value == "")
				  continue;
			else
					$para_filter[$key] = $value;
		}
		return $para_filter;
	}
 
	/**
	 * 	作用：array转xml
	 */
	public function arrayToXml($arr){
		$xml = "<xml>";
		foreach ($arr as $key=>$val)
		{
			if (is_numeric($val))//检测变量是否为数字或数字字符串
			{
				$xml.="<".$key.">".$val."</".$key.">";
			}
			else
				$xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
		}
		$xml.="</xml>";
		return $xml;
	}
	
	/**
	 * 	作用：将xml转为array
	 */
	public function xmlToArray($xml){
		//将XML转为array
		$array_data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
		return $array_data;
	}
 
	public function getHttpResponseGET($url) {
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_HEADER, 0 ); // 过滤HTTP头
		curl_setopt($curl,CURLOPT_RETURNTRANSFER, 1);// 显示输出结果
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);//SSL证书认证
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);//严格认证
		$responseText = curl_exec($curl);
		//var_dump( curl_error($curl) );//如果执行curl过程中出现异常，可打开此开关，以便查看异常内容
		curl_close($curl);
	
		return $responseText;
	}
 
	/**
	 * 	作用：以post方式提交xml到对应的接口url
	 */
	public function postXmlCurl($xml,$url,$second=30){
		//初始化curl
		$ch = curl_init();
		//设置超时
		curl_setopt($ch,CURLOPT_TIMEOUT,$second);
		//这里设置代理，如果有的话
		//curl_setopt($ch,CURLOPT_PROXY, '8.8.8.8');
		//curl_setopt($ch,CURLOPT_PROXYPORT, 8080);
		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
		curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);
		//curl_setopt($curl, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);//必须要加上的，微信官方的规定
		//设置header2014/12/12 星期五 11:47:29
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		//要求结果为字符串且输出到屏幕上
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		//post提交方式
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
		//运行curl
		$data = curl_exec($ch);
		//curl_close($ch);
		//返回结果
		if($data){
			curl_close($ch);
			return $data;
		}else{
			$error = curl_errno($ch);
			echo "curl出错，错误码:$error"."<br>";
			echo "<a href='http://curl.haxx.se/libcurl/c/libcurl-errors.html'>错误原因查询</a></br>";
			curl_close($ch);
			return false;
		}
	}
	 
	// 打印log
	function  log_result($file,$word){
		$fp = fopen($file,"a");
		flock($fp, LOCK_EX) ;
		fwrite($fp,"执行日期：".strftime("%Y-%m-%d-%H：%M：%S",time())."\n".$word."\n\n");
		flock($fp, LOCK_UN);
		fclose($fp);
	}
	
	
 
}
?>