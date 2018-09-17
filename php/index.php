<?
header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
header('Access-Control-Allow-Headers: Origin,Content-Type,Accept,X-Requested-With');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: content-type');
include "api.php";
$api = new Api();
$_id_list = $_POST['ids'];
$all_lead = $api->list_leads($_id_list);

$all_leads = $all_lead["_embedded"]["items"];

$leads_info = [];

foreach ($all_leads as $key => $value) {
  $leads_info[$key]['name'] = $value['name'];
  $leads_info[$key]['created_at'] = date("d.m.y, H:i:s" ,$value['created_at']);
  $leads_info[$key]['company'] = $value['company']['id'];
  $leads_info[$key]['contacts'] = $value['contacts']['id'];
  foreach ($value['custom_fields'] as $key_custom_value_all => $value_custom_all) {
      foreach($value_custom_all["values"] as $key_values => $value_customs) {
        $leads_info[$key]["custom_fields_values"][] = $value_customs["value"];
      }
  }
  foreach ($value['tags'] as $tag_key => $tag_value) {
    $leads_info[$key]['tags'] = $tag_value["name"];
  }
// заносим компании
  $data_company_all = $api->list_company($leads_info[$key]['company']);
  $data_company[] = $data_company_all["_embedded"]["items"];
  foreach ($data_company as $key_company => $value_company) {
    $leads_info[$key]['company'] = $value_company[0]["name"];
  }

// заносим контакты
  $data_contact_all = $api->list_contact($leads_info[$key]['contacts']);
  foreach ($data_contact_all["_embedded"]["items"] as $key_data => $value_data) {
    $leads_info[$key]['contacts'][$key_data] = $value_data["name"];
  }


}

foreach ($leads_info as $key => $value) {
    $leads_info[$key]['contacts'] = implode(",", $value['contacts']);
}

foreach ($leads_info as $key => $value) {
    if(count($value['custom_fields_values']) > 0) {
      $leads_info[$key]['custom_fields_values'] = implode(",", $value['custom_fields_values']);
    }
  }

$fp = fopen('file.csv', 'w');

$titles = ["Имя сделки", "Дата создания", "Компания", "Контакты", "Доп.поля", "Тэги"];
fputcsv($fp, $titles, ",");


foreach($leads_info as $key => $value) {
  fputcsv($fp, $value, ",");
}
fclose($fp);

echo "https://widget.ru/file.csv";
