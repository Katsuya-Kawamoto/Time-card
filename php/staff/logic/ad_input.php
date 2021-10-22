<?php

if(!isset($_POST["year"]) || !strlen($_POST["year"])){
    $output["err-year"]="勤務年を入力してください";
}else{
    $Year=htmlspecialchars($_POST["year"],ENT_QUOTES,'UTF-8');
}
if(!isset($_POST["month"]) || !strlen($_POST["month"])){
    $output["err-month"]="勤務月を入力してください";
}else{
    $Month=htmlspecialchars($_POST["month"],ENT_QUOTES,'UTF-8');
}
if(!isset($_POST["day"]) || !strlen($_POST["day"])){
    $output["err-day"]="勤務日を入力してください";
}else{
    $Day=htmlspecialchars($_POST["day"],ENT_QUOTES,'UTF-8');
}
if(!isset($_POST["work_type"]) || !strlen($_POST["work_type"])){
    $output["err-work_type"]="勤務形態が選択されていません。";
}else{
    $work_type=htmlspecialchars($_POST["work_type"],ENT_QUOTES,'UTF-8');
}
if(!isset($_POST["s_time"]) || !strlen($_POST["s_time"])){
    $output["err-s_time"]="勤務開始時間（時）を入力してください";
}else{
    $s_time=htmlspecialchars($_POST["s_time"],ENT_QUOTES,'UTF-8');
}
if(!isset($_POST["s_minutes"]) || !strlen($_POST["s_minutes"])){
    $output["err-s_minutes"]="勤務開始時間（分）を入力してください";
}else{
    $s_minutes=htmlspecialchars($_POST["s_minutes"],ENT_QUOTES,'UTF-8');
}
if(!isset($_POST["e_time"]) || !strlen($_POST["e_time"])){
    $output["err-e_time"]="勤務終了時間（時）を入力してください";
}else{
    $e_time=htmlspecialchars($_POST["e_time"],ENT_QUOTES,'UTF-8');
}
if(!isset($_POST["e_minutes"]) || !strlen($_POST["e_minutes"])){
    $output["err-e_minutes"]="勤務終了時間（分）を入力してください";
}else{
    $e_minutes=htmlspecialchars($_POST["e_minutes"],ENT_QUOTES,'UTF-8');
}
if(!isset($_POST["break_time"]) || !strlen($_POST["break_time"])){
    $output["err-break_time"]="休憩終了時間（時）を入力してください";
}else{
    $break_time=htmlspecialchars($_POST["break_time"],ENT_QUOTES,'UTF-8');
    $_SESSION["break_time"]=$break_time;
}
if(!isset($_POST["break_minutes"]) || !strlen($_POST["break_minutes"])){
    $output["err-break_minutes"]="休憩終了時間（分）を入力してください";
}else{
    $break_minutes=htmlspecialchars($_POST["break_minutes"],ENT_QUOTES,'UTF-8');
    $_SESSION["break_minutes"]=$break_minutes;
}
if(!isset($_POST["midnight_time"]) || !strlen($_POST["midnight_time"])){
    $output["err-midnight_time"]="深夜終了時間（時）を入力してください";
}else{
    $midnight_time=htmlspecialchars($_POST["midnight_time"],ENT_QUOTES,'UTF-8');
}
if(!isset($_POST["midnight_minutes"]) || !strlen($_POST["midnight_minutes"])){
    $output["err-midnight_minutes"]="深夜終了時間（分）を入力してください";
}else{
    $midnight_minutes=htmlspecialchars($_POST["midnight_minutes"],ENT_QUOTES,'UTF-8');
}
if(!isset($_POST["work_time"]) || !strlen($_POST["work_time"])){
    $output["err-work_time"]="勤務時間（時）を入力してください";
}else{
    $work_time=htmlspecialchars($_POST["work_time"],ENT_QUOTES,'UTF-8');
}
if(!isset($_POST["work_minutes"]) || !strlen($_POST["work_minutes"])){
    $output["err-work_minutes"]="勤務時間（分）を入力してください";
}else{
    $work_minutes=htmlspecialchars($_POST["work_minutes"],ENT_QUOTES,'UTF-8');
}
if(!isset($_POST["over_time"]) || !strlen($_POST["over_time"])){
    $output["err-over_time"]="時間外労働時間（時）を入力してください";
}else{
    $over_time=htmlspecialchars($_POST["over_time"],ENT_QUOTES,'UTF-8');
}
if(!isset($_POST["over_minutes"]) || !strlen($_POST["over_minutes"])){
    $output["err-over_minutes"]="時間外労働時間（分）を入力してください";
}else{
    $over_minutes=htmlspecialchars($_POST["over_minutes"],ENT_QUOTES,'UTF-8');
}
/*パスワード保留

if(!isset($_POST["pass"]) || !strlen($_POST["pass"])){
    $output["err-pass"]="パスワード入力してください";
}else{
    $pass=htmlspecialchars($_POST["pass"],ENT_QUOTES,'UTF-8');
}
if(!isset($_POST["pass_conf"]) || !strlen($_POST["pass_conf"])){
    $output["err-pass_conf"]="確認用パスワードを入力してください";
}else{
    $pass_conf=htmlspecialchars($_POST["pass_conf"],ENT_QUOTES,'UTF-8');
}
*/

if(count($output)>0){
    //エラーがあった場合は戻す
    $output["header-sei"]=$_SESSION["header-sei"];
    $output["e-id"]=$_SESSION['e-id'];
    $_SESSION=$output;
    if(isset($_GET["id"])){                     //GET値ある場合は付与
        $address.="?id=".$_POST["id"];
    }
    header('Location: '.$address);
    return;
}

?>