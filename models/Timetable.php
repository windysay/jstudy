<?php

namespace app\models;

use Yii;
use app\components\Help;

/**
 * This is the model class for table "{{%timetable}}".
 *
 * @property string $id
 * @property string $teacher_id
 * @property string $student_id
 * @property string $date
 * @property string $start_time
 * @property string $end_time
 * @property integer $status
 * @property integer $createtime
 * @property string $ordertime
 */
class Timetable extends \yii\db\ActiveRecord
{	

	const CLASS_TIME_FIRST=10;  //早上开课时间  10:00:00
	const CLASS_TIME_LAST=22;  //晚上最后一节课上课时间 21:00:00
	const CLASS_TIME=50; //每节课50分钟
	
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%timetable}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['teacher_id', 'date', 'start_time', 'end_time','status'], 'required'],
            [['teacher_id', 'student_id', 'date', 'start_time', 'end_time', 'status', 'createtime', 'ordertime'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'teacher_id' => '讲师id',
            'student_id' => '学生id',
            'date' => '日期',
            'start_time' => '上课时间', //（课程为50分钟）
            'end_time' => '下课时间',
            'status' => '此节课的预约状态。 1表示学生可以预约； 0表示管理员删除课程； 2表示学生已预约 ；3为上课中； 4表示上课已经完成，5表示为预约，但是过期了，默认为1',
            'createtime' => 'Createtime',
            'ordertime' => '学生预约时间',
        ];
    }
    
    public function scenarios(){   //自定义验证场景
    	$scenarios = parent::scenarios();
    	$scenarios['teacher-add']=['teacher_id','date','start_time',"end_time"];  //教师自己提交时间
    	$scenarios['admin-add']=['teacher_id','date','start_time',"end_time"];  //管理员提交时间
    	$scenarios['bespeaked']=['student_id','status'];  //预约
    	$scenarios['status']=['status'];  //预约
    	return $scenarios;
    } 

    public function beforeSave($insert)
    {
    	if (parent::beforeSave($insert)) {
    		if($this->isNewRecord){
    			if(empty($this->status)){
    				$this->status=1;
    			}
    			$this->createtime=time();
    		}else{
    			if($this->status==2){   //预约
    				$this->ordertime=time();
    			}elseif($this->status==0||$this->status==1){
    				$this->student_id=null;
    				$this->ordertime=null;
    			}
    		}
    		return true;
    	} else {
    		return false;
    	}
    }
    
    public static function statusText($class,$scenario=1){
    	 if($scenario==1){
    	 	switch ($class['status']){
    	 		case 0:$text='该课程已删除';break;
    	 		case 1:$text='可预约';break;
    	 		case 2:$text='预约成功';break;
    	 		case 3:$text='上课中';break;
    	 		case 4:$text='课程已结束';break;
    	 		case 5:$text='无人预约，课程已过期';break;
    	 		default:$text='未知状态';break;
    	 	}
    	 }else{
    	 	switch ($class['status']){
    	 		case 0:$text='已删除';break;
    	 		case 1:$text='可预约';break;
    	 		case 2:$text='已预约';break;
    	 		case 3:$text='上课中';break;
    	 		case 4:$text='已结束';break;
    	 		case 5:$text='无人预约';break;
    	 		default:$text='未知状态';break;
    	 	}
    	 }
    	 
    	 return $text;
    								
    }
    
    public static  function weekDay(){
    	$this_week_begin=Help::dealtime('week')[0];  //获取本星期第一天0点的时间戳  返回一个数组  array(第一天0点时间戳，当前时间戳)
    	$week1=array(); //用一个数组来存放这周，每一天的0点时间戳
    	$week2=array(); //用一个数组来存放下周，每一天的0点时间戳
    	 
    	$day=$this_week_begin;  //每一天的0点时间戳
    	for($i=0;$i<14;$i++){
    		$day=$this_week_begin+3600*24*$i;
    		if($i<7){
    			$week1[]=$day;
    		}else{
    			$week2[]=$day;
    		}
    	}
    	return ['week1'=>$week1,'week2'=>$week2];
    }
    
    public static function dayClassTime(){
    	$class_first=self::CLASS_TIME_FIRST;
    	$class_last=self::CLASS_TIME_LAST;
    	$class_time=self::CLASS_TIME;
    
    	$day_times=array();  //用一个数组来存放 一天中每个时间段节点
    	for ($i=$class_first;$i<=$class_last;$i++){
    		$time_begin=$i.":00:00";   //这个时间节点的开始 如 10:00:00
    		$time_end=$i.":".$class_time.":00";  //这个时间节点的结束  10:50:00
    		$time_text=$i.":00-".$i.':'.$class_time;
    		$time_text2=$i.":00";
    		$times=['begin'=>$time_begin,'end'=>$time_end,'text'=>$time_text,'text2'=>$time_text2];
    		$day_times[]=$times;
    	}
    	return $day_times;
    }
    
    public static function weekText(){
    	return [
    			'1'=>'一',
    			'2'=>'二',
    			'3'=>'三',
    			'4'=>'四',
    			'5'=>'五',
    			'6'=>'六',
    			'0'=>'日',
    	];
    }
    
    
    
}
