<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use \app\components\Help;
use backend\models\Member;////////////////////////
use \app\modules\student\models\Order;
use yii\helpers\Html;
use yii\web\NotFoundHttpException;
use \app\components\CacheData;
use \app\components\alipay\AlipayPub;
use \app\models\AlipayNotify;
use common\components\CommonData;

use backend\modules\v2\models\MoneyDetail;
use backend\modules\v2\models\MerchantBalance;
use common\models\AlipayRefundOrder;
use app\modules\student\models\OrderGoods;
use yii\db\Exception;

 
class AlipayController extends Controller{//支付宝支付控制器
	public $enableCsrfValidation = false;
	public $layout='main';
	public function init(){
		parent::init();
		$this->getView()->registerCssFile(Yii::$app->homeUrl.'css/student/pay.css');
		$this->getView()->registerCssFile(Yii::$app->homeUrl.'css/student/cart.css');
	}
	
	public function actionCeshi(){
		return $this->render("ceshi");
	}
	
	public function actionConfirm($sn){//立即购买订单页面
		$order_sn=Html::encode($sn);
		if(\Yii::$app->user->isGuest){
			$order=\app\modules\student\models\Order::find()->where(['order_sn'=>$order_sn])->asArray()->one();
		}else{
			$user_id=Yii::$app->user->identity->id;
			$order=Order::find()->where(['student_id'=>$user_id,'order_sn'=>$order_sn])->asArray()->one();
		}
	
		if(($order===null)||($order['pay_status']==1)||($order['m_delete']==1)||($order['order_status_m']==3)){  //没查到数据  或者状态为 0  代表已删除
			throw new NotFoundHttpException('您访问的页面不存在.');
		}
		return $this->render('confirm',[
				'order'=>$order,
				]);
	
	}
	
	public function actionReturnUrl(){
		$this->getView()->title="付款成功";
		return $this->render("return-url");
	}
 
	public function actionChoicePay($sn){//选择支付方式,传入订单号
		$order_sn=Html::encode($sn);
		$order=Order::find()->where(['order_sn'=>$order_sn])->one();
		return $this->render('choice-pay',['sn'=>$sn,'order'=>$order]);
	}
 
	public function actionPayNow($sn){//处理支付的动作。,传入微信订单号 微信支付showwxpaytitle=1
		    $order_sn=Html::encode($sn);
		    $order=Order::find()->where('order_sn=:order_sn',[':order_sn'=>$order_sn])->one();
		    $orderGoods=OrderGoods::find()->where('order_sn=:order_sn',[':order_sn'=>$order_sn])->one();
		    $order_name=$orderGoods['name'];
		    $subject=$order_name?$order_name:'课程购买';
			$body=1;
		    $alipay=new AlipayPub('pay');//请求支付类型
		    $alipay->setParameter('seller_email',AlipayPub::SELLER_EMAIL);
		    $alipay->setParameter('out_trade_no',$order_sn);
		    $alipay->setParameter('subject',$subject);
		    $alipay->setParameter('total_fee',$order->total_pay);
		    $alipay->setParameter('body',$body);
//		    $alipay->setParameter('extra_common_param',$order->student_id);//回传参数（user_id）
		    $sign=$alipay->getSign($alipay->parameters);
		    $alipay->setParameter('sign',$sign);
		    $alipay->setParameter('sign_type',AlipayPub::SIGN_TYPE);
            $url=AlipayPub::ALIPAY_GATEWAY_NEW;
            $url.=$alipay->formatBizQueryParaMap($alipay->parameters,false);
            $this->redirect($url);
	}
 
    public function actionNotifyUrl(){ //支付宝支付成功回调地址
    	$data=$_POST;
    	$ver=new \app\components\alipay\AlipayNotify();
    	$shi=$ver->verifyNotify($data);
        if($shi){
         $transaction=Yii::$app->db->beginTransaction(); //开始事务
         try{
            $alipay=new AlipayNotify();
            $alipay->order_type=1;//订单类型 1表示正常的商品购买订单 2表示系统官方自己的支付订单
//            $alipay->pay_pcormobile=2;//1为移动支付 2为pc端支付
            $alipay->notify_id=$data['notify_id'];
            $alipay->trade_no=$data['trade_no'];
            $alipay->out_trade_no=$data['out_trade_no'];
            $alipay->subject=$data['subject'];
            $alipay->body=$data['body'];
            $alipay->trade_status=$data['trade_status'];
            $alipay->seller_email=$data['seller_email'];
            $alipay->buyer_email=$data['buyer_email'];
            $alipay->total_fee=$data['total_fee'];
            $alipay->payment_type=$data['payment_type'];  
            $alipay->out_channel_inst=$data['out_channel_inst'];
            $alipay->refund_status=$data['refund_status'];
            $alipay->gmt_refund=Help::getStrtotime_2($data['gmt_refund']);
            $alipay->gmt_create=Help::getStrtotime_2($data['gmt_create']);
            $alipay->gmt_payment=Help::getStrtotime_2($data['gmt_payment']);
            $alipay->gmt_close=Help::getStrtotime_2($data['gmt_close']);
            $alipay->time_end=Help::getStrtotime_2($data['notify_time']);
//            $seller_data=CommonData::saveSellerByPay($data['out_trade_no']);//保存分销商数据

            
            $all_data=\app\models\AlipayNotify::saveDataByPay($alipay);
            
            
            
            if($alipay->save()&&$all_data ){
               echo 'success';
               $transaction->commit();
            }
          }catch (Exception $e) {
          	 var_dump($alipay->errors);exit();
             $transaction->rollBack();
          }
        }else echo 0;
    }
 
    public function actionNotifyUrlRefund(){ //申请支付宝退款之后，支付宝处理结果的通知地址
        $data=$_POST;
   //     $data=['result_details'=>'2015122521001004200019546902^0.01^SUCCESS'];
        $ver=new AlipayNotify();
        $shi=$ver->verifyNotify($data);
      if($shi){
         $refund_result_details=$data['result_details']; //不退手续费结果返回格式：交易号^退款金额^处理结果。
         $refund_result_details_arr=explode('^',$refund_result_details);
         $trade_no=$refund_result_details_arr[0]; //支付宝交易单号
         $refund_result=$refund_result_details_arr[2]; //退款结果   成功为’SUCCESS‘
         if($refund_result!='SUCCESS'){
             echo '退款失败';
             exit();
         }
         $alipayNotify=\common\models\AlipayNotify::find()->where('trade_no=:trade_no',[':trade_no'=>$trade_no])->one();
         $alipayNotify->refund_status='REFUND_SUCCESS';
         $alipayNotify->gmt_refund=time();
         
         $order=Order::find()->where('order_sn=:order_sn',[':order_sn'=>$alipayNotify->out_trade_no])->one();
         if($order===null){
             echo '订单'.$order_sn.'不存在';
             exit();
         }elseif($order->order_status==0||$order->order_status==4||$order->order_status_m==1||$order->order_status_m==3||$order->order_status_m==4){
             echo '订单'.$order_sn.'不符合退款条件';
             exit();
         }
         $order->order_status_m=4; //更改订单收货状态为交易失败
         
         $balance=MerchantBalance::find()->where('merchant_id=:merchant_id',[':merchant_id'=>$order->merchant_id])->one();
         $moneydetail=MoneyDetail::find()->where('order_sn=:order_sn',[':order_sn'=>$order->order_sn])->one();
         $new_u_balance=$balance->u_balance-$moneydetail->total_fee;
         //更改商家余额
         $balance->u_balance=$new_u_balance;
          
         //更改该笔交易的收支明细交易状态为成功
         $moneydetail->u_balance=$new_u_balance;
         $moneydetail->status=1;
         $moneydetail->refund_status=1;  //有退款
         
         $member=Member::findOne($order->member_id);
         $member->scenario='monetary-integral';
         $member->monetary-=round($moneydetail->total_fee);
         $member->integral-=round($moneydetail->total_fee);
         
         $transaction=Yii::$app->db->beginTransaction();  //开始事务
         if($order->update() &&$moneydetail->update()&&$balance->save()&&$member->save()&&$alipayNotify->save()){
             $transaction->commit();
             echo '订单'.$order->order_sn.'数据保存成功';
         }else{
             $transaction->rollBack();
             //退款操作
//              $alipayRefundOrder=new AlipayRefundOrder();
//              $alipayRefundOrder->alipay_notify_id=$alipayNotify->id;
//              $alipayRefundOrder->alipay_trade_no=$alipayNotify->trade_no;
//              $alipayRefundOrder->order_sn=$alipayNotify->out_trade_no;
//              $alipayRefundOrder->save();
             echo '订单'.$order->order_sn.'数据保存失败';
         }
         
         
        }
    }
 
 
}
