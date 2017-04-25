<?php

namespace app\modules\student\models;

use Yii;

/**
 * This is the model class for table "{{%order}}".
 *
 * @property string $id
 * @property string $student_id
 * @property string $order_sn
 * @property string $course_id
 * @property integer $order_status
 * @property integer $m_delete
 * @property integer $pay_type
 * @property integer $pay_status
 * @property string $total_price
 * @property string $total_pay
 * @property string $coupon_money
 * @property string $c_name
 * @property string $c_mobile
 * @property string $c_message
 * @property string $order_mark
 * @property string $pay_time
 * @property string $createtime
 */
class Order extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['student_id', 'order_sn', 'pay_status', 'total_price', 'total_pay', 'coupon_money', 'c_name'], 'required'],
            [['student_id', 'order_status', 'm_delete', 'pay_type', 'pay_status', 'c_mobile', 'pay_time', 'createtime'], 'integer'],
            [['total_price', 'total_pay', 'coupon_money'], 'number'],
            [['order_sn'], 'string', 'max' => 20],
            [['c_name'], 'string', 'max' => 30],
            [['c_message'], 'string', 'max' => 50],
            [['order_mark'], 'string', 'max' => 200],
            [['order_sn'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'student_id' => '学生会员id',
            'order_sn' => '订单编号',
            'course_id' => '订单名称',
            'order_status' => '管理员后台订单状态（ 0删除订单，1交易成功，2交易中，3订单关闭）',
            'm_delete' => '买家是否删除订单，1已删除，0未删除',
            'pay_type' => '1表示支付宝支付',
            'pay_status' => '支付状态 1为已经支付 0表示未支付',
            'total_price' => '订单总价',
            'total_pay' => '买家实付款',
            'coupon_money' => '优惠券、代金券、优惠码 等优惠渠道所优惠的金额（）',
            'c_name' => '会员姓名',
            'c_mobile' => '会员电话',
            'c_message' => '客户留言',
            'order_mark' => '订单标记',
            'pay_time' => '付款时间',
            'createtime' => '创建时间',
        ];
    }
    
    public function beforeSave($insert)
    {
    	if (parent::beforeSave($insert)) {
    		if($this->isNewRecord){
    			$this->createtime=time();
    			$this->order_status=2;
    			$this->m_delete=0;
    			$this->pay_type=1;
    		}else{
    		}
    		return true;
    	} else {
    		return false;
    	}
    }
}
