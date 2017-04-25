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



class NewsController extends Controller
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
	        				['actions' => ['index','category','add-category','update-category',
	        						'ajax-delete-category','add-material','update-material',
	        						'ajax-upload-coverurl','ajax-delete-material','ppt','add-ppt','update-ppt',
	        						'contact',
	        				],
	        				    'allow' => true,
	        						'matchCallback' =>function ($rule, $action) {
	        							return AdminCheckAccess::fangwen($this->module,$this->id);
	        						},
	        				],
        				  ],
        		],
        ];
    }

    public function actionIndex($catid='all'){//素材管理
    	    $catid=Html::encode($catid);
        	if($catid!='all'){
        		$catname=MaterialCategory::getCategoryName($catid);
        		$data=MaterialPhoto::find()->where(['catid'=>$catid,'type'=>1]);
        	}else{
        		$catname='所有素材';
        		$data=MaterialPhoto::find()->where(['type'=>1]);
        	}
        	$pages = new Pagination(['totalCount' =>$data->count(), 'pageSize' =>'20']);
        	$models = $data->offset($pages->offset)->limit($pages->limit)->orderBy("createtime DESC")->all();

        	return $this->render('index',[
        			'models' => $models,
        			'pages' => $pages,
        			'count'=>$data->count(),
        			'catname'=>$catname
        	]);
   }
    public function actionPpt(){//素材管理
        	$catname='所有素材';
        	$data=MaterialPhoto::find()->where(['type'=>2]);
        	$pages = new Pagination(['totalCount' =>$data->count(), 'pageSize' =>'20']);
        	$models = $data->offset($pages->offset)->limit($pages->limit)->orderBy("createtime DESC")->all();

        	return $this->render('ppt',[
        			'models' => $models,
        			'pages' => $pages,
        			'count'=>$data->count(),
        			'catname'=>$catname
        	]);
   }
   
   public function actionAddMaterial(){//添加素材
   	$model = new MaterialPhoto();
   	$showcover_list=MaterialPhoto::showCoverList();
   	if ($model->load(Yii::$app->request->post()) && $model->save()) {
   		Yii::$app->session->setFlash('success', "素材新建成功");
   		return $this->redirect(['index']);
   	} else {
   		return $this->render('_material_form', [
   				'model' => $model,'showcover_list'=>$showcover_list,
   		]);
   	}
   }
   public function actionAddPpt(){//添加素材
   	$model = new MaterialPhoto();
   	$model->type=2;
   	$showcover_list=MaterialPhoto::showCoverList();
   	if ($model->load(Yii::$app->request->post()) && $model->save()) {
   		Yii::$app->session->setFlash('success', "添加成功");
   		return $this->redirect(['ppt']);
   	} else {
   		return $this->render('_ppt_form', [
   				'model' => $model,'showcover_list'=>$showcover_list,
   		]);
   	}
   }
   public function actionContact(){// 联系哦我们
   	$model=MaterialPhoto::find()->where(['type'=>3])->one();
   	if($model===null){
   		$model = new MaterialPhoto();
   		$model->title='联系我们';
   	}
   	$model->type=3;
   	$model->scenario='contact';
   	if ($model->load(Yii::$app->request->post()) && $model->save()) {
   		Yii::$app->session->setFlash('success', "保存成功");
   		return $this->redirect(['contact']);
   	} else {
   		return $this->render('_contact_form', [
   				'model' => $model,
   		]);
   	}
   }

    public function actionUpdatePpt($id){//添加素材
        $id=Html::encode($id);
        $model=MaterialPhoto::findOne($id);
        $showcover_list=MaterialPhoto::showCoverList();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', "更新成功");
            return $this->redirect(['index']);
        } else {
            return $this->render('_ppt_form', [
                'model' => $model,'showcover_list'=>$showcover_list,
            ]);
        }
    }
   public function actionUpdateMaterial($id){//添加素材
   	$id=Html::encode($id);
   	$model=MaterialPhoto::findOne($id);
   	$showcover_list=MaterialPhoto::showCoverList();
   	if ($model->load(Yii::$app->request->post()) && $model->save()) {
   		Yii::$app->session->setFlash('success', "素材更新成功");
   		return $this->redirect(['index']);
   	} else {
   		return $this->render('_material_form', [
   				'model' => $model,'showcover_list'=>$showcover_list,
   				]);
   	}
   }
    
    public function actionCategory(){
    	return $this->render('category');
    }
    public function actionAddCategory(){//素材分类
    	$model = new MaterialCategory();
    	if ($model->load(Yii::$app->request->post()) && $model->save()) {
    		Yii::$app->session->setFlash('success', "新建分类成功");
    		return $this->redirect(['category']);
    	} else {
    		return $this->render('_category_form', [
    				'model' => $model
    				]);
    	}
    }
    public function actionUpdateCategory($id){//更新素材分类
    	$id=Html::encode($id);
    	$model = MaterialCategory::findOne($id);
    	if ($model->load(Yii::$app->request->post()) && $model->save()) {
    		Yii::$app->session->setFlash('success', "更新分类成功");
    		return $this->redirect(['category']);
    	} else {
    		return $this->render('_category_form', [
    				'model' => $model
    				]);
    	}
    }
    public function actionAjaxDeleteCategory(){//新建菜单
    	$id=Html::encode($_POST['id']);
    	MaterialCategory::findOne($id)->delete();
    	echo 1;
    }
    
    public function actionAjaxDeleteMaterial(){//删除素材
    	$id=Html::encode($_POST['id']);
    	MaterialPhoto::findOne($id)->delete();
    	echo 1;
    }
    
    public function actionDetail($id){
    	$this->getView()->title="修改档案资料";
    	$model=Order::findOne(Html::encode($id));
    	if($model===null){
    		throw new NotFoundHttpException('您访问的页面不存在.');
    	}
    	return $this->render('detail',['model'=>$model]);
    }
    
    public function actionAjaxUploadCoverurl() {
    	$file=$_POST['data'];
    	if($file){
    		$image=new UploadImages64($file,4);
    	}
    	echo  json_encode($image->imageFolder);
    }
}
