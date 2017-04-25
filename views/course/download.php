<?php
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use yii\helpers\Html;
use app\components\Help;
use yii\widgets\LinkPager;
$this->title = "自由教材";
$this->params['breadcrumbs'][] = $this->title;
AppAsset::register($this);
?>
<center>
<div class="course-download clearfix">
	<ul class="material_download clearfix">
	<?php foreach ($model as $v):?>
		<li class="pull-left">
			<div class="img pull-left"><img alt="" src="<?=Yii::$app->homeUrl.'images/'.$v['coverurl'] ?>"></div>
			<div class="title pull-left"><?=Html::encode($v['title']) ?></div>
			<div class="description pull-left" title="<?=$v['description'] ?>"><?= Html::encode(Help::subtxt($v['description'],40)) ?></div>
			<a target="_blank" href="<?=Url::toRoute(["file-download","link"=>$v['link']])?>" class="btn btn_download">下载</a>
		</li>
	<?php endforeach;?>
	</ul>

 	<div class="clearfix fenye_main"> 
	      <?= LinkPager::widget(['pagination' => $pages]); ?>
	       <div class="count_box">记录总数: <?=$count; ?> 条</div>
	 </div>	

</div>
</center>