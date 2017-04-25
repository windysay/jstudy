<?php
namespace app\modules\admin\components;

class AdminMenu{
 
public function menuTop($grade=1){//返回管理员后台最上面一栏的操作模块
	switch($grade){
		case 1:$menu=['site','course','teacher','student',/* 'voucher', */'order','news','account'];break;
		default:$menu=['site','wechat','goods'];break;
	}
	return $menu;
}

public function menuTopName($con){    //后台顶部导航栏的中文栏目名称,传入默认控制器即可获得和menuLeftName相对应
	$menu=array();
	$menu['site']="首页";
	$menu['course']="课程管理";
	$menu['teacher']="讲师管理";
	$menu['student']="学生管理";
	$menu['order']="订单管理";
//	$menu['voucher']="课券管理";
	$menu['news']="新闻公告";
	$menu['download']="教材资源";
	$menu['account']="账户安全";
	return $menu[$con];
}

public function menuLeftName($con){    //后台左边导航栏的中文栏目名称,传入默认控制器即可获得
	$menu=array();
	$menu['site']="常用";
	$menu['course']="课程管理";
	$menu['teacher']="讲师管理";
     $menu['student']='学生管理';
	$menu['order']='订单管理';
//	$menu['voucher']='课券管理';
	$menu['news']='新闻公告素材';
	$menu['download']='教材资源';
	$menu['account']="账户安全";
	return $menu[$con];
}
 
public function menuLeftCon($con){   //模块分别管理的控制器,传入控制器名称，返回控制器所属模块下面所有的控制器
	$f_m=array();
	$f_m['site_m']=['site']; //该模块所包含的控制器
	$f_m['course_m']=['course']; //该模块所包含的控制器
	$f_m['teacher_m']=['teacher']; //该模块所包含的控制器
	$f_m['student_m']=['student']; //该模块所包含的控制器
	$f_m['order_m']=['order']; //该模块所包含的控制器
//	$f_m['voucher_m']=['voucher']; //该模块所包含的控制器
  	$f_m['news_m']=['news']; //该模块所包含的控制器  
  	$f_m['download_m']=['download']; //该模块所包含的控制器  
	$f_m['account_m']=['account']; //该模块所包含的控制器
    foreach($f_m as $k=>$v){
 			if(in_array($con,$v))
	 				return $f_m[$k];//就返回模块下面所有的控制器
		 	 }
     }
  
     public function menuList($con=null){   //所有控制器以及控制器的动作
     	$menuarr=array();
     	
     	$menu_0['index']='管理员中心';
     	$menuarr['site']=$menu_0;
     	 
     	
    // 	$menu_1['index']='预约概况';
     	$menu_1['index']='未来预约记录';
     	$menu_1['history-record']='历史预约/取消记录';
     	$menu_1['completed']='完成课程一览';
     	$menu_1['setmeal']='课程套餐设置'; //微信
     	$menuarr['course']=$menu_1; //微信
     	
    	 $menu_2['index']='所有学生';
          $menuarr['student']=$menu_2; //微信

          $menu_3['index']='全部讲师';
          $menu_3['teacher-time']='讲师时间表'; //微信
          $menu_3['create']='登记讲师';
          $menu_3['hastime']='已提交课程老师';
          $menuarr['teacher']=$menu_3; //微信
          
          $menu_4['index']='所有订单';
          $menu_4['money']='资金流水';
          $menuarr['order']=$menu_4; //微信
          
          $menu_6['index']='文字公告';
          $menu_6['category']='栏目管理';
          $menu_6['ppt']='海报公告';
          $menu_6['contact']='联系我们';
          $menuarr['news']=$menu_6; //微信

          $menu_5['index']='教材资源';
          $menuarr['download']=$menu_5; //微信
          
          $menu_7['index']='用户名/邮箱';
          $menu_7['reset-password']='修改密码';
          $menuarr['account']=$menu_7; //微信
           

     	if($con)   //如果有就返回此控制器的所有动作
     		return $menuarr[$con];
     	else //否则就返回所有控制器
     		return $menuarr;
     }
 
 
     public function  accessVisit($grade=1){//管理员访问权限，返回此管理员可以操作控制器
     	$menu=array();
     	$menu[]='site';//
     	$menu[]='course';//
     	$menu[]='teacher';
      	$menu[]='student';//
     	$menu[]='order';//
 //    	$menu[]='voucher';//
     	$menu[]='news';//相亲
     	$menu[]='download';//相亲
     	$menu[]='account';//账目
          return $menu;
     }
     
     
      
///////////////////////////////
}