<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use app\modules\admin\components\AdminCheckAccess;
use app\modules\admin\models\Admin;
use app\modules\student\models\Student;
use yii\helpers\Html;
use yii\data\Pagination;
use app\modules\student\models\Order;
use app\modules\admin\models\MoneyDetail;



class OrderController extends Controller
{
	public $layout='main';

	public function init(){
		parent::init();
		$this->getView()->registerCssFile(Yii::$app->homeUrl.'css/teacher/site.css');
		$this->getView()->registerCssFile(Yii::$app->homeUrl.'css/admin/course.css');
		$this->getView()->registerCssFile(Yii::$app->homeUrl.'css/admin/goods.css');
	}
	 

 	public function behaviors(){
        return [
         'access' => [
        		'class' => AccessControl::className(),
        		'rules' => [
	        				['actions' => ['index','list','detail','edit','ajax-change-status','money'],
	        				    'allow' => true,
	        						'matchCallback' =>function ($rule, $action) {
	        							return AdminCheckAccess::fangwen($this->module,$this->id);
	        						},
	        				],
        				  ],
        		],
        ];
    }

    public function actionIndex($start=null,$end=null){
    	if($start&&$end){
    		$start=strtotime($start);
    		$end=strtotime($end);
    		$end=$end+3600*24-1;
	    	if($end>time()){
	    		$end=time();
	    	}
    	}else{//计算当月的月初和最后一天
    		$first_day=date('Y-m-01',time());
    		$start=strtotime($first_day);
    		$end=time();
    	}
    	$data=Order::find()->andWhere('createtime>='.$start)->andWhere('createtime<='.$end)->orderBy('createtime DESC');
    	$pages = new Pagination(['totalCount' =>$data->count(), 'pageSize' =>'50']);
    	$model = $data->offset($pages->offset)->limit($pages->limit)->all();
    	return $this->render("index",['model'=>$model,'pages'=>$pages,'count'=>$data->count()]);
    }
    
    public function actionDetail($id){
    	$model=Order::findOne(Html::encode($id));
    	if($model===null){
    		throw new NotFoundHttpException('您访问的页面不存在.');
    	}
    	return $this->render('detail',['model'=>$model]);
    }
    
    public function actionMoney($start=null,$end=null){
    	if($start&&$end){
    		$start=strtotime($start);
    		$end=strtotime($end);
    		$end=$end+3600*24-1;
    		if($end>time()){
    			$end=time();
    		}
    	}else{//计算当月的月初和最后一天
    		$first_day=date('Y-m-01',time());
    		$start=strtotime($first_day);
    		$end=time();
    	}
    	$data=MoneyDetail::find()->andWhere('createtime>='.$start)->andWhere('createtime<='.$end)->orderBy('createtime DESC');
    	$pages = new Pagination(['totalCount' =>$data->count(), 'pageSize' =>'50']);
    	$model = $data->offset($pages->offset)->limit($pages->limit)->all();
    	return $this->render("money-list",['model'=>$model,'pages'=>$pages,'count'=>$data->count()]);
    }
    
    public function actionAjaxUploadCoverurl() {
    	$file=$_POST['data'];
    	if($file){
    		$image=new UploadImages64($file,5);
    		//   $image->thumb(150,150);
    		//     		$qiniu = new \common\extensions\qiniu\QiniuConfig($image->imageFolder,$image->imageAbsolute);//七牛上传
    		//     		unlink($image->imageAbsolute);//删除自己服务器图片
    	}
    	echo  json_encode($image->imageFolder);
    }
    
    public function actionAjaxChangeStatus(){
    	$type=Yii::$app->request->post('type');
    	$id=Yii::$app->request->post('id');
    	$model=Student::findOne($id);
    	if($model===null){
    		throw new NotFoundHttpException('您访问的页面不存在.');
    	}
    	$model->scenario="status";
    	if($type=="close") $model->status=0;
    	else $model->status=1;
    	if($model->save()) echo 1;
    	else echo 0;
    	 
    
    }
    
    
}
