<?php
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use yii\helpers\Html;
$this->title = $model['title'];
$this->params['breadcrumbs'][] = $category_name;
AppAsset::register($this);
?>
<center>
<div class="site-news  clearfix">
	<div class="left pull-left">
		<ul>
		<?php foreach ($newsAll as $k=>$v):?>
		<li class="news_title <?= isset($_GET['id'])&&$v['id']==$_GET['id']?'active':null ?>"><a href="<?= Url::toRoute(['news','id'=>$v['id']]) ?>"><?= date('m-d',$v['createtime']).'&nbsp;&nbsp;'.$v['title'] ?></a></li>
		<?php endforeach;?>
		</ul>
	</div>
	<div class="right pull-right">
		<div class="title"><?=Html::encode($model['title']) ?></div>
		<div class="author"><?= $model['author']?'作者：'.Html::encode($model['author']).'&nbsp;&nbsp;&nbsp;':null ?> <?= date('Y-m-d h:i',$model['createtime']) ?></div>
		<div class="content"><?=$model['content'] ?></div>
	</div>
</div>
</center>