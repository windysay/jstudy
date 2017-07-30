<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\web\Controller;
use yii\base\Object;
use yii\filters\AccessControl;
use yii\helpers\Html;
use yii\web\NotFoundHttpException;
use app\modules\admin\components\AdminCheckAccess;
use yii\data\Pagination;
use app\components\Help;
use app\modules\admin\models\Admin;
use app\models\CourseMeal;
use app\components\UploadImages64;
use app\modules\teacher\models\Teacher;
use app\modules\student\models\Student;
use app\modules\student\models\TimetableCancel;
use app\models\Timetable;
use app\components\GlobalConst;
use app\extensions\sendcloud\SendCloud;


class CourseController extends Controller
{
	public $layout='main';

	public function init(){
		parent::init();
		$this->getView()->registerCssFile(Yii::$app->homeUrl.'css/admin/goods.css');
		$this->getView()->registerCssFile(Yii::$app->homeUrl.'css/admin/course.css');
	}


 	public function behaviors(){
        return [
         'access' => [
        		'class' => AccessControl::className(),
        		'rules' => [
	        				['actions' => ['index','history-record','completed','setmeal',
	        							'add-course','update-course','ajax-upload-coverurl',
	        							'ajax-delete-course','class-detail','ajax-change-class',
	        							'bespeak-class'
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


    // public function actionIndex($keywords=null)  
    // {	
    // 	if($keywords){
    // 		$keywords=Html::encode($keywords);
    // 		if(preg_match('/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i', $keywords)){ //匹配邮箱
    // 			$teachers=Teacher::find()->where('email=:email',[':email'=>$keywords])->asArray()->all();
    // 		}else{ //匹配名字
    // 			$teachers=Teacher::find()->where('name=:name',[':name'=>$keywords])->asArray()->all();
    // 		}
    // 		$teachers_id_arr=[];
    // 		foreach ($teachers as $k=>$v){
    // 			$teachers_id_arr[]=$v['id'];
    // 		}

    // 		$data=Timetable::find()->where(['in','teacher_id',$teachers_id_arr])->andWhere('start_time>'.time())->orderBy('start_time ASC');
    // 	}else{
    // 		$data=Timetable::find()->where('start_time>'.time())->orderBy('start_time ASC');
    // 	}

    // 	$pages = new Pagination(['totalCount' =>$data->count(), 'pageSize' =>'30']);
    // 	$classes = $data->offset($pages->offset)->limit($pages->limit)->all();

    // 	return $this->render('index',[
    // 		'pages' => $pages,
    //     	'count'=>$data->count(),
    //     	'classes'=>$classes,
    //     ]);
    // }

    public function actionIndex($keywords=null){
        if($keywords){
            $keywords=Html::encode($keywords);
            if(preg_match('/\d{4}-\d{2}-\d{2}/', $keywords)){ //匹配邮箱
              $time=strtotime($keywords);
              $data=Timetable::find()->where('date='.$time)->orderBy('start_time ASC');
            }else{
               $data=Timetable::find()->where('start_time>'.time())->orderBy('start_time ASC');
            }
        }else{
            $data=Timetable::find()->where('start_time>'.time())->orderBy('start_time ASC');
        }

        $pages = new Pagination(['totalCount' =>$data->count(), 'pageSize' =>'30']);
        $classes = $data->offset($pages->offset)->limit($pages->limit)->all();

        return $this->render('index',[
            'pages' => $pages,
            'count'=>$data->count(),
            'classes'=>$classes,
        ]);
    }


    public function actionHistoryRecord($s=null,$e=null)  //s是start_date e是end_date
    {
    	if($s&&$e){
    		$start_time=strtotime($s);
    		$end_time=strtotime($e)+3600*24-1;
    		// if($end_time>time()){
    		// 	$end_time=time();
    		// }
    	}else{  //计算当月的月初和最后一天
    		$first_day=date('Y-m-01',time());
    		$start_time=strtotime($first_day);
    		$end_time=time();
    	}
    	// $data=TimetableCancel::find()->where(['status'=>0])
				 //    		->orWhere(['status'=>1])
				 //    		->orWhere(['status'=>5])
				 //    		->andWhere('start_time>='.$start_time)
				 //    		->andWhere('end_time<='.$end_time)
				 //    		->orderBy('start_time DESC');

        $data=TimetableCancel::find()->where('start_time>='.$start_time)
                                        ->andWhere('end_time<='.$end_time)
                                     ->orderBy('start_time DESC');
    	$pages = new Pagination(['totalCount' =>$data->count(), 'pageSize' =>'30']);
    	$classes = $data->offset($pages->offset)->limit($pages->limit)->all();

    	return $this->render('history-record',[
    		'pages' => $pages,
        	'count'=>$data->count(),
        	'classes'=>$classes,
    		'start_time'=>$start_time,
    		'end_time'=>$end_time,
        ]);

    }
    public function actionCompleted($s=null,$e=null)  //s是start_date e是end_date
    {
    	if($s&&$e){
    		$start_time=strtotime($s);
    		$end_time=strtotime($e)+3600*24-1;
    		if($end_time>time()){
    			$end_time=time();
    		}
    	}else{  //计算当月的月初和最后一天
    		$first_day=date('Y-m-01',time());
    		$start_time=strtotime($first_day);
    		$end_time=time();
    	}
    	$data=Timetable::find()->where(['status'=>4])
				    		->orWhere(['status'=>2])
				    		->orWhere(['status'=>3])
				    		->andWhere('date>='.$start_time)
				    		->andWhere('date<='.$end_time)
				    		->orderBy('start_time DESC');

    	$pages = new Pagination(['totalCount' =>$data->count(), 'pageSize' =>'30']);
    	$classes = $data->offset($pages->offset)->limit($pages->limit)->all();

        return $this->render('history-record',[
    		'pages' => $pages,
        	'count'=>$data->count(),
        	'classes'=>$classes,
        	'start_time'=>$start_time,
    		'end_time'=>$end_time,
        ]);

    }

    public function actionClassDetail($id,$bespeaked=null){    //某一节课的详细信息  $bespeaked表示要查询学生出来给老师选
    	$id=Html::encode($id);
    	$class=Timetable::find()->where('id=:id',[':id'=>$id])->one();
    	if($class===null){
    		throw new NotFoundHttpException('您访问的页面不存在.');
    	}
    	$teacher=Teacher::find()->where('id=:id',[':id'=>$class->teacher_id])->one();
    	if($class->student_id){
    		$student=Student::find()->where('id=:id',[':id'=>$class->student_id])->one();
    	}else{
    		$student=null;
    	}

        return $this->render('class-detail',[
    			'class'=>$class,
    			'teacher'=>$teacher,
    			'student'=>$student,
    	]);
    }

    public function actionBespeakClass($id, $stu = null)
    {    //给某学生预约课
    	$id=Html::encode($id);
    	$class=Timetable::find()->where('id=:id',[':id'=>$id])->one();
    	if($class===null){
    		throw new NotFoundHttpException('您访问的页面不存在.');
    	}
    	if($class->status!=1){
            Yii::$app->session->setFlash('error', "该课程不可预约");
    		return $this->redirect(['class-detail',
    				'id'=>$class->id,
    		]);
    	}
    	$teacher=Teacher::find()->where('id=:id',[':id'=>$class->teacher_id])->one();

        if($stu){   //如果有姓名
    		$keywords=Html::encode($stu);
    		$data=Student::find()->where(['like','realname',$keywords])->andWhere('status!=0')->orderBy('createtime DESC');
    	}else{
    		$keywords=null;
    		$data=Student::find()->where('status!=0')->orderBy('createtime ASC');
    	}
    	$pages = new Pagination(['totalCount' =>$data->count(), 'pageSize' =>'30']);
    	$students = $data->offset($pages->offset)->limit($pages->limit)->all();

        return $this->render('bespeak-class',[
    			'class'=>$class,
    			'teacher'=>$teacher,
    			'pages' => $pages,
    			'count'=>$data->count(),
    			'students'=>$students,
    			'keywords'=>$keywords,
    	]);
    }
    public function actionAjaxChangeClass(){
    	$id=Yii::$app->request->post('id');
    	$status=Yii::$app->request->post('status');
    	$student_id=Yii::$app->request->post('s');

        $class=Timetable::find()->where('id=:id',[':id'=>$id])->one();
    	$teacher=Teacher::find()->where('id=:id',[':id'=>$class->teacher_id])->one();
		if($class->student_id){
			$student=Student::find()->where('id=:id',[':id'=>$class->student_id])->one();
		}else{
			$student=Student::find()->where('id=:id',[':id'=>$student_id])->one();
		}

        $old_status=$class->status;   //课程更改之前的状态
    	$old_student_id=$class->student_id;

        $transation=Yii::$app->db->beginTransaction();
    	if($class->status==2&&$class->student_id){   //这节课有学生已经预约了  要归还学生的上课券
    		$student->scenario='course_ticket';
    		$student->course_ticket+=1;
    		$student->save();

            $cancel=new TimetableCancel();
    		$cancel->timetable_id=$class->id;
    		$cancel->teacher_id=$class->teacher_id;
    		$cancel->student_id=$class->student_id;
    		$cancel->date=$class->date;
    		$cancel->start_time=$class->start_time;
    		$cancel->end_time=$class->end_time;
    		$cancel->type=2;
    		$cancel->save();


        }
    	$class->status=$status;
    	$class->student_id=$student_id;
    	if($class->save()){
    		$transation->commit();
    		// 这里判断  如果和学生有关联  要发信息通知学生
    		if($old_status==2&&$class->status==1){   //这节课被之前有预约  但是现在被取消了
                $mail1 = $teacher->email;
                $name1 = $teacher->name;
    			$date_time1=date('m月d日  H:i',$class->start_time).' - '.date('H:i',$class->end_time);
                $subject1 = 'IPEARPERA-您的预约课程被取消';//邮件标题
                $html1 = '【IPERAPERA】' . $date_time1 . '授業のキャンセルが入りました、詳しくは講師ホームで確認下さい。（生徒による当日キャンセルの場合のみ20%時給が発生します。）';
    			$label1='cancel-class';   //邮件标签

                $mail2 = $student->email;
    			$name2=Student::memberName($student);
    			$date_time2=date('m月d日  H:i',$class->start_time).' - '.date('H:i',$class->end_time);
                $subject2 = 'IPEARPERA-您的预约课程被取消';//邮件标题
                $html2 = '【IPEARPERA】尊敬的 ' . $name2 . '，您' . $date_time2 . '的课程已被取消预约，特此通知。';
    			$label2='cancel-class';   //邮件标签
    		}else if($old_status==1&&$class->status==2){  //这节课现在 被预约了
    			$mail1=$teacher['email'];
    			$name1=$teacher['name'];
    			$date_time1=date('m月d日  H:i',$class->start_time).' - '.date('H:i',$class->end_time);
                $subject1 = 'IPEARPERA-您有新的预约课程';//邮件标题
                $html1 = '【IPERAPERA】' . $date_time1 . '授業の予約が入りました、生徒情報は講師ホームで確認下さい。当日は時間を間違えないようお願いします。';
    			$label1='bespeak-class';   //邮件标签

                $mail2=$student['email'];
    			$name2=Student::memberName($student);
    			$date_time2=date('m月d日  H:i',$class->start_time).' - '.date('H:i',$class->end_time);
                $subject2 = 'IPEARPERA-您有新的预约课程';//邮件标题
                $html2 = '【IPERAPERA】亲爱的会员 ' . $name2 . '，您已成功预约' . $date_time2 . '的课程，请当日登录您的skype或QQ，不要迟到哦';
    			$label2='bespeak-class';   //邮件标签
    		}
    		$send_res1=json_decode(SendCloud::send_mail($mail1, $subject1, $html1,$label1),true);
    		$send_res2=json_decode(SendCloud::send_mail($mail2, $subject2, $html2,$label2),true);

    		echo 1;
    	}else{
    		$transation->rollBack();
    		echo 0;
    	}
    }


    public function actionSetmeal(){
    	$course=CourseMeal::find()->asArray()->all();
    	return $this->render('setmeal',['model'=>$course]);
    }

    public function actionUpdateCourse($id){
    	$model=CourseMeal::findOne(Html::encode($id));
    	if($model===null){
    		throw new NotFoundHttpException('您访问的页面不存在.');
    	}
    	if ($model->load(Yii::$app->request->post())&&$model->save()) {
    		return $this->redirect('setmeal');
    	}
    	return $this->render('_course_form',['model'=>$model]);
    }


    public function actionAddCourse(){
    	$model=new CourseMeal();
    	if ($model->load(Yii::$app->request->post())&&$model->save()) {
    		return $this->redirect('setmeal');
    	}
    	return $this->render('_course_form',['model'=>$model]);
    }

    public function actionAjaxDeleteCourse(){
    	$id=Html::encode($_POST['id']);
    	$model=CourseMeal::findOne($id);
        if($model===null){
    		return false;
    	}
    	if($model->delete())
    		echo 1;
    }

    public function actionAjaxUploadCoverurl() {
    	$file=$_POST['data'];
    	if($file){
    		$image=new UploadImages64($file,6);
    		//   $image->thumb(150,150);
//     		$qiniu = new \common\extensions\qiniu\QiniuConfig($image->imageFolder,$image->imageAbsolute);//七牛上传
//     		unlink($image->imageAbsolute);//删除自己服务器图片
    	}
    	echo  json_encode($image->imageFolder);
    }


}
