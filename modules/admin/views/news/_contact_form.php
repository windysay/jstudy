<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use app\modules\admin\models\MaterialCategory;
   
$this->registerJsFile(Yii::$app->homeUrl.'widget/ueditor/ueditor.config.js',['depends' => [yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Yii::$app->homeUrl.'widget/ueditor/ueditor.all.min.js',['depends' => [yii\web\JqueryAsset::className()]]);
 
?>

<div class="material-photo-form form_basic">
    <?php $form = ActiveForm::begin(); ?>
    	<?php // echo $form->errorSummary($model); ?>
	
    <?= $form->field($model, 'title')->textInput(['maxlength' => 40]) ?>
    <?= $form->field($model, 'author')->textInput(['maxlength' => 20]) ?>
	<?= $form->field($model, 'content')->textarea(['class'=>'content_textarea']) ?>	
    <div class="form-group submit_group" >
        <?= Html::submitButton($model->isNewRecord ? '保存' : '确认修改', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
 
    <?php ActiveForm::end(); ?>

</div>

<script>
    <?php $this->beginBlock('MY_VIEW_JS_END') ?>
    

    
    var editor1 = UE.getEditor('materialphoto-content',{
	    	initialFrameWidth : 700,
	        initialFrameHeight: 300,
	        scaleEnabled:true
        });//百度编辑器
    
    var dir="<?=Yii::$app->homeUrl.'images/'; ?>"
    editor1.ready(function() {
 	editor1.execCommand('serverparam', {
         'dir':dir,
     });
  });


    
	$(document).ready(function(){
 

  
	})//////
    <?php $this->endBlock(); ?>
</script>
    
    
<?php
    $this->registerJs($this->blocks['MY_VIEW_JS_END'],\yii\web\View::POS_END);
 
?>
 
