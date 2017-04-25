<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->registerJsFile(Yii::$app->homeUrl.'js/LocalResizeIMG.js',['depends' => [yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Yii::$app->homeUrl.'widget/ueditor/ueditor.config.js',['depends' => [yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Yii::$app->homeUrl.'widget/ueditor/ueditor.all.min.js',['depends' => [yii\web\JqueryAsset::className()]]);
?>

<div class="goods-form form_basic">

    <?php $form = ActiveForm::begin(); ?>
    <div class="info_div" style="">
		<div class="info_div_head">
			<span>基本信息</span>
		</div>
    <?= $form->field($model, 'name')->textInput(['maxlength' => 50]) ?>
    <?= $form->field($model, 'description')->textArea(['maxlength' => 200,'data-toggle'=>"tooltip",'data-placement'=>"top",'data-original-title'=>"空格代替换行,方便前台数据显示"]) ?>
    <?= $form->field($model, 'course_ticket')->textInput(['maxlength' => 10]) ?>
	<div class="price_div" >	      
    <?= $form->field($model, 'price')->textInput(['maxlength' => 10]) ?>
    <?= $form->field($model, 'promotion_price')->textInput(['maxlength' => 10]) ?>
    </div>
	
	<div class="form-group field-coursemeal-coverurl required form_my_coverurl">
		<label class="control-label" for="coursemeal-coverurl">套餐封面</label>
		 <div class="coverurl">
		   <div class="img show_cover">
		    <?php if($model['coverurl']):?>
		   	<img class="upload_img_box_img"  src="<?= Yii::$app->homeUrl.'images/'.$model['coverurl']; ?>"  />
		   	<?php endif;?>
		   </div>
		   <div class="button"><?= Html::button('上传图片',['class' => 'btn btn-success btn-sm','id'=>'file_upload']) ?>
           </div><label style="width:150px;font-size: 12px;">图片尺寸最好是600x400</label>
		 </div>
		 
          <input type="file"   name="userfile"  id="file_upload_input"    class="file_btn"  style="width:1px;height:1px;filter:alpha(Opacity=0);-moz-opacity:0;opacity:0;position:absolute;top:0;left:0;z-index:-3;" value="图片+"/>
		  <input type="hidden" id="course-coverurl" class="form-control" name="CourseMeal[coverurl]" value="<?= $model['coverurl']?$model['coverurl']:null ?>">
	</div>

	<?php // $form->field($model, 'content')->textarea(['class'=>'content_textarea']) ?>	
	
	<div class="form-group submit_group" >
         <?= Html::submitButton($model->isNewRecord ? '保存' : '保存更改', ['class' => $model->isNewRecord ? 'btn btn-success submit_btn' : 'btn btn-primary submit_btn']) ?>
    </div>
	</div>

    <?php ActiveForm::end(); ?>
    	
</div>

<script>
<?php $this->beginBlock('MY_VIEW_JS_END') ?>
function showCover(dirurl){
        var url="<?= Yii::$app->homeUrl; ?>"+'images/'+dirurl;
	//	var url="http://dn-windysay.qbox.me/"+dirurl+'-thumb';
        $(".coverurl .show_cover").html('');
        $(".coverurl .show_cover").append('<img class="upload_img_box_img" src="'+url+'"  />');
        $("#course-coverurl").val(dirurl);
    }
$(document).ready(function(){ 
	//var editor1 = UE.getEditor('coursemeal-content');
	
    $("#file_upload").click(function(){
        $("#file_upload_input").click()
    })

$('input:file').localResizeIMG({
    width:600,
    quality: 0.9,
    success: function (result) {
		  $("#file_upload").text('正在上传图片')
		  $("#file_upload").attr("disabled","disabled")
        var img = new Image();
        img.src = result.base64;
       // $('body').append(img);
        //console.log(result);
       $.ajax({
  	 	     url: "<?=Url::toRoute('ajax-upload-coverurl'); ?>",
            type:'POST',
            data:{data:result.clearBase64},
            dataType: 'json',
            //timeout: 1000,
            error: function(){
                alert('上传失败');
           	 $("#file_upload").removeAttr("disabled"); 
				 $("#file_upload").text("上传图片"); 	
           },
            success: function(data){
                showCover(data)
           	 $("#file_upload").removeAttr("disabled"); 
				 $("#file_upload").text("上传图片"); 	
           }
        }); 
    }
});
})	

<?php $this->endBlock(); ?>
</script>

<?php
    $this->registerJs($this->blocks['MY_VIEW_JS_END'],\yii\web\View::POS_END);
?>
