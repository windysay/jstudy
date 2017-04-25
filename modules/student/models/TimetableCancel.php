<?php

namespace app\modules\student\models;

use Yii;

/**
 * This is the model class for table "{{%timetable_cancel}}".
 *
 * @property string $id
 * @property string $timetable_id
 * @property string $teacher_id
 * @property string $student_id
 * @property string $date
 * @property string $start_time
 * @property string $end_time
 * @property integer $type
 * @property string $canceltime
 */
class TimetableCancel extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%timetable_cancel}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['timetable_id', 'teacher_id', 'student_id', 'date', 'start_time', 'end_time', 'type'], 'required','message'=>'{attribute}不可为空'],
            [['timetable_id', 'teacher_id', 'student_id', 'date', 'start_time', 'end_time', 'type', 'canceltime'], 'integer','message'=>'{attribute}只能为整数']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'timetable_id' => '上课日期表id',
            'teacher_id' => '老师id',
            'student_id' => '学生id',
            'date' => '日期',
            'start_time' => '课程正式开始的时间',
            'end_time' => '课程结束的时间',
            'type' => '1表示学生取消，2表示管理员取消',
            'canceltime' => '取消的时间',
        ];
    }
    
    public function beforeSave($insert)
    {
    	if (parent::beforeSave($insert)) {
    		if($this->isNewRecord){
    			$this->canceltime=time();
    		}else{
    			
    		}
    		return true;
    	} else {
    		return false;
    	}
    }
    
    public static function cacenlType($type){
    	switch ($type){
    		case 1;$text='学生取消';break;
    		case 2;$text='管理员取消';break;
    		default:$text='未知';break;
    	}
    	return $text;
    }
    
}
