<?php

namespace app\models;

use Yii;
use app\components\Help;

/**
 * This is the model class for table "{{%sms_limit}}".
 *
 * @property string $id
 * @property string $phone
 * @property integer $status
 * @property integer $times
 * @property string $todaytime
 */
class SmsLimit extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%sms_limit}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['phone'], 'required'],
            [['phone', 'status', 'times', 'todaytime'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'phone' => 'Phone',
            'status' => '手机获取验证码状态，1表示正常 0表示今日已经无法获取验证码了',
            'times' => '今日剩余短信条数',
            'todaytime' => '今日零点时间戳',
        ];
    }

    public function beforeSave($insert){
    	if (parent::beforeSave($insert)) {
    		if($this->isNewRecord){
    			$this->status=1;
    			$this->todaytime=Help::getZeroStrtotime('today');
    		}
    		else{
    			$this->todaytime=Help::getZeroStrtotime('today');
    		}
    		return true;
    	} else {
    		return false;
    	}
    }
 
}
