<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title="添加课程时间";
$this->registerJsFile(Yii::$app->homeUrl.'js/LocalResizeIMG.js',['depends' => [yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Yii::$app->homeUrl.'widget/ueditor/ueditor.config.js',['depends' => [yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Yii::$app->homeUrl.'widget/ueditor/ueditor.all.min.js',['depends' => [yii\web\JqueryAsset::className()]]);
?>

<div class="teacher-add-timetable form_basic timetable_form clearfix">
	<p class="f_top_title clearfix">
	     <?= Html::encode($this->title) ?>
	 </p>
	<div class="teacher_info_basic pull-left">
		 	<img class="headimg" src="<?= Yii::$app->urlManager->baseUrl.'/images/'.$teacher['headimg'] ;?>">
			<p class="name"><?= Html::encode($teacher['name']); ?></p>
			<p class="info"><?= Html::encode($teacher['info']); ?></p>
	</div>
	<div class="pull-left">
    <?php $form = ActiveForm::begin(); ?>
    	
    	<div class="hide">
    		  <?= $form->field($model, 'teacher_id')->textInput(['readonly' => 'readonly']) ?>
    		  <?= $form->field($model, 'date')->textInput(['readonly' => 'readonly']) ?>
    		  <?= $form->field($model, 'start_time')->textInput(['readonly' => 'readonly']) ?>
    		  <?= $form->field($model, 'end_time')->textInput(['readonly' => 'readonly']) ?>
    	</div> 
	    <div class="form-group field-timetable-date required">
			<label class="control-label">日期</label>
			<p type="text" class="time"><?= date('Y-m-d',$model->start_time) ?></p>
		</div>
	    <div class="form-group field-timetable-date required">
			<label class="control-label">上课时间</label>
			<p type="text" class="time"><?= date('H : i',$model->start_time) ?></p>
		</div>
	    <div class="form-group field-timetable-date required">
			<label class="control-label">下课时间</label>
			<p type="text" class="time"><?= date('H : i',$model->end_time) ?></p>
		</div>
		<?php echo $form->errorSummary($model); ?>
		
		<div class="form-group submit_group" >
	         <?= Html::submitButton('添加该课程时间', ['class' => 'btn btn-success submit_btn']) ?>
	    </div>
    <?php ActiveForm::end(); ?>
    </div>	
</div>

<script>
<?php $this->beginBlock('MY_VIEW_JS_END') ?>

$(document).ready(function(){ 
	
	


})	

<?php $this->endBlock(); ?>
</script>

<?php
    $this->registerJs($this->blocks['MY_VIEW_JS_END'],\yii\web\View::POS_END);
?>
