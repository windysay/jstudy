<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use app\modules\admin\components\AdminCheckAccess;
use app\modules\admin\models\Admin;



class SiteController extends Controller
{
	public $layout='main';

	public function init(){
		parent::init();
		$this->getView()->registerCssFile(Yii::$app->homeUrl.'css/admin/site.css');
	}
	 

 	public function behaviors(){
        return [
         'access' => [
        		'class' => AccessControl::className(),
        		'rules' => [
	        				['actions' => ['index',],
	        				    'allow' => true,
	        						'matchCallback' =>function ($rule, $action) {
	        							return AdminCheckAccess::fangwen($this->module,$this->id);
	        						},
	        				],
        				  ],
        		],
        ];
    }


    public function actionIndex()
    {	
    	$admin_id=Yii::$app->admin->identity->id;
    	$admin=Admin::findOne($admin_id);
        return $this->render('index',[
        	'admin'=>$admin	
        ]);
        
    }
    
    
    
    
}
