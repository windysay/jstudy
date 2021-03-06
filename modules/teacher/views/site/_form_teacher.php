<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
$this->registerJsFile(Yii::$app->homeUrl.'js/LocalResizeIMG.js',['depends' => [yii\web\JqueryAsset::className()]]);
$this->title="修改档案资料";
?>
<div class="account-index">
    <div class="f_top_title">修改档案资料</div>
    <div class="xm_box clearfix">
<div class="teacher-form form_basic">

    <?php $form = ActiveForm::begin(); ?>
	<?php if($model->isNewRecord):?>
    <?= $form->field($model, 'email')->textInput(['maxlength' => 40,'disabled'=>$model->isNewRecord?false:'disabled']) ?>
    
    <?= $form->field($model, 'password')->passwordInput(['maxlength' => 64]) ?>
    <?php else:?>
    <?= $form->field($model, 'email')->textInput(['maxlength' => 40,'disabled'=>$model->isNewRecord?false:'disabled']) ?>
    
    <?= $form->field($model, 'name')->textInput(['maxlength' => 30]) ?>

    <?= $form->field($model, 'sex')->radioList(['1'=>"男",'0'=>'女']) ?>


    <?= $form->field($model, 'skype')->textInput(['maxlength' => 30]) ?>
    <div class="form-group field-teacher-headimg required form_my_coverurl">
		<label class="control-label" for="teacher-headimg">讲师照片</label>
		 <div class="coverurl">
		   <div class="img show_cover">
		    <?php if($model['headimg']):?>
		   	<img class="upload_img_box_img"  src="<?= Yii::$app->homeUrl.'images/'.$model['headimg']; ?>"  />
		   	<?php endif;?>
		   </div>
		   <div class="button"><?= Html::button('上传图片',['class' => 'btn btn-success btn-sm','id'=>'file_upload']) ?>
           </div><label style="width:150px;font-size: 12px;">图片尺寸最好是600x400</label>
		 </div>
		 
          <input type="file"   name="userfile"  id="file_upload_input"    class="file_btn"  style="width:1px;height:1px;filter:alpha(Opacity=0);-moz-opacity:0;opacity:0;position:absolute;top:0;left:0;z-index:-3;" value="图片+"/>
		  <input type="hidden" id="course-coverurl" class="form-control" name="Teacher[headimg]" value="<?= $model['headimg']?$model['headimg']:null ?>">
	</div>

    <?= $form->field($model, 'info')->textArea(['maxlength' => 200]) ?>
	<?php endif;?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '登记讲师' : '更新资料', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>
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
