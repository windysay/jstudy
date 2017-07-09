<?php

namespace app\modules\student\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\CourseMeal;
use yii\helpers\Html;
use app\modules\student\models\Order;
use app\components\Help;
use app\modules\student\components\StudentCheckAccess;
use app\modules\student\models\Student;

class OrderController extends Controller
{
    public $layout = 'main';

    public function init()
    {
        parent::init();
        $this->getView()->registerCssFile(Yii::$app->homeUrl . 'css/student/basic.css');
        $this->getView()->registerCssFile(Yii::$app->homeUrl . 'css/student/order.css');
        $this->getView()->registerCssFile(Yii::$app->homeUrl . 'css/student/site.css');
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['ajax-delete-order', 'index'],
                        'allow' => true,
                        'matchCallback' => function ($rule, $action) {
                            return StudentCheckAccess::fangwen($this->id);
                        },
                    ],
                    [
                        'actions' => ['index', 'detail'],
                        'allow' => true,
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $userId = Yii::$app->user->id;
        $model = Order::find()->where(['m_delete' => 0, 'student_id' => $userId])->asArray()->orderBy("createtime DESC")->all();
        $student = Student::findOne($userId);
        return $this->render('index', ['model' => $model, 'student' => $student]);
    }

    public function actionDetail($sn)
    {
        $model = Order::find()->where(['order_sn' => Html::encode($sn)])->asArray()->one();
        return $this->render('detail', ['order' => $model]);
    }

    public function actionAjaxDeleteOrder()
    {
        $order_sn = Html::encode($_POST['sn']);
        $order = Order::findOne(['order_sn' => $order_sn, 'student_id' => Yii::$app->user->id, 'm_delete' => 0]);
        if ($order === null) {  //没查到数据
            echo 0;
            exit();
        } else {
            $order->m_delete = 1;
            if ($order->update())
                echo 1;
            else
                echo 3;
        }
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

}
