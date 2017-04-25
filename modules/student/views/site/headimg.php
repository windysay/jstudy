<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use app\modules\student\models\Student;


$this->title = '头像信息';
$this->registerJsFile(Yii::$app->homeUrl.'js/LocalResizeIMG.js',['depends' => [yii\web\JqueryAsset::className()]]);
?>
<script>
	 <?php $this->beginBlock('MY_VIEW_JS_END') ?>
	 
	 	var imgpath="<?= Yii::$app->urlManager->baseUrl.'/images/' ?>";
	 	
		$(document).ready(function(){
		     $('input:file').localResizeIMG({
		         width:800,
		         quality: 0.7,
		         success: function (result) {
		    		  $(".save_btn").attr("disabled","disabled")
		             var img = new Image();
		             img.src = result.base64;
		             $.ajax({
		       	 	     url: "<?=Url::toRoute('ajax-upload-headimg'); ?>",
		                 type:'POST',
		                 data:{data:result.clearBase64},
		                 dataType: 'json',
		                 //timeout: 1000,
		                 error: function(){
		                     alert('上传失败');
		                },
		                 success: function(data){
		 					 $(".headimg").attr('src',imgpath+data);
		 					 $(".headimg").attr('data-headimg',data);
		 					 $(".save_btn").attr("disabled",false)
		                }
		             }); 
		         }
		     });
				
		 $(".headimg").click(function(){
		  	$("#file_upload_input").click();
		 })
		 $(".save_btn").click(function(){
		  	var headimg=$(".headimg").attr("data-headimg");
		  	if(!headimg){
				warn("请先点击头像上传",0);
		  	}else{
				ajax_save_headimg(headimg);
		  	}
		 })

	})//////

	function ajax_save_headimg(headimg){
		$.ajax({//一个Ajax过程
			   type:"POST", //以post方式与后台沟通 
			   url:"<?= Url::toRoute('ajax-save-headimg') ?>", 
			   dataType:'json',//从php返回的值以 JSON方式 解释
			   data:{"headimg":headimg},
			   cache:false,
			   success:function(msg){//如果调用php成功,注意msg是返回的对象，这个你可以自定义 
					if(msg==1){
						 warn('保存成功',1);
					}else{
						 warn('保存失败',0);
					}
			   },
			   error:function(){
				   warn('保存失败',0);
			   }
		})//一个Ajax过程  
	}
 	
	<?php $this->endBlock(); ?>
</script>
	
<?php
    $this->registerJs($this->blocks['MY_VIEW_JS_END'],\yii\web\View::POS_END);
?>	

<div class="site-headimg">
	
	 <p class="f_top_title clearfix">
	     <?= Html::encode($this->title) ?>
	 	 <span class="r_box info_type_a pull-right">
    	  	 <a class="w_link_a"  href="<?=Url::toRoute(['info']); ?>">基本信息</a>
    	  	 <span class="ge">|</span>
	  	 <a class="w_link_a _index_a"  href="<?=Url::toRoute(['headimg']); ?>">头像信息</a>
	  	 </span>
	 </p>
   
   <div class="headimg_main">
	   <img class="headimg" src="<?= Yii::$app->homeUrl.'images/'.($student['headimg']?$student['headimg']:"basic/basic_header.jpg") ?>" title=""/>	   
	   <div class="name"><?= $name ?></div>
	   	<input type="file" name="userfile" id="file_upload_input" class="file_btn" style="width: 1px; height: 1px; filter: alpha(Opacity =0); -moz-opacity: 0; opacity: 0; position: absolute; top: 0; left: 0; z-index: -3;" value="图片+" />
    </div>
    <div class="btn_div"><button class="btn btn-success save_btn">保存头像</button></div>
 

</div>
