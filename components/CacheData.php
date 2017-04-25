<?php
namespace app\components;

use Yii;
use common\models\HashCwxid;
use yii\helpers\Html;
use backend\modules\v1\models\WeixinInfo;
use common\components\PostCurl;
use common\models\WeixinAccesstoken;
use backend\models\Member;
use yii\caching\DbDependency;
use backend\modules\v2\models\Goods;
use backend\modules\v2\models\Store;
use backend\modules\v1\models\ReplyAutosubscribe;
use backend\modules\v1\models\ReplyList;
use backend\modules\v1\models\MaterialPhoto;
use common\extensions\sendsms\SendSms;
use frontend\modules\seller\models\Seller;
use common\components\Help;

class  CacheData{//缓存类
	
	public static  function getCwxid($hash_cwxid){//通过加密之后的cwxid(64) 获得真正的cwxid(28)
		 //$hash_cwxid=Html::encode($hash_cwxid);
		 $cache_key="_c_cwxid_".$hash_cwxid;
         $cache=Yii::$app->memcache;
         $return_value=$cache->get($cache_key);
         if($return_value===false){
         	$model=HashCwxid::find()->where('hash_cwxid=:hash_cwxid',[':hash_cwxid'=>$hash_cwxid])->asArray()->one();
         	if($model===null) //如果没有查询到结果
         		  return null;
         	$c_data=$model['cwxid'];
         	$cache->set($cache_key,$c_data,0);
         	$return_value=$model['cwxid'];
         }
         return $return_value;
	}
	
	public static  function getWeixinInfo(){//获得微信公众号的基本信息
		$cache_key="_c_weixin_info";
		$cache=Yii::$app->memcache;
		$return_value=$cache->get($cache_key);
		if($return_value===false){
			$model=WeixinInfo::find()->asArray()->one();
			if($model===null) //如果没有查询到结果
				return null;
			$c_data=$model;
		 	$sql="select *  from we_weixin_info";
		 	$dependency = new DbDependency(['sql'=>$sql]);
			$cache->set($cache_key,$c_data,0,$dependency);
			$return_value=$model;
		}
		return $return_value;
	}

	public static  function getAccessToken(){//获得微信access_token全局票据，有效期2个小时
		$cache_key="_c_weixin_access_token";
		$cache=Yii::$app->memcache;
		$return_value=$cache->get($cache_key);
		if(!$return_value){//如果没有缓存
				$access_token=WeixinInterface::getAccessToken();
				$cache->set($cache_key,$access_token,7000);
				// $model=WeixinAccesstoken::find()->one();
				// if($model){
				// 	$model->access_token=$access_token;
				// 	$model->save();
				// }else{
				// 	$model=new WeixinAccesstoken();
				// 	$model->access_token=$access_token;
				// 	$model->save();
				// }
			   $return_value=$access_token;
		}
		return $return_value;
	}
	
 

	public static function getAccessToken2(){// 备用获得access_token,微信唯一票据 有效期2个小时
		$cache_key="_c_weixin_access_token";
		$cache=Yii::$app->memcache;
		$return_value=$cache->get($cache_key);
		if(!$return_value){//如果没有缓存
			$weixininfo=CacheData::getWeixinInfo();
			$_appid=$weixininfo['appid'];
			$_secret=$weixininfo['appsecret'];
			$appurl="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$_appid."&secret=".$_secret;
			$appresult=PostCurl::https_request($appurl); //获取到返回的access_token的json数据
			$appresultarr = json_decode($appresult,true);
			$access_token=$appresultarr['access_token']; //获取到access_token的值
			$cache->set($cache_key,$access_token,7000);
			$return_value=$access_token;
		}
		return $return_value;
	}


	// public static  function getMemberByCwxid($hash_cwxid){//获得会员信息
	// 	$cwxid=self::getCwxid($hash_cwxid);
	// 	$model=Member::find()->where('cwxid=:cwxid',[':cwxid'=>$cwxid])->asArray()->one();
 //        return $model;
	// } 
	// public static  function getMemberByUid($uid){//获得会员信息
	// 	$model=Member::find()->where('id=:id',[':id'=>$uid])->asArray()->one();
 //        return $model;
	// }

	public static  function getMemberByCwxid($hash_cwxid){//获得会员信息
		$cache_key="_c_member_".$hash_cwxid;
		$cache=Yii::$app->memcache;
		$return_value=$cache->get($cache_key);
		if($return_value===false){
			$cwxid=self::getCwxid($hash_cwxid);
			$model=\backend\models\Member::find()->where('cwxid=:cwxid',[':cwxid'=>$cwxid])->asArray()->one();
			$sql='select * from we_member where cwxid="'.$cwxid.'"';
			$dependency = new DbDependency(['sql'=>$sql]);
			$c_data=$model;
			$cache->set($cache_key,$c_data,0,$dependency);
			$return_value=$model;
		}
        return $return_value;
	}
	public static  function getSellerByCwxid($hash_cwxid){//获得会员信息
		$cache_key="_c_seller_".$hash_cwxid;
		$cache=Yii::$app->memcache;
		$return_value=$cache->get($cache_key);
		if($return_value===false){
			$cwxid=self::getCwxid($hash_cwxid);
			$model=\frontend\modules\seller\models\Seller::find()->where('cwxid=:cwxid',[':cwxid'=>$cwxid])->asArray()->one();
			$sql='select * from we_seller where cwxid="'.$cwxid.'"';
			$dependency = new DbDependency(['sql'=>$sql]);
			$c_data=$model;
			$cache->set($cache_key,$c_data,0,$dependency);
			$return_value=$model;
		}
        return $return_value;
	}
	public static  function getMemberByUid($uid){//获得会员信息
		$cache_key="_c_member_".$uid;
		$cache=Yii::$app->memcache;
		$return_value=$cache->get($cache_key);
		if($return_value===false){
			$model=Member::find()->where('id=:id',[':id'=>$uid])->asArray()->one();
			$sql='select * from we_member where id="'.$uid.'"';
			$dependency = new DbDependency(['sql'=>$sql]);
			$c_data=$model;
			$cache->set($cache_key,$c_data,0,$dependency);
			$return_value=$model;
		}
        return $return_value;
	}
	 
	public static  function getGoods($goods_id){//获得商品信息
		$cache_key="_c_goods_".$goods_id;
		$cache=Yii::$app->memcache;
		$return_value=$cache->get($cache_key);
		if($return_value===false){
			$model=Goods::find()->where('id=:id',[':id'=>$goods_id])->asArray()->one();
			$sql='select * from we_goods where id='.$goods_id;
			$dependency = new DbDependency(['sql'=>$sql]);
			$c_data=$model;
			$cache->set($cache_key,$c_data,0,$dependency);
			$return_value=$model;
		}
        return $return_value;
	}
	
	public static  function getStore($store_id){//获得商品信息
		$cache_key="_c_store_".$store_id;
		$cache=Yii::$app->memcache;
		$return_value=$cache->get($cache_key);
		if($return_value===false){
			$model=Store::find()->where('id=:id',[':id'=>$store_id])->asArray()->one();
			$sql='select * from we_store where id='.$store_id;
			$dependency = new DbDependency(['sql'=>$sql]);
			$c_data=$model;
			$cache->set($cache_key,$c_data,0,$dependency);
			$return_value=$model;
		}
        return $return_value;
	}
 
	public static  function getKeywordReply($key){//获得关键字回复信息
		$key_md5=md5($key);//关键字md5加密之后就是缓存的键，方便缓存
		$cache_key="_c_keyword_reply_".$key_md5;
		$cache=Yii::$app->memcache;
		$return_value=$cache->get($cache_key);
		if($return_value===false){
			$model=ReplyList::find()->where('keyword=:keyword',[':keyword'=>$key])->asArray()->one();
			$sql='select * from we_weixin_reply_list where keyword="'.$key.'"';
			$dependency = new DbDependency(['sql'=>$sql]);
			$c_data=$model;
			$cache->set($cache_key,$c_data,0,$dependency);
			$return_value=$model;
		}
		return $return_value;
	}

	public static  function getMaterialInfo($id){//通过id获得素材信息
		$cache_key="_c_material_".$id;
		$cache=Yii::$app->memcache;
		$return_value=$cache->get($cache_key);
		if($return_value===false){
			$model=MaterialPhoto::find()->where('id=:id',['id'=>$id])->asArray()->one();
			$sql='select * from we_material_photo where id='.$id;
			$dependency = new DbDependency(['sql'=>$sql]);
			$c_data=$model;
			$cache->set($cache_key,$c_data,0,$dependency);
			$return_value=$model;
		}
		return $return_value;
	}
 
	public static  function getSmsAccessToken(){//获得sms的access_token
		$cache_key="_c_sms_access_token";
		$cache=Yii::$app->memcache;
		$return_value=$cache->get($cache_key);
		if($return_value===false){
			$data=SendSms::getAccessToken();
			$expires_in=$data['expires_in'];//到期时间戳
			$c_data=$data['access_token'];
			$cache->set($cache_key,$c_data,$expires_in);
			$return_value=$c_data;
		}
		return $return_value;
	}

 
	public static  function getSellerById($id){//获得分销商
		$cache_key="_c_seller_".$id;
		$cache=Yii::$app->memcache;
		$return_value=$cache->get($cache_key);
		if($return_value===false){
			$model=Seller::find()->where('id=:id',[':id'=>$id])->asArray()->one();
			if($model===null) //如果没有查询到结果
				return null;
			$sql='select * from we_seller where id='.$id;
			$dependency = new DbDependency(['sql'=>$sql]);
			$c_data=$model;
			$cache->set($cache_key,$c_data,0,$dependency);
			$return_value=$model;
		}
		return $return_value;
	}
 
  
	
}
 
?>