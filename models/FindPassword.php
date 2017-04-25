<?php
namespace app\models;
 
use Yii;
use yii\base\Model;
use app\modules\student\models\Student;

/**
 * Login form
 */
class FindPassword extends Model
{
    public $phone;
    public $mail;
    public $verifyCode;//验证码
 
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['phone','verifyCode'], 'required','message'=>'请输入{attribute}'],
        	[['phone'],'validatePhone'],
            [['mail'],'email'],
            ['verifyCode','captcha','message'=>'验证码错误'],
        ];
    }
    
    public function attributeLabels()
    {
    	return array(
    			'phone'=>'手机号码',
    			'mail'=>'验证邮箱',
    			'verifyCode'=>'验证码',
    	);
    }
 
    public function validatePhone($attribute, $params){//自定义验证规则 rules
    	if (!$this->hasErrors()) {
    		 $phone= $this->phone;
    		 $student=Student::findOne(['mobile'=>$phone]);
      		if ($student===null) {
    			$this->addError($attribute, '此手机号码未被注册，请更换');
    		 }
    	}
    }
 
    
    
    
}
