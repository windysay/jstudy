<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\base\Object;
use app\models\Timetable;
use app\modules\student\models\Student;

$this->title = "生徒信息";

?>
<div class="course-student-info">
    <p class="f_top_title clearfix">
        <?= Html::encode($this->title) ?>
    </p>
    <div class="clearfix">
        <div class="left_info pull-left">
            <img class="headimg"
                 src="<?= $student['headimg'] ? Yii::$app->urlManager->baseUrl . '/images/' . $student['headimg'] : null; ?>">
        </div>
        <div class="right_info pull-left">
            <div class="info_div clearfix">
                <p class="head pull-left">昵称</p>
                <p class="info pull-left"><?= $student['realname'] ?: '--' ?></p>
            </div>
            <div class="info_div clearfix">
                <p class="head pull-left">用户名</p>
                <p class="info pull-left"><?= $student['username'] ?: '--' ?></p>
            </div>
            <div class="info_div clearfix">
                <p class="head pull-left">性别</p>
                <p class="info pull-left"><?= Student::sexText($student['sex']); ?></p>
            </div>
            <div class="info_div clearfix">
                <p class="head pull-left">电话</p>
                <p class="info pull-left"><?= $student['mobile'] ?: '--' ?></p>
            </div>
            <div class="info_div clearfix">
                <p class="head pull-left">邮箱</p>
                <p class="info pull-left"><?= $student['email'] ?></p>
            </div>
            <div class="info_div clearfix">
                <p class="head pull-left">Skype</p>
                <p class="info pull-left"><?= $student['skype'] ?: '--' ?></p>
            </div>
            <div class="info_div clearfix">
                <p class="head pull-left">QQ</p>
                <p class="info pull-left"><?= $student['qq'] ?: '--' ?></p>
            </div>
        </div>
    </div>
</div><!-- site-index -->

<script type="text/javascript">
    <?php $this->beginBlock('MY_VIEW_JS_END') ?>

    $(document).ready(function () {


    })

    <?php $this->endBlock(); ?>
</script>

<?php
$this->registerJs($this->blocks['MY_VIEW_JS_END'], \yii\web\View::POS_END);
?>
