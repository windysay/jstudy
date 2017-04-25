<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%material_download}}".
 *
 * @property string $id
 * @property string $title
 * @property string $description
 * @property string $size
 * @property string $coverurl
 * @property string $link
 * @property string $type
 * @property string $createtime
 */
class MaterialDownload extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%material_download}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'coverurl','description'], 'required','message'=>"{attribute}不能为空"],
            [['size', 'createtime'], 'integer'],
            [['title'], 'string', 'max' => 100],
            [['description', 'coverurl', 'link'], 'string', 'max' => 200],
            [['type'], 'string', 'max' => 10]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => '标题',
            'description' => '描述',
            'size' => '文件大小',
            'coverurl' => '资源封面',
            'link' => '下载链接',
            'type' => '文件类型',
            'createtime' => 'Createtime',
        ];
    }
    
    public function beforeSave($insert){
    	if(parent::beforeSave($insert)){
    		if($this->isNewRecord){
    			$this->createtime=time();
    		}else{
    			
    		}
    		return true;
    	}else return false;
    }
    
}
