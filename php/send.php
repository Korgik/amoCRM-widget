<?
header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
header('Content-Disposition: attachment; filename="'.$my_file.'"');
header("Content-Type: application/force-download");
header("Content-Type: application/octet-stream");
header("Content-Type: application/download");
header("Content-Description: File Transfer");
$my_file = 'exec.txt';
$handle=fopen($my_file, "rb");
$contents = fread($handle, filesize($my_file)); 
echo $contents; 
fclose($handle); 

