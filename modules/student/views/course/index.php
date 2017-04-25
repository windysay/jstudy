<?php 

use yii\helpers\Url;
use yii\helpers\Html;
use yii\base\Object;
use app\models\Timetable;
use yii\grid\GridView;
use yii\widgets\LinkPager;
use app\modules\teacher\models\Teacher;
use app\modules\student\models\Student;

$this->title="未来预约记录";
?>
 <script type="text/javascript">
<?php $this->beginBlock('MY_VIEW_JS_BEGIN') ?>
$(document).ready(function(){
		

})
		

<?php $this->endBlock(); ?>
</script>
     
<?php
    $this->registerJs($this->blocks['MY_VIEW_JS_BEGIN'],\yii\web\View::POS_END);
?>

<div class="course-index">

</div><!-- site-index -->
