<?
$prefix = 'custom_field_';
$title_key = $prefix . 568742;

$test = strpos($title_key, $prefix);
var_dump($test);
if (strpos($title_key, $prefix) !== false) {


}

$len = mb_strlen($prefix);
var_dump(substr($title_key, $len));









// /*формирование CSV*/

// $put = fopen($file, 'w');
// fputcsv($put, array_keys($titles), ";");
// $count = count($leads_info);
// for ($i = 0; $i < $count; $i++) {
//     $lead = array_shift($leads_info);
//     $row = array_merge($titles, $lead);
//     fputcsv($put, $row, ";");
// }
// fclose($put);
// echo $file;


