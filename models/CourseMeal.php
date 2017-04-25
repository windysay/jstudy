<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%course_meal}}".
 *
 * @property string $id
 * @property string $name
 * @property string $description
 * @property string $course_ticket
 * @property string $coverurl
 * @property string $price
 * @property string $promotion_price
 * @property string $content
 * @property string $sales
 * @property string $createtime
 * @property string $updatetime
 */
class CourseMeal extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%course_meal}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'description', 'course_ticket', 'price', 'promotion_price'], 'required','message'=>'{attribute}必填'],
            [['course_ticket', 'sales' ], 'integer','message'=>'{attribute}必须为数字'],
            [['price', 'promotion_price'], 'number','message'=>'{attribute}必须为数字'],
            [['content'], 'string'],
            [['name', 'coverurl'], 'string', 'max' => 100],
            [['description'], 'string', 'max' => 1000]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '套餐名称',
            'description' => '套餐描述',
            'course_ticket' => '上课卷数目',
            'coverurl' => '封面图',
            'price' => '套餐价格',
            'promotion_price' => '优惠价',
            'content' => '详细介绍',
            'sales' => '套餐总销量',
            'createtime' => 'Createtime',
            'updatetime' => 'Updatetime',
        ];
    }
    
    public function beforeSave($insert)
    {
    	if (parent::beforeSave($insert)) {
    		if($this->isNewRecord){
    			$this->createtime=time();
    		}else{
    			$this->updatetime=time();
    		}
    		return true;
    	} else {
    		return false;
    	}
    }
}
