<?php

namespace app\modules\student\controllers;

use Yii;
use yii\web\Controller;
use yii\base\Object;
use yii\filters\AccessControl;
use yii\helpers\Html;
use yii\web\NotFoundHttpException;
use app\modules\student\components\StudentCheckAccess;
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
		$this->getView()->registerCssFile(Yii::$app->homeUrl.'css/student/basic.css');
		$this->getView()->registerCssFile(Yii::$app->homeUrl.'css/student/course.css');
	}
	 

 	public function behaviors(){
        return [
         'access' => [
        		'class' => AccessControl::className(),
        		'rules' => [
	        				['actions' => ['index','bespeaked','completed','canceled',
	        							'class-detail','ajax-cancel-class',
	        				],
	        				    'allow' => true,
	        						'matchCallback' =>function ($rule, $action) {
	        							return StudentCheckAccess::fangwen($this->id);
	        						},
	        				],
        				  ],
        		],
        ];
    }


    public function actionIndex( )  
    {	
    	$student_id=Yii::$app->user->identity->id;
    	$data=Timetable::find()->where('student_id=:student_id',[':student_id'=>$student_id])->andWhere('end_time>'.time())->orderBy('start_time ASC');
    	$pages = new Pagination(['totalCount' =>$data->count(), 'pageSize' =>'30']);
    	$classes = $data->offset($pages->offset)->limit($pages->limit)->all();
		
    	return $this->render('bespeaked',[
    		'pages' => $pages,
        	'count'=>$data->count(),
        	'classes'=>$classes,
        ]);
    }
    public function actionCompleted($s=null,$e=null)  //s是start_date e是end_date
    {	
    	$student_id=Yii::$app->user->identity->id;
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
				    		->andWhere('student_id=:student_id',[':student_id'=>$student_id])
				    		->andWhere('start_time>='.$start_time)
				    		->andWhere('end_time<='.$end_time)
				    		->orderBy('start_time DESC');
    	
    	$pages = new Pagination(['totalCount' =>$data->count(), 'pageSize' =>'30']);
    	$classes = $data->offset($pages->offset)->limit($pages->limit)->all();
		
    	return $this->render('completed',[
    		'pages' => $pages,
        	'count'=>$data->count(),
        	'classes'=>$classes,
        	'start_time'=>$start_time,
    		'end_time'=>$end_time,
        ]);
        
    }
 	
    public function actionCanceled($s=null,$e=null)  //s是start_date e是end_date
    {
    	$student_id=Yii::$app->user->identity->id;
    	if($s&&$e){
    		$start_time=strtotime($s);
    		$end_time=strtotime($e)+3600*24-1;
    	}else{  //计算当月的月初和最后一天
    		$first_day=date('Y-m-01',time());
    		$start_time=strtotime($first_day);
    		$end_time=strtotime("$first_day +1 month")-1;  //本月最后一天
    	}
    	$data=TimetableCancel::find()->where('student_id=:student_id',[':student_id'=>$student_id])
								    	->andWhere('start_time>='.$start_time)
								    	->andWhere('end_time<='.$end_time)
    								 ->orderBy('start_time DESC');
    	$pages = new Pagination(['totalCount' =>$data->count(), 'pageSize' =>'30']);
    	$classes = $data->offset($pages->offset)->limit($pages->limit)->all();
    
    	return $this->render('canceled',[
    			'pages' => $pages,
    			'count'=>$data->count(),
    			'classes'=>$classes,
    			'start_time'=>$start_time,
    			'end_time'=>$end_time,
    	]);
    }
    
    public function actionClassDetail($id){    //某一节课的详细信息  $bespeaked表示要查询学生出来给老师选
    	$id=Html::encode($id);
    	$student_id=Yii::$app->user->identity->id;
    	$class=Timetable::find()->where('id=:id AND student_id=:student_id',[':id'=>$id,':student_id'=>$student_id])->one();
    	if($class===null){
    		throw new NotFoundHttpException('您访问的页面不存在.');
    	}
    	$teacher=Teacher::find()->where('id=:id',[':id'=>$class->teacher_id])->one();
    	
    	return $this->render('class-detail',[
    			'class'=>$class,
    			'teacher'=>$teacher,
    	]);
    }

    public function actionAjaxCancelClass(){   //学生预约
    	$id=Yii::$app->request->post('id');
    	$status=Yii::$app->request->post('status');
    	$student_id=Yii::$app->user->identity->id;
    	$tomorrow=Help::getZeroStrtotime('tomorrow');  //明天0点时间戳
    	
    	$class=Timetable::find()->where('id=:id AND student_id=:student_id',[':id'=>$id,':student_id'=>$student_id])
    							->andWhere('start_time>'.$tomorrow)  //明天以后的课才能取消
    							->one();
    	$class->status=1;  //取消课程 status设为1
    	$class->student_id=null;

    	$cancel=new TimetableCancel();
    	$cancel->timetable_id=$class->id;
    	$cancel->teacher_id=$class->teacher_id;
    	$cancel->student_id=$student_id;
    	$cancel->date=$class->date;
    	$cancel->start_time=$class->start_time;
    	$cancel->end_time=$class->end_time;
    	$cancel->type=1;
    	
    	$student=Student::find()->where('id=:id',[':id'=>$student_id])->one();
    	$student->scenario='course_ticket';
    	$student->course_ticket+=1;
    	
    	$teacher=Teacher::find()->where('id=:id',[':id'=>$class->teacher_id])->one();
    	$transation=Yii::$app->db->beginTransaction();
    	if($class->save()&&$cancel->save()&&$student->save()){
    		$transation->commit();
    		// 要发信息通知老师
    		$mail=$teacher->email;
    		$name=$teacher->name;
    		$date_time=date('m月d日  H:i',$class->start_time).' - '.date('H:i',$class->end_time);
            $subject = 'IPEARPERA-您的预约课程被取消';//邮件标题
            $html = '【IPERAPERA】' . $date_time . '授業のキャンセルが入りました、詳しくは講師ホームで確認下さい。（生徒による当日キャンセルの場合のみ20%時給が発生します。）';
    		$label='cancel-class';   //邮件标签
    		$send_res=json_decode(SendCloud::send_mail($mail, $subject, $html,$label),true);
    		if($send_res['message']=='success'){
    			echo 1;exit();
    		}else{
    			echo 2;exit();
    		}
    		echo 1;
    	}else{
    		$transation->rollBack();
    		echo 0;
    	}
    }

 
    
}
