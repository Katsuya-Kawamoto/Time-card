<?php
/*
    CSV出力(従業員別)
*/

//ログインされているか確認
require "../connect.php";

$sql="  SELECT * FROM `working_hours` 
        LEFT OUTER JOIN `working_time` ON working_hours.keey = working_time.keey 
        LEFT OUTER JOIN `over_time_reason` ON working_hours.keey = over_time_reason.keey 
        LEFT OUTER JOIN `working_info` ON working_hours.keey = working_info.keey 
        WHERE month=:month AND year=:year";

$year=$_POST["year"];
$month=$_POST["month"];

require "./timecard_output.php";

if(isset($_POST["check"])){
        $SQL=$sql." AND number=".$_POST["check"][0];
        csv_output($SQL,$year,$month,$_POST["check"][0]);
}else{
    echo "出力エラー";
}

$stmt=null;
$pdo=null;

?>