<?php
/**
 * 従業員の勤怠情報一覧を取得
 */

//リスト表示
$count=20;                                                                          //出力件数
if(isset($_GET["page"])){                                                           //ページ数の選択があった場合
    $offset=($_GET["page"]-1)*$count;                                               //ページ数×件数以降のデータ取得
}else{
    $offset=0;                                                                      //選択が無い場合は0からスタート
}
//メンバー情報の取得
require_once "../logic/db_access.php";
$db=new db();
$result=$db->member_list($offset,$count);
foreach ($result as $row) {
    // データベースのフィールド名で出力
    $info[]=$row;                                                                   //従業員の情報を取得
    $member_number[]=$row["number"];                                                //従業員の社員番号を取得
}
require_once "../../logic/time_input.php";
    $time=Time_input();                                                             //現在の日付取得
//勤務状況取得
if(isset($info)){
    if(isset($_GET["month"])&&isset($_GET["year"])){
        $year = $_GET["year"];
        $month = $_GET["month"];
    }else{                                                                           //GETで年月の選択が無かった場合
        $year =  $time["year"];                                                      //今月の情報を取得
        $month = $time["month"];
    }

    foreach($info as $value1){
        $time_info=time_info_input($value1["number"],$month,$year);                  //今月の勤怠状況取得
        $time_cl=time_calculation($time_info);                                       //総勤務時間算出
        $time_count=time_count($time_info);                                          //出勤回数算出
        
        //多次元配列でデータ取得=>[社員番号=>[社員毎のデータ]]
        $member_info[$value1["number"]]["number"]=$value1["number"];
        $member_info[$value1["number"]]["sei"]=$value1["sei"];
        $member_info[$value1["number"]]["mei"]=$value1["mei"];
        $member_info[$value1["number"]]["work_count"]=$time_count["work_count"];
        $member_info[$value1["number"]]["over_count"]=$time_count["over_count"];
        $member_info[$value1["number"]]["holiday_count"]=$time_count["holiday_count"];
        $member_info[$value1["number"]]["OVER_count"]=$time_count["OVER_count"];
        $member_info[$value1["number"]]["midnight_count"]=$time_count["midnight_count"];
        $member_info[$value1["number"]]["work_time"]=$time_cl["work_time"];
        $member_info[$value1["number"]]["over_time"]=$time_cl["over_time"];
        $member_info[$value1["number"]]["holiday_time"]=$time_cl["holiday_time"];
        $member_info[$value1["number"]]["OVER_time"]=$time_cl["OVER_time"];
        $member_info[$value1["number"]]["midnight_time"]=$time_cl["midnight_time"];
        $member_info[$value1["number"]]["work_minutes"]=$time_cl["work_minutes"];
        $member_info[$value1["number"]]["over_minutes"]=$time_cl["over_minutes"];
        $member_info[$value1["number"]]["holiday_minutes"]=$time_cl["holiday_minutes"];
        $member_info[$value1["number"]]["OVER_minutes"]=$time_cl["OVER_minutes"];
        $member_info[$value1["number"]]["midnight_minutes"]=$time_cl["midnight_minutes"];
    }
}

//件数取得
$info_count=$db->member_count();
$page=ceil((int)$info_count["COUNT(`number`)"]/$count);//ページ数取得