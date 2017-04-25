<?php
use yii\helpers\Html;
use yii\helpers\Url;
use \app\modules\admin\models\MaterialCategory;


?>
<div class="material-category">
   <p class="create_p">
       <?= Html::a('新建分类', ['add-category'], ['class' => 'btn btn-success']) ?>
  </p>
  
<div class="material_category_box my_category">
<table class="table table-bordered table-hover w_table"  id="w_table">
<thead><tr>
<!--<th class="category_tb_shouzhan_th">收展</th>-->
<th>栏目名称</th>
<th>栏目排序</th>
<th>操作</th>
</tr></thead>
<tbody>
       <?=MaterialCategory::showCategoryLists('<span class="cat_gexian"></span>',0);?>
</tbody>

</table>
 </div>
 
</div>
 
 
 <script>
    <?php $this->beginBlock('MY_VIEW_JS_END') ?>


    
    function ok_btn(obj){
    	var id=$(obj).attr("data-id")
    	setTimeout(function(){window.location.reload()},1500)  
        $.ajax({//一个Ajax过程
    		   type:"POST", //以post方式与后台沟通 
    		   url:"<?=Url::toRoute("ajax-delete-category"); ?>", 
    		   dataType:'json',//从php返回的值以 JSON方式 解释
    		   data:{"id":id},
    		   cache:false,
    		   success:function(msg){//如果调用php成功,注意msg是返回的对象，这个你可以自定义 
    			   window.location.reload()
    		   }
    		  })//一个Ajax过程  
    }
     

    $(document).ready(function(){
     
     $(".caozuo_delete_a").click(function(){
           var id=  $(this).attr('data-id')
           deleteAlert(id,'确定删除此分类')
     })

      $("#w_table tr").click(function(){
          var topidname=$(this).attr("data-topid")
          var f_id=$(this).attr("data-id")
           if(topidname==0){
         	  var status= $(this).attr('data-status')
         	  if(!status){
                  $(this).attr('data-status','w_tr_close')
                  $(this).find(".tdww0").attr('class','tdww02')
                  $(".ww"+f_id).hide()
              }else if(status=='w_tr_close'){
                  $(this).attr('data-status','w_tr_open')
                  $(this).find(".tdww02").attr('class','tdww0')
                  $(".ww"+f_id).show()
              }else{
                  $(this).attr('data-status','w_tr_close')
                  $(this).find(".tdww0").attr('class','tdww02')
                  $(".ww"+f_id).hide()
             }
         }       
      })
     
      var warn_message="<?=Yii::$app->session->getFlash('success') ?>"
          if(warn_message)    
               warn(warn_message,1) 

               
    })
     
 

 
 
    <?php $this->endBlock(); ?>
</script>
<?php
    $this->registerJs($this->blocks['MY_VIEW_JS_END'],\yii\web\View::POS_END);
?>
 
