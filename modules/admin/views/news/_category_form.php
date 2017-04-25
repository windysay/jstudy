<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\admin\models\MaterialCategory;

?>

<div class="material-category-form form_basic">

    <?php $form = ActiveForm::begin(); ?>

	<div class="form-group field-materialcategory-fid required has-success" data-original-title="" title="">
	  <label class="control-label" for="materialcategory-fid" data-original-title="" title="">父分类</label>
	  <select class="form-control category_select" name="MaterialCategory[fid]" id="MaterialCategory_fid">
	       <option value="0">根目录</option> 
           <?=MaterialCategory::findChildOption('',0);?>
	   </select>
	  </div>
    <?= $form->field($model, 'name')->textInput(['maxlength' => 30]) ?>

    <?= $form->field($model, 'sort')->textInput(['maxlength' => 8]) ?>

    <div class="form-group submit_group" >
        <?= Html::submitButton($model->isNewRecord ? '保存' : '确认修改', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
 
    
    <?php ActiveForm::end(); ?>

</div>


<script>
    <?php $this->beginBlock('MY_VIEW_JS_END') ?>
    
    $(document).ready(function(){
     //当前分类所属分类
     var fcatid="<?=$model['fid']; ?>"; 
     $(".category_select option[value="+fcatid+"]").attr("selected","selected")
    //默认显示当前区域的课件
    })
 
    <?php $this->endBlock(); ?>
</script>
<?php
    $this->registerJs($this->blocks['MY_VIEW_JS_END'],\yii\web\View::POS_END);
?>

 