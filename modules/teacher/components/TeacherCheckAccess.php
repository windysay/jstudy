<?php
namespace app\modules\teacher\components;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\HttpException;
use app\modules\teacher\components\TeacherMenu;

class  TeacherCheckAccess  extends Controller{
 
    public static function fangwen($con){//访问权限
    	if(!Yii::$app->teacher->id)//如果没有登录 就返回false,然后就要退出到登录界面
    			return false;
    	$status=Yii::$app->teacher->identity->status;
    	if($status!=1)
    		return false;
    	$adminmenu=new TeacherMenu;
    	$menu=$adminmenu->accessVisit(1);//返回登录者可以访问的控制器模块
        if($menu===false)//如果没有此模块权限
        	    throw new HttpException(404, '没有找到此页面');
    	return in_array($con,$menu);//如果有次权限就返回真 否则就返回假
    }
 
	
}