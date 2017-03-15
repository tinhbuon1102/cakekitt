<?php
// メモリ上限を変更（必要に応じて）
ini_set('memory_limit', '256M');
 
// KEN_ALL.CSV の場所
$file = 'KEN_ALL.CSV';
 
// 変換後のファイル保存先
$dir = __DIR__ . '/zipcode';
 
setlocale(LC_ALL, 'ja_JP.UTF-8');
 
$data = file_get_contents($file);
$data = mb_convert_encoding($data, 'UTF-8', 'sjis-win');
$temp = tmpfile();
 
fwrite($temp, $data);
rewind($temp);
 
$groups = array();
$groupIndexes = array();
for($i=0;$i<10;$i++){
    $groupIndexes[$i] = 0;
}
 
$prev = null;
$index = 0;
while (($data = fgetcsv($temp, 0, ",")) !== FALSE) {
    $prefix = substr($data[2], 0, 1);
    if(!strlen($prefix)){
        $index++;
        continue;
    }
    $groupIndex = $groupIndexes[$prefix];
 
    $columns = array(
        $data[2],
        $data[6],
        $data[7],
        ($data[8] == '以下に掲載がない場合') ?
            '' : preg_replace('/（.+?）/u', '', $data[8])
    );
 
    if(!is_null($prev) && $prev[0] == $columns[0]){
        $groups[$prefix][$groupIndex - 1][3] .= $columns[3];
    } else {
        $groups[$prefix][$groupIndex] = $columns;
        $index++;
        $groupIndexes[$prefix]++;
    }
    $prev = $columns;
}
fclose($temp);
 
foreach($groups as $prefix => $group){
    $converted = fopen($dir . DIRECTORY_SEPARATOR . $prefix . '.csv', "w");
    if(flock($converted, LOCK_EX)){
        foreach($group as $columns){
            // ダブルクォート
            $columns = array_map(function($value){
                $value = str_replace('"', '""', $value);
                return '"' . $value . '"';
            }, $columns);
            fwrite($converted, implode(',', $columns) . "\n");
        }
        flock($converted, LOCK_UN);
    }
    fclose($converted);
}
 
echo "Completed.";