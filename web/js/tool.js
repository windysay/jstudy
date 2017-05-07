function warn(tool,status){//tool提示语  status状态 成功或者失败,一般用于保存成功之后，跳转回来之后提示用户。
    		if(status==1)
    		      $("body").append('<div class="_js_warn"><span class="tool_success tool">'+tool+'</span></div>')
    		else
    		      $("body").append('<div class="_js_warn"><span class="tool_failure tool">'+tool+'</span></div>')
     setTimeout(function(){$("._js_warn").slideUp(300)},2600)
}

function warnRedirect(tool,status,url){//tool提示语  status状态 成功或者失败, url跳转的地址
	if(status==1)
	      $("body").append('<div class="_js_warn"><span class="tool_success tool">'+tool+'</span></div>')
	else
	      $("body").append('<div class="_js_warn"><span class="tool_failure tool">'+tool+'</span></div>')
setTimeout(function(){window.location.href=url},2000)
}

function deleteAlert(id,tool){//删除提示框
    if($(".w_ajax_delete_box").length > 0 ){//如果已经存在
   	 $(".ok_btn").attr("data-id",id)
   	 $(".modal_header_tool").text(tool)
   	 $('.w_ajax_delete_box').modal() 
    }else{ 
       $('body').after('<div class="modal fade bs-example-modal-sm w_ajax_delete_box" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true"><div class="modal-dialog modal-sm"><div class="modal-content"><div class="modal-header modal_header_tool">'+tool+'</div><div class="modal-body"><button type="button"  class="col-xs-offset-2 col-sm-offset-2 col-md-offset-2 col-lg-offset-2 btn btn-success ok_btn" data-id="'+id+'"  onclick="ok_btn(this)"  data-toggle="modal" data-target=".bs-example-modal-sm">確認</button><button type="button" class="col-xs-offset-3 col-sm-offset-3 col-md-offset-3 col-lg-offset-3 btn btn-warning no_btn"  onclick="no_btn(this)" data-toggle="modal"  data-target=".bs-example-modal-sm">やり直す</button> </div></div></div></div>')
       $('.w_ajax_delete_box').modal()
    }
}
 
function deleteAlertMore(id,tool,function_name){//删除提示框,用于一张页面有多个动作要调用提示框
   if($(".w_ajax_delete_box").length > 0 ){//如果已经存在
  	 $(".ok_btn").attr("data-id",id)
  	 $(".ok_btn").attr("onclick",function_name+'(this)')
  	 $(".modal_header_tool").text(tool)
  	 $('.w_ajax_delete_box').modal() 
   }else{ 
      $('body').after('<div class="modal fade bs-example-modal-sm w_ajax_delete_box" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true"><div class="modal-dialog modal-sm"><div class="modal-content"><div class="modal-header modal_header_tool" >'+tool+'</div><div class="modal-body"><button type="button"  class="col-xs-offset-2 col-sm-offset-2 col-md-offset-2 col-lg-offset-2 btn btn-success ok_btn" data-id="'+id+'"  onclick="'+function_name+'(this)"  data-toggle="modal" data-target=".bs-example-modal-sm">確認</button><button type="button" class="col-xs-offset-3 col-sm-offset-3 col-md-offset-3 col-lg-offset-3 btn btn-warning no_btn"  onclick="no_btn(this)" data-toggle="modal"  data-target=".bs-example-modal-sm">やり直す</button> </div></div></div></div>')
      $('.w_ajax_delete_box').modal()
   }
}

function nowWarnBeij(status,tool,url){//成功还是失败 提示语 跳转地址【废弃】
	$("#alert_beij_div").remove()
	if(status==1){//成功
	    $("body").append('<div id="alert_beij_div"><div class="_success_warn_ _warn_box">'+tool+'</div></div>')
        $("#_success_warn_").slideDown(400)
		setTimeout(function(){$("#alert_beij_div").slideUp(300)},1500);//隐藏
		if(url!=0){
		    setTimeout(function(){window.location.href=url},2000)
		}
	}else{//失败
	    $("body").append('<div id="alert_beij_div"><div class="_failure_warn_ _warn_box">'+tool+'</div></div>')
	    $("#_failure_warn_").slideDown(300)
		setTimeout(function(){$("#alert_beij_div").slideUp(300);},1500);//隐藏
		if(url!=0){
		    setTimeout(function(){window.location.href=url},2000)
		}
	}
    var width=$("._warn_box").width()
    var left=parseInt(width/2)
    $("._warn_box").css('margin-left',-left)
}

function showCenterBox(tishi){//在页面正中间显示提示框，并且有隐藏框的按钮
	var html='<div class="alert_center_box">'+tishi+'<span class="glyphicon glyphicon-remove close_span"></span></div>'
	$("body").after(html)
    var width=$(".alert_center_box").width()
    var height=$(".alert_center_box").height()
    var left=parseInt(width/2)
    var top=parseInt(height/2)
    $(".alert_center_box").css('margin-left',-left)
    $(".alert_center_box").css('margin-top',-top)
}
$(document).on('click',".alert_center_box .close_span",function(){
    $(".alert_center_box").remove()
})

 
function setMyCookie(name,value,day){ //设置cookie,传入cookie名称，值，以及过期时间，一般以天为单位
	 var date=new Date(); //获取当前时间 
	 date.setTime(date.getTime()+day*24*3600*1000); 
	 document.cookie=name+'='+value+';path=/;expires='+date.toUTCString(); // //将userId和userName两个cookie设置为1天后过期 
}

function getMyCookie(c_name){//读取cookie的值
	 if (document.cookie.length>0){
	   c_start=document.cookie.indexOf(c_name + "=")
		   if (c_start!=-1){ 
		     c_start=c_start + c_name.length+1 
		     c_end=document.cookie.indexOf(";",c_start)
		     if (c_end==-1) c_end=document.cookie.length
		     return unescape(document.cookie.substring(c_start,c_end))
		     } 
	   }
	 return ""
}

function deleteMyCookie(name) {  //删除已经设置好的全局cookie, “;path=/”
   var date = new Date();
   date.setTime(date.getTime() - 10000); //删除一个cookie，就是将其过期时间设定为一个过去的时间
   document.cookie=name+'=0;path=/;expires=' + date.toUTCString();//解决了safari浏览器的问题
}

function deleteMyCookie2(name) {  //删除已经设置好的cookie,只能删除当前页面的cookie
   var date = new Date();
   date.setTime(date.getTime() - 10000); //删除一个cookie，就是将其过期时间设定为一个过去的时间
   document.cookie = name + "=0;expires=" + date.toUTCString();
}
 
function date2str(d) {//js格式化时间  015-06-16 05；43:34
               var ret = d.getFullYear() + "-"
               ret += ("00" + (d.getMonth() + 1)).slice(-2) + "-"
               ret += ("00" + d.getDate()).slice(-2) + " "
               ret += ("00" + d.getHours()).slice(-2) + ":"
               ret += ("00" + d.getMinutes()).slice(-2) + ":"
               ret += ("00" + d.getSeconds()).slice(-2)
               return ret;
}

 
 












