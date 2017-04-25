<?php

namespace app\extensions\ucpaas;

use Yii;
/**
 * Created by PhpStorm.
 * User: UCPAAS JackZhao
 * Date: 2014/10/22
 * Time: 12:04
 * Dec : ucpass php sdk
 */
class Ucpaas
{


    const SoftVersion = "2014-06-30";  // 云之讯REST API版本号。当前版本号为：2014-06-30
  
    const BaseUrl = "https://api.ucpaas.com/";  //API请求地址

    const ACCOUNTSID="eca7a188845f699537676e0045e89db8";  //string  开发者账号ID。由32个英文字母和阿拉伯数字组成的开发者账号唯一标识符。
    
    const TOKEN="17789f61531727342f95ddff5767db71";  //string  开发者账号TOKEN  
 
    const SEND_CODE_APPID="3f71c26318e64b2f80dc75a8800c3540";  //发送验证码 /语音验证码     应用id
    
    const SEND_CODE_TMP_ID="8219";  //发送验证/语音验证码的模板id
//  const SEND_CODE_TMP_ID="7873";  //发送验证/语音验证码的模板id  这个是旧的 已经被删除 但是还可以用 
    
    private $timestamp; // string 时间戳

    /**
     * @param $options 数组参数必填
     * $options = array(
     *
     * )
     * @throws Exception
     */
    public function  __construct()
    {
        /*if (is_array($options) && !empty($options)) {
            $this->accountSid = isset($options['accountsid']) ? $options['accountsid'] : '';
            $this->token = isset($options['token']) ? $options['token'] : '';
            $this->timestamp = date("YmdHis") + 7200;
        } else {
            throw new Exception("非法参数");
        }*/
         $this->timestamp = date("YmdHis") + 7200;
    }
    
    
    /**
     * @param $appId
     * @param $verifyCode
     * @param $to
     * @param string $type
     * @return mixed|string
     * @throws Exception
     */
    public function voiceCode($verifyCode,$to,$type = 'json'){   //语音验证码
        $appId=self::SEND_CODE_APPID;
        $url = self::BaseUrl . self::SoftVersion . '/Accounts/' . self::ACCOUNTSID . '/Calls/voiceCode?sig=' . $this->getSigParameter();
        if($type == 'json'){
            $body_json = array('voiceCode'=>array(
                'appId'=>$appId,
                'verifyCode'=>$verifyCode,
                'to'=>$to
            ));
            $body = json_encode($body_json);
        }elseif($type == 'xml'){
            $body_xml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
                        <voiceCode>
                            <verifyCode>'.$verifyCode.'</clientNumber>
                            <to>'.$to.'</charge>
                            <appId>'.$appId.'</appId>
                        </voiceCode>';
            $body = trim($body_xml);
        }else {
            throw new Exception("只能json或xml，默认为json");
        }
        $data = $this->getResult($url, $body, $type,'post');
        return $data;
    }
    
    /**
     * @param $appId
     * @param $to
     * @param $templateId
     * @param null $param
     * @param string $type
     * @return mixed|string
     * @throws Exception
     */
    public function templateSMS($to,$param=null,$type = 'json'){   //短信验证码
        $appId=self::SEND_CODE_APPID;
        $templateId=self::SEND_CODE_TMP_ID;
        $url = self::BaseUrl . self::SoftVersion . '/Accounts/' . self::ACCOUNTSID . '/Messages/templateSMS?sig=' . $this->getSigParameter();
        if($type == 'json'){
            $body_json = array('templateSMS'=>array(
                'appId'=>$appId,
                'templateId'=>$templateId,
                'to'=>$to,
                'param'=>$param
            ));
            $body = json_encode($body_json);
        }elseif($type == 'xml'){
            $body_xml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
                        <templateSMS>
                            <templateId>'.$templateId.'</templateId>
                            <to>'.$to.'</to>
                            <param>'.$param.'</param>
                            <appId>'.$appId.'</appId>
                        </templateSMS>';
            $body = trim($body_xml);
        }else {
            throw new Exception("只能json或xml，默认为json");
        }
        $data = $this->getResult($url, $body, $type,'post');
        return $data;
    }
    public function templateSendNotice($to,$scenarios,$param=null,$type = 'json'){   //发送短信通知 模板
        $appId=self::SEND_NOTICE_APPID;
        switch ($scenarios){
            case 'sendOrder': $templateId=self::SEND_ORDER_CONSIGNEE_NOTICE_TMP_ID;break;
            case 'goodsNotice':$templateId=self::ABOUT_GOODS_NOTICE_TMP_ID;break;
            case 'drawBalanceSuccess': $templateId=self::DRAW_BALANCE_SUCCESS_NOTICE_TMP_ID;break;
            case 'drawBalanceFail': $templateId=self::DRAW_BALANCE_FAIL_NOTICE_TMP_ID;break;
            case 'newOrderWarn':$templateId=self::NEW_ORDER_WARN_NOTICE_TMP_ID;break;
            case 'remindAdmin':$templateId=self::REMIND_ADMIN_NOTICE_TMP_ID;break;
            case 'sendOrderNew': $templateId=self::SEND_ORDER_CONSIGNEE_NOTICE_TMP_ID_NEW;break;
            case 'eggPrepare':$templateId=self::EGG_PREPARE_NOTICE_TMP_ID;break;
            case 'eggLogistics':$templateId=self::EGG_LOGISITICS_NOTICE_TMP_ID;break;
            case 'eggOther':$templateId=self::EGG_OTHER_NOTICE_TMP_ID;break;
            default:return false;break;
        }
       
        $url = self::BaseUrl . self::SoftVersion . '/Accounts/' . self::ACCOUNTSID . '/Messages/templateSMS?sig=' . $this->getSigParameter();
        if($type == 'json'){
            $body_json = array('templateSMS'=>array(
                'appId'=>$appId,
                'templateId'=>$templateId,
                'to'=>$to,
                'param'=>$param
            ));
            $body = json_encode($body_json);
        }elseif($type == 'xml'){
            $body_xml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
                        <templateSMS>
                            <templateId>'.$templateId.'</templateId>
                            <to>'.$to.'</to>
                            <param>'.$param.'</param>
                            <appId>'.$appId.'</appId>
                        </templateSMS>';
            $body = trim($body_xml);
        }else {
            throw new Exception("只能json或xml，默认为json");
        }
        $data = $this->getResult($url, $body, $type,'post');
        return $data;
        
        
    }
    
    /**
     * @return string
     * 包头验证信息,使用Base64编码（账户Id:时间戳）
     */
    private function getAuthorization()
    {
        $data = self::ACCOUNTSID. ":" . $this->timestamp;
        return trim(base64_encode($data));
    }

    /**
     * @return string
     * 验证参数,URL后必须带有sig参数，sig= MD5（账户Id + 账户授权令牌 + 时间戳，共32位）(注:转成大写)
     */
    private function getSigParameter()
    {
        $sig =  self::ACCOUNTSID.self::TOKEN . $this->timestamp;
        return strtoupper(md5($sig));
    }

    /**
     * @param $url
     * @param string $type
     * @return mixed|string
     */
    private function getResult($url, $body = null, $type = 'json',$method)
    {
        $data = $this->connection($url,$body,$type,$method);
        if (isset($data) && !empty($data)) {
            $result = $data;
        } else {
            $result = '';
        }
        return $result;
    }

    /**
     * @param $url
     * @param $type
     * @param $body  post数据
     * @param $method post或get
     * @return mixed|string
     */
    private function connection($url, $body, $type,$method)
    {
        if ($type == 'json') {
            $mine = 'application/json';
        } else {
            $mine = 'application/xml';
        }
        if (function_exists("curl_init")) {
            $header = array(
                'Accept:' . $mine,
                'Content-Type:' . $mine . ';charset=utf-8',
                'Authorization:' . $this->getAuthorization(),
            );
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            if($method == 'post'){
                curl_setopt($ch,CURLOPT_POST,1);
                curl_setopt($ch,CURLOPT_POSTFIELDS,$body);
            }
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            $result = curl_exec($ch);
            curl_close($ch);
        } else {
            $opts = array();
            $opts['http'] = array();
            $headers = array(
                "method" => strtoupper($method),
            );
            $headers[]= 'Accept:'.$mine;
            $headers['header'] = array();
            $headers['header'][] = "Authorization: ".$this->getAuthorization();
            $headers['header'][]= 'Content-Type:'.$mine.';charset=utf-8';

            if(!empty($body)) {
                $headers['header'][]= 'Content-Length:'.strlen($body);
                $headers['content']= $body;
            }

            $opts['http'] = $headers;
            $result = file_get_contents($url, false, stream_context_create($opts));
        }
        return $result;
    }

    
    
   /*******************      以下的代码是该接口的其他功能  暂未用到              ************************************************/ 
    /**
     * @param string $type 默认json,也可指定xml,否则抛出异常
     * @return mixed|string 返回指定$type格式的数据
     * @throws Exception
     */
    public function getDevinfo($type = 'json')
    {
        if ($type == 'json') {
            $type = 'json';
        } elseif ($type == 'xml') {
            $type = 'xml';
        } else {
            throw new Exception("只能json或xml，默认为json");
        }
        $url = self::BaseUrl . self::SoftVersion . '/Accounts/' . $this->accountSid . '?sig=' . $this->getSigParameter();
        $data = $this->getResult($url,null,$type,'get');
        return $data;
    }


    /**
     * @param $appId 应用ID
     * @param $clientType 计费方式。0  开发者计费；1 云平台计费。默认为0.
     * @param $charge 充值的金额
     * @param $friendlyName 昵称
     * @param $mobile 手机号码
     * @return json/xml
     */
    public function applyClient($appId, $clientType, $charge, $friendlyName, $mobile, $type = 'json')
    {
        $url = self::BaseUrl . self::SoftVersion . '/Accounts/' . $this->accountSid . '/Clients?sig=' . $this->getSigParameter();
        if ($type == 'json') {
            $body_json = array();
            $body_json['client'] = array();
            $body_json['client']['appId'] = $appId;
            $body_json['client']['clientType'] = $clientType;
            $body_json['client']['charge'] = $charge;
            $body_json['client']['friendlyName'] = $friendlyName;
            $body_json['client']['mobile'] = $mobile;
            $body = json_encode($body_json);
        } elseif ($type == 'xml') {
            $body_xml = '<?xml version="1.0" encoding="utf-8"?>
                        <client><appId>'.$appId.'</appId>
                        <clientType>'.$clientType.'</clientType>
                        <charge>'.$charge.'</charge>
                        <friendlyName>'.$friendlyName.'</friendlyName>
                        <mobile>'.$mobile.'</mobile>
                        </client>';
            $body = trim($body_xml);
        } else {
            throw new Exception("只能json或xml，默认为json");
        }
        $data = $this->getResult($url, $body, $type,'post');
        return $data;
    }

    /**
     * @param $clientNumber
     * @param $appId
     * @param string $type
     * @return mixed|string
     * @throws Exception
     */
    public function releaseClient($clientNumber,$appId,$type = 'json'){
        $url = self::BaseUrl . self::SoftVersion . '/Accounts/' . $this->accountSid . '/dropClient?sig=' . $this->getSigParameter();
        if($type == 'json'){
            $body_json = array();
            $body_json['client'] = array();
            $body_json['client']['clientNumber'] = $clientNumber;
            $body_json['client']['appId'] = $appId;
            $body = json_encode($body_json);
        }elseif($type == 'xml'){
            $body_xml = '<?xml version="1.0" encoding="utf-8"?>
                        <client>
                        <clientNumber>'.$clientNumber.'</clientNumber>
                        <appId>'.$appId.'</appId >
                        </client>';
            $body = trim($body_xml);
        }else {
            throw new Exception("只能json或xml，默认为json");
        }
        $data = $this->getResult($url, $body, $type,'post');
        return $data;
    }

    /**
     * @param $appId
     * @param $start
     * @param $limit
     * @param string $type
     * @return mixed|string
     * @throws Exception
     */
    public function getClientList($appId,$start,$limit,$type = 'json'){
        $url = self::BaseUrl . self::SoftVersion . '/Accounts/' . $this->accountSid . '/clientList?sig=' . $this->getSigParameter();
        if($type == 'json'){
            $body_json = array('client'=>array(
                'appId'=>$appId,
                'start'=>$start,
                'limit'=>$limit
            ));
            $body = json_encode($body_json);
        }elseif($type == 'xml'){
            $body_xml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
                        <client>
                            <appId>'.$appId.'</appId>
                            <start>'.$start.'</start>
                            <limit>'.$limit.'</limit>
                        </client>';
            $body = trim($body_xml);
        }else {
            throw new Exception("只能json或xml，默认为json");
        }
        $data = $this->getResult($url, $body, $type,'post');
        return $data;
    }

    /**
     * @param $appId
     * @param $clientNumber
     * @param string $type
     * @return mixed|string
     * @throws Exception
     */
    public function getClientInfo($appId,$clientNumber,$type = 'json'){
        if ($type == 'json') {
            $type = 'json';
        } elseif ($type == 'xml') {
            $type = 'xml';
        } else {
            throw new Exception("只能json或xml，默认为json");
        }
        $url = self::BaseUrl . self::SoftVersion . '/Accounts/' . $this->accountSid . '?sig=' . $this->getSigParameter(). '&clientNumber='.$clientNumber.'&appId='.$appId;
        $data = $this->getResult($url,null,$type,'get');
        return $data;
    }

    /**
     * @param $appId
     * @param $mobile
     * @param string $type
     * @return mixed|string
     * @throws Exception
     */
    public function getClientInfoByMobile($appId,$mobile,$type = 'json'){
        if ($type == 'json') {
            $type = 'json';
        } elseif ($type == 'xml') {
            $type = 'xml';
        } else {
            throw new Exception("只能json或xml，默认为json");
        }
        $url = self::BaseUrl . self::SoftVersion . '/Accounts/' . $this->accountSid . '/ClientsByMobile?sig=' . $this->getSigParameter(). '&mobile='.$mobile.'&appId='.$appId;
        $data = $this->getResult($url,null,$type,'get');
        return $data;
    }

    /**
     * @param $appId
     * @param $date
     * @param string $type
     * @return mixed|string
     * @throws Exception
     */
    public function getBillList($appId,$date,$type = 'json'){
        $url = self::BaseUrl . self::SoftVersion . '/Accounts/' . $this->accountSid . '/billList?sig=' . $this->getSigParameter();
        if($type == 'json'){
            $body_json = array('appBill'=>array(
                'appId'=>$appId,
                'date'=>$date,
            ));
            $body = json_encode($body_json);
        }elseif($type == 'xml'){
            $body_xml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
                        <appBill>
                            <appId>'.$appId.'</appId>
                            <date>'.$date.'</date>
                        </appBill>';
            $body = trim($body_xml);
        }else {
            throw new Exception("只能json或xml，默认为json");
        }
        $data = $this->getResult($url, $body, $type,'post');
        return $data;
    }

    /**
     * @param $appId
     * @param $clientNumber
     * @param $chargeType
     * @param $charge
     * @param string $type
     * @return mixed|string
     * @throws Exception
     */
    public function chargeClient($appId,$clientNumber,$chargeType,$charge,$type = 'json'){
        $url = self::BaseUrl . self::SoftVersion . '/Accounts/' . $this->accountSid . '/chargeClient?sig=' . $this->getSigParameter();
        if($type == 'json'){
            $body_json = array('client'=>array(
                'appId'=>$appId,
                'clientNumber'=>$clientNumber,
                'chargeType'=>$chargeType,
                'charge'=>$charge
            ));
            $body = json_encode($body_json);
        }elseif($type == 'xml'){
            $body_xml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
                        <client>
                            <clientNumber>'.$clientNumber.'</clientNumber>
                            <chargeType>'.$chargeType.'</chargeType>
                            <charge>'.$charge.'</charge>
                            <appId>'.$appId.'</appId>
                        </client>';
            $body = trim($body_xml);
        }else {
            throw new Exception("只能json或xml，默认为json");
        }
        $data = $this->getResult($url, $body, $type,'post');
        return $data;

    }

    /**
     * @param $appId
     * @param $fromClient
     * @param $to
     * @param null $fromSerNum
     * @param null $toSerNum
     * @param string $type
     * @return mixed|string
     * @throws Exception
     */
    public function callBack($appId,$fromClient,$to,$fromSerNum=null,$toSerNum=null,$type = 'json'){
        $url = self::BaseUrl . self::SoftVersion . '/Accounts/' . $this->accountSid . '/Calls/callBack?sig=' . $this->getSigParameter();
        if($type == 'json'){
            $body_json = array('callback'=>array(
                'appId'=>$appId,
                'fromClient'=>$fromClient,
                'fromSerNum'=>$fromSerNum,
                'to'=>$to,
                'toSerNum'=>$toSerNum
            ));
            $body = json_encode($body_json);
        }elseif($type == 'xml'){
            $body_xml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
                        <callback>
                            <fromClient>'.$fromClient.'</clientNumber>
                            <fromSerNum>'.$fromSerNum.'</chargeType>
                            <to>'.$to.'</charge>
                            <toSerNum>'.$toSerNum.'</toSerNum>
                            <appId>'.$appId.'</appId>
                        </callback>';
            $body = trim($body_xml);
        }else {
            throw new Exception("只能json或xml，默认为json");
        }
        $data = $this->getResult($url, $body, $type,'post');
        return $data;
    }



} 