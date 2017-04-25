<?php

namespace app\modules\admin\models;

use Yii;

/**
 * This is the model class for table "{{%material_category}}".
 *
 * @property string $id
 * @property string $fid
 * @property string $name
 * @property string $coverurl
 * @property string $sort
 */
class MaterialCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%material_category}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fid', 'name'], 'required','message'=>'此项必填'],
            [['fid', 'sort'], 'integer'],
            [['name'], 'string', 'max' => 30],
            [['coverurl'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fid' => '父分类',
            'name' => '名称',
            'coverurl' => '封面图片',
            'sort' => '排序',
        ];
    }

    public function beforeSave($insert){
    	if (parent::beforeSave($insert)) {
    		if($this->isNewRecord){
    
    		}
    		else{
    		}
    		return true;
    	} else {
    		return false;
    	}
    }
    
    
    public function deleteChildCat($id){//递归删除所有子栏目
    	$arr=self::findAll(['fid'=>$id]);
    	foreach ($arr as $k => $v){
    		$this->deleteChildCat($v->id);
    		$v->delete();
    	}
    }
    
    public function countname(){//返回栏目的数量
    	$count_name=self::find()->count();
    	return  $count_name;
    }
    
    public function getname($catid){//获得栏目名字
    	$arr=self::findOne($catid);
    	return $arr->name;
    }
    
    public function  getWebcolumnList(){//根目录为0
    	$column=self::find()->all();
    	foreach($column as $k=>$v){
    		$arr[$v->id]=$v->name;
    	}
    	return $arr;
    }
    
    public static function getCategoryName($catid){//获得栏目名字
    	$arr=self::findOne($catid);
    	return $arr->name;
    }
    
    public function  getWebcolumnList2(){//这个是素材管理界面调用的方法根目录为0
    	$column=self::find()->all();
    	$arr=array('all'=>'所有素材');//根目录为0
    	foreach($column as $k=>$v){
    		$arr[$v->id]=$v->name;
    	}
    	return $arr;
    }
    
    public function getColumnNewsSum($catid){
    	$count=MaterialPhoto::find()->where(["catid"=>$catid])->count();
    	return $count;
    }
     
    public function getTopCategoryList($fid=0){//
    	$arr=self::find()->where(['fid'=>$fid])->all();
    	return $arr;
    }
    
    public static function findChildOption($prefix,$fid,$type=1){//$type为1则为option $type为2则为li 查找子节点,//新建栏目的时候，获得所属栏目列表
    	$arr=self::find()->orderBy('sort ASC')->all();
    	foreach ($arr as $k => $v){
    	if($v->fid== $fid){
    	if($type==1)
    		echo '<option  value="'.$v['id'].'">'.$prefix.$v['name'].'</option>';
    				else
    		echo "<li data-value=".$v['id']."><a href='javascript:void()'>".$prefix.$v['name']."</a></li>";
    			self::findChildOption('&nbsp&nbsp&nbsp&nbsp'.$prefix,$v['id'],$type);
    		}
    	}
    	}
    
    	public static function findChildOptionNo($prefix,$fid,$type=1){//$type为1则为option $type为2则为li 查找子节点,//新建栏目的时候，获得所属栏目列表
    		$arr=self::find()->orderBy('sort ASC')->all();
    		foreach ($arr as $k => $v){
    		$soncat=self::find()->where(['fid'=>$v->id])->one();
    		if($soncat)
    			$disable="disabled  style='color:#ccc;'";
    			else
    				$disable="";
    				if($v->fid== $fid){
    			if($type==1)
    				echo '<option '.$disable.' value="'.$v['id'].'">'.$prefix.$v['name'].'</option>';
    				else
    						echo "<li  ".$disable."  data-value=".$v['id']."><a href='javascript:void()'>".$prefix.$v['name']."</a></li>";
    			self::findChildOption('&nbsp&nbsp&nbsp&nbsp'.$prefix,$v['id'],$type);
    		}
    			}
    			}
    
    
    			public function findChildArray($prefix,$fid){//查找子节点,//新建栏目的时候，获得所属栏目列表
    			$arr=self::find()->orderBy('sort ASC')->all();
    			$m=array();
    			$m[0]='根目录';
    				foreach ($arr as $k => $v){
    				if($v->fid==$fid){
    					$m[$v->id]=$prefix.$v->name;
    					$this->findChildArray('&nbsp&nbsp&nbsp&nbsp'.$prefix,$v->id);
    				}
    			}
    			return $m;
    		}
    
    		public static function showCategoryLists($tag,$fid){//在category界面显示所有分类
    				$arr=self::find()->orderBy('sort ASC')->all();
    				foreach ($arr as $k => $v){
    				if($v->fid== $fid){
    				echo <<< xxx
    				<tr data-id="{$v->id}"><td>{$tag}{$v->name}</td><td>{$v->sort}</td><td class="tb_other">
                   <a class="caozuo_update_a caozuo_a btn btn-sm btn-default" title="修改" href="update-category?id={$v->id}">
                      <span class="glyphicon glyphicon-pencil"></span>
                    </a>
                    <a class="caozuo_delete_a caozuo_a  btn  btn-sm btn-default"  data-id="{$v->id}" title="删除" href="javascript:void()">
				        <span class="glyphicon glyphicon-remove"></span>
				    </a>
                   </td></tr>
xxx;
                       self::showCategoryLists('&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp'.$tag,$v['id']);
		         }
		    }
	 }
}