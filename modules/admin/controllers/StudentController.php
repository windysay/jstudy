<?php

namespace app\modules\admin\controllers;

use app\components\UploadImages64;
use app\models\Timetable;
use app\modules\admin\components\AdminCheckAccess;
use app\modules\student\models\Student;
use Yii;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\NotFoundHttpException;


class StudentController extends Controller
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
                    ['actions' => ['index', 'detail', 'edit', 'ajax-change-status', 'record'],
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
            if (preg_match('/^(13|14|15|17|18)[0-9]{9}$/', $keywords)) { //匹配手机
                $data = Student::find()->where(['mobile' => $keywords]);
            } else if (preg_match('/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i', $keywords)) { //匹配邮箱
                $data = Student::find()->where(['email' => $keywords]);
            } else //匹配名字
                $data = Student::find()->where(['like', 'realname', $keywords]);
        } else $data = Student::find()->orderBy('createtime DESC');
        $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => '30']);
        $model = $data->offset($pages->offset)->limit($pages->limit)->orderBy('createtime DESC')->all();
        return $this->render("index", ['model' => $model, 'pages' => $pages, 'count' => $data->count()]);
    }

    public function actionDetail($id)
    {
        $this->getView()->title = "修改档案资料";
        $model = Student::findOne(Html::encode($id));
        if ($model === null) {
            throw new NotFoundHttpException('您访问的页面不存在.');
        }
        return $this->render('detail', ['model' => $model]);
    }

    public function actionEdit($id)
    {
        $this->getView()->title = "修改档案资料";
        $model = Student::findOne(Html::encode($id));
        $model->scenario = "update-info";

        if ($model === null) {
            throw new NotFoundHttpException('您访问的页面不存在.');
        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect('index');
        }
        return $this->render('_form_student', ['model' => $model]);
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
        $type = Yii::$app->request->post('type');
        $id = Yii::$app->request->post('id');
        $model = Student::findOne($id);
        if ($model === null) {
            throw new NotFoundHttpException('您访问的页面不存在.');
        }
        $model->scenario = "status";
        if ($type == "close") $model->status = Student::STATUS_DISABLE;
        else $model->status = Student::STATUS_ACTIVE;
        if ($model->save()) echo 1;
        else echo 0;
    }

    public function actionRecord($sid, $s = null, $e = null)  //s是start_date e是end_date
    {
        $student_id = Html::encode($sid);
        if ($s && $e) {
            $start_time = strtotime($s);
            $end_time = strtotime($e) + 3600 * 24 - 1;
            $data = Timetable::find()->where('student_id=:student_id', [':student_id' => $student_id])
                ->andWhere('start_time>=' . $start_time)
                ->andWhere('end_time<=' . $end_time)
                ->orderBy('start_time DESC');
        } else {
            $data = Timetable::find()->where('student_id=:student_id', [':student_id' => $student_id])
                ->orderBy('start_time DESC');
        }
        $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => '30']);
        $classes = $data->offset($pages->offset)->limit($pages->limit)->all();

        return $this->render('record', [
            'pages' => $pages,
            'count' => $data->count(),
            'classes' => $classes,
        ]);

    }

}
