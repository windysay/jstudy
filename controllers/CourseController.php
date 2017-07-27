<?php

namespace app\controllers;

use app\components\Help;
use app\extensions\sendcloud\SendCloud;
use app\models\CourseMeal;
use app\models\MaterialDownload;
use app\models\Timetable;
use app\modules\student\components\StudentCheckAccess;
use app\modules\student\models\Order;
use app\modules\student\models\OrderGoods;
use app\modules\student\models\Student;
use app\modules\teacher\models\Teacher;
use Yii;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class CourseController extends Controller
{
    public $layout = 'main';

    public function init()
    {
        parent::init();
        $this->getView()->registerCssFile(Yii::$app->homeUrl . 'css/site.css');
        $this->getView()->registerCssFile(Yii::$app->homeUrl . 'css/account.css');
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['ajax-submit-order', 'ajax-bespeak-class'
                        ],
                        'allow' => true,
                        'matchCallback' => function ($rule, $action) {
                            return StudentCheckAccess::fangwen($this->id);
                        },
                    ],
                    [
                        'actions' => ['index', 'teachers', 'search', 'download', 'file-download', 'timetable'],
                        'allow' => true,
                    ],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }


    public function actionIndex()
    {
        $model = CourseMeal::find()->asArray()->orderBy("promotion_price ASC")->all();
        return $this->render('index', ['model' => $model]);
    }

    public function actionAjaxSubmitOrder()
    {
        $course_id = Html::encode($_POST['goods_id']);
        $course = CourseMeal::findOne($course_id);
        $student = Student::findOne(Yii::$app->user->id);
        $order = new Order();
        $order->student_id = Yii::$app->user->id;
        $order->order_sn = Help::orderSn();
        $order->course_id = $course->id;
        $order->total_price = $course->promotion_price;
        $order->total_pay = $course->promotion_price;
        $order->coupon_money = 0;
        $order->pay_status = 0;
        $order->c_name = Yii::$app->user->identity->username ? Yii::$app->user->identity->username : Yii::$app->user->identity->email;
        $order->c_mobile = Yii::$app->user->identity->mobile ? Yii::$app->user->identity->mobile : Yii::$app->user->identity->skype;
        if ($order->save()) {
            $orderGoods = new OrderGoods;
            $orderGoods->order_sn = $order->order_sn;
            $orderGoods->name = $course->name . '(' . $course->course_ticket . '节课)';
            $orderGoods->coverurl = $course->coverurl;
            $orderGoods->price = $course->price;
            $orderGoods->promotion_price = $course->promotion_price;
            $orderGoods->total_count = $course->course_ticket;
            if ($orderGoods->save()) {
                echo json_encode($orderGoods->order_sn);
            } else echo 0;
            die;
        } else echo 0;
        die;
    }

    public function actionSearch($date = null, $time = null)
    {  //time 1上午  2下午 3晚上
        switch ($time) {
            case 1:
                $time_begin = $date . ' 07:00:00';
                $time_end = $date . ' 11:59:59';
                break;
            case 2:
                $time_begin = $date . ' 12:00:00';
                $time_end = $date . ' 18:59:59';
                break;
            case 3:
                $time_begin = $date . ' 19:00:00';
                $time_end = $date . ' 23:59:59';
                break;
            default:
                $time_begin = $date . ' 07:00:00';
                $time_end = $date . ' 23:59:59';
                break;
        }
        $time_begin = strtotime($time_begin);
        $time_end = strtotime($time_end);
        $course = Timetable::find()->where('start_time>=' . $time_begin)->andWhere('end_time<=' . $time_end)->orderBy('start_time ASC')->asArray()->all();

        $tomorrow_begin = Help::getZeroStrtotime('tomorrow');  //明天0点
        $week_begin = Help::getZeroStrtotime('week');    //本周第一天0点
        $two_week_end = $week_begin + 3600 * 24 * 14 - 1;    //两个星期的最后一天 23:59:59

        return $this->render('search', [
            'course' => $course,
            'tomorrow_begin' => $tomorrow_begin,
            'two_week_end' => $two_week_end,
        ]);

    }

    public function actionTeachers($kw = null)
    {
        if ($kw) {   //如果有姓名
            $keywords = Html::encode($kw);
            $data = Teacher::find()->where(['like', 'name', $keywords])->andWhere(['status' => Teacher::STATUS_ACTIVE])->orderBy('createtime DESC');
        } else {
            $data = Teacher::find()->where(['status' => Teacher::STATUS_ACTIVE])->orderBy('createtime DESC');
        }
        $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => '20']);
        $teachers = $data->offset($pages->offset)->limit($pages->limit)->all();

        return $this->render('teachers', [
            'pages' => $pages,
            'count' => $data->count(),
            'teachers' => $teachers,
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

    public function actionAjaxBespeakClass()
    {   //学生选课
        $id = Yii::$app->request->post('id');  //课程id
        $id = Html::encode($id);
        $student_id = Yii::$app->user->identity->id;
        if (\Yii::$app->user->isGuest) {
            echo json_encode('guest');
            exit();
        }

        $student = Student::find()->where('id=:id', [':id' => $student_id])->one();
        if ($student->course_ticket <= 0) {
            echo json_encode('no_ticket');
            exit();
        }
        if (isset(Yii::$app->user->identity->status) && Yii::$app->user->identity->status == Student::STATUS_NOACTIVE) {
//            Yii::$app->session->setFlash('success', '选课前，请先到邮箱激活账号, 邮件已发送到您注册邮箱');
            $url = Url::toRoute(['/account/active', 'user_id' => Yii::$app->user->id]);
            $content = "用户注册需要邮箱激活，请点击链接进行激活 <a href='" . Url::toRoute(['/account/active', 'user_id' => Yii::$app->user->id]) . "'>" . base64_encode($url) . "</a>";
            SendCloud::send_mail(Yii::$app->user->identity->email, '激活iperapera账号', $content, '激活账号');
            echo json_encode('email_active');
            exit();
            //            return $this->redirect('/site/index');
        }
        if (empty(Yii::$app->user->identity->mobile)) {
            Yii::$app->session->setFlash('success', '选课前，请先绑定手机号码');
            echo json_encode('telephone_bind');
            exit();
//            return $this->redirect('/student/site/bind-mobile');
        }
        $class = Timetable::find()->where('id=:id AND status=:status', [':id' => $id, ':status' => 1])->andWhere('start_time>' . time())->one();
        if ($class === null) {
            echo json_encode('error_class');
            exit();
        }
        $class->status = 2;
        $class->student_id = $student->id;
        $student->scenario = 'course_ticket';
        $student->course_ticket -= 1;

        $teacher = Teacher::find()->where('id=:id', [':id' => $class->teacher_id])->one();

        $transaction = Yii::$app->db->beginTransaction();  //开始事务
        if ($class->save() && $student->save()) {
            $transaction->commit();
            // 要发信息通知老师
            $mail = $teacher->email;
            $name = $teacher->name;
            $date_time = date('m月d日  H:i', $class->start_time) . ' - ' . date('H:i', $class->end_time);
            $subject = '日语口语在线学习-您有新的预约课程';//邮件标题
            $html = '【日语口语在线学习】尊敬的 ' . $name . '，您好，您的课程已被预约，上课时间为' . $date_time . '。';
            $label = 'bespeak-class';   //邮件标签
            $send_res = json_decode(SendCloud::send_mail($mail, $subject, $html, $label), true);
            if ($send_res['message'] == 'success') {

            } else {

            }
            echo json_encode('success');
        } else {
            $transaction->rollBack();
            echo json_encode('fail');
        }
    }

    public function actionDownload()
    {
        $data = MaterialDownload::find();
        $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => '20']);
        $model = $data->offset($pages->offset)->limit($pages->limit)->orderBy('createtime ASC')->all();
        if ($model === null) {
            throw new NotFoundHttpException('您访问的页面不存在.');
        }
        return $this->render('download', ['model' => $model, 'pages' => $pages, 'count' => $data->count()]);
    }

    public function actionFileDownload($link)
    {
        $link = Html::encode($link);
        $file = '../web/uploads/' . $link;
        header("Content-type: application/octet-stream");
        header('Content-Disposition: attachment; filename="' . basename($file) . '"');
        header("Content-Length: " . filesize($file));
        readfile($file);
    }

}
