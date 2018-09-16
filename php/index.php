<?
header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
header('Access-Control-Allow-Headers: Origin,Content-Type,Accept,X-Requested-With');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: content-type');
include "api.php";
$api = new Api();
$_id_list = $_POST['ids'];
// echo $_id_list;
$all_lead = $api->listleads($_id_list);

$all_leads = $all_lead["_embedded"]["items"];
// print_r($all_leads);

$name_lead = [];
$created_at = [];
$tags = [];
$castom_field = [];
$contact_name = [];
$leads_info = [];
$contacts_resp = [];
$company_resp = [];
// print_r($all_leads);
foreach ($all_leads as $key => $value) {

    // print_r("зашел");
  $leads_info[$key]['name'] = $value['name'];
  $leads_info[$key]['created_at'] = $value['created_at'];
  $leads_info[$key]['company'] = $value['company']['id'];
  $leads_info[$key]['contacts'] = $value['contacts']['id'];
  foreach ($value['custom_fields'] as $key_custom_value_all => $value_custom_all) {
      foreach($value_custom_all["values"] as $key_values => $value_customs){
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
  // print_r($value_company);
    $leads_info[$key]['company'] = $value_company[0]["name"];
  }

// заносим контакты
  $data_contact_all = $api->list_contact($leads_info[$key]['contacts']);
  foreach ($data_contact_all["_embedded"]["items"] as $key_data => $value_data) {
    $leads_info[$key]['contacts'][$key_data] = $value_data["name"];
  }


}


foreach ($leads_info as $key => $value){
    $leads_info[$key]['contacts'] = implode(",", $value['contacts']);
}

foreach ($leads_info as $key => $value){
  if(in_array($value['custom_fields_values'], $leads_info[$key]['custom_fields_values'])){
      $leads_info[$key]['custom_fields_values'] = implode(",", $value['custom_fields_values']);
    } else {

    }
  }
print_r($leads_info);

$fp = fopen('file.csv', 'w');

$titles = ["Имя сделки", "Дата создания", "Компания", "Контакты", "Доп.поля", "Тэги"];
fputcsv($fp, $titles, ",");


foreach($leads_info as $key => $value){

  fputcsv($fp, $value, ",");

}
fclose($fp);

echo "https://widget.ru/file.csv";
