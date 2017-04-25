<?php 
use yii\helpers\Url;
use yii\helpers\Html;
use common\extensions\alipay\AlipayNotify;
$this->title='支付宝支付';


// $m=Yii::$app->memcache->get('_ceshi_');
// echo 'm='.$m;
 
$http="http://www.tianyuanhaoke.com/alipay/return-url?body=%E4%B8%8D%E5%B1%9E%E4%BA%8E%E7%BA%A2%E5%AF%8C%E5%A3%AB%E7%9A%84%E8%8B%B9%E6%9E%9C%EF%BC%8C%E4%B8%8D%E6%98%AF%E4%B8%80%E9%A2%97%E5%A5%BD%E8%8D%89%E8%8E%93&buyer_email=13794311355&buyer_id=2088702143352252&exterface=create_direct_pay_by_user&extra_common_param=2&is_success=T&notify_id=RqPnCoPT3K9%252Fvwbh3InSN9qOq5vq9dGRr90J863XjG1gV8%252BXxHSo%252BM8xylWBMap8Tn5L&notify_time=2015-05-13+15%3A33%3A12&notify_type=trade_status_sync&out_trade_no=150513153225HYY40242&payment_type=1&seller_email=youto95%40163.com&seller_id=2088511540696122&subject=%E4%B8%8D%E5%B1%9E%E4%BA%8E%E7%BA%A2%E5%AF%8C%E5%A3%AB%E7%9A%84%E8%8B%B9%E6%9E%9C%EF%BC%8C%E4%B8%8D%E6%98%AF%E4%B8%80%E9%A2%97%E5%A5%BD%E8%8D%89%E8%8E%93&total_fee=0.01&trade_no=2015051300001000250050584636&trade_status=TRADE_SUCCESS&sign=af55153ab8a65b5994ec2654a2414f29&sign_type=MD5";
echo $_GET['body'];
?>
 