<?php

namespace app\modules\teacher\models;

use Yii;
use yii\base\NotSupportedException;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "{{%teacher}}".
 *
 * @property string $id
 * @property string $username
 * @property string $email
 * @property integer $status
 * @property string $password
 * @property integer $salt
 * @property string $mobile
 * @property integer $sex
 * @property string $name
 * @property string $skype
 * @property string $qq
 * @property string $wechat
 * @property string $info
 * @property string $comment
 * @property string $address
 * @property string $register_ip
 * @property string $voice_url
 * @property string $createtime
 * @property integer $updatetime
 */
class Teacher extends \yii\db\ActiveRecord implements IdentityInterface
{
	public $rememberMe = true;
	public $confirmPassword;//确定密码
	public $oldPassword;//老密码
	public $newPassword;//新密码
	public $confirmNewPassword;//确定新密码
	
	public $authKey;//此属性必须填写，yii user必填属性

    const STATUS_ACTIVE = 1;  //默认
    const STATUS_DISABLE = 0;
	
	/**
	 * @inheritdoc
	 */
    public static function tableName()
    {
        return '{{%teacher}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'password', 'email', 'confirmPassword','oldPassword','newPassword','confirmNewPassword','code','info' ], 'required','message'=>"请输入{attribute}"],
            [['status', 'salt', 'mobile', 'sex',], 'integer','message'=>'{attribute}必须为数字'],
            [['username', 'wechat'], 'string', 'max' => 32],
            [['email'], 'string', 'max' => 40],
            [['password'], 'string', 'max' => 64],
            [['name', 'skype'], 'string', 'max' => 30],
            [['qq'], 'string', 'max' => 11],
            [['info', 'voice_url'], 'string', 'max' => 200],
            [['headimg'], 'string', 'max' => 500],
            [['address'], 'string', 'max' => 50],
            [['register_ip'], 'string', 'max' => 15],
            
            [['username'] ,'match', 'pattern' =>'/^[[A-Za-z][A-Za-z0-9]{5,17}$/','message'=>'用户名填写错误'],
            [['mobile'] ,'match', 'pattern' =>'/^(13|14|15|17|18)[0-9]{9}$/','message'=>'电话号码填写错误'],
            [['password'], 'match','pattern'=>'/^[\w\W]{6,18}$/','message'=>'密码长度必须为6-18位'],
            [['password','newPassword','confirmNewPassword','paypassword','newPaypassword','confirmNewPaypassword'], 'string', 'length' =>[6,18],'tooShort'=>'{attribute}至少为6位','tooLong'=>'{attribute}最多为18位'],
            ['confirmPassword','compare','compareAttribute'=>'password','message'=>'两次输入的密码不一致'],
            ['confirmNewPassword','compare','compareAttribute'=>'newPassword','message'=>'两次输入的密码不一致'],
            [['oldPassword'], 'checkOldPassword', 'message' => '原密码错误'],
            
            ['email', 'email','message'=>'请输入正确的邮箱'],
            ['email', 'unique','message'=>'该邮箱已被占用'],
            [['username','email'] ,'unique', 'message' => '此{attribute}已经被使用'],
            
            [['phoneCode','code'],'match', 'pattern' =>'/^[0-9]{6}$/','message'=>'{attribute}格式不正确'],
//            ['phoneCode', 'validatePhoneCode','message'=>'验证码错误'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => '用户名',//(英文开头，最长只能为20位，只能小写),
            'email' => '登陆邮箱',
            'status' => '讲师現在状況', //0表示冻结 1表示正常使用
            'password' => '密码',
            'salt' => '盐（4位）',
            'mobile' => '讲师手机号',
            'sex' => '性别',//0为女性 1为男性
            'name' => '讲师姓名',
            'skype' => 'Skype账号 ',
            'qq' => 'Qq号码',
            'wechat' => '微信号',
            'headimg'=>'讲师头像',
            'info' => '讲师介绍',
            'comment'=>'讲师评语',
            'address' => '所在地址',
            'register_ip' => '注册ip',
            'createtime' => '创建時間',
            'updatetime' => 'Updatetime',
            'oldPassword' => '元パスワード', //原密码
            'newPassword' => '新しいパスワード',//新密码
            'confirmPassword' => '再確認入力',//确认密码
            'confirmNewPassword' => '确认密码',
            'voice_url' => '语音介绍',

            'phoneCode'=>'验证码',  //手机
            'code'=>'验证码',  //邮箱
        ];
    }

    public function scenarios(){   //自定义验证场景
    	$scenarios = parent::scenarios();
    	//$scenarios['login'] = ['username', 'password','verifyCode'];
    	$scenarios['register'] = ['email','password','name'];//,'code'
    	$scenarios['mobile-register'] = ['password','mobile' ,'phoneCode','register_source'];//,'code'
    	$scenarios['email-register'] = ['password','confirmPassword','email','register_source' /* ,'code' */ ];//,'code'
    	$scenarios['update-info'] = ['status','sex','name','skype','qq','headimg','info','comment'];
    	$scenarios['update-teacher'] = ['sex','name','skype','headimg','info'];
    	$scenarios['login-psd'] = ['oldPassword', 'newPassword','confirmNewPassword'];
    	$scenarios['bind-mobile']=['mobile','phoneCode'];//仔细检查这里的引号['mobile','phoneCode']
    	$scenarios['replace-mobile']=['mobile','phoneCode'];//仔细检查这里的引号['mobile','phoneCode']
    	$scenarios['email-login']=['email'];
    	$scenarios['mail-validate']=['email'];
    	$scenarios['status']=['status'];
    	$scenarios['find-set-password'] = ['newPassword,phoneCode'];
    	$scenarios['email-reset-password'] = ['newPassword','confirmNewPassword'];
    	$scenarios['confirm-order']=['monetary'];
    	$scenarios['update-headimg']=['headimg'];
    	$scenarios['set-username']=['username'];
    	$scenarios['update-monetary']=['monetary'];
    	$scenarios['save-integral']=['integral'];
    	$scenarios['monetary-integral']=['monetary','integral'];
        $scenarios['voice_url'] = ['voice_url'];
    	return $scenarios;
    }
    
    public function beforeSave($insert)
    {
    	if (parent::beforeSave($insert)) {
    		if($this->isNewRecord){
    			$this->createtime=time();
    			$this->salt=$this->createSalt();
    			$this->password=$this->hashPassword($this->password);  //注册时填的密码
    		}else{
    			if($this->newPassword){
    				$this->password=$this->hashPassword($this->newPassword);   //修改密码
    			}
    			$this->updatetime=time();
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
        $model=self::findOne(Yii::$app->teacher->id);
        if(!$this->validatePassword($this->oldPassword)){
            $this->addError($attribute, '原密码错误');//这里是错误提示
        }
    }
    
    public static function findIdentity($id){
        return static::findOne(['id' => $id]);
    }
    
    public static function findIdentityByAccessToken($token,$type = null){
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }
    
    public static function findByMobile($mobile){//通过电话号码查找用户
        return static::findOne(['mobile' =>$mobile]);
    }
    
    public static function findByEmail($account){
    	return static::findOne(['email'=>$account]);
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
    
    public function uniqueEmail(){
    	$model=static::findByEmail($this->email);
    	//return $model===null;
    	if($model===null) return true;
    	else return false;
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
