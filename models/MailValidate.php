<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%mail_validate}}".
 *
 * @property string $id
 * @property integer $type
 * @property string $pid
 * @property string $email
 * @property string $token
 * @property string $createtime
 */
class MailValidate extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%mail_validate}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'email', 'token'], 'required'],
            [['type', 'pid', 'createtime'], 'integer'],
            [['email'], 'string', 'max' => 40],
            [['token'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => '1表示会员 2表示讲师',
            'pid' => '讲师id或者会员id',
            'email' => '验证邮箱',
            'token' => '32位随机数',
            'createtime' => '创建时间',
        ];
    }
    
    public function beforeSave($insert)
    {
    	if (parent::beforeSave($insert)) {
    		if($this->isNewRecord){
    			$this->createtime=time();
    		}else{
    		}
    		return true;
    	} else {
    		return false;
    	}
    }
    
}
