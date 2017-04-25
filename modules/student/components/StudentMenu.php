<?php
namespace app\modules\student\components;

class StudentMenu{
 
	public function menuLeft($grade){//菜单列表权限，返回此管理员可以操作的目录
		switch($grade){
			case 1:$menu=['site','course','order'];break;
			default:$menu=['site','course','order'];break;
		}
		return $menu;
	}
 
	public function menuLeftName($con){    //后台左边导航栏的中文栏目名称,传入默认控制器即可获得
		$menu=array();
		$menu['site']="学生中心";
		$menu['course']="课程预约";
		$menu['order']="上课券";
		return $menu[$con];
	}
 
     public function menuList($con=null){   //所有控制器以及控制器的动作
     	$menuarr=array();
     	
     	$menu_1['index']='学生中心';
     	$menu_1['info']='个人信息';
     	$menu_1['safe']='账户安全';
     	$menu_1['suggestion']="意见建议";
     	$menuarr['site']=$menu_1; //
     	
     	$menu2['index']='已预约课程';
     	$menu2['completed']='已完成课程';
     	$menu2['canceled']='取消预约记录';
     	$menuarr['course']=$menu2; //系统设置
     	
     	$menu3['index']='购买记录';
     	$menuarr['order']=$menu3; //系统设置
     	
  		

     	if($con)   //如果有就返回此控制器的所有动作
     		return $menuarr[$con];
     	else //否则就返回所有控制器
     		return $menuarr;
     }
 
     public function  accessVisit($grade=1){//会员访问权限，返回此管理员可以操作控制器
     	$menu=array();
     	$menu[]='site';//我的服务
     	$menu[]='course';//订单中心
        $menu[]='suggestion';//购物车    
        $menu[]='order';//购物车    
     	return $menu;
     }
 
///////////////////////////////
}