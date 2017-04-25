<?php

namespace app\models;

use Yii;
use app\modules\student\models\Order;
use app\modules\student\models\OrderGoods;
use app\modules\admin\models\MoneyDetail;
use app\modules\student\models\Student;

/**
 * This is the model class for table "{{%alipay_notify}}".
 *
 * @property string $id
 * @property integer $order_type
 * @property string $notify_id
 * @property string $trade_no
 * @property string $out_trade_no
 * @property string $subject
 * @property string $body
 * @property string $trade_status
 * @property string $seller_email
 * @property string $buyer_email
 * @property string $total_fee
 * @property integer $payment_type
 * @property string $out_channel_inst
 * @property string $refund_status
 * @property string $gmt_refund
 * @property string $gmt_create
 * @property string $gmt_payment
 * @property string $gmt_close
 * @property string $time_end
 */
class AlipayNotify extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%alipay_notify}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_type', 'notify_id', 'payment_type'], 'required'],
            [['order_type', 'payment_type', 'gmt_refund', 'gmt_create', 'gmt_payment', 'gmt_close', 'time_end'], 'integer'],
            [['total_fee'], 'number'],
            [['notify_id'], 'string', 'max' => 40],
            [['trade_no'], 'string', 'max' => 64],
            [['out_trade_no', 'trade_status'], 'string', 'max' => 20],
            [['subject', 'body', 'seller_email', 'buyer_email'], 'string', 'max' => 100],
            [['out_channel_inst', 'refund_status'], 'string', 'max' => 30],
            [['out_trade_no'], 'unique']
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
            'notify_id' => '通知校验ID',
            'trade_no' => '支付宝交易号',
            'out_trade_no' => '商户订单号',
            'subject' => '商品名称',
            'body' => '商品描述',
            'trade_status' => '交易状态',
            'seller_email' => '卖家账户',
            'buyer_email' => '买家账户',
            'total_fee' => '支付总金额',
            'payment_type' => '支付类型 1商品购买4捐赠 47电子卡券 （一般为1）',
            'out_channel_inst' => '实际支付渠道',
            'refund_status' => '退款状态',
            'gmt_refund' => '退款时间',
            'gmt_create' => '交易创建时间',
            'gmt_payment' => '交易付款时间 ',
            'gmt_close' => '交易关闭时间',
            'time_end' => '支付宝通知时间',
        ];
    }
    
    public static function saveDataByPay($notify){//在支付完成的时候，处理会员，商家等数据
    	$order_sn=$notify->out_trade_no;
    	$order=Order::find()->where('order_sn=:order_sn',[':order_sn'=>$order_sn])->one();
    	if(!$order)//如果没有订单
    		return false;
    	if($order){//订单支付状态更新
    		$order->pay_type=1;//1表示支付宝支付
    		$order->pay_status=1;//支付状态，已经支付
    		$order->order_status=1;
    		$order->pay_time=$notify->gmt_payment;
    		if(!$order->update()){//如果更新不成功
    			return false;
    		}
//     		$orderGoods=OrderGoods::find()->where('order_sn=:order_sn',[':order_sn'=>$order_sn])->asArray()->all();
//     		$orderGoodsArr='';
//     		foreach ($orderGoods as $v){
//     			$orderGoodsArr.=$v['sku_name'].'x'.$v['total_count'].' ';
//     		}
    		// $param是一个字符串，用“,”连接   分别是 "用户名，订单号，快递名称，运单号";
    		//     $param=$orderGoodsArr.','.$order->order_sn.','.$order->c_name.','.$order->c_mobile.','.AddressCode::getAddress($order->c_address_code,2).$order->c_address.'('.$order->c_message.')';
    		//     $ucpaas=new Ucpaas();
    		//     $ucpaasRes=$ucpaas->templateSendNotice('15920167894', 'newOrderWarn',$param);
    	}
//     	$moneydetail=MoneyDetail::find()->where('order_sn=:order_sn',[':order_sn'=>$order_sn])->one();
//    	$userbalance=MerchantBalance::find()->where('merchant_id=:merchant_id',[':merchant_id'=>$notify->merchant_id])->one();
     	$orderGoods=OrderGoods::find()->where('order_sn=:order_sn',[':order_sn'=>$order_sn])->one();
//     	if($moneydetail){
//     		$moneydetail->content="$orderGoods->name";
//     		$moneydetail->total_fee=$notify->total_fee;
// //     		$moneydetail->a_balance=$userbalance['a_balance'];
// //     		$moneydetail->u_balance=$userbalance['u_balance']+$moneydetail->total_fee;
//     		if(!$moneydetail->update())//如果更新不成功
//     			return false;
//     	}else{
    		$moneydetail=new MoneyDetail();
    		$moneydetail->order_type=1;//订单类型 1表示正常的商品购买订单 2表示系统官方自己的支付订单
    		$moneydetail->pay_type=1;//收支类型  1为收入 2为支出
    		$moneydetail->pay_channel=2;//支付渠道 1表示微信支付 2表示支付宝支付 3表示余额支付
    		$moneydetail->status=2;//交易状态（1成功 2交易中）
    		$moneydetail->order_sn=$order_sn;
    		$moneydetail->content=$orderGoods->name;
    		$moneydetail->refund_status=0;//退款状态 0表示未退款 1表示退款
    		$moneydetail->total_fee=$notify->total_fee;
//     		$moneydetail->a_balance=empty($userbalance['a_balance'])?'0':$userbalance['a_balance'];
//     		$moneydetail->u_balance=empty($userbalance['u_balance'])?'0':$userbalance['u_balance']+$moneydetail->total_fee;
 //   	}
//     	$userbalance->u_balance+=$moneydetail->total_fee;
//     	if(!$userbalance->update())//如果更新不成功
//     		return false;
     	$member=Student::findOne($order['student_id']);
    	if(!$member)
    		return false;
    	$member->scenario='monetary-integral';   //增加用户的
    	$member->monetary+=$moneydetail->total_fee;
    	$member->integral+=round($moneydetail->total_fee);
    	$coursemeal=\app\models\CourseMeal::find()->where('id=:id',[":id"=>2])->one();
    	$member->buy_ticket+=$coursemeal->course_ticket;
    	$member->course_ticket+=$coursemeal->course_ticket;
//    	$coursemeal->sales=$coursemeal->sales+1;
    	if(!$moneydetail->save() &&!$member->update() /* &&!$coursemeal->update() */)
    		return false; 
    	else
    		return true;
    }
    
    
    
    
}
