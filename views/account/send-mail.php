<?php 
use yii\helpers\Url;
?>
 
<div class="site-send-mail">
 
 
  发送邮件
  
<?php if($model) echo '发送成功';?>
 
 
</div>
 

 <script type="text/javascript">
    <?php $this->beginBlock('MY_VIEW_JS_END') ?>
//////////////////////
$(document).ready(function(){
 
 
////////////////////////
})
    <?php $this->endBlock(); ?>
</script>
<?php
    $this->registerJs($this->blocks['MY_VIEW_JS_END'],\yii\web\View::POS_END);
?>
