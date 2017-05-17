<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\base\Exception;
use yii\web\Controller;
use yii\filters\AccessControl;
use app\modules\admin\components\AdminCheckAccess;
use app\modules\admin\models\Admin;
use app\modules\teacher\models\Teacher;
use app\modules\student\models\Student;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;
use app\components\UploadImages64;
use yii\helpers\Html;
use app\models\Timetable;

class TeacherController extends Controller
{
    public $layout = 'main';

    public function init()
    {
        parent::init();
        $this->getView()->registerCssFile(Yii::$app->homeUrl . 'css/teacher/site.css');
        $this->getView()->registerCssFile(Yii::$app->homeUrl . 'css/admin/course.css');
        $this->getView()->registerCssFile(Yii::$app->homeUrl . 'css/admin/goods.css');
    }


    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    ['actions' => ['index', 'detail', 'teacher-time', 'timetable', 'history',
                        'add-timetable', 'create', 'edit', 'hastime',
                        'ajax-upload-coverurl', 'ajax-change-status', 'upload'
                    ],
                        'allow' => true,
                        'matchCallback' => function ($rule, $action) {
                            return AdminCheckAccess::fangwen($this->module, $this->id);
                        },
                    ],
                ],
            ],
        ];
    }

    public function actionIndex($keywords = null)
    {
        if ($keywords) {
            $keywords = Html::encode($keywords);
            if (preg_match('/^[[A-Za-z][A-Za-z0-9]{5,17}$/', $keywords)) { //匹配用户名
                $data = Teacher::find()->where(['like', 'username', $keywords])->andWhere(['status' => 1]);
            } else if (preg_match('/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i', $keywords)) { //匹配邮箱
                $data = Teacher::find()->where(['like', 'email', $keywords])->andWhere(['status' => 1]);
            } else //匹配名字
                $data = Teacher::find()->where(['like', 'name', $keywords])->andWhere(['status' => 1]);
        } else {
            $data = Teacher::find();
        }
        $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => '30']);
        $model = $data->offset($pages->offset)->limit($pages->limit)->orderBy('createtime DESC')->all();
        return $this->render("index", ['model' => $model, 'pages' => $pages, 'count' => $data->count()]);
    }


    public function actionDetail($id)
    {
        $id = Html::encode($id);
        $model = Teacher::find()->where('id=:id', [':id' => $id])->one();
        if ($model === null) {
            throw new NotFoundHttpException('您访问的页面不存在.');
        }
        return $this->render('detail', ['model' => $model]);
    }

    public function actionCreate()
    {
        $this->getView()->title = "新建讲师档案";
        $model = new Teacher();
        $model->scenario = "register";
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect('index');
        }
        return $this->render('_form_teacher', ['model' => $model]);
    }

    public function actionEdit($id)
    {
        $this->getView()->title = "修改讲师档案";
        $id = Html::encode($id);
        $model = Teacher::find()->where('id=:id', [':id' => $id])->one();
        $model->scenario = "update-info";
        if ($model === null) {
            throw new NotFoundHttpException('您访问的页面不存在.');
        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect('index');
        }
        return $this->render('_form_teacher', ['model' => $model]);
    }

    public function actionAjaxUploadCoverurl()
    {
        $file = $_POST['data'];
        if ($file) {
            $image = new UploadImages64($file, 5);
            //   $image->thumb(150,150);
            //     		$qiniu = new \common\extensions\qiniu\QiniuConfig($image->imageFolder,$image->imageAbsolute);//七牛上传
            //     		unlink($image->imageAbsolute);//删除自己服务器图片
        }
        echo json_encode($image->imageFolder);
    }

    public function actionAjaxChangeStatus()
    {
        $id = Yii::$app->request->post('id');
        $model = Teacher::findOne($id);
        if ($model === null) {
            throw new NotFoundHttpException('您访问的页面不存在.');
        }
        $transaction = Yii::$app->db->beginTransaction();  //开始事务
        try {
            $timetable = Timetable::find()->where(['teacher_id' => $id])->all();
            if ($timetable) {
                foreach ($timetable as $value) {
                    $timetable_pre = Timetable::findOne($value->id);
                    if ($timetable_pre->student_id != "" && $timetable_pre->status == 3) {
                        $student = Student::findOne($timetable_pre->student_id);
                        $student->scenario = "course_ticket";
                        $student->course_ticket += 1;
                        $student->update();
                    }
                    #遍历老师课程状态->管理员删除
                    $timetable_pre->status = 0;
                    $timetable_pre->update();
                }
            }
            #status==0标志删除老师
            $model->scenario = "status";
            $model->status = 0;
            $model->update();
            #遍历老师学生选课，退回上课券
            $transaction->commit();
            echo 1;
        } catch (Exception $e) {
            $transaction->rollBack();
            echo $e->getMessage();
        }
    }

    public function actionTeacherTime($kw = null)
    {
        if ($kw) {
            $keywords = Html::encode($kw);
            if (preg_match('/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i', $keywords)) { //匹配邮箱
                $data = Teacher::find()->where(['like', 'email', $keywords])->andWhere('status!=0')->orderBy('createtime DESC');
            } else //匹配名字
                $data = Teacher::find()->where(['like', 'name', $keywords])->andWhere('status!=0')->orderBy('createtime DESC');
        } else {
            $data = Teacher::find()->where('status!=0')->orderBy('createtime DESC');
        }
        $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => '50']);
        $teachers = $data->offset($pages->offset)->limit($pages->limit)->all();

        return $this->render('teachers', [
            'pages' => $pages,
            'count' => $data->count(),
            'teachers' => $teachers,
            'keywords' => isset($keywords) ? $keywords : null,
        ]);
    }

    public function actionTimetable($t)
    {
        $teacher_id = Html::encode($t);
        $teacher = Teacher::find()->where('id=:teacher_id', [':teacher_id' => $teacher_id])->asArray()->one();
        if ($teacher === null) {
            throw new NotFoundHttpException('您访问的页面不存在.');
        }

        $weekDay = Timetable::weekDay();
        $week1 = $weekDay['week1'];
        $week2 = $weekDay['week2'];
        $day_times = Timetable::dayClassTime(); //用一个数组来存放 一天中每个时间段节点
        $weekText = Timetable::weekText();

        return $this->render('timetable', [
            'teacher' => $teacher,
            'week1' => $week1,
            'week2' => $week2,
            'day_times' => $day_times,
            'weekText' => $weekText,
        ]);
    }

    public function actionHistory($t, $s = null, $e = null)  //s是start_date e是end_date
    {
        $teacher_id = Html::encode($t);
        $teacher = Teacher::find()->where('id=:teacher_id', [':teacher_id' => $teacher_id])->asArray()->one();
        if ($teacher === null) {
            throw new NotFoundHttpException('您访问的页面不存在.');
        }
        if ($s && $e) {
            $start_time = strtotime($s);
            $end_time = strtotime($e) + 3600 * 24 - 1;
            if ($end_time > time()) {
                $end_time = time();
            }
        } else {  //计算当月的月初和最后一天
            $first_day = date('Y-m-01', time());
            $start_time = strtotime($first_day);
            $end_time = time();
        }

        $data = Timetable::find()->where('teacher_id=:teacher_id', [':teacher_id' => $teacher_id])
            ->andWhere('start_time>=' . $start_time)
            ->andWhere('end_time<=' . $end_time)
            ->orderBy('start_time DESC');


        $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => '20']);
        $classes = $data->offset($pages->offset)->limit($pages->limit)->all();

        return $this->render('history', [
            'teacher' => $teacher,
            'pages' => $pages,
            'count' => $data->count(),
            'classes' => $classes,
            'start_time' => $start_time,
            'end_time' => $end_time,
        ]);

    }

    public function actionAddTimetable($t, $s, $e)
    {  // start_time,end_time,teacher_id
        $start_time = Html::encode($s);
        $end_time = Html::encode($e);
        $teacher_id = Html::encode($t);

        $teacher = Teacher::find()->where('id=:id', [':id' => $teacher_id])->one();
        if ($teacher === null) {
            throw new NotFoundHttpException('您访问的页面不存在.');
        }
        $date = strtotime(date('Y-m-d', $start_time));

        $modelExists = Timetable::find()->where('teacher_id=:teacher_id', [':teacher_id' => $teacher_id])
            ->andWhere('start_time=:start_time', [':start_time' => $start_time])
            ->exists();
        if ($modelExists) {
            Yii::$app->session->setFlash('success', "该讲师在该时间已有课程");
            return $this->redirect(['timetable',
                't' => $teacher->id,
            ]);
        }
        $model = new Timetable();

        $model->date = $date;
        $model->start_time = $start_time;
        $model->end_time = $end_time;
        $model->teacher_id = $teacher_id;

        if ($model->load(Yii::$app->request->post())) {
            $model->scenario = 'admin-add';
            $model->date = $date;
            $model->start_time = $start_time;
            $model->end_time = $end_time;
            $model->teacher_id = $teacher_id;
            if ($model->save()) {
                Yii::$app->session->setFlash('success', "添加成功");
                return $this->redirect(['timetable',
                    't' => $teacher->id,
                ]);
            }
        }

        return $this->render('add-timetable', [
            'model' => $model,
            'teacher' => $teacher,
        ]);
    }

    public function actionHastime()
    {
        $data = Timetable::find()->select('teacher_id')->where('start_time>' . time())->groupBy('teacher_id')->orderBy('createtime ASC');
        $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => '30']);
        $classes = $data->offset($pages->offset)->limit($pages->limit)->asArray()->all();
        return $this->render("hastime", [
            'classes' => $classes,
            'pages' => $pages,
            'count' => $data->count(),
        ]);
    }

    public function actionUpload()
    {
        $_FILES = $_FILES['Teacher'];
        $fileName = date('YmdHis') . rand(10000, 999999) . '.' . pathinfo($_FILES["name"]["voice_url"], PATHINFO_EXTENSION);
        $uploadPath = Yii::$app->basePath . "/web/uploads/voice/";
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0777);
        }
        move_uploaded_file($_FILES["tmp_name"]["voice_url"], $uploadPath . $fileName);
        $id = $_GET['id'];
        $teacher = Teacher::findOne($id);
        $teacher->voice_url = Yii::$app->request->getHostInfo() . "/uploads/voice/" . $fileName;
        $teacher->scenario = 'voice_url';
        $teacher->save();
        $result = ["files" => [
            [
                'url' => $uploadPath . $fileName,
                'name' => $_FILES['name']['voice_url'],
                'size' => $_FILES['size']['voice_url'],
                'type' => $_FILES['type']['voice_url'],
            ]
        ]];
        return json_encode($result);
    }

    /*
        public function actionAjaxSaveTimetable(){
            $id_str=Yii::$app->request->post('id_str');
            $status_str=Yii::$app->request->post('status_str');
            $time_begin_str=Yii::$app->request->post('time_begin_str');
            $time_end_str=Yii::$app->request->post('time_end_str');
            $date_str=Yii::$app->request->post('date_str');
            $teacher_id=Yii::$app->request->post('tid');

            $id_arr=explode(",",substr($id_str, 0, -1));
            $status_arr=explode(",",substr($status_str, 0, -1));
            $time_begin_arr=explode(",",substr($time_begin_str, 0, -1));
            $time_end_arr=explode(",",substr($time_end_str, 0, -1));
            $date_arr=explode(",",substr($date_str, 0, -1));

            $save_count=0;   //用一个数来标识  保存成功的model个数
            $transaction=Yii::$app->db->beginTransaction();  //开始事务
            foreach ($id_arr as $k=>$v){
                $status_text=$status_arr[$k];
                if($id=$v){
                    $model=Timetable::find()->where('id=:id',[':id'=>$v])->one();
                    if($model===null){
                        continue;
                    }
                    switch ($status_text){
                        case 'deleted':   //管理员删除
                            $model->status=0;
                            $model->student_id=null;
                            $model->ordertime=null;
                            break;
                        case 'no_choose':   //未提交
                            $model->status=1;
                            $model->student_id=null;
                            $model->ordertime=null;
                            break;
                        case 'choosed':     //已选择  可预约
                            $model->status=1;
                            $model->student_id=null;
                            $model->ordertime=null;
                            break;
                        case 'bespeaked': //已预约
                            $model->status=2;
                            break;
                        default:
                            continue;
                            break;
                    }
                }else{
                    $model= new Timetable();
                    $model->teacher_id=$teacher_id;
                    $model->date=$date_arr[$k];
                    $model->start_time=$time_begin_arr[$k];
                    $model->end_time=$time_end_arr[$k];
                    $model->status=1;
                }
                if($model->save()){
                    $save_count++;
                }
            }
            if($save_count==count($id_arr)){
                $transaction->commit();
                echo 1;
            }else{
                $transaction->rollBack();
                echo 0;
            }

        }
         */

}
