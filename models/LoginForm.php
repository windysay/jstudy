<?php
namespace app\models;
 
use Yii;
use yii\base\Model;
use app\modules\student\models\Student;
use app\modules\teacher\models\Teacher;
use app\modules\admin\models\Admin;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;
    public $verifyCode;//验证码
    public $loginType;//登录类型

    private $_user = false;

    public function __construct($type){//会员登录。商户登录
    	parent::__construct();
    	$this->loginType=$type;
    }
    
    
    public function rules()
    {
        return [
            [['username', 'password','verifyCode'], 'required','message'=>'请输入{attribute}'],
            ['rememberMe', 'boolean',/* 'message'=>'只能为真或假' */],
            ['password', 'validatePassword'],//自定义验证登录密码
            ['verifyCode','captcha','message'=>'验证码错误','captchaAction'=>'account/captcha'],
        	//	array('verifyCode', 'captcha','on'=>'captchaRequired','message'=>'验证码错误', 'allowEmpty'=>!CCaptcha::checkRequirements()),
        ];
    }
    public function attributeLabels()
    {
    	return array(
    			'username'=>'用户名',
    			'password'=>'密码',
    			'rememberMe'=>'记住密码',
    			'verifyCode'=>'验证码',
    	);
    }
 
    public function validatePassword($attribute, $params){//自定义验证规则 rules
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, '用户名或者密码错误');
            }
        }
    }
 
    public function login(){//会员登录
        if($this->validate()) {//validate() 执行数据验证(rules)。默认验证的属性名称列表，任何属性中列出适用的验证规则应该验证。
            return Yii::$app->user->login($this->getUser(),$this->rememberMe ? 3600 * 24 * 30 : 0);
        } else {
            return false;
        }
    }

    public function teacherLogin(){//老师登录
        if($this->validate()) {//validate() 执行数据验证(rules)。默认验证的属性名称列表，任何属性中列出适用的验证规则应该验证。
            return Yii::$app->teacher->login($this->getUser(),$this->rememberMe ? 3600 * 24 * 30 : 0);
        } else {
            return false;
        }
    }
    public function adminLogin(){//管理员登录
        if($this->validate()) {//validate() 执行数据验证(rules)。默认验证的属性名称列表，任何属性中列出适用的验证规则应该验证。
            return Yii::$app->admin->login($this->getUser(),$this->rememberMe ? 3600 * 24 * 30 : 0);
        } else {
            return false;
        }
    }
 
    public function getUser(){
        if ($this->_user === false) {
            if($this->loginType=='student'){
            	$this->_user = Student::findByUsername($this->username);
            }elseif($this->loginType=='teacher'){
            	$this->_user = Teacher::findByEmail($this->username);
            }elseif($this->loginType=='admin'){
            	$this->_user = Admin::findByUsername($this->username);
            }
        }
        return $this->_user;
    }
    
}
