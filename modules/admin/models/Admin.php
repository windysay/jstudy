<?php

namespace app\modules\admin\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\base\NotSupportedException;

  
class Admin extends ActiveRecord implements IdentityInterface
{	
	public $authKey;//此属性必须填写，yii user必填属性
	public $rememberMe=true;
	public $confirmPassword;//确定密码
	public $oldPassword;//老密码
	public $newPassword;//新密码
	public $confirmNewPassword;//确定新密码
	
    public static function tableName()
    {
        return '{{%admin}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'salt', 'mobile', 'grade', 'createtime', 'logintime'], 'integer'],
            [['username', 'password','oldPassword','confirmNewPassword','newPassword', 'email'], 'required','message'=>"{attribute}不能为空"],
            [['username', 'admin_name'], 'string', 'max' => 20],
            [['password'], 'string', 'max' => 64],
            [['email'], 'string', 'max' => 40],
            ['email', 'email','message'=>'请输入正确的邮箱'],
        	[['username'] ,'match', 'pattern' =>'/^[[A-Za-z0-9]{6,20}$/','message'=>'只能是6位以上的字母或数字'],
        	[['username','email'] ,'unique', 'message' => '此{attribute}已被占用'],
        	[['oldPassword'], 'checkOldPassword', 'message' => '原密码错误'],
            [['password'], 'match','pattern'=>'/^[\w\W]{6,18}$/','message'=>'密码长度必须为6-18位'],
            [['password','newPassword','confirmNewPassword','oldPassword'], 'string', 'length' =>[6,18],'tooShort'=>'{attribute}至少为6位','tooLong'=>'{attribute}最多为18位'],
            ['confirmPassword','compare','compareAttribute'=>'password','message'=>'两次输入的密码不一致'],
            ['confirmNewPassword','compare','compareAttribute'=>'newPassword','message'=>'两次输入的密码不一致'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'status' => '管理员状态 1为正常 0为冻结',
            'username' => '登陆名',
            'password' => '密码',
            'salt' => '盐',
            'admin_name' => '姓名',
            'mobile' => '手机号码',
            'email' => '电子邮箱',
            'grade' => '管理员等级 0为最高 ',
            'createtime' => '注册时间',
            'logintime' => '管理员上次登录时间',
            'oldPassword' => '原密码',
            'newPassword' => '新密码',
            'confirmPassword' => '确认密码',
            'confirmNewPassword' => '确认密码',
        ];
    }

    public function scenarios(){
    	$scenarios=parent::scenarios();
    	$scenarios['name-email']=['username','email'];
    	$scenarios['re-password'] = ['oldPassword', 'newPassword','confirmNewPassword'];
        $scenarios['email-reset-password'] = ['newPassword','confirmNewPassword'];
    	return $scenarios;
    }

    public function beforeSave($insert)
    {
    	if (parent::beforeSave($insert)) {
    		if($this->isNewRecord){
    			$this->salt=$this->createSalt();
    			$this->password=$this->hashPassword($this->password);  //注册时填的密码
    		}else{
    			if($this->newPassword){
    				$this->password=$this->hashPassword($this->newPassword);   //修改密码
    			}
    		}
    		return true;
    	} else {
    		return false;
    	}
    }
    
    protected function createSalt(){     //  生成随机数 盐
        $salt='';
        for($i=1;$i<=4;$i++){
            $random=rand(0, 9);
            $salt.=$random;
        }
        return $salt;
    }
    
    public function hashPassword($input_password){  // 通过盐  对密码进行加密
        $salt=$this->salt;
        $satl_part1=substr($salt, 0,2);  //获取盐的前二位
        $satl_part2=substr($salt, 2,2);  //获取盐的后二位
        $satl_part1_plus=$satl_part1+9;  //盐的前二位 +9
        $satl_part2_plus=$satl_part2+3;  //盐的后二位 +3
        $new_num=$satl_part1_plus.$input_password.$satl_part2_plus;
        $password=hash('sha256',$new_num);
        return $password;
    }
    
    public function validatePassword($password){//验证登录密码是否正确
        return $this->hashPassword($password)===$this->password;
    }
    public function validateConfirmPassword($confirm_password){//验证登录密码是否正确
        return $this->hashPassword($confirm_password)===$this->confirm_password;
    }
    
    public function checkOldPassword($attribute,$params){//自定义验证规则，在rules函数里面使用
        $model=self::findOne(Yii::$app->admin->id);
        if(!$this->validatePassword($this->oldPassword)){
            $this->addError($attribute, '原密码错误');//这里是错误提示
        }
    }
    
    public static function findIdentity($id){
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token,$type = null){
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }
    
    public static function findByMobile($mobile){//通过电话号码查找用户
        return static::findOne(['mobile' =>$mobile]);
    }
    
    public static function findByUsername($account){//通过用户输入的用户名或者手机号码查找用户
        if(preg_match("/^(13|14|15|17|18)[0-9]{9}$/",$account)){//如果是手机号码
            return static::findOne(['mobile' =>$account]);
        }else{//用户名
            return static::findOne(['username' =>$account]);
        }
    }
    
    public function getId(){
        return $this->getPrimaryKey();//缓存主键值
    }
    
    public function getAuthKey(){
        return $this->authKey;
    }
    
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }
    
    public function setPassword($password){
        $this->password = Yii::$app->security->generatePasswordHash($password);
    }
    
    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }
    
    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }
    
    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
    
}

 