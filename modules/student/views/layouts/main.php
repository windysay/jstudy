<?php 
use yii\helpers\Url;
use yii\widgets\Block;
use app\components\AreaDecorator;
use app\modules\student\components\StudentMenu;
use app\modules\student\components\StudentCheckAccess;

$menu=new StudentMenu;
$menuLeftCon=$menu->menuLeft(1);//左侧菜单
 
?>

<?php  AreaDecorator::begin(['viewFile'=>'@app/views/layouts/main.php'])?>

 <div class="layout_member_main clearfix">
   <div class="layout_nav_main">
          <?php foreach($menuLeftCon as $k=>$v): ?>
             <ul class="layout_m_menu">
                    <li class="top"><a href="<?= Url::toRoute([$v.'/index'])?>"><?php echo $menu->menuLeftName($v); ?></a></li>
                    <?php  foreach($menu->menuList($v) as $k2=>$v2): ?>
                    <?php if($k2==$this->context->action->id&&$this->context->id==$v): ?>
                    <li class="menu_left_active_index"><span class="ge_border"></span><a href="<?= Url::toRoute([$v.'/'.$k2]) ?>"><?php echo $v2; ?></a></li>
                    <?php else: ?>
                   <li class=""><a href="<?= Url::toRoute([$v.'/'.$k2]) ?>"><?php echo $v2; ?></a></li>
                   <?php endif; ?>
                  <?php endforeach; ?>
               </ul>
           <?php endforeach; ?>
    
 
   </div>  
    <div class="layout_info_main">
       <?=$content; ?>
    </div>  
  </div> <!-- member_main -->
 
<?php  AreaDecorator::end();?>
<script>
$(document).ready(function(){
	 /** Yii Flash提示*/
	 var warn_message="<?=Yii::$app->session->getFlash('success') ?>";
	 if(warn_message){
		warn(warn_message,1)
	}
})
 
</script>

 

