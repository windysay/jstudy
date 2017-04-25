/**
 * js 验证类
 */
function Validate(){  //验证类
    this.formVMail=function(idname){//表单中，输入邮箱的验证，有样式绑定【传入input的id】
    	  var value=$("#"+idname).val()
		  var mail=/^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;    //邮箱的正则表达式
			  if(!mail.test(value)) {//如果验证不通过
				  $("#"+idname).siblings(".my_f_warn").remove();
				  $("#"+idname).parent().addClass("has-error")
                  $("#"+idname).after('<p class="my_f_warn">请输入正确的邮箱</p>')
		          return '_no_'
		      }else{//如果通过
				  $("#"+idname).parent().removeClass("has-error")
	              $("#"+idname).siblings(".my_f_warn").remove();
				  return value
		      } 
    } 
    this.formVMobile=function(idname){//表单中，输入手机号码的验证，有样式绑定【传入input的id】
  	  var value=$("#"+idname).val()
		  var mail=/^(13|14|15|17|18)[0-9]{9}$/;    //手机的正则表达式
			  if(!mail.test(value)) {//如果验证不通过
				  $("#"+idname).siblings(".my_f_warn").remove();
				  $("#"+idname).parent().addClass("has-error")
                $("#"+idname).after('<p class="my_f_warn">请输入正确的手机号码</p>')
		          return '_no_'
		      }else{//如果通过
				  $("#"+idname).parent().removeClass("has-error")
	              $("#"+idname).siblings(".my_f_warn").remove();
				  return value
		      } 
  } 
    this.formVPassword=function(idname){//表单中，输入密码的验证，有样式绑定【传入input的id】
    	  var value=$("#"+idname).val()
  		  var mail=/^[A-Za-z0-9]{6,18}$/;    //密码的正则表达式
  			  if(!mail.test(value)) {//如果验证不通过
  				  $("#"+idname).siblings(".my_f_warn").remove();
  				  $("#"+idname).parent().addClass("has-error")
                  $("#"+idname).after('<p class="my_f_warn">密码为6-18位字母或数字组合</p>')
  		          return '_no_'
  		      }else{//如果通过
  				  $("#"+idname).parent().removeClass("has-error")
  	              $("#"+idname).siblings(".my_f_warn").remove();
  				  return value
  		      } 
    } 
      
    this.formVInt=function(idname){//	  表单中，判断输入的必须为6位数字(任意组合) 有样式绑定【传入input的id】
  	  var value=$("#"+idname).val()
  	  var mail=/^[0-9]{6}$/;    // 
  		  if(!mail.test(value)) {//如果验证不通过
  			  $("#"+idname).siblings(".my_f_warn").remove();
  			  $("#"+idname).parent().addClass("has-error")
                $("#"+idname).after('<p class="my_f_warn">验证码格式错误</p>')
  	          return '_no_'
  	      }else{//如果通过
  			  $("#"+idname).parent().removeClass("has-error")
                $("#"+idname).siblings(".my_f_warn").remove();
  			  return value
  	      }     
      }
 
 
  this.VMoney=function(value){//验证金额
	  var money=/^(([1-9]\d{0,9})|0)(\.\d{1,2})?$/;  //金额正则表达式
		  if(money.test(value)) {//如果验证通过
	          return true;
	      }else{//如果不通过
			  return false;
	      }   
}
 
    this.VMobile=function(value){//验证手机号码
		  var mobile=/^(13|14|15|17|18)[0-9]{9}$/    //手机号码的正则表达式
  		  if(mobile.test(value)) {//如果验证通过
  	          return true;
  	      }else{//如果不通过
  			  return false;
  	      }   
  }
        
  
 this.VMoneyX=function(value){//判断输入的必须为有点小数金额或者整数金额	  
	      var money=/^[0-9]{0,5}([0-9]{0,1}|\.\d{1,2})$/   	  
		  if(!money.test(value)) {
             return false
	      }else{
	    	  return true
	      }            
	}	
	  
 
 
 this.VPassword=function(value){//  密码长度必须为6到16位的数字或小写字母		  
		  var password=/[0-9|a-z]{6,16}/
	      if(!password.test(value)) {
	             return false
		  }else{
			     return true
		  }           
	}	
 
 
 this.VDate=function(value){   //日期类型为 2015-04-14
	  var date=/^(?:(?!0000)[0-9]{4}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-8])|(?:0[13-9]|1[0-2])-(?:29|30)|(?:0[13578]|1[02])-31)|(?:[0-9]{2}(?:0[48]|[2468][048]|[13579][26])|(?:0[48]|[2468][048]|[13579][26])00)-02-29)$/;
	  if(!date.test(value)) {
          return false
	  }else{
		     return true
	  } 
 }
	   
////////////////////////////
}