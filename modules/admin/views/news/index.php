<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use app\modules\admin\models\MaterialCategory;
use app\components\Help;

?>
<div class="wechat-material">
 	<div class="top_tool_div clearfix">
 		<?php if($this->context->action->id=='index'):?>
   	 	<p class="pull-left"><?= Html::a('添加素材', ['add-material'], ['class' => 'btn btn-success']) ?> </p>
		<div class="input-group  select_store_div pull-right">
		        <button type="button" class="btn btn-default dropdown-toggle show_time" data-toggle="dropdown"><?=$catname?>&nbsp;&nbsp;&nbsp;<span class="caret"></span></button>
			  <ul class="dropdown-menu dropdown-menu-right category_list" role="menu">
			    <li data-value="all"><a href="<?=Url::toRoute("index");?>">所有素材</a></li>
			     <?=MaterialCategory::findChildOption('',0,2); ?>
			  </ul>
		</div><!-- /input-group -->
		<?php else:?>
		<p class="pull-left"><?= Html::a('添加幻灯片', ['add-ppt'], ['class' => 'btn btn-success']) ?> </p>
		<?php endif;?>
	</div>

<div class="course-index">
 		<table class="table table-hovered course_list">
 			<thead>
 			 	<tr>
 			 		<td></td>
 			 		<td>所属栏目</td>
 			 		<td>标题</td>
 			 		<td>作者</td>
 			 		<td>创建时间</td>
 			 		<td>操作</td>
 			 	</tr>
 			 </thead>
 			 <tbody>
 			 	<?php if(!$models):?>
 			 	<tr><td colspan="6" id="no_data_remind">暂无数据</td></tr>
 			 	<?php else :?>
 			 	<?php foreach ($models as $k=>$v):?>
 			 	<tr id="box<?=$v['id'] ?>">
 			 		<td><?= $k+1 ?></td>
 			 		<?php $catetory=MaterialCategory::findOne(['id'=>$v['catid']]);?>
 			 		<td><?= $catetory['name']?></td>
 			 		<td title="<?=$v['title']?>"><?= Help::subtxt($v['title'],10)?></td>
 			 		<td><?= $v['author']?></td>
 			 		<td><?= date('Y-m-d h:i',$v['createtime']) ?></td>
 			 		<td><a href="<?=Url::toRoute(["/site/news",'id'=>$v['id']]) ?>">预览</a> / <?=Html::a('编辑',['update-material','id'=>$v['id']]).' / '?><a href="javascript::void(0)" data-id="<?=$v['id'] ?>" class="caozuo_delete_a">删除</a></td>
 			 	</tr>
 			 	<?php endforeach;?>
 			 	<?php endif;?>
 			 </tbody>
 		</table>
 		<div class="fenye_main pull-left clearfix" style="width:100%;"> 
	          <?= LinkPager::widget(['pagination' => $pages]); ?>
	          <div class="count_box">记录总数: <?=$count; ?> 条</div>
	     </div>
 		
</div><!-- site-index -->

<div class="material_manage_box box_list_main clearfix hide">

  <?php foreach ($models as $k=>$v): ?>
   <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 w_box" id="<?php echo 'box'.$v->id; ?>">
      <div class="box">
      <p class="title_text" ><a href="<?=Url::toRoute(['/material/show','id'=>$v->id]); ?>" target="_blank" class="top_a"><?=$v->title; ?></a></p>
      <img  class="img-responsive cover_img"  src="<?=Yii::$app->homeUrl.'images/'.$v->coverurl; ?>" />
      <div class="caozuo_box">
      <?= Html::a('编辑', ['update-material?id='.$v->id], ['class' => 'pull-left']) ?>
      <a data-id="<?php echo $v->id; ?>"  class="pull-right caozuo_delete_a"   href="javascript:void()">删除</a>
      <a data-id="<?php echo $v->id; ?>"  class="pull-right caozuo_copy_a"   href="javascript:void()">复制链接</a>
      </div>
      </div>
  </div>  
 <?php endforeach; ?>
 
</div><!-- product_main -->
 
 
</div>

<script>
    <?php $this->beginBlock('MY_VIEW_JS_END') ?>

    function ok_btn(obj){
    	var id=$(obj).attr("data-id")
        $.ajax({//一个Ajax过程
    		   type:"POST", //以post方式与后台沟通 
    		   url:"<?=Url::toRoute("ajax-delete-material"); ?>", 
    		   dataType:'json',//从php返回的值以 JSON方式 解释
    		   data:{"id":id},
    		   cache:false,
    		   success:function(msg){//如果调用php成功,注意msg是返回的对象，这个你可以自定义 
					if(msg==1){
	    				 $("#box"+id).fadeOut();
	    				 warn("删除成功",1) 
					}
    		   }
    		  })//一个Ajax过程  
    }

    $(document).ready(function(){

         $(".category_list li").click(function(){
              var catid=$(this).attr('data-value')
              var url="<?=Url::toRoute('index?catid='); ?>"
              window.location.href=url+catid
          })
 
    	   $(".caozuo_copy_a").click(function(){
    		   var id=$(this).attr("data-id")
    		   var url2="<?=Url::toRoute("material/show"); ?>"
    		   var url='?id='+url2+id   
    		   var tishi='复制此链接：'+url
    	       showCenterBox(tishi)
    	   })
    	   
    	     $(".close_copy_box_span").click(function(){
    	         $("#copy_lianjie_box").hide()
    	     })

    	 
/*        $(".w_box .box").hover(function(){//操作栏
              $(this).find(".caozuo_box").slideDown(300)
        },function(){
             $(this).find(".caozuo_box").slideUp(300)
       }) */
 
       $(".caozuo_delete_a").click(function(){
             var id=  $(this).attr('data-id')
             deleteAlert(id,'确定删除此素材')
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