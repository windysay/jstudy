<?php
namespace app\components;

class  Help{//帮助类 调用方式类似 Help::orderSn();

    public static function getWeekTime($time, $tag = 'start')
    {
        $ret = array();
        $timestamp = $time;
        $w = strftime('%u', $timestamp);
        $ret['start'] = strtotime(date('Y-m-d 00:00:00', $timestamp - ($w - 1) * 86400));
        $ret['end'] = strtotime(date('Y-m-d 23:59:59', $timestamp + (7 - $w) * 86400));
        return $ret[$tag];
    }

	public static function xiaoshu($num){  //将数字转换成价格格式 12元->12.00元
		$num=sprintf("%01.2f",$num);
		return $num;
	}
	
	public static function subtxt($txt, $len){ //文本截取  多余的变成”.......“
		return mb_strlen($txt, 'utf8')>$len ? mb_substr($txt, 0, $len, 'utf8').'...' : $txt;
	}
	
	public static  function zhuanTime($t){//把2014-02-23或者2014:02:23这样的日期转换成时间戳
			$fuhao=substr($t,4,1);//2014-02-23或者2014:02:23获得 "："或者 "-"
			$arr=explode($fuhao,$t);//截取为数组
			$mt=$arr[0].'-'.$arr[1].'-'.$arr[2];
			return strtotime($mt);//转换成时间戳
	}
	public static function trimall($str){//删除字符串所有空格
		$qian=array(" ","　","\t","\n","\r");
		$hou=array("","","","","");
		return str_replace($qian,$hou,$str);
	 }
	 public static function orderSn(){ //生成20位订单号
	 	  $arr=['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','1','2','3','4','5','6','7','8','9'];
	 	  $sj='';
	 	  for($i=0;$i<4;$i++){
	 	  	$sj.=$arr[rand(0,34)];
	 	  }
	      return  date('y').date('m').date('d').date('H').date('i').date('s').$sj.substr(microtime(),2,4);//生成订单号 20位数
                       //年(2)月（2）日（2）时(2) 分(2) 秒(2) 随机数（4）毫秒数（4）
	 }

	 public static function goodsNumberSn(){//商品编号生成
	 	$arr=['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','1','2','3','4','5','6','7','8','9'];
	 	$sj='';
	 	for($i=0;$i<2;$i++){
	 		$sj.=$arr[rand(0,34)];
	 	}
	 	return  'N'.date('y').date('m').date('d').substr(time(),-5).$sj.substr(microtime(),2,4);//生成订单号 20位数
	 	//(1)年(2)月（2）日（2）一天的秒数（5）随机数（2）毫秒数（4）
	 }

	 public static function random($wei){//随机数生成
	 	$arr=['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','1','2','3','4','5','6','7','8','9'];
	 	$sj=null;
	 	for($i=0;$i<$wei;$i++){
	 		$sj.=$arr[rand(0,34)];
	 	}
	 	return  $sj;
	 }
 
	 public static function getStrtotime($time){//把时间转换成时间戳 20141212222222转成1412563265
	 	$year=substr($time,0,4);
	 	$month=substr($time,4,2);
	 	$day=substr($time,6,2);
	 	$hour=substr($time,8,2);
	 	$minute=substr($time,10,2);
	 	$s=substr($time,12,2);
	 	$new=$year.'-'.$month.'-'.$day.' '.$hour.':'.$minute.':'.$s;
	 	return $time=strtotime($new);
	 }

	 public static function getStrtotime_2($time){//把时间转换成时间戳  2009-08-12 11:08:32转换成 1423265326
        if($time==null){
        	return null;
        } 
	 	return  strtotime($time);
	 }
	 
	 public static function getTime($time){
	 	$nowtime=time();
	 	$timeLong=$nowtime-$time;
	 	if($timeLong<60){   //一分钟以内
	 		$text="刚刚";
	 	}
	 	else if($timeLong>=60&&$timeLong<60*60){  //一小时以内
	 		$text=floor($timeLong/60).'分钟前';
	 	}
	 	else if($timeLong>=60*60&&$timeLong<60*60*24){  //一天以内
	 		$text=floor($timeLong/3600).'小时前';
	 	}
	 	else if($timeLong>=60*60*24&&$timeLong<60*60*24*7){  //一周以内
	 		$text=floor($timeLong/(3600*24)).'天前';
	 	}
// 	 	else if($timeLong>=60*60*24*7&&$timeLong<60*60*24*30){  //一月以内
// 	 		$text=round($timeLong/(3600*24*7)).'周前';
// 	 	}
	 	else{  //一周后
	 		$text=date("Y年m月d日",$time);
	 	}
	 	return $text;
	 }
 
	 public static function dealtime($time){    //获取今天、昨天、本周、本月的第一天0点时间戳
	 	$d_z=date('Y-m-d',strtotime("-1 day"));//获得昨日的日期 2014-03-9
	 	$d_z_c=strtotime($d_z);  //获得昨日凌晨零点的时间戳
	 	$d_j=date('Y-m-d');//获得今日的日期 2014-03-9
	 	$d_j_c=strtotime($d_j);  //获得今日凌晨零点的时间戳
	 	//	$week=date("Y-m-d",mktime(0,0,0,date("m"),date("d")-date("w")+1,date("Y"))); //获得本周周一日期 2014-05-03
	 	//	$week_c=strtotime($week);  //获得本周凌晨零点的时间戳
	 	$week=date('N')-1;  //一周中的第几天
	 	$week_c=$d_j_c-$week*3600*24;  //获得本周凌晨零点的时间戳
	 	$month=date('Y-m-').'01';//获得本月的第一天2014-05-01
	 	$month_c=strtotime($month); //获得本月凌晨零点的时间戳
	 	switch($time){
	 		case 'today': $arr=array($d_j_c,$d_j_c+86400);break;
	 		case 'yesterday': $arr=array($d_z_c,$d_j_c);break;
	 		case 'week': $arr=array($week_c,time());break;
	 		case 'month': $arr=array($month_c,time());break;
	 		default: $arr=array(1,time());break;//所有时间
	 	}
	 	return $arr;
	 }
 

	 public static function getZeroStrtotime($text){    //$text=today || yestoday || week || month  获取今天、昨天、本周、本月的第一天0点时间戳
	 	$d_z=date('Y-m-d',strtotime("-1 day"));//获得昨日的日期 2014-03-9
	 	$d_z_c=strtotime($d_z);  //获得昨日凌晨零点的时间戳
	 	$d_j=date('Y-m-d');//获得今日的日期 2014-03-9
	 	$d_j_c=strtotime($d_j);  //获得今日凌晨零点的时间戳
	 	$d_t_c=$d_j_c+3600*24;//获得明天0点 
	 	$week=date('N')-1;  //一周中的第几天
	 	$week_c=$d_j_c-$week*3600*24;  //获得本周凌晨零点的时间戳
	 	$month=date('Y-m-').'01';//获得本月的第一天2014-05-01
	 	$month_c=strtotime($month); //获得本月凌晨零点的时间戳
	 	switch($text){
	 		case 'today': $time=$d_j_c;break;
	 		case 'tomorrow': $time=$d_t_c;break;
	 		case 'yesterday': $time=$d_z_c;break;
	 		case 'week':  $time=$week_c;break;
	 		case 'month': $time=$month_c;break;
	 		default: $time=time();
	 	}
	 	return $time;
	 }
	 
	 
    public static function transformAmountScore($sum){//把元为单位的金额转换成分为单位的金额12.03
    	$value=$sum*100;
    	$re = sprintf("%d",$value);
    	return $re;
    } 
    
    public static function transformAmountYuan($sum){//把分为单位1203的金额转换成元为单位的金额12.03
    	$value=$sum/100;
    	$re = sprintf("%.2f",$value);
    	return $re;
    }
 
    public static function hourList(){//24小时列表
       $arr=[];
       for($i=0;$i<24;$i++){
       	 if($i<10){
       	 	$key='0'.$i;
       	 	$arr[$key]=$key;
       	 }else{
       	 	$arr[$i]=$i;
       	 }
       }
       return $arr;
    }

    public static function mintueListZ(){//分钟列表 
    	return $arr=['00'=>'00','05'=>'05','10'=>'10','15'=>'15','20'=>'20','25'=>'25','30'=>'30','35'=>'35','40'=>'40','45'=>'45','50'=>'50','55'=>'55'];
    }

    public static function byStringGetArray($string){//把类似这种字符串【rand_code=xxxx&identifier=yyyyy】分割成数组。
    	$arr2=explode('&',$string);
    	$arr=[];
    	for($i=0;$i<count($arr2);$i++){
    		$arr3=explode('=',$arr2[$i]);
    		$arr[$arr3[0]]=$arr3[1];
    	}
    	return $arr;
    }
    
    public static function randCode(){  //生成随机验证码 6位数字
            $code='';
            for ($i=1;$i<=6;$i++){
                $code.=rand(0, 9);
            }
            return $code;
    }
   
    public static function restTime($deadline){  //计算剩下的时间     deadline为最后期限
        $time= $deadline-time(); //设置添加物流后3天自动确认订单提示
        if($time>24*3600){
            $day=intval($time/(24*3600));
            $hour=intval(($time-$day*24*3600)/3600);
            $result=$day.'天'.$hour.'小时';
        }else {
            $hour=intval($time/3600);
            $min=intval(($time-$hour*3600)/60);
            $result=$hour.'小时'.$min.'分';
        }
        return $result;
    }

    public static function mergerImg($dst,$src,$save_url) {// dst背景图  src二维码图 save_url合成图片保存地址   合成图片方法，也就是在一张背景图上面加上一个二维码
        list($max_width, $max_height) = getimagesize($dst);
        $dests = imagecreatetruecolor($max_width, $max_height);
        $dst_im = imagecreatefromjpeg($dst);
        imagecopy($dests,$dst_im,0,0,0,0,$max_width,$max_height);
        imagedestroy($dst_im);
        $src_im = imagecreatefrompng($src);
        $src_info = getimagesize($src);
        imagecopy($dests,$src_im,490,790,0,0,$src_info[0],$src_info[1]);
        imagedestroy($src_im);
        imagejpeg($dests,$save_url); //$save_url='D:\WWW\ceshi\img\12345.jpg'
    }
  

}
 
?>