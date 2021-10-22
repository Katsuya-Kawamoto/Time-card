<?php

/*
従業員全体の勤怠出力
*/

//ログイン
require_once "./login.php";

if(!$_SERVER["REQUEST_METHOD"]==="POST"){
    die("直接アクセス禁止");
}
/**
 * 月ごとの勤務状況取得
 */
$sql="  SELECT * FROM `working_hours` 
        LEFT OUTER JOIN `working_time` ON working_hours.keey = working_time.keey 
        LEFT OUTER JOIN `over_time_reason` ON working_hours.keey = over_time_reason.keey 
        LEFT OUTER JOIN `working_info` ON working_hours.keey = working_info.keey 
        WHERE month=:month AND year=:year";

$year=$_POST["year"];
$month=$_POST["month"];

require "./timecard_output.php";
csv_output($sql,$year,$month);

?>
