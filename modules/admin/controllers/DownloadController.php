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
use app\modules\admin\models\MaterialCategory;
use app\modules\admin\models\MaterialPhoto;
use app\components\UploadImages64;
use app\models\MaterialDownload;
use app\models\UploadForm;
use yii\web\UploadedFile;

class DownloadController extends Controller
{
	public $layout='main';

	public function init(){
		parent::init();
		$this->getView()->registerCssFile(Yii::$app->homeUrl.'css/teacher/site.css');
		$this->getView()->registerCssFile(Yii::$app->homeUrl.'css/admin/course.css');
		$this->getView()->registerCssFile(Yii::$app->homeUrl.'css/admin/wechat.css');
	}
	 

 	public function behaviors(){
        return [
         'access' => [
        		'class' => AccessControl::className(),
        		'rules' => [
	        				['actions' => ['index','add-material','update-material','ajax-upload-coverurl','ajax-delete-material'],
	        				    'allow' => true,
	        						'matchCallback' =>function ($rule, $action) {
	        							return AdminCheckAccess::fangwen($this->module,$this->id);
	        						},
	        				],
        				  ],
        		],
        ];
    }
    
    public function actionIndex($keywords=null){
    	if($keywords){
    		$keywords=Html::encode($keywords);
    		$data=MaterialDownload::find()->where(['like','name',$keywords]);
    	}else $data=MaterialDownload::find()->orderBy('createtime DESC');
    	$pages = new Pagination(['totalCount' =>$data->count(), 'pageSize' =>'30']);
    	$model = $data->offset($pages->offset)->limit($pages->limit)->orderBy('createtime ASC')->all();
    	return $this->render("index",['model'=>$model,'pages'=>$pages,'count'=>$data->count()]);
    }
   
   public function actionAddMaterial(){//添加素材
   	$model = new MaterialDownload();
   	if ($model->load(Yii::$app->request->post()) && $model->save()) {
   		Yii::$app->session->setFlash('success', "资料上传成功");
   		return $this->redirect(['index']);
   	} else {
   		return $this->render('_download_form', [
   				'model' => $model,
   				]);
   	}
   }
   
   public function actionUpdateMaterial($id){//更新素材
   	$id=Html::encode($id);
   	$model=MaterialDownload::findOne($id);
   	if ($model->load(Yii::$app->request->post()) && $model->save()) {
   		Yii::$app->session->setFlash('success', "资料更新成功");
   		return $this->redirect(['index']);
   	} else {
   		return $this->render('_download_form', [
   				'model' => $model,
   				]);
   	}
   }
    
    public function actionAjaxDeleteMaterial(){//删除素材
    	$id=Html::encode($_POST['id']);
    	MaterialDownload::findOne($id)->delete();
    	echo 1;
    }
    
    public function actionAjaxUploadFile(){
    	$model = new UploadForm();
    	
    	if (Yii::$app->request->isPost) {
    		$model->file = UploadedFile::getInstance($model, 'file');
    	
    		if ($model->file && $model->validate()) {
    			$model->file->saveAs('uploads/' . $model->file->baseName . '.' . $model->file->extension);
    		}
    	}
    	
    	return $this->render('upload', ['model' => $model]);
//     	$file=$_POST['data'];
//     	$fileName=$file['file'];
//     	$fileSize=$file['size'];
//     	if($file){
//     		$newDir=Yii::getAlias("@webroot").'/uploadFile';  //文件物理目录
//     		if(!file_exists($newDir))mkdir($newDir)){
    			
//     		}
//     		$dataDir=date('Ymd',time());
//     		$fileFolder='uploadFile'.'/'.$dataDir;
//     		//$pathAbsolute=;
//     	}
    }
    
    public function actionAjaxUploadCoverurl() {
    	$file=$_POST['data'];
    	if($file){
    		$image=new UploadImages64($file,4);
    	}
    	echo  json_encode($image->imageFolder);
    }
}
