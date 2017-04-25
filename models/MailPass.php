<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%mail_pass}}".
 *
 * @property string $id
 * @property integer $type
 * @property string $pid
 * @property string $email
 * @property string $createtime
 */
class MailPass extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%mail_pass}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'pid', 'email', 'createtime'], 'required'],
            [['type', 'pid', 'createtime'], 'integer'],
            [['email'], 'string', 'max' => 40]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => '1表示学生 2表示讲师',
            'pid' => '讲师id 或者会员id',
            'email' => '商户验证邮箱',
            'createtime' => 'Createtime',
        ];
    }
}
