<?php

namespace app\models;

use Yii;
use app\models\SmsLimit;
use app\components\Help;
use app\extensions\ucpaas\Ucpaas;
/**
 * This is the model class for table "{{%sms_ucpaas}}".
 * 此类关联的表 为保存云之讯验证码接口发送的验证码".
 * @property string $id
 * @property integer $code_type
 * @property string $ucpaas_id
 * @property integer $use_type
 * @property string $phone
 * @property string $code
 * @property integer $status
 * @property string $ip
 * @property string $createtime
 * @property string $updatetime
 */
class SmsUcpaas extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    
    const PHONE_CODE_TIMES = 20;//【每个手机号每天接收短信验证码的最大次数】
    
    public static function tableName()
    {
        return '{{%sms_ucpaas}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code_type', 'ucpaas_id', 'use_type', 'phone', 'code',], 'required'],
            [['code_type', 'use_type', 'phone', 'status', 'createtime', 'updatetime'], 'integer'],
            [['ucpaas_id'], 'string', 'max' => 32],
            [['code'], 'string', 'max' => 6],
            [['ip'], 'string', 'max' => 15]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code_type' => '验证码类型 1为短信验证码 2为语音验证码',
            'ucpaas_id' => '云之讯返回的调用id',   //短信验证码返回的是smsId   语音验证码返回的是 callId
            'use_type' => '验证码使用场景 1表示注册场景  2表示找回密码场景 3表示跟换手机场景  4表示绑定银行账号场景   5表示商家提现场景',
            'phone' => '手机号码',
            'code' => '验证码 6位数字',
            'status' => ' 1标示还未验证 0标示已经验证',
            'ip' => '用户ip',
            'createtime' => '创建时间',
            'updatetime' => '验证通过时间',
        ];
    }
    
    public function beforeSave($insert){
        if (parent::beforeSave($insert)) {
            if($this->isNewRecord){
                $ip=Yii::$app->request->userIP;//终端ip
                $this->ip="$ip";
                $this->status=1;
                $this->createtime=time();
            }
            else{
                $this->updatetime=time();
            }
            return true;
        } else {
            return false;
        }
    }
    /*作用：发送验证码
     *
     */
    public  static function sendCode($code_type,$phone,$use_type){
    	
        $smslimit=SmsLimit::find()->where(['phone'=>$phone])->one();
        if($smslimit){
            $todayztime=Help::getZeroStrtotime('today');
            if($smslimit['todaytime']<$todayztime){//如果日期小于今天，那就更新times
                $smslimit->times=self::PHONE_CODE_TIMES;
                $smslimit->update();
            }else{//如果是今天
                if($smslimit->times==0){//如果没有短信次数了
                    return 'times_out';
                }
            }
        }else{
            $smslimit=new SmsLimit();
            $smslimit->phone=$phone;
            $smslimit->times=self::PHONE_CODE_TIMES;
            $smslimit->save();
        }
        
        $code=Help::randCode();
        $ucpaas=new Ucpaas();
        if($code_type=="2"){  //如果验证码类型是语音验证码
            $ucpaasRes=$ucpaas->voiceCode($code,$phone);  //发送语音验证码  返回格式为 {"resp":{"respCode":"000000","voiceCode":{"callId":"f7c91b106c036fb4997bd918339f42cd","createDate":"20150611150016"}}}
        }else{   //否则就是短信验证码  
            $param=$code.",5"; // $param类型为字符串  用于替换短信模板里的参数   （ 您的验证码为  $code，5分钟内有效）
            $ucpaasRes=$ucpaas->templateSMS($phone,$param);  //发送短信验证码  返回格式为  {"resp":{"respCode":"000000","templateSMS":{"createDate":"20150611150237","smsId":"1e8e2b11f30a80429cb6ad994ebbd9e6"}}}
        }
        $res=json_decode($ucpaasRes,true);
        if(!empty($ucpaasRes)&&$res['resp']['respCode']=='000000'){   //发送成功
            if($code_type=="2"){
                $ucpaas_id=$res["resp"]["voiceCode"]["callId"];  //语音验证吗
            }else{
                $ucpaas_id=$res["resp"]["templateSMS"]["smsId"];   //短信验证码 
            }
            $smsUcpaas=new SmsUcpaas();
            $smsUcpaas->code_type=$code_type;//验证码类型
            $smsUcpaas->ucpaas_id="$ucpaas_id";
            $smsUcpaas->use_type=$use_type;//验证码类型
            $smsUcpaas->phone=$phone;
            $smsUcpaas->code=$code;
            
            $smslimit->times-=1;
            
            $transaction=Yii::$app->db->beginTransaction();  //开始事务
            if($smsUcpaas->save()&&$smslimit->update()){
                $transaction->commit();
                return 'success';
            }else{
                $transaction->rollBack();
                return 'fail';
            }
           
        }else{
            return 'fail';
        }

    }    

    public static function validateCode($phone,$use_type,$code,$status=1){//验证手机验证码 返回sms实例或者fasle
    	$smsUcpaas=SmsUcpaas::find()->where(['use_type'=>$use_type,'phone'=>$phone,'code'=>$code,'status'=>1])->one();
        if($smsUcpaas===null){//如果没有  
            return 'no_code';
        }else if($smsUcpaas->createtime<time()-60*5){  //已过期
            return 'code_overdue';
        }else{
           return 'success';
        }
    }
    
    
    
}
