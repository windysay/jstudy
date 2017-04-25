<?php
use yii\helpers\Url;
use app\components\Help;
use app\modules\admin\models\Admin;
use app\modules\teacher\models\Teacher;
use app\modules\student\models\Student;
$this->title = 'Iperaperaへようこそ！';

$this->registerJsFile(Yii::$app->homeUrl.'js/plus/left.js',['depends' => [yii\web\JqueryAsset::className()]]);
$this->registerCssFile(Yii::$app->homeUrl.'widget/bootstrap-datetimepicker-master/css/bootstrap-datetimepicker.min.css',['depends' => [yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Yii::$app->homeUrl.'widget/bootstrap-datetimepicker-master/js/bootstrap-datetimepicker.min.js',['depends' => [yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Yii::$app->homeUrl.'widget/bootstrap-datetimepicker-master/js/locales/bootstrap-datetimepicker.zh-CN.js',['depends' => [yii\web\JqueryAsset::className()]]);
?>
<style style="text/css">
.header_nav {display:none;}
</style>

<div class="site-index" style="margin:0;">
 	<div class="top">
 	<div class="top_1200 cearfix">
 		<div class="home pull-left">
 			<a href="<?= Url::toRoute(['/site/index']) ?>">Iperaperaへようこそ！</a>
 		</div>
 		<?php if(!\Yii::$app->user->isGuest):?>
        <?php $student=Student::findOne(Yii::$app->user->id)?>
        <div class="dropdown pull-right">
		      <a class="dropdown-toggle my_site_menu_dropdown" id="dropdownMenu1" data-toggle="dropdown">
				<?= Student::memberName($student) ?>
			    <span class="caret"></span>
			  </a>
			  <ul class="dropdown-menu dropdown-menu-right" role="menu" aria-labelledby="dropdownMenu1">
			    <li role="presentation"><a role="menuitem" tabindex="-1" href="<?= Url::toRoute(['/student/site/index']) ?>">账户中心</a></li>
			    <li role="presentation"><a role="menuitem" tabindex="-1" href="<?=Url::toRoute(['/account/logout']); ?>">退出登录</a></li>
			  </ul>
		</div>
		<?php elseif(!\Yii::$app->teacher->isGuest):?>
        <?php $teacher=Teacher::findOne(Yii::$app->teacher->id)?>
        <div class="dropdown pull-right">
		      <a class="dropdown-toggle my_site_menu_dropdown" id="dropdownMenu1" data-toggle="dropdown">
				<?= $teacher['name']?$teacher['name']:$teacher['email'] ?>
			    <span class="caret"></span>
			  </a>
			  <ul class="dropdown-menu dropdown-menu-right" role="menu" aria-labelledby="dropdownMenu1">
			    <li role="presentation"><a role="menuitem" tabindex="-1" href="<?= Url::toRoute(['/teacher/site/index']) ?>">マイホーム</a></li>
			    <li role="presentation"><a role="menuitem" tabindex="-1" href="<?=Url::toRoute(['/account/teacher-logout']); ?>">ログアウト</a></li>
			  </ul>
		</div>
		<?php elseif(!\Yii::$app->admin->isGuest):?>
        <?php $admin=Admin::findOne(Yii::$app->admin->id)?>
        <div class="dropdown pull-right">
		       <a class="dropdown-toggle my_site_menu_dropdown" id="dropdownMenu1" data-toggle="dropdown">
				<?= $admin['username'] ?>
			    <span class="caret"></span>
			  </a>
			  <ul class="dropdown-menu dropdown-menu-right" role="menu" aria-labelledby="dropdownMenu1">
			    <li role="presentation"><a role="menuitem" tabindex="-1" href="<?= Url::toRoute(['/admin/site/index']) ?>">账户中心</a></li>
			    <li role="presentation"><a role="menuitem" tabindex="-1" href="<?=Url::toRoute(['/account/admin-logout']); ?>">退出登录</a></li>
			  </ul>
		</div>
		<?php else:?>
 		<div class="pull-right clearfix">
	 		<a href="<?= Url::toRoute(['/site/zeren']) ?>" class="pull-right">注册</a>
	 		<a class="pull-right">丨</a>
	 		<a href="<?= Url::toRoute(['/account/login']) ?>" class="pull-right">登录</a>
 		</div>
 		<?php endif;?>
 	</div>
 	</div>
 	<div class="middle cearfix  width_1200" style="margin:0 auto;">
 		<div class="left pull-left">
 			<img src="<?= Yii::$app->homeUrl.'images/pc/4.jpg'  ?>">
 		</div>
 		<div class="right pull-left">
 			<div class="middle_nav clearfix">
 				<a href="<?= Url::toRoute(['/site/index']) ?>" class="pull-left lia chuci_a">初次见面</a>
 				<a href="<?= Url::toRoute(['/course/index']) ?>" class="pull-left lia">课程购买</a>
 				<a href="<?= Url::toRoute(['/course/teachers']) ?>" class="pull-left lia">讲师们</a>
 				<a href="<?= Url::toRoute(['/site/benzhan']) ?>" class="pull-left lia hide">关于本站</a>
 				<a href="<?= Url::toRoute(['/site/contact']) ?>" class="pull-left lia">联系我们</a>
 				<div class="er_nav">
	              <a href="<?=Url::toRoute('site/benzhan');?>">关于本站</a>
	              <a href="<?=Url::toRoute('site/jiangshi');?>">关于讲师</a>
	              <a href="<?=Url::toRoute('site/shangke');?>">关于上课</a>
	              <a href="<?=Url::toRoute('site/xuefei');?>">关于费用</a>                           
 				</div>
 			</div>
			<div id="carousel-example-generic_my" class="carousel slide" data-ride="carousel">
				  <!-- Indicators -->
				  <ol class="carousel-indicators">
				    <?php foreach ($ppt as $pptk1=>$pptv1):?>
				    <li data-target="#carousel-example-generic_my" data-slide-to="<?= $pptk1 ?>" class="<?= $pptk1==0?'active':null ?>"></li>
				    <?php endforeach;?>
				  </ol>
				  <!-- Wrapper for slides -->
				  <div class="carousel-inner" role="listbox">
				  	 <?php foreach ($ppt as $pptk2=>$pptv2):?>
				    <div class="item <?= $pptk2==0?'active':null ?>">
				      	<img src="<?= Yii::$app->homeUrl.'images/'.$pptv2['coverurl']  ?>" title="<?= $pptv2['title'] ?>" >
				    </div>
				    <?php endforeach;?>
				  </div>
			</div>	 <!-- end slide -->
			<div class="roll_pictrue">
					<div class="pmc_top_menu_layer  pmc_top_menu_layer_fangan">
						  <ul  class="pmc_top_menu pmc_top_menu_fangan" >
						  		<?php foreach ($teachers as $k=>$v):?>
								<li class="info_block">
							 		<a class="thumbnail" href="<?= Url::toRoute(['/course/timetable','t'=>$v['id']]) ?>">
									      <img data-src="holder.js/300x200" alt="..." src="<?= Yii::$app->homeUrl.'images/'.$v['headimg']  ?>">
									      <div class="caption">
									         <h4 class="teacher_name"><?= $v['name'] ?></h4>
									         <p class="teacher_comment"><?= $v['comment'] ?></p>
									      </div>
									</a>
						     	</li>
						     	<?php endforeach;?>
						     	<div class="clear"></div>
						  </ul>
					</div>
					<div class="prev page_turn" onclick = "rotatePlayer( 'left' );"><span>《</span></div>
					<div class="next page_turn" onclick = "rotatePlayer( 'right' );"><span class="">》</span></div>
			</div><!-- end roll_pictrue-->
			<div class="common_div clearfix">
				<div class="seacher pull-left">
					  <p class="title">课程搜索</p>
					  <div class="form-group form-inline updatemy">
					    <label>上课日期</label>
					    <input class="form_date input_control" size="10" type="text" value="<?= date('Y-m-d',$tomorrow_begin) ?>" readonly="readonly">
					    <span class="caret xiaosanjiao"></span>
					  </div>
					  <div class="form-group form-inline">
					    <label>上课时间</label>
					    <select class="form_time input_control">
					    	<option value="4">全天</option>
					    	<option value="1">上午</option>
					    	<option value="2">下午</option>
					    	<option value="3">晚上</option>
					    </select>
					  </div>
					  <div class="form-group form-inline hide">
					    <label>讲师类型</label>
					    <select class="form_type input_control">
					    	<option value="">严厉型</option>
					    	<option value="">傲娇型</option>
					    	<option value="">治愈型</option>
					    </select>
					  </div>
					  <p class="btn btn-sm btn-danger search_course_btn">确认</p>
				</div>
				<div class="notice pull-left">
					 <p class="title">公告栏</p>
					 <ul>
					 	<?php foreach ($news as $v):?>
					 	<li><a href="<?= Url::toRoute(["news","id"=>$v['id']]) ?>" target="_blank"><?=date("Y-m-d",$v['createtime']).' '.Help::subtxt($v['title'],20) ?></a></li>
					 	<?php endforeach;?>
					 </ul>
				</div>
				<div class="login pull-left">
					<a class="btn btn-sm btn-danger" href="<?= Url::toRoute(['/account/teacher-login']) ?>">講師登録</a>
					<a class="btn btn-sm btn-danger" href="<?= Url::toRoute(['/account/admin-login']) ?>">管理员登录</a>
				</div>
			</div> <!-- end common_div -->
 		</div>   <!-- end right -->
 	</div>    <!-- end middle -->
</div>






 <script type="text/javascript">
<?php $this->beginBlock('MY_VIEW_JS_END') ?>
  
$(document).ready(function(){
	$('.form_date').datetimepicker({
        language:  'zh-CN',
        format: "yyyy-mm-dd",
        weekStart: 1,
        todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 2,
		minView: 2,
		forceParse: 0,
		startDate:"<?= date('Y-m-d',$tomorrow_begin) ?>",
	//	endDate:"<?= date('Y-m-d',$two_week_end) ?>",
		pickerPosition: "top-right"
    });
    $(".search_course_btn").click(function(){
		var date=$(".form_date").val();
		var time=$(".form_time").val();
		var href="<?= Url::toRoute(['/course/search']); ?>"
		window.location.href=href+"?date="+date+"&time="+time;
    })

  
  $(".middle_nav .chuci_a").hover(function(){
    $('.er_nav').slideDown(300);
  },function(){
  	$('.er_nav').hide();
  })

  $(".er_nav").hover(function(){
    $(this).show();
  },function(){
  	$(this).slideUp(300);
  })







})	

<?php $this->endBlock(); ?>
</script>
     
<?php
    $this->registerJs($this->blocks['MY_VIEW_JS_END'],\yii\web\View::POS_END);
?>