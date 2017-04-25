<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
$this->registerJsFile(Yii::$app->homeUrl.'js/LocalResizeIMG.js',['depends' => [yii\web\JqueryAsset::className()]]);
?>
<div class="account-index">
    <div class="f_top_title">修改档案资料</div>
    <div class="xm_box clearfix">
<div class="teacher-form form_basic">

    <?php $form = ActiveForm::begin(); ?>

     <?= $form->field($model, 'mobile')->textInput(['maxlength' => 11]) ?>
        
    <?= $form->field($model, 'email')->textInput(['maxlength' => 40]) ?>
    
    <?= $form->field($model, 'realname')->textInput(['maxlength' => 30]) ?>

    <?= $form->field($model, 'status')->dropDownList(['1'=>"正常使用",'0'=>'冻结账号']) ?>

    <?= $form->field($model, 'course_ticket')->textInput(['maxlength'=>6]) ?>
    
    <?= $form->field($model, 'sex')->radioList(['1'=>"男",'0'=>'女']) ?>
    <?= $form->field($model, 'qq')->textInput(['maxlength' => 30]) ?>
    <?= $form->field($model, 'skype')->textInput(['maxlength' => 30]) ?>

    <div class="submit_group form-group">
        <?= Html::submitButton($model->isNewRecord ? '登记档案' : '更新档案', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
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
