<?php

use app\models\Timetable;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = "讲师时间表";
$this->params['breadcrumbs'][] = $this->title;

$this->registerCssFile(Yii::$app->homeUrl . 'css/teachers-timetable.css', ['depends' => [yii\web\JqueryAsset::className()]]);
?>
<div class="course-timetable timetable_list clearfix width_1200">

    <div class="all">
        <div class="teacher_info_basic pull-left">
            <a href="javascript:void(0)">
                <img class="headimg"
                     src="<?= $teacher['headimg'] ? Yii::$app->urlManager->baseUrl . '/images/' . $teacher['headimg'] : null; ?>">
                <p class="name"><?= Html::encode($teacher['name']); ?></p>
                <audio src="<?= $teacher['voice_url'] ?>" controls="autoplay" style="width: 180px"
                       controlsList="nodownload">
                    您的浏览器不支持播放。
                </audio>
                <p class="info" style="text-align: center"><?= Html::encode($teacher['info']); ?></p>
            </a>
        </div>
        <div class="time_info pull-left">
            <div class="week week1">
                <table class="table table-bordered">
                    <tr class="week_date">
                        <td class="time_td"></td>
                        <?php foreach ($week1 as $k1 => $v1): ?>
                            <td>
                                <?php $w = date('w', $v1); ?>
                                <p class="week_text <?= $w == 6 || $w == 0 ? 'weekend' : null ?>"><?= $weekText[$w]; ?></p>
                                <p class="date_text"><?= date('m-d', $v1); ?></p>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                    <?php
                    $monday = strtotime('last monday');
                    $sunday = strtotime('next monday');
                    $timeTable1 = Timetable::find()->where(['teacher_id' => $teacher['id']])->andWhere(['>', 'date', $monday])->andWhere(['<', 'date', $sunday])->orderBy('start_time ASC')->all();
                    foreach ($timeTable1 as $class) {
                        /** @var $class Timetable */
                        if ($class->start_time <= time() && $class->end_time > time() && $class->status == 2) {   //上课中
                            $class->status = 3;
                            $class->save(false);
                        } elseif ($class->end_time <= time() && ($class->status == 2 || $class->status == 3)) {  //已经上完了
                            $class->status = 4;
                            $class->save(false);
                        } elseif ($class->start_time <= time() && ($class->status == 1)) {  //已经过期了
                            $class->status = 5;
                            $class->save(false);
                        }
                        $timeTableArray_1[date('m-d', $class->date) . ' ' . date('H:i', $class->start_time)] = array(
                            'status' => $class->status
                        );
                    }
                    print_r($timeTableArray_1);
                    die;
                    ?>
                    <?php foreach ($day_times as $kk1 => $vv1): ?>
                        <tr>
                            <td><?= $vv1['text2'] ?></td>
                            <?php foreach ($week1 as $k1 => $v1): ?>
                                <?php
                                $date = date('Y-m-d', $v1);
                                $daily_timetable = Timetable::find()->where(['teacher_id' => $teacher['id']])->andWhere(['>', 'date', ''])->asArray()->all();
                                var_export($daily_timetable);
                                echo '<br/>';
                                $time_begin = $date . ' ' . $vv1['begin'];   //如10:00:00
                                $time_end = $date . ' ' . $vv1['end'];  //11:00:00
                                $time_begin = strtotime($time_begin);
                                $time_end = strtotime($time_end);
                                //查询这一天  这个时间段的课
                                $class = Timetable::find()->where('teacher_id=:teacher_id', [':teacher_id' => $teacher['id']])->andWhere('start_time=' . $time_begin)/* ->andWhere('end_time<='.$time_end) */
                                ->one();
                                if ($class) {
                                    if ($class->start_time <= time() && $class->end_time > time() && $class->status == 2) {   //上课中
                                        $class->status = 3;
                                        $class->save();
                                    } elseif ($class->end_time <= time() && ($class->status == 2 || $class->status == 3)) {  //已经上完了
                                        $class->status = 4;
                                        $class->save();
                                    } elseif ($class->start_time <= time() && ($class->status == 1)) {  //已经过期了
                                        $class->status = 5;
                                        $class->save();
                                    }
                                }
                                ?>
                                <td>
                                    <?php if ($class === null): ?>
                                        <?php if ($time_begin >= time()): ?>
                                        <?php endif; ?>
                                    <?php elseif ($class->status == 0):  //管理员已删除?>
                                        <?php if ($class->start_time <= time()): ?>

                                        <?php else: ?>

                                        <?php endif; ?>
                                    <?php elseif ($class->status == 2 || $class->status == 3):  //已预约 //上课中 ?>
                                        <a class="time_block bespeaked" href="JavaScript::void(0)"
                                           style="cursor:default;">已预约</a>
                                    <?php elseif ($class->status == 4):  //已完成?>
                                        <a class="time_block completed" href="JavaScript::void(0)"
                                           style="cursor:default;">已结束</a>
                                    <?php elseif ($class->status == 1):  //可预约?>
                                        <a class="time_block choosed" href="JavaScript::void(0)"
                                           data-id="<?= $class['id'] ?>" title='点击预约' data-toggle='tooltip'
                                           data-placement='top'>可预约</a>
                                    <?php endif; ?>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
            <div class="week week2">
                <table class="table table-bordered">
                    <tr class="week_date">
                        <td class="time_td"></td>
                        <?php foreach ($week2 as $k2 => $v2): ?>
                            <td>
                                <?php $w = date('w', $v2); ?>
                                <p class="week_text <?= $w == 6 || $w == 0 ? 'weekend' : null ?>"><?= $weekText[$w]; ?></p>
                                <p class="date_text"><?= date('m-d', $v2); ?></p>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                    <?php foreach ($day_times as $kk2 => $vv2): ?>
                        <tr>
                            <td><?= $vv2['text2'] ?></td>
                            <?php foreach ($week2 as $k2 => $v2): ?>
                                <?php
                                $date = date('Y-m-d', $v2);
                                $time_begin = $date . ' ' . $vv2['begin'];   //如10:00:00
                                $time_end = $date . ' ' . $vv2['end'];  //11:00:00
                                $time_begin = strtotime($time_begin);
                                $time_end = strtotime($time_end);
                                //查询这一天  这个时间段的课
                                $class = Timetable::find()->where('teacher_id=:teacher_id', [':teacher_id' => $teacher['id']])->andWhere('start_time=' . $time_begin)/* ->andWhere('end_time<='.$time_end) */
                                ->one();
                                if ($class) {
                                    if ($class->start_time <= time() && $class->end_time > time() && $class->status == 2) {   //上课中
                                        $class->status = 3;
                                        $class->save();
                                    } elseif ($class->end_time <= time() && ($class->status == 2 || $class->status == 3)) {  //已经上完了
                                        $class->status = 4;
                                        $class->save();
                                    } elseif ($class->start_time <= time() && ($class->status == 1)) {  //已经过期了
                                        $class->status = 5;
                                        $class->save();
                                    }
                                }
                                ?>
                                <td>
                                    <?php if ($class === null): ?>
                                        <?php if ($time_begin >= time()): ?>
                                        <?php endif; ?>
                                    <?php elseif ($class->status == 0):  //管理员已删除?>
                                        <?php if ($class->start_time <= time()): ?>

                                        <?php else: ?>

                                        <?php endif; ?>
                                    <?php elseif ($class->status == 2 || $class->status == 3):  //已预约 //上课中 ?>
                                        <a class="time_block bespeaked" href="JavaScript::void(0)"
                                           data-id="<?= $class['id'] ?>" style="cursor:default;">已预约</a>
                                    <?php elseif ($class->status == 4):  //已完成?>
                                        <a class="time_block completed" href="JavaScript::void(0)"
                                           style="cursor:default;">已结束</a>
                                    <?php elseif ($class->status == 1):  //可预约?>
                                        <a class="time_block choosed" href="JavaScript::void(0)"
                                           data-id="<?= $class['id'] ?>" title='点击预约' data-toggle='tooltip'
                                           data-placement='top'>可预约</a>
                                    <?php endif; ?>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
    </div><!-- site-index -->

    <script type="text/javascript">
        <?php $this->beginBlock('MY_VIEW_JS_END') ?>

        $(document).ready(function () {
            $(".choosed").click(function () {
                var id = $(this).attr("data-id");
                deleteAlertMore(id, '确定预约此课程', 'ajax_bespeak_class');
            })
        })
        function ajax_bespeak_class(obj) {
            var id = $(obj).attr("data-id");
            $.ajax({//一个Ajax过程
                type: "POST", //以post方式与后台沟通
                url: "<?= Url::toRoute('ajax-bespeak-class') ?>",
                dataType: 'json',//从php返回的值以 JSON方式 解释
                data: {"id": id},
                cache: false,
                success: function (msg) {//如果调用php成功,注意msg是返回的对象，这个你可以自定义
                    if (msg == 'guest') {
                        warn('请先登录', 0);
                    } else if (msg == 'success') {
                        var url = window.location.href;
                        warnRedirect('预约成功', 1, url);
                    } else if (msg == 'no_ticket') {
                        warn('你没有上课券，请先去购买', 0);
                    } else if (msg == 'error_class' || msg == 'fail') {
                        warn('预约失败', 0);
                    }
                },
                error: function () {

                }
            })//一个Ajax过程
        }

        <?php $this->endBlock(); ?>
    </script>

    <?php
    $this->registerJs($this->blocks['MY_VIEW_JS_END'], \yii\web\View::POS_END);
    ?>
