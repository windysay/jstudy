<?php

namespace app\modules\student\controllers;

use app\modules\teacher\models\Teacher;
use Yii;
use yii\helpers\Html;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\modules\student\models\Student;
use app\modules\student\components\StudentCheckAccess;
use app\components\UploadImages64;
use yii\web\Cookie;
use app\models\Feedback;
use app\extensions\sendcloud\SendCloud;
use app\modules\admin\models\Admin;

class SiteController extends Controller
{
	public $layout='main';
	
	public function init(){
		parent::init();
		$this->getView()->registerCssFile(Yii::$app->homeUrl.'css/student/basic.css');
		$this->getView()->registerCssFile(Yii::$app->homeUrl.'css/student/site.css');
	}
	
	public function behaviors(){
		return [
				'access' => [
						'class' => AccessControl::className(),
						'rules' => [
									 [
										'actions' => [
												'index','info','headimg','ajax-upload-headimg','ajax-save-headimg','suggestion',
												'safe','username','login-psd','change-mobile','change-mobile-ok','bind-mobile','teacher-info'
										],
										'allow' => true,
										'matchCallback' =>function ($rule, $action) {
											return StudentCheckAccess::fangwen($this->id);
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
	
	
    public function actionIndex()
    {	
    	$student_id=Yii::$app->user->id;
    	$student=Student::findOne($student_id);
        return $this->render('index',[
        		'student'=>$student
        ]);
    }
    public function actionInfo(){
    	$student_id=Yii::$app->user->id;
    	$model = Student::findOne($student_id);
    	$model->scenario = 'update-student';
    	if ($model->load(Yii::$app->request->post()) && $model->save()) {
    		Yii::$app->session->setFlash('success', "保存成功");
    		return $this->redirect(['info']);
    	} else {
    		return $this->render('info', [
    				'model' => $model,
    		]);
    	}
    }
    
    public function actionHeadimg(){//头像修改
    	$student_id=Yii::$app->user->id;
    	$student=Student::findOne($student_id);
    	$name=Student::memberName($student);
    	return $this->render('headimg', [
    			'student'=>$student,
    			'name'=>$name,
    	]);
    }
    public function actionTeacherInfo($id){
        $id=Html::encode($id);
        $model=Teacher::find()->where('id=:id',[':id'=>$id])->one();
        if($model===null){
            throw new NotFoundHttpException('您访问的页面不存在.');
        }
        return $this->render('detail',['model'=>$model]);
    }
    public function actionAjaxUploadHeadimg(){//上传头像
    	$file=$_POST['data'];
    	if($file){
    		$image=new UploadImages64($file,2);
    		$url=$image->imageFolder;
    	}
    	echo  json_encode($url);
    }
    public function actionAjaxSaveHeadimg(){ //上传头像
    	$headimg=Yii::$app->request->post('headimg');
    	$student=Student::findOne(Yii::$app->user->id);
    	$student->scenario='save-headimg';
    	$student->headimg=$headimg;
    	if($student->update()){
    		echo 1;
    	}else{
    		echo 0;
    	}
    }
    
    
    public function actionSafe(){//账户安全
    	$student_id=Yii::$app->user->identity->id;
    	$model=Student::find()->where('id=:id',[':id'=>$student_id])->one();
    
    	return $this->render('safe',[
    			'model'=>$model,
    	]);
    }
    
    public function actionUsername(){  //设置用户名
    	$student_id=Yii::$app->user->identity->id;
    	$model=Student::find()->where('id=:id',[':id'=>$student_id])->one();
    	if($model->username){
    		Yii::$app->session->setFlash('error','您已设置用户名');
    		return $this->redirect(['safe']);
    	}
    	$model->scenario='username';
    	if ($model->load(Yii::$app->request->post()) && $model->save()) {
    		Yii::$app->session->setFlash('success', "保存成功");
    		return $this->redirect(['safe']);
    	} else {
    		return $this->render('username', [
    				'model' => $model,
    		]);
    	}
    }
    public function actionLoginPsd(){  // 修改密码
    	$student_id=Yii::$app->user->identity->id;
    	$model=Student::find()->where('id=:id',[':id'=>$student_id])->one();
    	$model->scenario='change-pswd';
    	if (Yii::$app->request->isAjax && $model->load($_POST)){
    		Yii::$app->response->format = 'json';
    		return \yii\widgets\ActiveForm::validate($model);
    	}
    	if ($model->load(Yii::$app->request->post()) && $model->save()) {
    		Yii::$app->session->setFlash('success', "保存成功");
    		return $this->redirect(['safe']);
    	} else {
    		return $this->render('login-psd', [
    				'model' => $model,
    		]);
    	}
    }
    /** 修改手机号码1 */
    public function actionChangeMobile(){//第一步，验证原来手机号码
    	$student_id=Yii::$app->user->identity->id;
    	$model=Student::find()->where('id=:id',[':id'=>$student_id])->one();
    	if(!$model->mobile){
    		return $this->redirect('bind-mobile');
    	}
    	$model->scenario='replace-mobile';
    	$model->phoneCodeUseType=3;
    	if (Yii::$app->request->isAjax && $model->load($_POST)){
    		Yii::$app->response->format = 'json';
    		return \yii\widgets\ActiveForm::validate($model);
    	}
    	if ($model->load(Yii::$app->request->post())) {
    		$cookies = Yii::$app->response->cookies;
    		$cookies->add(new Cookie(['name' =>'change_mobile_ok','value' =>$student_id,'expire'=>time()+300]));
    		$cookies->add(new Cookie(['name' =>'code_countdown_time','value' =>null,'expire'=>time()-300]));
    		$cookies->add(new Cookie(['name' =>'code_type','value' =>null,'expire'=>time()-300]));
    		return $this->redirect(['change-mobile-ok']);
    	} else {
    		return $this->render('change-mobile', ['model' => $model]);
    	}
    
    }
    /** 修改手机号码  绑定新号码 */
    public function actionChangeMobileOk(){//第二部，验证现在的手机号码
    	$cookies=Yii::$app->request->cookies;
    	$student_id=Yii::$app->user->identity->id;
    	$change_mobile_ok=$cookies->getValue('change_mobile_ok');
    	if($change_mobile_ok==$student_id){  //判断有这个cookie 说明是验证第一步通过才进来的  就删除这个cookie
    		$model=Student::find()->where('id=:id',[':id'=>$student_id])->one();
    		if(!$model->mobile){
    			return $this->redirect('bind-mobile');
    		}
    		$model->scenario='bind-mobile';
    		$model->phoneCodeUseType=4;
    		if (Yii::$app->request->isAjax && $model->load($_POST)){
    			Yii::$app->response->format = 'json';
    			return \yii\widgets\ActiveForm::validate($model);
    		}
    		if ($model->load(Yii::$app->request->post()) && $model->save()) {
    			$cookies = Yii::$app->response->cookies;
    			$cookies->add(new Cookie(['name' =>'change_mobile_ok','value' =>null,'expire'=>time()-300]));
    			$cookies->add(new Cookie(['name' =>'code_countdown_time','value' =>null,'expire'=>time()-300]));
    			$cookies->add(new Cookie(['name' =>'code_type','value' =>null,'expire'=>time()-300]));
    			Yii::$app->session->setFlash('success', "保存成功");
    			return $this->redirect(['safe']);
    		} else {
    			return $this->render('bind-mobile',['model'=>$model]);
    		}
    		 
    		 
    	}else{  //没有这个cookie  说明是直接通过输入链接进来的  得跳回  第一步
    		return $this->redirect('replace-mobile');
    	}
    }
    public function actionBindMobile(){//绑定手机号码
    	$student_id=Yii::$app->user->identity->id;
    	$model=Student::find()->where('id=:id',[':id'=>$student_id])->one();
    	if($model->mobile){
            return $this->render('change-mobile', ['model' => $model]);
    	}
    
    	$model->scenario='bind-mobile';
    	$model->phoneCodeUseType=4;
    	if (Yii::$app->request->isAjax && $model->load($_POST)){
    		Yii::$app->response->format = 'json';
    		return \yii\widgets\ActiveForm::validate($model);
    	}
    	if ($model->load(Yii::$app->request->post()) && $model->save()) {
    		Yii::$app->session->setFlash('success', "保存成功");
    		return $this->redirect(['safe']);
    	} else {
    		return $this->render('bind-mobile', [
    				'model' => $model,
    		]);
    	}
    }
    
    public function actionSuggestion(){
    	$model=new Feedback();
    	if ($model->load(Yii::$app->request->post()) && $model->save()) {
    		Yii::$app->session->setFlash('success', "谢谢您的宝贵意见");
    		
    		// 要发信息通知管理员
    		$admin=Admin::find()->where(['grade'=>1])->one();
    		$to_email=$admin->email;
    		$name=$admin->admin_name?$admin->admin_name:'管理员';
    		 $subject='日语口语在线学习-您收到新的意见或建议';//邮件标题
    		$html='【日语口语在线学习】尊敬的'.$name.',您好，您收到新的意见或建议：'.$model->content;
    		$label='admin-suggestion';   //邮件标签
    		$send_res=json_decode(SendCloud::send_mail($to_email, $subject, $html,$label),true);
    		if($send_res['message']=='success'){
    		
    		}else{
    			$mail= Yii::$app->mailer->compose();
    			$mail->setTo($to_email);
    			$mail->setSubject("您收到新的意见或建议");
    			//$mail->setTextBody('zheshisha ');   //发布纯文字文本
    			$mail->setHtmlBody('尊敬的'.$name.',您好，您收到新的意见或建议：<br/>'.$model->content);    //发布可以带html标签的文本
    			if($mail->send()){
    				
    			}else{
    			
    			}
    		} 
    		return $this->redirect(['index']);
    	} else {
    		return $this->render('suggestion', [
    				'model' => $model,
    		]);
    	}
    }
}
