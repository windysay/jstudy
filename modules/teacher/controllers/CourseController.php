<?php

namespace app\modules\teacher\controllers;

use app\components\Help;
use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use app\modules\teacher\components\TeacherCheckAccess;
use app\modules\teacher\models\Teacher;
use app\modules\student\models\Student;
use app\modules\student\models\TimetableCancel;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;
use app\components\UploadImages64;
use yii\helpers\Html;
use app\models\Timetable;


class CourseController extends Controller
{
    public $layout='main';

    public function init(){
        parent::init();
        $this->getView()->registerCssFile(Yii::$app->homeUrl.'css/teacher/site.css');
        $this->getView()->registerCssFile(Yii::$app->homeUrl.'css/teacher/basic.css');
    }

    public function behaviors(){
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    ['actions' => ['index','ajax-save-timetable','student-info','history','cancel',
                        'class-detail'

                    ],
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
        $teacher_id=Yii::$app->teacher->identity->id;
        $teacher=Teacher::find()->where('id=:teacher_id',[':teacher_id'=>$teacher_id])->asArray()->one();
        if($teacher===null){
            throw new NotFoundHttpException('您访问的页面不存在.');
        }

        $this_month=date('Y-m-01',time());
        $this_month_begin=strtotime($this_month);   //本月第一天  0：00：00
        $this_month_end=time();    //本月最后時間   也就是现在
        $before_month_begin=strtotime("$this_month -1 month");  //上月第一天 0：00：00
        $before_month_end=$this_month_begin-1;  //上月最后一天23：59：59

        $before_month_course=Timetable::find()->where('teacher_id=:teacher_id',[':teacher_id'=>$teacher_id])
            ->andWhere('start_time>='.$before_month_begin)
            ->andWhere('end_time<='.$before_month_end)
            ->all();
        $before_month_count=0;  //上个月已完成课程总数
        foreach ($before_month_course as $k1=> $v1){
            if ($v1->start_time<=time()&&$v1->end_time>time()&&$v1->status==2){   //上课中
                $v1->status=3;
                $v1->save();
            }elseif($v1->end_time<=time()&&($v1->status==2||$v1->status==3)){  //已经上完了
                $v1->status=4;
                $v1->save();
            }elseif($v1->start_time<=time()&&($v1->status==1)){  //已经过期了
                $v1->status=5;
                $v1->save();
            }
            if($v1->status==4){
                $before_month_count+=1;
            }
        }
        $this_month_course=Timetable::find()->where('teacher_id=:teacher_id',[':teacher_id'=>$teacher_id])
            ->andWhere('start_time>='.$this_month_begin)
            ->andWhere('end_time<='.$this_month_end)
            ->all();
        $this_month_count=0;  //本月已完成课程总数
        foreach ($this_month_course as $k2=> $v2){
            if ($v2->start_time<=time()&&$v2->end_time>time()&&$v2->status==2){   //上课中
                $v2->status=3;
                $v2->save();
            }elseif($v2->end_time<=time()&&($v2->status==2||$v2->status==3)){  //已经上完了
                $v2->status=4;
                $v2->save();
            }elseif($v2->start_time<=time()&&($v2->status==1)){  //已经过期了
                $v2->status=5;
                $v2->save();
            }
            if($v2->status==4){
                $this_month_count+=1;
            }
        }

        $future_course_all=Timetable::find()->where('teacher_id=:teacher_id',[':teacher_id'=>$teacher_id])
            ->andWhere('end_time>='.time())
            ->orderBy('start_time ASC')
            ->all();
        $future_course=array();  //未来预约
        foreach ($future_course_all as $k3=> $v3){
            if ($v3->start_time<=time()&&$v3->end_time>time()&&$v3->status==2){   //上课中
                $v3->status=3;
                $v3->save();
            }elseif($v3->start_time<=time()&&($v3->status==1)){  //已经过期了
                $v3->status=5;
                $v3->save();
            }
            if($v3->status==0||$v3->status==2||$v3->status==3){
                $future_course[]=$v3;
            }
        }

        $weekDay=Timetable::weekDay();
        $week1=$weekDay['week1'];
        $week2=$weekDay['week2'];
        $day_times=Timetable::dayClassTime(); //用一个数组来存放 一天中每个時間段节点
        $weekText=Timetable::weekText();
        $weekBeginTime = Help::getWeekTime(time(), 'start');
        $weekEndTime = Help::getWeekTime(time(), 'end');
        $week1_data_exists=Timetable::find()->where('teacher_id=:teacher_id',[':teacher_id'=>$teacher_id])
            ->andWhere('start_time>=' . $weekBeginTime)
            ->andWhere('end_time<=' . $weekEndTime)
            ->exists();
        $week1_can_submit=!$week1_data_exists;    //如果这个星期有提交过時間表的记录  那这个星期就不能再次提交了

        $weekBeginTime = Help::getWeekTime($week2[0], 'start');
        $weekEndTime = Help::getWeekTime($week2[0], 'end');
        $week2_data_exists=Timetable::find()->where('teacher_id=:teacher_id',[':teacher_id'=>$teacher_id])
            ->andWhere('start_time>' . $weekBeginTime)
            ->andWhere('end_time<' . $weekEndTime)
            ->exists();
        $week2_can_submit=!$week2_data_exists;  //如果这个星期有提交过時間表的记录  那这个星期就不能再次提交了

        return $this->render('index',[
            'teacher'=>$teacher,
            'before_month_begin'=>$before_month_begin,
            'before_month_end'=>$before_month_end,
            'this_month_begin'=>$this_month_begin,
            'this_month_end'=>$this_month_end,
            'before_month_count'=>$before_month_count,
            'this_month_count'=>$this_month_count,
            'future_course'=>$future_course,
            'week1'=>$week1,
            'week2'=>$week2,
            'day_times'=>$day_times,
            'weekText'=>$weekText,
            'week1_can_submit'=>$week1_can_submit,
            'week2_can_submit'=>$week2_can_submit,
        ]);
    }


    public function actionAjaxSaveTimetable(){
        $time_begin_str=Yii::$app->request->post('time_begin_str');
        $time_end_str=Yii::$app->request->post('time_end_str');
        $date_str=Yii::$app->request->post('date_str');

        $teacher_id=Yii::$app->teacher->identity->id;

        $time_begin_arr=explode(",",substr($time_begin_str, 0, -1));
        $time_end_arr=explode(",",substr($time_end_str, 0, -1));
        $date_arr=explode(",",substr($date_str, 0, -1));

        $save_count=0;   //用一个数来标识  保存成功的model个数
        $transaction=Yii::$app->db->beginTransaction();  //开始事务
        foreach ($time_begin_arr as $k=> $v){
            $model= new Timetable();
            $model->teacher_id=$teacher_id;
            $model->date=$date_arr[$k];
            $model->start_time=$time_begin_arr[$k];
            $model->end_time=$time_end_arr[$k];
            $model->status=1;
            if($model->save()){
                $save_count++;
            }
        }
        if($save_count==count($time_begin_arr)){
            $transaction->commit();
            echo 1;
        }else{
            $transaction->rollBack();
            echo 0;
        }
    }

    public function actionStudentInfo($s){
        $id=Html::encode($s);
        $student=Student::find()->where('id=:id',[':id'=>$id])->asArray()->one();
        return $this->render('student-info',[
            'student'=>$student,
        ]);

    }

    public function actionHistory($s=null,$e=null)  //s是start_date e是end_date
    {
        $teacher_id=Yii::$app->teacher->identity->id;
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
        $data = Timetable::find()->where('teacher_id=:teacher_id', [':teacher_id' => $teacher_id])
            ->andWhere('start_time>='.$start_time)
            ->andWhere('end_time<='.$end_time)
            ->andWhere('status!=5')
            ->orderBy('start_time DESC');

        $pages = new Pagination(['totalCount' =>$data->count(), 'pageSize' =>'30']);
        $classes = $data->offset($pages->offset)->limit($pages->limit)->all();

        return $this->render('history',[
            'pages' => $pages,
            'count'=>$data->count(),
            'classes'=>$classes,
            'start_time'=>$start_time,
            'end_time'=>$end_time,
        ]);

    }

    public function actionCancel($s=null,$e=null){
        $teacher_id=Yii::$app->teacher->identity->id;
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
        $data=TimetableCancel::find()->where('teacher_id=:teacher_id',[':teacher_id'=>$teacher_id])
            ->andWhere('canceltime>='.$start_time)
            ->andWhere('canceltime<='.$end_time)
            ->orderBy('canceltime DESC');

        $pages = new Pagination(['totalCount' =>$data->count(), 'pageSize' =>'30']);
        $classes = $data->offset($pages->offset)->limit($pages->limit)->all();

        return $this->render('cancel',[
            'pages' => $pages,
            'count'=>$data->count(),
            'classes'=>$classes,
            'start_time'=>$start_time,
            'end_time'=>$end_time,
        ]);

    }

    public function actionClassDetail($c)
    {
        $id=Html::encode($c);
        $teacher_id=Yii::$app->teacher->identity->id;
        $class=Timetable::find()->where('id=:id AND teacher_id=:teacher_id',[':id'=>$id,':teacher_id'=>$teacher_id])->one();
        if($class===null){
            throw new NotFoundHttpException('您访问的页面不存在.');
        }
        $student=Student::find()->where('id=:id',[':id'=>$class->student_id])->one();
        $teacher=Teacher::find()->where('id=:id',[':id'=>$class->teacher_id])->one();

        return $this->render('class-detail',[
            'class'=>$class,
            'teacher'=>$teacher,
            'student'=>$student?$student:null,
        ]);

    }


}