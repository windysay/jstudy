<?php

use app\modules\student\models\Student;

$this->title = "学员档案详情";
?>
<div class="account-index">
    <div class="f_top_title">学员详细档案</div>
    <div class="xm_box clearfix">
        <div class="teacher_info_left pull-left">
            <img class="headimg" alt=""
                 src="<?= $model['headimg'] ? Yii::$app->homeUrl . 'images/' . $model['headimg'] : null ?>">
        </div>
        <div class="teacher_info_right pull-right">
            <p><label>ID：</label><?= 'No.' . $model['id'] ?></p>
            <p><label>用户名：</label><?= $model['username'] ?></p>
            <p><label>姓名：</label><?= $model['realname'] ?></p>
            <p><label>邮箱：</label><?= $model['email'] ?></p>
            <p><label>手机：</label><?= $model['mobile'] ?></p>
            <p><label>可用上课券：</label><?= $model['course_ticket'] ?></p>
            <p><label>购买上课券：</label><?= $model['buy_ticket'] ?></p>
            <p><label>支付总金额：</label><?= $model['monetary'] ?></p>
            <p><label>性别：</label><?= $model['sex'] ? "男" : "女" ?></p>
            <p><label>Skype：</label><?= $model['skype'] ?></p>
            <p><label>状态：</label><?= $model['status'] == Student::STATUS_ACTIVE ? '正常使用中' : '冻结' ?></p>
            <p><label>注册时间：</label><?= date("Y-m-d h:i:s", $model['createtime']) ?></p>
        </div>
    </div>
</div>

<script type="text/javascript">
    <?php $this->beginBlock('MY_VIEW_JS_END') ?>
    $(document).ready(function () {


    })

    <?php $this->endBlock(); ?>
</script>

<?php
$this->registerJs($this->blocks['MY_VIEW_JS_END'], \yii\web\View::POS_END);
?>	