<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use app\modules\admin\models\MaterialCategory;
   
$this->registerJsFile(Yii::$app->homeUrl.'js/LocalResizeIMG.js',['depends' => [yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Yii::$app->homeUrl.'widget/ueditor/ueditor.config.js',['depends' => [yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Yii::$app->homeUrl.'widget/ueditor/ueditor.all.min.js',['depends' => [yii\web\JqueryAsset::className()]]);
 
?>

<div class="material-photo-form form_basic">
    <?php $form = ActiveForm::begin(); ?>
    	<?php echo $form->errorSummary($model); ?>
	<div class="form-group field-materialphoto-catid required has-success" data-original-title="" title="">
		<label class="control-label" for="materialphoto-catid" data-original-title="" title="">所属分类</label>
	   <select class="form-control category_select" name="MaterialPhoto[catid]" id="materialphoto-catid">
           <?=MaterialCategory::findChildOptionNo('',0);?>
	   </select>
	</div>
    <?= $form->field($model, 'title')->textInput(['maxlength' => 40]) ?>
    <?= $form->field($model, 'author')->textInput(['maxlength' => 20]) ?>
    
    <div class="form-group field-materialphoto-coverurl required form_my_coverurl">
		<label class="control-label" for="store-coverurl">封面图片</label>
		 <div class="coverurl">
		   <div class="img show_cover">
		   	<?php if($model['coverurl']):?>
		   		<img class="upload_img_box_img"  src="<?= Yii::$app->homeUrl.'images/'.$model['coverurl']; ?>"  />
		   	<?php endif;?>
		   </div>
		   <div class="button"><?= Html::button('上传图片',['class' => 'btn btn-success btn-sm','id'=>'file_upload']) ?>
           </div>
           <div class="tishi_hit">图片建议大小: 900*500</div>
		 </div>
		 <div class="help-block" data-original-title="" title=""></div>
          <input type="file"   name="userfile"  id="file_upload_input"    class="file_btn"  style="width:1px;height:1px;filter:alpha(Opacity=0);-moz-opacity:0;opacity:0;position:absolute;top:0;left:0;z-index:-3;" value="图片+"/>
		  <input type="text" id="materialphoto-coverurl" class="form-control hide" name="MaterialPhoto[coverurl]" value="<?=$model['coverurl']?$model['coverurl']:null ?>">
	</div>
    
    <?= $form->field($model, 'show_cover')->DropdownList($showcover_list,['data-toggle'=>'tooltip','data-placement'=>'top','title'=>'在图文消息中，是否显示封面图片']) ?>
    <div class="description_box" style="height:120px;">
    <?= $form->field($model, 'description')->textarea() ?>
    </div>
	<?= $form->field($model, 'content')->textarea(['class'=>'content_textarea']) ?>	
    <div class="form-group submit_group" >
        <?= Html::submitButton($model->isNewRecord ? '保存' : '确认修改', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
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
        $("#materialphoto-coverurl").val(dirurl);
    }
    
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
 
        $("#cmbProvince,#cmbCity,#cmbArea").change(function(){
    		var d_province=$("#cmbProvince").val()
    		var d_city=$("#cmbCity").val()
    		var d_country=$("#cmbArea").val()
            $.ajax({
      	 	     url: "<?=Url::toRoute('ajax-district-list'); ?>",
                type:'POST',
                data:{'d_province':d_province,'d_city':d_city,'d_country':d_country},
                dataType: 'json',
                success: function(data){
             	   $("#store-district_id").html('')
             	   var html=''
                   for(var i=0;i<data.length;i++){
                	   html+='<option value="'+data[i][0]+'">'+data[i][1]+'</option>'
                   }
                   $("#store-district_id").html(html)
               }
               })
            }); 
 
     $("#file_upload").click(function(){
          $("#file_upload_input").click()
     })
 
     $('input:file').localResizeIMG({
         width:700,
         quality: 0.8,
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
  
		})//////
    <?php $this->endBlock(); ?>
</script>
    
    
<?php
    $this->registerJs($this->blocks['MY_VIEW_JS_END'],\yii\web\View::POS_END);
 
?>
 
