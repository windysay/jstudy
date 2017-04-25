<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\CourseMeal;
use app\modules\teacher\models\Teacher;
use app\modules\admin\controllers\NewsController;
use app\modules\admin\models\MaterialPhoto;
use app\modules\admin\models\MaterialCategory;
use app\models\MaterialDownload;
use yii\web\HttpException;
use yii\helpers\Html;
use yii\data\Pagination;
use app\components\Help;

class SiteController extends Controller
{
    public $layout='main';
    
    public function init(){
        parent::init();
        $this->getView()->registerCssFile(Yii::$app->homeUrl.'css/site.css');
        $this->getView()->registerCssFile(Yii::$app->homeUrl.'css/account.css');
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['index','course','news','contact','benzhan','jiangshi','shangke','xuefei','zeren'],
                        'allow' => true,
                    ],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            // 'captcha' => [
            //     'class' => 'yii\captcha\CaptchaAction',
            //     'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            // ],
            'captcha' => [//验证码
                'class' => 'yii\captcha\CaptchaAction',
                'backColor'=>0xe8ebed,  
                'foreColor'=>0x22a0dc,
                'height'=>'40', 
                'width'=>'110', 
                'minLength'=>4, 
                'maxLength'=>4, 
                //'transparent'=>true,//透明背景
            ],
        ];
    }

    public function actionIndex()
    {	
    	$teachers=Teacher::find()->where('status=1')->asArray()->all();
    	$news=MaterialPhoto::find()->where(['type'=>1])->limit(5)->orderBy('createtime DESC')->asArray()->all();
    	$ppt=MaterialPhoto::find()->where(['type'=>2])->orderBy('createtime DESC')->asArray()->all();
    	$tomorrow_begin=Help::getZeroStrtotime('tomorrow');  //明天0点
    	$week_begin=Help::getZeroStrtotime('week');    //本周第一天0点
    	$two_week_end=$week_begin+3600*24*14-1;    //两个星期的最后一天 23:59:59
    	
        return $this->render('index',[
        	'teachers'=>$teachers,
        	'news'=>$news,
        	'ppt'=>$ppt,
        	'tomorrow_begin'=>$tomorrow_begin,
        	'two_week_end'=>$two_week_end,
        ]);
    }


    public function actionContact()
    {	
    	$model=MaterialPhoto::find()->where(['type'=>3])->one();
		return $this->render('contact',[
    		'model'=>$model,
    	]);
    }

    public function actionBenzhan(){   
        $model=MaterialPhoto::findOne(6);
        return $this->render('benzhan',['model'=>$model]);
    }  

    public function actionShangke(){   
        $model=MaterialPhoto::findOne(8);
        return $this->render('shangke',['model'=>$model]);
    } 

    public function actionXuefei(){   
        $model=MaterialPhoto::findOne(9);
        return $this->render('xuefei',['model'=>$model]);
    } 

    public function actionJiangshi(){   
        $model=MaterialPhoto::findOne(7);
        return $this->render('jiangshi',['model'=>$model]);
    } 

    public function actionZeren(){ //责任条款  
        return $this->render('zeren');
    } 
    //文章展示
    public function actionNews($id){
    	$model=MaterialPhoto::find()->where('id=:id',[':id'=>$id])->one();
    	if($model===null){
    		throw new NotFoundHttpException('您访问的页面不存在.');
    	}
    	$category=MaterialCategory::findOne(['id'=>$model['catid']]);
    	$newsAll=MaterialPhoto::find()->where(['type'=>$model['type']])->select(['title','id','createtime'])->limit(10)->orderBy('createtime DESC')->asArray()->all();
    	return $this->render('news',[
    		'model'=>$model,
    		'newsAll'=>$newsAll,
    		'category_name'=>$category->name,
    	]);
    }

    
    public function actionAbout()
    {
        return $this->render('about');
    }
}
