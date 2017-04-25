<?php
namespace app\extensions\sendcloud;

use Yii;

class  SendCloud{
//	const API_USER = 'tianyuanhaoke';// 之前的测试用的应用id
//	const API_key = 'h2BG1yWXdaeRzNIK';// 之前的测试用的秘钥  
	const API_USER = 'tianyuanhaoke2015';// 应用id
	const API_key = 'EAddtmHKPGwBbnEl';//应用秘钥
	const URL = 'http://sendcloud.sohu.com/webapi/mail.send.json';//
//	const FROM='313078747@qq.com';  //发件邮箱
	const FROM='service@tianyuanhaoke.com ';  //发件邮箱
	const FROMNAME='IPERAPERA';  //发件人名称 
	const LABEL_MAIL_VALIDATE=10519;     //验证邮箱 的标签     标签是为了后台统计数据用的 分类
	const LABEL_SEND_ORDER=10520;    //订单发货的标签 
	const LABEL_SEND_CODE=10853;    //发送验证码的标签 
	const LABEL_RESET_PASSWORD=11036;    //发送邮件重置密码的标签
	const CANCEL_CLASS=36088;    //日语取消预约
	const BESPEAK_CLASS=36089;    //日语有新课程预约
	const ADMIN_SUGGESTION=37543; //日语管理 意见建议
	
   public static function send_mail($to,$subject,$html,$type) {
		$api_user = self::API_USER;
		$api_key = self::API_key;
		$url = self::URL;
		$from=self::FROM;
		$fromname=self::FROMNAME;
		if($type=='mail-validate'){
			$label=self::LABEL_MAIL_VALIDATE;
		}else if($type=='send-order'){
			$label=self::LABEL_SEND_ORDER;
		}else if($type=='send-code'){
			$label=self::LABEL_SEND_CODE;
		}else if($type=='reset-password'){
			$label=self::LABEL_RESET_PASSWORD;
		}else if($type=='bespeak-class'){
			$label=self::BESPEAK_CLASS;
		}else if($type=='cancel-class'){
			$label=self::CANCEL_CLASS;
		}else if($type=='admin-suggestion'){
			$label=self::ADMIN_SUGGESTION;
		}else{
			$label=null;
		}
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($ch, CURLOPT_URL, $url);
	
		curl_setopt($ch, CURLOPT_POSTFIELDS, array(
		'api_user' => $api_user, # 使用api_user和api_key进行验证
		'api_key' => $api_key,
		'from' => $from, # 发信人，用正确邮件地址替代
		'fromname' => $fromname,
		'to' => $to, # 收件人地址，用正确邮件地址替代，多个地址用';'分隔
		'subject' => $subject,  //邮件标题
		'html' => $html,  //邮件内容 支持HTML格式和文本格式
		'label' => $label,  //邮件标签
		));
	
		$result = curl_exec($ch);
	
		if($result === false) {
			echo curl_error($ch);
		}
		curl_close($ch);
		return $result;
	}
	
	
	//第二种发送邮件的方法
	public static function send_mail2($to,$subject,$html) {
		$api_user = self::API_USER;
		$api_key = self::API_key;
		$url = self::URL;
		$from=self::FROM;
		$fromname=self::FROMNAME;
		
		$param = array(
				'api_user' => $api_user, # 使用api_user和api_key进行验证
				'api_key' => $api_key,
				'from' => $from, # 发信人，用正确邮件地址替代
				'fromname' => $fromname,
				'to' => $to, # 收件人地址，用正确邮件地址替代，多个地址用';'分隔
				'subject' => $subject,  //标题
				'html' => $html  //内容 支持html格式和文本格式
		);
	
		/*      $file = "./test.php"; #你的附件路径
		 $handle = fopen('./test.php','rb');
		$content = fread($handle,filesize($file));
		*/
		$eol = "\r\n";
		$data = '';
	
		$mime_boundary=md5(time());
	
		// 配置参数
		foreach ( $param as $key => $value ) {
			$data .= '--' . $mime_boundary . $eol;
			$data .= 'Content-Disposition: form-data; ';
			$data .= "name=" . $key . $eol . $eol;
			$data .= $value . $eol;
		}
	
		// 配置文件
		$data .= '--' . $mime_boundary . $eol;
		$data .= 'Content-Disposition: form-data; name="somefile"; filename="filename.txt"' . $eol;
		$data .= 'Content-Type: text/plain' . $eol;
		$data .= 'Content-Transfer-Encoding: binary' . $eol . $eol;
		$data .=  $eol;
		$data .= "--" . $mime_boundary . "--" . $eol . $eol;
	
		$options = array(
				'http' => array(
						'method' => 'POST',
						'header' => 'Content-Type: multipart/form-data;boundary='.$mime_boundary . $eol,
						'content' => $data
				));
		$context  = stream_context_create($options);
		$result = file_get_contents($url, FILE_TEXT, $context);
	
		return $result;
		fclose($handle);
	}
	

	
}
?>