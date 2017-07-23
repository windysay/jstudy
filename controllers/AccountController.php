<?php

namespace app\controllers;

use app\components\Help;
use app\extensions\sendcloud\SendCloud;
use app\models\FindPassword;
use app\models\LoginForm;
use app\models\MailValidate;
use app\modules\admin\models\Admin;
use app\modules\student\models\Student;
use app\modules\teacher\models\Teacher;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\Cookie;
use yii\web\NotFoundHttpException;

/**
 * Account controller
 */
class AccountController extends Controller
{//登录、注册、找回账户等等
    public $layout = 'main';

    public function init()
    {
        parent::init();
        $this->getView()->registerCssFile(Yii::$app->homeUrl . 'css/account.css');
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['login', 'forget-password'],
                'rules' => [
                    [
                        'actions' => ['captcha', 'ceshi', 'teacher-login', 'register', 'login', 'admin-login', 'register2',
                            'ajax-send-password-email', 'email-reset-password', 'forget-userpwd-email', 'ajax-send-userpwd-email',
                            'error', 'index', 'send-mail', 'forget-password', 'forget-password-email', 'set-password',
                            'ajax-set-newpassword', 'teacher-logout', 'admin-logout', 'choice-login', 'email-reset-userpwd',
                            'ajax-send-adminpwd-email', 'email-reset-adminpwd', 'forget-adminpwd-email', 'active'
                        ],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    //  'logout' => ['post'],//只能点击退出按钮才能够退出来，防止攻击
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
            'captcha' => [//验证码
                'class' => 'yii\captcha\CaptchaAction',
                'backColor' => 0xe8ebed,
                'foreColor' => 0x22a0dc,
                'height' => '40',
                'width' => '110',
                'minLength' => 4,
                'maxLength' => 4,
                //'transparent'=>true,//透明背景
            ],
        ];
    }

    public function actionChoiceLogin()
    {//没有权限，就登录那个界面
        $return_url = Yii::$app->request->referrer;
        if (strpos($return_url, 'teacher') || strpos($return_url, 'teacher-login'))
            return $this->redirect('teacher-login');
        elseif (strpos($return_url, 'admin') || strpos($return_url, 'admin-login'))
            return $this->redirect('admin-login');
        else
            return $this->redirect('login');
    }

    public function actionLogin()
    {//学生会员登陆
        if (!\Yii::$app->user->isGuest) {
            return $this->redirect(Url::toRoute("student/site"));
        }
        $model = new LoginForm('student');
        if ($model->load(Yii::$app->request->post())) {
            $username = Yii::$app->request->post('LoginForm')['username'];
            $student = Student::find()->where(['email' => $username])->orWhere(['username' => $username])->one();
            /** @var $student Student */
            if ($student->status == Student::STATUS_DISABLE) {
                Yii::$app->session->setFlash('error', "你的账户已被冻结!");
                return $this->render('login', [
                    'model' => $model,
                ]);
            } else {
                if ($model->login()) {
                    return $this->redirect(['student/site/index']);
                } else {
                    return $this->render('login', [
                        'model' => $model,
                    ]);
                }
            }
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionTeacherLogin()
    {//老师登录
        //Yii::$app->merchant->identityClass = 'backend\models\Teacher';//设置user-class
        if (!\Yii::$app->teacher->isGuest) {
            return $this->redirect(['teacher/site/index']);
        }
        $model = new LoginForm('teacher');
        if ($model->load(Yii::$app->request->post())) {
            $teacher = Teacher::findOne(['email' => Yii::$app->request->post('LoginForm')['username']]);
            if ($teacher->status == Teacher::STATUS_ACTIVE) {  //未冻结
                if ($model->teacherLogin()) {
                    return $this->redirect(['teacher/site/index']);
                } else {
                    return $this->render('teacher-login', [
                        'model' => $model,
                    ]);
                }
            } else {
                Yii::$app->session->setFlash('error', "你的账户已被冻结!");
                return $this->render('teacher-login', [
                    'model' => $model,
                ]);
            }
        } else {
            return $this->render('teacher-login', [
                'model' => $model,
            ]);
        }
    }

    public function actionAdminLogin()
    {//管理员登录
        //Yii::$app->admin->identityClass = 'backend\models\Admin';//设置user-class
        if (!\Yii::$app->admin->isGuest) {
            return $this->redirect(['admin/site/index']);
        }
        $model = new LoginForm('admin');
        if ($model->load(Yii::$app->request->post()) && $model->adminLogin()) {
            return $this->redirect(['admin/site/index']);
        } else {
            return $this->render('admin-login', [
                'model' => $model,
            ]);
        }
    }

    public function actionRegister2()
    {//会员注册
        $model = new Student();
        $model->scenario = 'register2';
        $model->phoneCodeUseType = 1;
        if (Yii::$app->request->isAjax && $model->load($_POST)) {
            Yii::$app->response->format = 'json';
            return \yii\widgets\ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $user = $model;
            Yii::$app->user->login($user, 3600 * 24 * 30);
            Yii::$app->session->setFlash('register_success', "恭喜，注册成功,赠送您一张上课券!");
            return $this->redirect(['student/site/index']);
        } else {
            return $this->render('register2', [
                'model' => $model,
            ]);
        }
    }

    public function actionRegister()
    {//最新的会员注册
        $model = new Student();
        $model->scenario = 'register';
        $model->phoneCodeUseType = 1;
        if (Yii::$app->request->isAjax && $model->load($_POST)) {
            Yii::$app->response->format = 'json';
            return \yii\widgets\ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $user = $model;
            Yii::$app->user->login($user, 3600 * 24 * 30);
            Yii::$app->session->setFlash('register_success', "请去您的注册邮箱，激活账号!");
            $url = Url::toRoute(['/account/active', 'user_id' => Yii::$app->user->id]);
            $content = "用户注册需要邮箱激活，请点击链接进行激活 <a href='" . Url::toRoute(['/account/active', 'user_id' => Yii::$app->user->id]) . "'>" . base64_encode($url) . "</a>";
            SendCloud::send_mail($user->email, '激活iperapera账号', $content, '激活账号');
            return $this->redirect(['student/site/index']);
        } else {
            $chengdu = ['大神级别' => '大神级别', '日常交流级别' => '日常交流级别', '中等级别' => '中等级别', '基础理解级别' => '基础理解级别', '完全初学者' => '完全初学者'];
            $xueximudi = ['兴趣（二次元党 日饭）' => '兴趣（二次元党 日饭）', '工作需要' => '工作需要', '个人充电（语言也是一门技艺）' => '个人充电（语言也是一门技艺）', '0' => '其他理由'];
            return $this->render('register', [
                'model' => $model, 'chengdu' => $chengdu, 'xueximudi' => $xueximudi
            ]);
        }
    }

    /**
     * 激活账号
     */
    public function actionActive()
    {
        $user_id = $_GET['user_id'];
        $user = Student::findOne($user_id);
        $user->status = Student::STATUS_ACTIVE;
        $user->scenario = 'status';
        if ($user->save(false)) {
            Yii::$app->session->setFlash('register_success', "恭喜，账号激活成功,赠送您一张上课券!");
            return $this->redirect(['student/site/index']);
        } else {
            Yii::$app->session->setFlash('register_success', "账号激活失败");
            return $this->redirect(['/site/index']);
        }
    }

    public function actionForgetPassword()
    {
        $model = new FindPassword();
        if (Yii::$app->request->isAjax && $model->load($_POST)) {
            Yii::$app->response->format = 'json';
            return \yii\widgets\ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post())) {
            $phone = Yii::$app->request->post()['FindPassword']['phone'];
            $cookies = Yii::$app->response->cookies;
            $cookies->add(new Cookie(['name' => 'find_password_verifycode', 'value' => $phone, 'expire' => time() + 1800]));
            return $this->redirect(['set-password']);
        }
        return $this->render('forget-password', ['model' => $model]);
    }

    public function actionForgetPasswordEmail()
    { //老师找回密码跳转
        return $this->render("forget-password-email");
    }

    public function actionForgetUserpwdEmail()
    { //学生找回密码跳转
        return $this->render("forget-userpwd-email");
    }

    public function actionForgetAdminpwdEmail()
    { //管理员找回密码跳转
        return $this->render("forget-adminpwd-email");
    }

    public function actionAjaxSendPasswordEmail()
    { //老师邮件找回密码
        $email = $_POST['email'];
        $user = Teacher::findOne(['email' => $email]);
        if ($user === null) {
            echo 0;
            return false;
        }
        $token = Help::random(32);
        $href = Url::toRoute(["email-reset-password", "email" => base64_encode($email), "token" => base64_encode($token)]);
        $subject = 'Iperapera - パスワード変更';
        $html = "本メールはIPERAPERA日本語オンラインコミュニケーションより、講師専用パスワード変更システムです。<br><br>" . date('Y-m-d H:i:s') . " パスワードの変更を求められました。以下のURLにてパスワードの再設定をしてください。<br/><br/>※安全上の為、URLの有効期間は30分です。尚、アクセスは一回のみになっておりますので、ご了承ください。<br><br><a href='" . $href . "'> " . $href . "</a><br><br>※本メールは送信専用メールアドレスから配信されております。ご返信いただいてもお答えできませんので、予めご了承ください。<br><br>※本メールにお心当たりのない場合は、誠にお手数ではございますが、本メールの削除をお願い申し上げます。";
        $label = '找回密码';
        $send_res = json_encode(SendCloud::send_mail($email, $subject, $html, $label), true);
        if ($send_res['message'] == 'success') {
            $model = new MailValidate();
            $model->pid = $user->id;
            $model->type = 2;
            $model->email = $email;
            $model->token = $token;
            if ($model->save())
                echo 1;
        }
        die();
    }

    public function actionAjaxSendUserpwdEmail()
    {
        $email = $_POST['email'];
        $user = Student::findOne(['email' => $email]);
        if ($user === null) {
            echo 0;
            return false;
        }
        $token = Help::random(32);
        $href = Url::toRoute(["email-reset-userpwd", "email" => base64_encode($email), "token" => base64_encode($token)]);
        $subject = '日语口语教学平台 - 用户密码重置';
        $html = "尊敬的用户：您好！您在 " . date('Y-m-d H:i:s') . " 申请重置密码，请点击下面的链接修改您的密码为了保证您帐号的安全性，该链接有效期为30分钟，点击一次后失效!<a href='" . $href . "'> " . $href . "</a>";
        $label = '密码重置';
        $send_res = json_encode(SendCloud::send_mail($email, $subject, $html, $label), true);
        if ($send_res['message'] == 'success') {
            $model = new MailValidate();
            $model->pid = $user->id;
            $model->type = 2;
            $model->email = $email;
            $model->token = $token;
            if ($model->save())
                echo 1;
        }
        die();
    }

    public function actionAjaxSendAdminpwdEmail()
    {
        $email = $_POST['email'];
        $user = Student::findOne(['email' => $email]);
        if ($user === null) {
            echo 0;
            return false;
        }
        $token = Help::random(32);
        $href = Url::toRoute(["email-reset-adminpwd", "email" => base64_encode($email), "token" => base64_encode($token)]);
        $subject = '日语口语教学平台 - 管理员密码重置';
        $html = "尊敬的管理员：您好！您在 " . date('Y-m-d H:i:s') . " 申请重置密码，请点击下面的链接修改您的密码为了保证您帐号的安全性，该链接有效期为30分钟，点击一次后失效!<a href='" . $href . "'> " . $href . "</a>";
        $label = '密码重置';
        $send_res = json_encode(SendCloud::send_mail($email, $subject, $html, $label), true);
        if ($send_res['message'] == 'success') {
            $model = new MailValidate();
            $model->pid = $user->id;
            $model->type = 2;
            $model->email = $email;
            $model->token = $token;
            if ($model->save())
                echo 1;
        }
        die();
    }

    public function actionEmailResetUserpwd($email, $token)
    {//点击验证邮箱
        $mail = base64_decode($email);
        $token = base64_decode($token);
        $member = Student::findOne(['email' => $mail]);
        if ($member === null) {
            throw new NotFoundHttpException('您访问的页面不存在.');
        }
        $member->scenario = 'email-reset-password'; //设置校验场景
        if ($member->load(Yii::$app->request->post()) && $member->save()) {
            Yii::$app->session->setFlash('success', "密码重置成功");
            Yii::$app->teacher->login($member, 3600 * 24 * 30);
            return $this->redirect(['/student/site/index']);
        } else {
            $mailValidate = MailValidate::find()->where(['email' => $mail, 'token' => $token])->one();
            if ($mailValidate === null) {
                throw new NotFoundHttpException('您访问的页面不存在.');
            }
            if ($mailValidate->createtime + 60 * 30 < time()) {
                $mailValidate->delete();
                $error = '该链接已过期';
                return $this->render('email-reset-password', ['error' => $error]);
                exit();
            }
            return $this->render('email-reset-password', ['member' => $member]);
        }
    }

    public function actionEmailResetAdminpwd($email, $token)
    {//点击验证邮箱
        $mail = base64_decode($email);
        $token = base64_decode($token);
        $member = Admin::findOne(['email' => $mail]);
        if ($member === null) {
            throw new NotFoundHttpException('您访问的页面不存在.');
        }
        $member->scenario = 'email-reset-password'; //设置校验场景
        if ($member->load(Yii::$app->request->post()) && $member->save()) {
            Yii::$app->session->setFlash('success', "密码重置成功");
            Yii::$app->teacher->login($member, 3600 * 24 * 30);
            return $this->redirect(['/admin/site/index']);
        } else {
            $mailValidate = MailValidate::find()->where(['email' => $mail, 'token' => $token])->one();
            //var_dump($token);die;
            if ($mailValidate === null) {
                throw new NotFoundHttpException('您访问的页面不存在.');
            }
            if ($mailValidate->createtime + 60 * 30 < time()) {
                $mailValidate->delete();
                $error = '该链接已过期';
                return $this->render('email-reset-password', ['error' => $error]);
                exit();
            }
            //$mailValidate->delete();
            return $this->render('email-reset-password', ['member' => $member]);
        }
    }

    public function actionEmailResetPassword($email, $token)
    {//点击验证邮箱
        $mail = base64_decode($email);
        $token = base64_decode($token);
        $member = Teacher::findOne(['email' => $mail]);
        if ($member === null) {
            throw new NotFoundHttpException('您访问的页面不存在.');
        }
        $member->scenario = 'email-reset-password'; //设置校验场景
        if ($member->load(Yii::$app->request->post()) && $member->save()) {
            Yii::$app->session->setFlash('success', "密码重置成功");
            Yii::$app->teacher->login($member, 3600 * 24 * 30);
            return $this->redirect(['/teacher/site/index']);
        } else {
            $mailValidate = MailValidate::find()->where(['email' => $mail, 'token' => $token])->one();
            //var_dump($token);die;
            if ($mailValidate === null) {
                throw new NotFoundHttpException('您访问的页面不存在.');
            }
            if ($mailValidate->createtime + 60 * 30 < time()) {
                $mailValidate->delete();
                $error = '该链接已过期';
                return $this->render('email-reset-password', ['error' => $error]);
                exit();
            }
            //$mailValidate->delete();
            return $this->render('email-reset-password', ['member' => $member]);
        }
    }

    public function actionSetPassword()
    {
        $cookies = Yii::$app->request->cookies;
        $cookie_phone = $cookies->getValue('find_password_verifycode');
        if (!$cookie_phone) {
            return $this->redirect(['forget-password']);
        }
        return $this->render('set-password', ['cookie_phone' => $cookie_phone]);
    }

    public function actionAjaxSetNewpassword()
    {//找回密码时候设置新密码保存
        $phoneCode = Yii::$app->request->post('code');
        $newPassword = Yii::$app->request->post('newPassword');
        $phone = Yii::$app->request->post('phone');
        $phoneCodeUseType = Yii::$app->request->post('use_type');

        $student = Student::findOne(['mobile' => $phone]);

        if ($student) {
            $student->scenario = 'find-set-password';
            $student->phoneCodeUseType = $phoneCodeUseType;
            $student->mobile = $phone;
            $student->phoneCode = $phoneCode;
            $student->newPassword = $newPassword;
            $validate = json_decode($student->validate(['phoneCode']));
            if ($validate) {
                if ($student->save()) {
                    $cookies = Yii::$app->response->cookies;
                    $cookies->add(new Cookie(['name' => 'find_password_verifycode', 'value' => '', 'expire' => time() - 1800]));
                    echo json_encode('success');
                } else {
                    echo json_encode('fail');
                }
            } else {
                echo json_encode('error_code');
            }

        }

    }


    public function actionSendMail()
    {//发送邮件
        $model = $this->sendEmail();
        return $this->render('send-mail', ['model' => $model]);
    }

    public function goModule($grade)
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        switch ($grade) {
            case 1:
                '';
                break;

        }
    }

    public function actionTeacherLogout()
    {
        Yii::$app->teacher->logout();
        return $this->redirect(['account/teacher-login']);

    }

    public function actionAdminLogout()
    {
        Yii::$app->admin->logout();
        return $this->redirect(['account/admin-login']);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->redirect(['account/login']);
    }


    public function actionError()
    {

        return $this->render('error');
    }


}
