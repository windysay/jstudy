<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%feedback}}".
 *
 * @property string $id
 * @property string $member_id
 * @property string $content
 * @property integer $status
 * @property string $createtime
 */
class Feedback extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%feedback}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content'], 'required','message'=>"意见建议不能为空"],
            [['member_id', 'status', 'createtime'], 'integer'],
            [['content'], 'string', 'max' => 1000]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'member_id' => '会员的id',
            'content' => '建议内容',
            'status' => '意见反馈状态，1表示不删除，0表示删除，默认为1',
            'createtime' => 'Createtime',
        ];
    }
    
    public function beforeSave($insert){
    	if(parent::beforeSave($insert)){
    		if($this->isNewRecord){
    			$this->createtime=time();
    			$this->member_id=Yii::$app->user->id;
    			$this->status=1;
    		}else{
    			
    		}
    		return true;
    	}else return false;
    }
}
