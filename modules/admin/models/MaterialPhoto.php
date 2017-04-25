<?php

namespace app\modules\admin\models;

use Yii;

/**
 * This is the model class for table "{{%material_photo}}".
 *
 * @property string $id
 * @property string $catid
 * @property integer $type
 * @property integer $show_cover
 * @property string $title
 * @property string $author
 * @property string $coverurl
 * @property string $description
 * @property string $content
 * @property string $createtime
 * @property string $updatetime
 */
class MaterialPhoto extends \yii\db\ActiveRecord
{
	
	const SHOWCOVER=1;
	const HIDECOVER=0;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%material_photo}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['catid', 'show_cover','coverurl'], 'required','message'=>'{attribute}必填'],
            [['catid', 'type', 'show_cover', 'createtime', 'updatetime'], 'integer'],
            [['description', 'content'], 'string'],
            [['title'], 'string', 'max' => 40],
            [['author'], 'string', 'max' => 20],
            [['coverurl'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'catid' => 'Catid',
            'type' => '素材类型 ',//1为网站公告 2为首页PPT  3是联系我们
            'show_cover' => '是否显示封面',// 1显示 0不显示
            'title' => '标题',
            'author' => '作者',
            'coverurl' => '封面图片',
            'description' => '摘要',
            'content' => '内容',
            'createtime' => 'Createtime',
            'updatetime' => 'Updatetime',
        ];
    }
    
    public function beforeSave($insert){
    	if (parent::beforeSave($insert)) {
    		if($this->isNewRecord){
    			$this->createtime=time();
    			$this->updatetime=time();
    		}
    		else{
    			$this->updatetime=time();
    		}
    		return true;
    	} else {
    		return false;
    	}
    }
    
    public function scenarios(){
    	$scenarios=parent::scenarios();
    	$scenarios['contact']=['title','author','content'];
    	return $scenarios;
    }
    
    public static function showCoverList(){//是否显示封面图片选择列表
    	return [self::SHOWCOVER=>'显示封面图片',self::HIDECOVER=>'不显示封面图片'];
    }
    
    
}
