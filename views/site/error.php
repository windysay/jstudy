<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = '访问错误';
?>
<div class="site-error">
		
		<h2 class="t1"><?= Html::encode($message) ?></h2>
        <p class="return_p"><a href="javascript:history.go(-1)">返回上一页</a></p>

</div>
