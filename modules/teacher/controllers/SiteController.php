<?php

namespace app\modules\teacher\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use app\modules\teacher\components\TeacherCheckAccess;
use app\modules\teacher\models\Teacher;
use yii\web\NotFoundHttpException;
use yii\helpers\Url;
use yii\helpers\Html;

class SiteController extends Controller
{
	public $layout='main';
	 
	public function init(){
		parent::init();
		$this->getView()->registerCssFile(Yii::$app->homeUrl.'css/teacher/basic.css');
		$this->getView()->registerCssFile(Yii::$app->homeUrl.'css/teacher/order.css');
		$this->getView()->registerCssFile(Yii::$app->homeUrl.'css/teacher/site.css');
	}
	
 	public function behaviors(){
        return [
         'access' => [
        		'class' => AccessControl::className(),
        		'rules' => [
	        				['actions' => ['index','info','login-psd','edit'],
	        				    'allow' => true,
	        						'matchCallback' =>function ($rule, $action) {
	        							return TeacherCheckAccess::fangwen($this->id);
	        						},
	        				],
        				  ],
        		],
        ];
    }


    public function actionIndex()
    {
    	$teacher=Teacher::find()->where(['id'=>Yii::$app->teacher->id])->one();
        return $this->render('index',['teacher'=>$teacher]);
    }
    public function actionInfo()
    {
    	$model=Teacher::find()->where(['id'=>Yii::$app->teacher->id])->one();
        return $this->render('info',['model'=>$model]);
    }
    
	public function actionEdit($id){
    	$model=Teacher::findOne(Html::encode($id));
    	$model->scenario="update-teacher";
    	if($model===null){
    		throw new NotFoundHttpException('您访问的页面不存在.');
    	}	
    	if ($model->load(Yii::$app->request->post())&&$model->save()) {
    		return $this->redirect(['index']);
    	}
    	return $this->render('_form_teacher',['model'=>$model]);
    }
    
    public function actionLoginPsd(){
    	$model=Teacher::findOne(['id'=>Yii::$app->teacher->id]);
    	if($model===null){
    		throw new NotFoundHttpException('您访问的页面不存在.');
    	}
    	$model->scenario='login-psd';
    	if (Yii::$app->request->isAjax && $model->load($_POST)){
    		Yii::$app->response->format = 'json';
    		return \yii\widgets\ActiveForm::validate($model);
    	}
        if ($model->load(Yii::$app->request->post())&&$model->save()) {
        	Yii::$app->session->setFlash('success', "保存成功");
    		return $this->redirect(["index"]);
    	}
    	return $this->render("login-psd",['model'=>$model]);
    }
    
}