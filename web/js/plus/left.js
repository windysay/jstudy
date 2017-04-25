var testVar = 1;
var page = 1;    //版页
var i = 4;       //每页显示图片个数


function rotatePlayer( toward ){
	
 var $ul = $( ".pmc_top_menu_fangan" );                                   //找到图片的展示区域模块
 var $ul_width = $( ".pmc_top_menu_layer_fangan" ).width();                   //找到外围div的宽度
 var $length = $( ".pmc_top_menu_fangan li" ).length;
 var $page_count = Math.ceil( $length / i );
 
 if( !$ul.is( ":animated" ) )                   //判断图片展示区域是否处于动画阶段
 {
  switch( toward )
  {
   case 'right':
    if( page == $page_count )
    {
     $ul.animate( {left:'0px'}, "slow" );      //可选参数 hide();或者fadeIn(),fadeOut()方法
     page = 1;
    }
    else
    {
     $ul.animate( {left:'-=' + $ul_width}, "slow" );
     page++;
    }
   break;
   case 'left':
    if( page == 1 )
    {
     $ul.animate( {left:'-=' + $ul_width * ( $page_count - 1 )}, "slow" );
     page = $page_count;
    }
    else
    {
     $ul.animate( {left:'+=' + $ul_width}, "slow" );
     page--;
    }
   break;
  }
 }
 
//$( ".pmc_top_widget b" ).eq( page - 1 ).addClass( "this" ).siblings().removeClass( "this" )
}
 
 


   //自动滚动
 
   setInterval(function() {
   $('.roll_pictrue .next').trigger('click');
//  $('.rgt_product').trigger('click');
   },10000)
 
  //自动滚动
 
 