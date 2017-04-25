<?php 
use app\components\qiniu\Auth;
use app\components\qiniu\QiniuConfig;
use app\components\qiniu\QiniuToken;
$this->registerJsFile(Yii::$app->homeUrl.'widget/uploadify/jquery.uploadify.js',['depends' => [yii\web\JqueryAsset::className()]]);
$this->registerCssFile(Yii::$app->homeUrl.'widget/uploadify/uploadify.css',['depends' => [yii\web\JqueryAsset::className()]]);
?>
<!--
	这个加载的是uploadify的js和css等，怎么用uploadify上传到七牛，然后再服务器做定时处理或者用软件定时下载到服务器上，解决带宽不够大
	上传速度慢的问题
-->
<style type="text/css">
#file_upload{
		 left:38%;
		 margin-top:30px;
		 margin:0 atuto;}
.uploadify-queue-item{
		margin-left:70px;
}
.queue-item {
	margin-left: 60px;
}
.swfupload{
	padding-right:30px;
	right: -30px;
}
</style>
</head>
<body>
	点击“浏览”按钮，选择您要上传的文档文件后，系统将自动上传并在完成后提示您。
	<p style="color:red;font-weight: bold;">请将文件压缩成ZIP或者RAR格式，否则可能会上传失败</p>
	<form>
		<div id="queue"></div>
		<input id="file_upload" name="file" type="file" multiple="true">
	</form>
	<label id="notice"></label>
	<?php
	require_once(Yii::$app->basePath."/components/qiniu/io.php");
	require_once(Yii::$app->basePath."/components/qiniu/rs.php");
	require_once(Yii::$app->basePath."/components/qiniu/fop.php");
	$bucket = "qiaosheng";
	$accessKey = 'QWsoSLf5-RY0bY2Gd5dABwCnuKueYBEtihFwJPin';
	$secretKey = 'NRUJ_wDyTA9mdyCXbsCNmsjp_sOY-WEGxoWNfoUE';
	Qiniu_SetKeys($accessKey, $secretKey);
	$putPolicy = new Qiniu_RS_PutPolicy($bucket);
	$upToken = $putPolicy->Token(null);
	$uptoken=QiniuConfig::getUploadToken();
	echo $uptoken;
	$auth=new Auth($accessKey, $secretKey);
	$bucket_url="http://7xqa7b.media1.z0.glb.clouddn.com/";
	$baseUrl = $bucket_url.'20160120-101523-11612.flv';
	$downloadUrl=$auth->privateDownloadUrl($baseUrl);
	echo $downloadUrl;
	?>
	
	<script type="text/javascript">
	<?php $this->beginBlock('MY_VIEW_JS_END') ?>
		//保留两位小数
		function formatFloat(src, pos)
		{
		   return Math.round(src*Math.pow(10, pos))/Math.pow(10, pos);
		}
		//计算尺寸
		function size(filesize)
		{
			var size = null;
			if(filesize/1048756 < 1){
				size=formatFloat(filesize/1024, 2)+"KB";
			}else if(filesize/1073747824<1){
				size=formatFloat(filesize/1048756, 2)+"MB";
			}else{
				size=formatFloat(filesize/1073747824, 2)+"GB";
			}
			return size;
		}
		$(function() {
			var filetype = null;
			var filesize = null;
			var now=new Date();
			$('#file_upload').uploadify({
				'onSelect' : function(file){
					filetype = file.type;
					$('#file_upload').uploadify('settings', 'formData', {"key":"<?php echo date("Ymd-His") . '-' . rand(10000,99999);?>"+filetype}) //上传文件的名称
                },
				'fileObjName' : 'file',  //七牛的上传的字段名称是file
				'formData'     : {
                    'token'     : '<?php echo $upToken;?>', //token
                },
				
				'buttonText'  : '选择文件',
				'swf'      : '<?=Yii::$app->homeUrl.'widget/uploadify/' ?>uploadify.swf',
				'uploader' : 'http://up.qiniu.com/',
				'method'   : 'POST',
				'onUploadSuccess' : function(file, data, response) {
					  filesize = size(file.size);
					  window.top.$('#softsize').val(filesize);
					  var data = $('#file_upload').uploadify('settings','formData');
					  window.top.$('#test').val(data.key);
                    if(data.key!==""){
						$("#notice").html("<font style='color:#73b304;bolid;font:700 14px Arial, Helvetica, sans-serif;'>上传成功！重新上传请单击选择文件按钮！</font>");
					}else{
						$("#notice").html("<font style='color:red;bolid;font:700 14px Arial, Helvetica, sans-serif;'>上传失败，请联系管理员！</font>");
					}
                 },
			});
		});
		<?php $this->endBlock(); ?>
		</script>

		<?php
		    $this->registerJs($this->blocks['MY_VIEW_JS_END'],\yii\web\View::POS_END);
		?>
