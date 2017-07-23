<?php

namespace app\modules\student\models;

use app\models\SmsUcpaas;
use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "{{%student}}".
 *
 * @property string $id
 * @property integer $status
 * @property integer $grade
 * @property integer $buy_ticket
 * @property integer $course_ticket
 * @property string $username
 * @property string $password
 * @property string $salt
 * @property string $realname
 * @property string $mobile
 * @property string $email
 * @property string $skype
 * @property string $qq
 * @property string $wechat
 * @property string $chengdu
 * @property string $xueximudi
 * @property integer $sex
 * @property string $address
 * @property string $headimg
 * @property string $monetary
 * @property string $integral
 * @property string $createtime
 */
class Student extends \yii\db\ActiveRecord implements IdentityInterface
{
    const STATUS_ACTIVE = 1;  //正常
    const STATUS_NOACTIVE = -1;  //未激活
    const STATUS_DISABLE = 0; //禁用
    const STATUS_DELETE = 2; //已删除

    public $rememberMe = true;
    public $confirmPassword;//确定密码
    public $oldPassword;//老密码
    public $newPassword;//新密码
    public $confirmNewPassword;//确定新密码
    public $authKey;//此属性必须填写，yii user必填属性
    public $phoneCode;//手机短信验证码
    public $emailcode;//邮箱验证码

    public $phoneCodeUseType;  //手机验证码应用场景   1，会员注册 ； 2，会员找回密码； 3，会员跟换手机号码之前的旧手机号码验证 ；4，会员跟换手机号码时，验证新手机号码
    public $emailcodeUseType;  //邮箱验证码应用场景

    public static function tableName()
    {
        return '{{%student}}';
    }

    public function rules()
    {
        return [
            [['status', 'grade', 'buy_ticket', 'course_ticket', 'mobile', 'sex', 'integral'], 'integer', 'message' => '{attribute}必须为数字'],
            [['username', 'email', 'password', 'confirmPassword', 'phoneCode', 'oldPassword', 'newPassword', 'confirmNewPassword'], 'required', 'message' => "请输入{attribute}"],
            [['monetary'], 'number', 'message' => '{attribute}只能为数字'],
            [['username', 'realname', 'skype'], 'string', 'max' => 30],
            [['password'], 'string', 'max' => 64],
            [['salt'], 'string', 'max' => 5],
            [['password'], 'match', 'pattern' => '/^[\w\W]{6,18}$/', 'message' => '密码长度必须为6-18位'],
            [['password', 'newPassword', 'confirmNewPassword'], 'string', 'length' => [6, 18], 'tooShort' => '{attribute}至少为6位', 'tooLong' => '{attribute}最多为18位'],
            ['confirmPassword', 'compare', 'compareAttribute' => 'password', 'message' => '两次输入的密码不一致'],
            ['confirmNewPassword', 'compare', 'compareAttribute' => 'newPassword', 'message' => '两次输入的密码不一致'],
            [['oldPassword'], 'checkOldPassword', 'message' => '原密码错误'],
            [['email'], 'string', 'max' => 40],
            [['username'], 'match', 'pattern' => '/^[[A-Za-z0-9]{6,18}$/', 'message' => '用户名只能是6位以上的字母或数字'],
            [['username', 'mobile', 'email'], 'unique', 'message' => '此{attribute}已被占用'],
            [['mobile'], 'match', 'pattern' => '/^(13|14|15|17|18)[0-9]{9}$/', 'message' => '电话号码填写错误'],
            ['email', 'email', 'message' => '请输入正确的邮箱'],
            [['qq'], 'string', 'max' => 10],
            [['wechat'], 'string', 'max' => 20],
            [['address'], 'string', 'max' => 100],
            [['headimg'], 'string', 'max' => 200],
            [['chengdu', 'xueximudi'], 'string', 'max' => 500],
            [['phoneCode', 'emailcode'], 'match', 'pattern' => '/^[0-9]{6}$/', 'message' => '{attribute}格式不正确'],
            ['phoneCode', 'validatePhoneCode', 'message' => '验证码错误'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增id',
            'status' => '会员状态',// 1表示正常 0表示拉黑 2表示删除会员 -1 未激活
            'grade' => '会员等级',
            'buy_ticket' => '购买上课券总数',
            'course_ticket' => '可用上课券数',
            'username' => '用户名（※）',
            'password' => '密码（※）',
            'salt' => '盐',
            'realname' => '昵称',
            'mobile' => '手机号码',
            'email' => '邮箱（※）',
            'skype' => 'Skype账号',
            'qq' => 'QQ账号',
            'wechat' => '微信号',
            'chengdu' => '日语程度',
            'xueximudi' => '学习日语目的',
            'sex' => '性别 ',//0为女性 1为男性
            'address' => '详细地址',
            'headimg' => '头像地址',
            'monetary' => '消费金额',
            'integral' => '积分',
            'createtime' => '创建时间',
            'oldPassword' => '原密码（※）',
            'newPassword' => '新密码（※）',
            'confirmPassword' => '确认密码（※）',
            'confirmNewPassword' => '确认密码（※）',

            'phoneCode' => '验证码',  //手机
            'code' => '验证码',  //邮箱
        ];
    }

    public function scenarios()
    {   //自定义验证场景
        $scenarios = parent::scenarios();
        //$scenarios['login'] = ['username', 'password','verifyCode'];
        $scenarios['register2'] = ['password', 'confirmPassword', 'mobile', 'email', 'phoneCode'];//,'code'
        $scenarios['register'] = ['username', 'password', 'confirmPassword', 'qq', 'skype', 'email', 'chengdu', 'xueximudi'];//,'code'
        $scenarios['username'] = ['username'];//,'code'
        $scenarios['update-info'] = ['qq', 'email', 'realname', 'sex', 'skype', 'course_ticket', 'mobile'];
        $scenarios['update-student'] = ['realname', 'sex', 'skype', 'qq', 'wechat', 'address'];
        $scenarios['change-pswd'] = ['oldPassword', 'newPassword', 'confirmNewPassword'];
        $scenarios['bind-mobile'] = ['mobile', 'phoneCode'];//仔细检查这里的引号['mobile','phoneCode']
        $scenarios['replace-mobile'] = ['mobile', 'phoneCode'];//仔细检查这里的引号['mobile','phoneCode']
        $scenarios['status'] = ['status'];
        $scenarios['find-set-password'] = ['newPassword,phoneCode'];
        $scenarios['email-reset-password'] = ['newPassword', 'confirmNewPassword'];
        $scenarios['save-headimg'] = ['headimg'];
        $scenarios['set-username'] = ['username'];
        $scenarios['monetary-integral'] = ['monetary', 'integral', 'course_ticket', 'buy_ticket'];
        $scenarios['course_ticket'] = ['course_ticket'];
        return $scenarios;
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_time',
                'updatedAtAttribute' => false,
                'value' => time()
            ],
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->status = self::STATUS_NOACTIVE;
                $this->grade = 1;
                $this->sex = 0;
                $this->buy_ticket = 0;
                $this->course_ticket = 1;  //注册成功赠送一张选课券
                $this->salt = $this->createSalt();
                $this->password = $this->hashPassword($this->password);  //注册时填的密码
                $this->createtime = time();
            } else {
                if ($this->newPassword) {
                    $this->password = $this->hashPassword($this->newPassword);   //修改密码
                }
            }
            return true;
        } else {
            return false;
        }
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes); //调动父类的afterSave()方法，这是必须的。

        if (!empty($this->phoneCode)) {
            $phoneUseType = $this->phoneCodeUseType;
            $phone = $this->mobile;
            $code = $this->phoneCode;
            $smsUcpaas = SmsUcpaas::find()->where(['phone' => $phone, 'code' => $code, 'use_type' => $phoneUseType, 'status' => 1])->andWhere("createtime>" . time() - 300)->one();
            if ($smsUcpaas) {
                $smsUcpaas->status = 0;
                $smsUcpaas->update();
            }
        }
    }

    public static function sexText($sex)
    {
        if ($sex == 0) {
            $text = '女';
        } elseif ($sex == 1) {
            $text = '男';
        } else {
            $text = '';
        }
        return $text;
    }

    protected function createSalt()
    {     //  生成随机数 盐
        $salt = '';
        for ($i = 1; $i <= 4; $i++) {
            $random = rand(0, 9);
            $salt .= $random;
        }
        return $salt;
    }

    public function hashPassword($input_password)
    {  // 通过盐  对密码进行加密
        $salt = $this->salt;
        $satl_part1 = substr($salt, 0, 2);  //获取盐的前二位
        $satl_part2 = substr($salt, 2, 2);  //获取盐的后二位
        $satl_part1_plus = $satl_part1 + 9;  //盐的前二位 +9
        $satl_part2_plus = $satl_part2 + 3;  //盐的后二位 +3
        $new_num = $satl_part1_plus . $input_password . $satl_part2_plus;
        $password = hash('sha256', $new_num);
        return $password;
    }

    public function validatePassword($password)
    {//验证登录密码是否正确
        return $this->hashPassword($password) === $this->password;
    }

    public function validateConfirmPassword($confirm_password)
    {//验证登录密码是否正确
        return $this->hashPassword($confirm_password) === $this->confirm_password;
    }

    public function checkOldPassword($attribute, $params)
    {//自定义验证规则，在rules函数里面使用
        $model = self::findOne(Yii::$app->user->id);
        if (!$this->validatePassword($this->oldPassword)) {
            $this->addError($attribute, '原密码错误');//这里是错误提示
        }
    }

    public static function memberName($student)
    {
        if ($student['realname']) {
            $name = $student['realname'];
        } else if ($student['username']) {
            $name = $student['username'];
        } else if ($student['mobile']) {
            $name = $student['mobile'];
        } else {
            $name = $student['email'];
        }
        return $name;
    }


    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    public static function findByMobile($mobile)
    {//通过电话号码查找用户
        return static::findOne(['mobile' => $mobile]);
    }

    public static function findByUsername($account)
    {//通过用户输入的用户名或者手机号码查找用户
        if (preg_match("/^(13|14|15|17|18)[0-9]{9}$/", $account)) {//如果是手机号码
            return static::findOne(['mobile' => $account]);
        } else if (preg_match("/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i", $account)) {//邮箱
            return static::findOne(['email' => $account]);
        } else {//用户名
            return static::findOne(['username' => $account]);
        }
    }

    public function validatePhone($attribute, $params)
    {//自定义验证规则 rules
        if (!$this->hasErrors()) {
            $phone = $this->mobile;
            $user = Student::findOne(['mobile' => $phone]);
            if ($user === null) {
                $this->addError($attribute, '此手机号码未被注册，请更换');
            }
        }
    }

    public function validatePhoneCode($attribute, $params)
    {
        $phone = $this->mobile;
        $phoneCodeUseType = $this->phoneCodeUseType;
        $phoneCode = $this->phoneCode;
        $check = SmsUcpaas::validateCode($phone, $phoneCodeUseType, $phoneCode);
        if ($check == "code_overdue") {
            $this->addError($attribute, '验证码已过期,请重新获取');
        } elseif ($check == "no_code") {
            $this->addError($attribute, '验证码错误');
        } else {
            return true;
        }
    }


    public function getId()
    {
        return $this->getPrimaryKey();//缓存主键值
    }

    public function getAuthKey()
    {
        return $this->authKey;
    }

    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    public function setPassword($password)
    {
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
 

