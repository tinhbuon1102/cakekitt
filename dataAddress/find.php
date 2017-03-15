<?php
//郵便番号
$zipcode = $_REQUEST['zipcode'];
 
$dir = __DIR__ . '/zipcode';
 
$zipcode = mb_convert_kana($zipcode, 'a', 'utf-8');
$zipcode = str_replace(array('-','ー'),'', $zipcode);
 
$result = array();
 
$file = $dir . DIRECTORY_SEPARATOR . substr($zipcode, 0, 1) . '.csv';
if(file_exists($file)){
    $spl = new SplFileObject($file);
    while (!$spl->eof()) {
        $columns = $spl->fgetcsv();
        if(isset($columns[0]) && $columns[0] == $zipcode){
            $result = array($columns[1], $columns[2], $columns[3]);
            break;
        }
    }
}

$response = array();
if(!empty($result)){
	$response['prefecture']  = $result[0];
	$response['district']  = $result[1];
	$response['town']  = $result[2];
} 
echo json_encode($response);