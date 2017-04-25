<?php
namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\HttpException;
use yii\helpers\Html;
use yii\base\Object;
use app\models\SmsUcpaas;
use app\modules\student\models\Student;
 
class SmscodeController extends Controller{//手机验证码发送控制器
     const student_REGISTER=1;//会员注册场景
     const student_FIND_PASSWORD=2;//会员找回密码场景
     const student_REPLACE_PHONE_OLD=3;//会员跟换手机号码之前的旧手机号码验证场景
     const student_REPLACE_PHONE_NEW=4;//会员跟换手机号码时，验证新手机号码场景
 
     //错误代码 error_phone 手机号码错误  
     //错误代码 phone_used 手机号码已被使用（注册）
     //错误代码 no_register 手机号码没有注册
     //错误代码 code_overdue 验证码已经过期
     //错误代码 error_code 验证码错误
     //错误代码 sed_fail 验证码发送失败
     
    public function behaviors(){
        return [
            'access' => [
                'class' => AccessControl::className(),
            	//'only' => ['login','forget-password'],
                'rules' => [
                    [
                        'actions' => ['ajax-send-code','ajax-validate-code'],
                        'allow' => true,
                    ],
                ],
            ],
        ];
    }
 
    public function actionAjaxSendCode(){//发送手机验证码 第一步 
        if($_POST['code_type']){
            $code_type=Html::encode($_POST['code_type']);  //验证码类型 1短信  2语音
        }else{
            $code_type=1;
        }
        $code_type=Html::encode($code_type);  //验证码类型 1短信  2语音
    	$phone=trim(Html::encode($_POST['phone']));
    	$use_type=Html::encode($_POST['use_type']);  //验证码使用场景 1表示注册场景  2表示找回密码场景 3表示跟换手机场景  4表示绑定银行账号场景   5表示商家提现场景
    	$phone_ok=preg_match("/^(13|14|15|16|17|18|19)[0-9]{9}$/",$phone); //如果是正确的手机号码 phone_ok=1 否则就为0
    	if($phone_ok==0){//手机号码不正确
    		return 'error_phone';//错误代码error_phone【手机号码不正确】
        }
        $this->sendCodeUse($code_type,$phone,$use_type);
    }
    
    public function actionAjaxValidateCode(){//验证手机验证码 1
  //    $code_type=Html::encode($_POST['code_type']);  //验证码类型 1短信  2语音
    	$code=Html::encode($_POST['code']);//验证码
    	$phone=Html::encode($_POST['phone']);//手机号码
    	$use_type=Html::encode($_POST['use_type']);//验证码使用场景
        $validata_res=SmsUcpaas::validateCode($phone,$use_type,$code);
        echo json_encode($validata_res);
    }
 
    public function  sendCodeUse($code_type,$phone,$use_type){//发送手机验证码 第二步
    	switch($use_type){
    		case  self::student_REGISTER:$return_value=$this->studentRegister($code_type,$phone);break;//商户注册场景
    		case  self::student_FIND_PASSWORD:$return_value=$this->studentFindpassword($code_type,$phone);break;//找回密码场景
    		case  self::student_REPLACE_PHONE_OLD:$return_value=$this->studentReplacephoneold($code_type,$phone);break;//跟换手机号码之前验证旧手机
    		case  self::student_REPLACE_PHONE_NEW:$return_value=$this->studentReplacephonenew($code_type,$phone);break;//跟换手机号码之前验证新手机
    		case  self::GUEST_SEARCH_ORDER:$return_value=$this->guestSearchOrder($code_type,$phone);break;//游客通过手机号码查询订单
    		default:;break;
    	}
    	echo json_encode($return_value);
    }
 
    public function studentRegister($code_type,$phone){//发送验证码【商家注册】
    	$user=Student::findOne(['mobile'=>$phone]);
    	if($user===null){//如果此手机号码没有被注册
    		$return_value=$this->sendCode($code_type,$phone,self::student_REGISTER);
    	}else{
    		$return_value='phone_used';//此手机号码已被使用（注册）
    	}
    	return $return_value;
    }
 
    public function studentFindpassword($code_type,$phone){//发送验证码【找回密码】
    	$user=Student::findOne(['mobile'=>$phone]);
    	if($user===null){//如果此手机号码没有被注册
    		$return_value='no_register';//此手机号码没有注册
    	}else{
    		$return_value=$this->sendCode($code_type,$phone,self::student_FIND_PASSWORD);
    	}
    	return $return_value;
    }
 
    public function studentReplacephoneold($code_type,$phone){//发送验证码 【跟换手机old】
    	$user=Student::findOne(['mobile'=>$phone]);
    	if($user===null){//如果此手机号码没有被注册
    			$return_value='no_register';//此手机号码没有注册
    	}else{
    		$return_value=$this->sendCode($code_type,$phone,self::student_REPLACE_PHONE_OLD);
    	}
    	return $return_value;
    }

    public function studentReplacephonenew($code_type,$phone){//发送验证码 【跟换手机new】
    	$user=Student::findOne(['mobile'=>$phone]);
    	if($user===null){//如果此手机号码没有被注册
    		$return_value=$this->sendCode($code_type,$phone,self::student_REPLACE_PHONE_NEW);
    	}else{
    		$return_value='phone_used';//此手机号码已被使用（注册）
    	}
    	return $return_value;
    }
    
    public function guestSearchOrder($code_type,$phone){//发送验证码 【通过手机号查询订单】GUEST_SEARCH_ORDER
    	$return_value=$this->sendCode($code_type,$phone,self::GUEST_SEARCH_ORDER);
    	return $return_value;
    }
 
   private  function sendCode($code_type,$phone,$use_type){//发送验证码
	   	$smsUcpaas=new SmsUcpaas();
	   	$result=$smsUcpaas->sendCode($code_type,$phone,$use_type);
	   	return $result;//error_failure验证码发送失败
   }
  
}
