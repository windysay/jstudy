<?php

namespace app\modules\student\components;

use app\modules\student\models\Student;
use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\HttpException;

class  StudentCheckAccess extends Controller
{
    public static function fangwen($con)
    {
        if (!Yii::$app->user->id)//如果没有登录 就返回false,然后就要退出到登录界面
            return false;
        if (Yii::$app->user->id && Yii::$app->user->identity->status == Student::STATUS_DISABLE) {
            Yii::$app->session->setFlash('error', '您的账号已经被冻结!');
            header("location: " . Url::toRoute('/account/logout'));
            exit;
        }
        $studentmenu = new StudentMenu;
        $menu = $studentmenu->accessVisit();//返回登录者可以访问的控制器模块
        if ($menu === false)//如果没有此模块权限
            throw new HttpException(404, '没有找到此页面');
        return in_array($con, $menu);//如果有次权限就返回真 否则就返回假
    }


}