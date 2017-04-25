<?php 
use yii\helpers\Url;
use app\components\AreaDecorator;
use yii\widgets\Block;
use app\modules\teacher\components\TeacherMenu;

$menu=new TeacherMenu;
$menuLeftCon=$menu->menuLeft(1);//左侧菜单
?>

<?php AreaDecorator::begin(['viewFile'=>'@app/views/layouts/main.php'])?>

 <div class="layout_member_main clearfix">
   <div class="layout_nav_main">
          <?php foreach($menuLeftCon as $k=>$v): ?>
             <ul class="layout_m_menu">
                    <li class="top"><a href="<?php echo Yii::$app->homeUrl.'teacher/'.$v; ?>"><?php echo $menu->menuLeftName($v); ?></a></li>
                    <?php  foreach($menu->menuList($v) as $k2=>$v2): ?>
                    <?php if($k2==$this->context->action->id&&$this->context->id==$v): ?>
                    <li class="menu_left_active_index"><span class="ge_border"></span><a href="<?php echo Yii::$app->homeUrl.'teacher/'.$v.'/'.$k2; ?>"><?php echo $v2; ?></a></li>
                    <?php else: ?>
                   <li class=""><a href="<?php echo Yii::$app->homeUrl.'teacher/'.$v.'/'.$k2; ?>"><?php echo $v2; ?></a></li>
                   <?php endif; ?>
                  <?php endforeach; ?>
               </ul>
           <?php endforeach; ?>
    
 
   </div>  
    <div class="layout_info_main">
       <?=$content; ?>
    </div>  
  </div> <!-- member_main -->

<?php AreaDecorator::end();?>
<script type="text/javascript">
	   $(document).ready(function(){
			
		})
</script>

 

