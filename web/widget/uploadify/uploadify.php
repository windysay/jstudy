<?php
/*
Uploadify
Copyright (c) 2012 Reactive Apps, Ronnie Garcia
Released under the MIT License <http://www.opensource.org/licenses/mit-license.php> 
*/

// Define a destination
$targetFolder = '/jstudy/web/uploads'; // Relative to the root

$verifyToken = md5('unique_salt' . $_POST['timestamp']);

if (!empty($_FILES) && $_POST['token'] == $verifyToken) {
	$tempFile = $_FILES['Filedata']['tmp_name'];
	$targetPath = $_SERVER['DOCUMENT_ROOT'] . $targetFolder;
	
	// Validate the file type
//	$fileTypes = array('jpg','jpeg','gif','png','txt','doc'); // File extensions
	$fileParts = pathinfo($_FILES['Filedata']['name']);
	$fileName=date("ymdHis",time()).rand(0,99).'.'.$fileParts['extension'];
// 	$fileName=$fileParts['basename'];
	$targetFile = rtrim($targetPath,'/') . '/'.$fileName ;
	
//	if (in_array($fileParts['extension'],$fileTypes)) {
	if(move_uploaded_file($tempFile, $targetFile)){
		echo $fileName;
	}
}else echo 0;
?>