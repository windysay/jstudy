<?php
namespace app\components;

use Yii;

class UploadImages64{
	const MAX_SIZE=7168000; //定义上传文件的最大容量   
//	const QINIU_URL='http://dn-windysay.qbox.me/';//https://dn-windysay.qbox.me/
	
//	public   $fileMimes;//上传支持的文件类型	
	public   $fileName;// = date("Ymdhis").rand(100,999);//上传后重命名文件的文件名	
	public   $newDir;//新建图片保存目录（形如：D:/appserv/www/upxin5/uploads/images/logo/20140117）
	public   $imageType;//上传文件的类型
	public   $imageFolder;//上传图片的文件夹+名称地址(类似：20140117/1389876456.jpg)	
  	public   $imageAbsolute;//上传图片的绝对地址（d:/www/upxin5/uploads/images/logo/20140117/132323.jpg）
  	//public   $imageurl;//上传图片的网络访问地址（http://www.hauhu.com/upxin5/uploads/images/logo/20140117/132323.jpg）
    public   $common;
 
 	public function __construct($file,$path_type){  //$path_type 为上传图片路径目录
 		switch ($path_type){
 			case 1:$path='store';break;
 			case 2:$path='headimg';break;
 			case 4:$path='material';break;
 			case 5:$path='teacher';break;
 			case 6:$path='course';break;
 			default:$path='other';break;
 		}
 		$this->common=Yii::getAlias("@webroot").'/';//取common的路径  别名
 		$this->fileName=date("ymdHis",time()).rand(100000,999999).'.jpg';
 		$this->imageType='jpg';
 		$dataDir=date('Ymd',time());
 		$this->newDir=$this->common.'images/'.$path.'/'.$dataDir;// 新建上传目录文件夹
 		$this->imageFolder=$path.'/'.$dataDir.'/'.$this->fileName; //设置图片文件夹+名称地址
 		//$this->imageurl=$this->common.'images/store/'.$this->imageFolder;//生成图片网络访问地址
 		$this->imageAbsolute=$this->newDir.'/'.$this->fileName;
 		//判断目录是否存在
 		//var_dump($this->newDir);
 		if (!is_dir($this->newDir)) {
 			mkdir($this->newDir,0777);  //最大权限0777,意思是，如果没有这个目录，那么就创建
 			$fp=fopen($this->newDir."/index.html","w+");//生成一张index.html空网页，防止被人看到此目录下面的文件
 			fwrite($fp,'');
 			fclose($fp);
 		} 		
 		$file_base64 = base64_decode($file);
 		file_put_contents($this->imageAbsolute,$file_base64);
	}
//////////////////////////////////////////////////	
 
   public function thumb($width,$height){
        @ini_set("memory_limit","1280M"); //处理大文件（4000px*3000px）
    	$new_width=$width;
    	$new_height=$height;
    	list($y_width, $y_height) = getimagesize($this->imageAbsolute);
        $im2 = imagecreatetruecolor($new_width,$new_height);
	    $im = imagecreatefromjpeg($this->imageAbsolute);
		imagecopyresampled($im2, $im, 0, 0, 0, 0,$new_width, $new_height, $y_width, $y_height);
		imagejpeg($im2,$this->imageAbsolute);//名称一样覆盖原图片
		imagedestroy($im);
		Imagedestroy($im2);
    }
 
    
  ////////////////////////////  
}
