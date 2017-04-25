<?php
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use yii\helpers\Html;
$this->title = '联系我们';
$this->params['breadcrumbs'][] = $this->title;
AppAsset::register($this);
?>
<center>
<div class="site-news  clearfix">
	<div class="left pull-left">
		<ul>
		<li class="news_title active"><a href="<?= Url::toRoute(['contact']) ?>">联系我们</a></li>
		</ul>
	</div>
	<div class="right pull-right">
		<div class="title"><?=Html::encode($model['title']) ?></div>
		<div class="author"><?= $model['author']?'作者：'.Html::encode($model['author']).'&nbsp;&nbsp;&nbsp;':null ?> <?= date('Y-m-d h:i',$model['createtime']) ?></div>
		<div class="content"><?=$model['content'] ?></div>
	</div>
</div>
</center>