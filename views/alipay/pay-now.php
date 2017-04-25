<?php 
use yii\helpers\Url;
use common\extensions\alipay\AlipayNotify;
$this->title='支付宝支付';
?>

<div class="pay-now">
 <div class="header_main">
  <div class="head_box">
	   <div class="load_box box">
	       <span class="yuan"></span>
	       <span class="yuan"></span>
	       <span class="yuan"></span>
	   </div>
  </div>
   
   <h1 class="title">支付处理中</h1>
 </div>
 <?php 
 
$m=new \app\components\alipay\AlipayNotify();
$bb=$m->ceshi();
print_r($bb);
echo '<br />';
 echo '<br />';
 echo 'url='.$url;
 ?>
  
   </div>