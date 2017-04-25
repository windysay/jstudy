<?php
namespace app\components\alipay;
/**
 * 支付宝支付主要类
 * ====================================================
 */
 
class  AlipayPubWpa extends AlipayPub{//支付宝手机支付

    const SERVICE='alipay.wap.create.direct.pay.by.user';
	const NOTIFY_URL_WPA='http://m.tianyuanhaoke.com/alipay/wpa-notify-url';//服务器异步通知页面路径
	const RETURN_URL_WPA='http://m.tianyuanhaoke.com/alipay/wpa-return-url';//页面跳转同步通知页面路径
 
	//=======【curl超时设置】===================================
	//本例程通过curl使用HTTP POST方法，此处可修改其超时时间，默认为30秒
	const CURL_TIMEOUT = 30;
 
 
	public $parameters;//请求参数，类型为关联数组
	public $response;//支付宝返回的响应
	public $result;//返回参数，类型为关联数组
 
	public function __construct() {

        $this->parameters['partner']=self::PARTNER;
        $this->parameters['_input_charset']=self::_INPUT_CHARSET;
        $this->parameters['service']=self::SERVICE;
        $this->parameters['payment_type']=self::PAYMENT_TYPE;
        $this->parameters['sign_type']=self::SIGN_TYPE;  
        $this->parameters['notify_url']=self::NOTIFY_URL_WPA;
        $this->parameters['return_url']=self::RETURN_URL_WPA;  
        $this->parameters['seller_id']=self::PARTNER;  
	}
	 
	/**
	 * 	作用：以post方式提交data到对应的接口url
	 */
	public function postCurl($data,$url,$second=30){
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
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
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

 

    public function getHttpResponsePOST($url, $cacert_url, $para, $input_charset = '') {
		if (trim($input_charset) != '') {
			$url = $url."_input_charset=".$input_charset;
		}
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);//SSL证书认证
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);//严格认证
		curl_setopt($curl, CURLOPT_CAINFO,$cacert_url);//证书地址
		curl_setopt($curl, CURLOPT_HEADER, 0 ); // 过滤HTTP头
		curl_setopt($curl,CURLOPT_RETURNTRANSFER, 1);// 显示输出结果
		curl_setopt($curl,CURLOPT_POST,true); // post传输数据
		curl_setopt($curl,CURLOPT_POSTFIELDS,$para);// post传输数据
		$responseText = curl_exec($curl);
		//var_dump( curl_error($curl) );//如果执行curl过程中出现异常，可打开此开关，以便查看异常内容
		curl_close($curl);
		
		return $responseText;
}

	/**
     * 解析远程模拟提交后返回的信息
	 * @param $str_text 要解析的字符串
     * @return 解析结果
     */
	public function parseResponse($str_text) {
		//以“&”字符切割字符串
		$para_split = explode('&',$str_text);
		//把切割后的字符串数组变成变量与数值组合的数组
		foreach ($para_split as $item) {
			//获得第一个=字符的位置
			$nPos = strpos($item,'=');
			//获得字符串长度
			$nLen = strlen($item);
			//获得变量名
			$key = substr($item,0,$nPos);
			//获得数值
			$value = substr($item,$nPos+1,$nLen-$nPos-1);
			//放入数组中
			$para_text[$key] = $value;
		}
		
		if( ! empty ($para_text['res_data'])) {
			//解析加密部分字符串
			if($this->alipay_config['sign_type'] == '0001') {
				$para_text['res_data'] = rsaDecrypt($para_text['res_data'], $this->alipay_config['private_key_path']);
			}
			//token从res_data中解析出来（也就是说res_data中已经包含token的内容）
            $res_data=urldecode($para_text['res_data']);//urldecode转码
            preg_match('/<request_token>(.+?)<\/request_token>/i',$res_data,$xml_arr);
            $para_text['request_token']=$xml_arr[1];
		}
		return $para_text;
	}
	
	
	
 
}
?>