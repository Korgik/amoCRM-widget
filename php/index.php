<?
header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
header('Access-Control-Allow-Headers: Origin,Content-Type,Accept,X-Requested-With');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: content-type');
$data = $_POST;
include "Api.php";
$api = new Api();

// Авторизация
$api->api_auth($data['login'], $data['hash'], $data['subdomain']);
$all_lead = $api->list_leads($data['ids'], $data['subdomain']);
$all_leads = $all_lead["_embedded"]["items"];
$leads_info = [];
foreach ($all_leads as $key => $value) {
  // var_dump($all_leads[$key]['custom_fields']);
  $leads_info[$key]['name'] = $value['name'];
  $leads_info[$key]['created_at'] = date("d.m.y, H:i:s", $value['created_at']);
  $leads_info[$key]['company'] = $value['company']['id'];
  $leads_info[$key]['contacts'] = $value['contacts']['id'];
  foreach ($value['tags'] as $tag_key => $tag_value) {
    $leads_info[$key]['tags'] .= $tag_value["name"];
  }
  // var_dump($leads_info);
  // кастом поля
  foreach ($value["custom_fields"] as $key_fileds => $value_fields) {
    foreach ($value_fields['values'] as $key_custom => $value_custom) {
      // проверка на юр лицо
      if(is_array($value_custom['value'])){
        foreach ($value_custom as $key_value => $value_value){
          $leads_info[$key]['custom_field_' . $value_fields['id']] .= $value_value['name'] . ',ИНН:' . $value_value['vat_id'] . ',КПП:' . $value_value['kpp'];
        }
      } else{
        $leads_info[$key]['custom_field_' . $value_fields['id']] .= $value_custom['value'];
      }
    }
  }

// заносим компании
  $data_company_all = $api->list_company($leads_info[$key]['company'], $data['subdomain']);
  $data_company[] = $data_company_all["_embedded"]["items"];
  foreach ($data_company as $key_company => $value_company) {
    $leads_info[$key]['company'] = $value_company[0]["name"];
  }
// заносим имена контактов вместо id
  $data_contact_all = $api->list_contact($leads_info[$key]['contacts'], $data['subdomain']);
  foreach ($data_contact_all["_embedded"]["items"] as $key_data => $value_data) {
    $leads_info[$key]['contacts'][$key_data] = $value_data["name"];
  }
}
// Клеим контакты
foreach ($leads_info as $key => $value) {
  $leads_info[$key]['contacts'] = implode(",", $value['contacts']);
}
// Клеим теги
foreach ($leads_info as $key => $value) {
  if (count($leads_info[$key]['tags']) > 1) {
    $leads_info[$key]['tags'] = implode(",", $value['tags']);
  }
}
var_dump($leads_info);
$titles = [
  'name' => "Имя сделки",
  'created_at' => "Дата создания",
  'company' => "Компания",
  'contacts' => "Контакты",
  'tags' => "Теги",
];

foreach ($all_leads as $key_lead => $value_lead) {
  foreach ($value_lead['custom_fields'] as $key_field => $value_field) {
    $titles['custom_field_' . $value_field['id']] = $value_field['name'];
  }
}
// var_dump($leads_info);
$fp = fopen('file.csv', 'w');
fputcsv($fp, $titles, ";");

$rows = [];
$title_keys = array_keys($titles);
$prefix = 'custom_field_';
$prefix_len = mb_strlen($prefix);
foreach ($leads_info as $key_lead => $value_lead) {
  $row = [];
  foreach ($title_keys as $title_key) {
    if (array_key_exists($title_key, $value_lead)) {
      $row[$title_key] = $value_lead[$title_key];
      $rows[] = $row;
    } elseif (strpos($title_key, $prefix) !== false) {
      $id_custom_field = substr($title_key, $prefix_len);
      $row['custom_field_' . $id_custom_field] = $value_lead[$title_key];
      $rows[] = $row;
    } else {
      $row[$title_key] = "";
      $rows[] = $row;
    }
  }
  fputcsv($fp, $row, ";");
}
fclose($fp);

echo "https://widget.ru/file.csv";
