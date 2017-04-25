<?php
namespace app\modules\admin\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\FindPassword;
use yii\captcha\CaptchaAction;
use app\modules\admin\models\Admin;
use app\modules\teacher\models\Teacher;
use app\modules\student\models\Student;
use app\components\Help;
use app\models\MailValidate;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\web\Cookie;
use yii\helpers\Html;
use app\modules\admin\components\AdminCheckAccess;

/**
 * Account controller
 */
class AccountController extends Controller{//登录、注册、找回账户等等
	public $layout='main';

	public function init(){
		parent::init();
		$this->getView()->registerCssFile(Yii::$app->homeUrl.'css/account.css');
	}

	public function behaviors(){
		return [
				'access' => [
						'class' => AccessControl::className(),
						'rules' => [
									 [
										'actions' => [
												'index','reset-password'
										],
										'allow' => true,
										'matchCallback' =>function ($rule, $action) {
											return AdminCheckAccess::fangwen($this->module,$this->id);
										},
									],
								],
						],
				'verbs' => [
						'class' => VerbFilter::className(),
						'actions' => [
								'logout' => ['post'],
						],
				],
			];
	}
    public function actions(){
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }
	
    public function actionIndex(){
    	$model=Admin::findOne(Yii::$app->admin->id);
    	if($model===null){
    		throw new NotFoundHttpException('您访问的页面不存在.');
    	}
    	$model->scenario="name-email";
    	if ($model->load(Yii::$app->request->post())&&$model->save()) {
    		Yii::$app->session->setFlash('success','保存成功');
    		return $this->redirect('index');
    	}
    	return $this->render('_form_admin',['model'=>$model]);
    }
    
    public function actionResetPassword(){
    	$model=Admin::findOne(Yii::$app->admin->id);
    	$model->scenario="re-password";
    	if($model===null){
    		throw new NotFoundHttpException('您访问的页面不存在.');
    	}
    	if (Yii::$app->request->isAjax && $model->load($_POST)){
    		Yii::$app->response->format = 'json';
    		return \yii\widgets\ActiveForm::validate($model);
    	}
    	if ($model->load(Yii::$app->request->post())&&$model->save()) {
    		return $this->redirect('index');
    	}
    	return $this->render('_form_admin_psd',['model'=>$model]);
    }
}
