<?php

namespace app\modules\admin\models;

use Yii;
use app\components\Help;

/**
 * This is the model class for table "{{%money_detail}}".
 *
 * @property string $id
 * @property integer $order_type
 * @property integer $pay_type
 * @property integer $pay_channel
 * @property integer $status
 * @property string $order_sn
 * @property string $content
 * @property string $total_fee
 * @property integer $refund_status
 * @property string $gmt_refund
 * @property string $createtime
 */
class MoneyDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%money_detail}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_type', 'pay_type', 'pay_channel', 'status', 'content', 'total_fee'], 'required'],
            [['order_type', 'pay_type', 'pay_channel', 'status', 'refund_status', 'gmt_refund', 'createtime'], 'integer'],
            [['total_fee'], 'number'],
            [['order_sn'], 'string', 'max' => 20],
            [['content'], 'string', 'max' => 100],
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
            'order_type' => '订单类型 1表示正常的商品购买订单 2表示系统官方自己的支付订单',
            'pay_type' => '收支类型  1为收入 2为支出',
            'pay_channel' => '支付渠道 1表示支付宝支付   2表示微信支付 3表示余额支付',
            'status' => '交易状态（1成功 2交易中）',
            'order_sn' => '商户订单号',
            'content' => '商品描述',
            'total_fee' => '收入总金额/支出总金额 单位为元',
            'refund_status' => '退款状态 0表示未退款 1表示退款',
            'gmt_refund' => '退款时间',
            'createtime' => '支付完成时间/交易时间',
        ];
    }
    
    public function beforeSave($insert){
    	if (parent::beforeSave($insert)) {
    		if($this->isNewRecord){
    			$this->order_sn=empty($this->order_sn)?Help::orderSn():$this->order_sn;
    			$this->status=empty($this->status)?2:$this->status;
    			$this->refund_status=empty($this->refund_status)?0:$this->refund_status;
    			$this->createtime=time();
    		}else{
    		}
    
    		return true;
    	} else {
    		return false;
    	}
    }
    
}
