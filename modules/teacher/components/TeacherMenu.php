<?php
namespace app\modules\teacher\components;

class TeacherMenu{
 
	public function menuLeft($grade=1){//菜单列表权限，返回此管理员可以操作的目录
		switch($grade){
			case 1:$menu=['site','course'];break;
			default:$menu=['site','course'];break;
		}
		return $menu;
	}
	 
	public function menuLeftName($con){    //后台左边导航栏的中文栏目名称,传入默认控制器即可获得
		$menu=array();
		$menu['site']="講師センター"; //教师中心
		$menu['course']="授業管理";
		return $menu[$con];
	}
 
     public function menuList($con=null){   //所有控制器以及控制器的动作
     	$menuarr=array();
     	     	
     	$menu_1['index']='マイホーム'; //我的主页
//      	$menu_1['info']='个人资料';
     	$menu_1['login-psd']='パスワード変更'; //通过邮箱修改密码
     	$menuarr['site']=$menu_1; //
     	
     	$menu_2['index']='シフト提出'; //我的课程
     	$menu_2['history']='済み記録'; //课程完成记录
     	$menu_2['cancel']='キャンセル記録'; //课程取消记录
     	$menuarr['course']=$menu_2; //微信



     	if($con)   //如果有就返回此控制器的所有动作
     		return $menuarr[$con];
     	else //否则就返回所有控制器
     		return $menuarr;
     }
 
     public function  accessVisit($grade=1){//会员访问权限，返回此管理员可以操作控制器
     	$menu=array();
     	$menu[]='site';//生徒预约
     	$menu[]='course';//我的時間表
     	return $menu;
     }
 
///////////////////////////////
}