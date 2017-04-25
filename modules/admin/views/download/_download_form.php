<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
$this->registerJsFile(Yii::$app->homeUrl.'js/LocalResizeIMG.js',['depends' => [yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Yii::$app->homeUrl.'widget/uploadify/jquery.uploadify.js',['depends' => [yii\web\JqueryAsset::className()]]);
$this->registerCssFile(Yii::$app->homeUrl.'widget/uploadify/uploadify.css',['depends' => [yii\web\JqueryAsset::className()]]);
?>
<div class="account-index">
    <div class="f_top_title"><?= $model->isNewRecord?'上传资料':'修改资料'?></div>
    <div class="xm_box clearfix">
<div class="teacher-form form_basic">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => 100])?>
    
    <?= $form->field($model, 'description')->textArea(['maxlength' => 200]) ?>

    <div class="form-group field-teacher-headimg required form_my_coverurl clearfix">
		<label class="control-label" for="material-download-coverurl">资源封面</label>
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
		  <input type="hidden" id="course-coverurl" class="form-control" name="MaterialDownload[coverurl]" value="<?= $model['coverurl']?$model['coverurl']:null ?>">
	</div>
	<div class="form-group field-teacher-headimg required clearfix">
		<label class="control-label pull-left" for="material-download-link">文件上传</label>
		<input id="file_upload_document" name="file_upload_document" type="file" multiple="true">
		<input type="hidden" id="meterialdown-link" class="form-control" name="MaterialDownload[link]" value="<?= $model['link']?$model['link']:null ?>">
	<?php if($model['link']):?>
	<div id="SWFUpload" class="uploadify-queue-item">
		<span class="fileName"><?= $model['link']?></span>
	</div>
	<?php endif;?>
	</div>
    <div class="submit_group form-group">
        <?= Html::submitButton($model->isNewRecord ? '上传资料' : '更新资料', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>
</div>
<script>
<?php $this->beginBlock('MY_VIEW_JS_END') ?>
function showCover(dirurl){
        var url="<?= Yii::$app->homeUrl; ?>"+'images/'+dirurl;
        $(".coverurl .show_cover").html('');
        $(".coverurl .show_cover").append('<img class="upload_img_box_img" src="'+url+'"  />');
        $("#course-coverurl").val(dirurl);
    }
    
$(document).ready(function(){ 
	$('#file_upload_document').uploadify({
		'formData'     : {
			'timestamp' : '<?php echo time();?>',
			'token'     : '<?php echo md5('unique_salt' . time());?>'
		},
        'swf'      : '<?=Yii::$app->homeUrl.'widget/uploadify/' ?>uploadify.swf',
        'uploader' : '<?=Yii::$app->homeUrl.'widget/uploadify/' ?>uploadify.php',
        'auto'          : true,
        'multi'         : false,
        'removeCompleted':false,
        'fileTypeExts'  : '*.jpg;*.jpge;*.gif;*.png;*.pdf;*.doc;*.docx;*.ppt;*.pptx;*.xsl;*.xlsx;*.zip;*.rar;*.txt;*.jnt',
        'onUploadSuccess':function(file,data,response){
        	$("#SWFUpload").hide();
        	$("#meterialdown-link").val(data);
        },
        //加上此句会重写onSelectError方法【需要重写的事件】
        'overrideEvents': ['onSelectError', 'onDialogClose'],
        //返回一个错误，选择文件的时候触发
        'onSelectError':function(file, errorCode, errorMsg){
            switch(errorCode) {
                case -110:
                    alert("文件 ["+file.name+"] 大小超出系统限制的大小！");
                    break;
                case -120:
                    alert("文件 ["+file.name+"] 大小异常！");
                    break;
                case -130:
                    alert("文件 ["+file.name+"] 类型不正确！");
                    break;
            }
        },
//        'cancelImg'     : '${pageContext.request.contextPath}/js/uploadify/uploadify-cancel.png',
    });
	
    $("#file_upload").click(function(){
        $("#file_upload_input").click()
    })
    
$('#file_upload_input').localResizeIMG({
    width:600,
    quality: 0.9,
    success: function (result) {
		  $("#file_upload").text('正在上传图片')
		  $("#file_upload").attr("disabled","disabled")
        var img = new Image();
        img.src = result.base64;
       $.ajax({
  	 	     url: "<?=Url::toRoute('ajax-upload-coverurl'); ?>",
            type:'POST',
            data:{data:result.clearBase64},
            dataType: 'json',
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
