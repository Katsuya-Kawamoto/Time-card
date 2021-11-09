<?php
/**
 * 従業員の個別の勤務情報の一覧取得
 */

    //勤務状況取得
    require_once "../../logic/time_input.php";
    $time =  Time_input();                                                              //現在の日付取得
    if(isset($_GET["month"])&&isset($_GET["year"])){
        $year = $_GET["year"];
        $month = $_GET["month"];
        if($year==$time["year"] && $month == $time["month"]){                           //GETの年月が今月だった場合
            $flag=true;                                                                 //$flgをtrue(編集機能ON)
        }else{
            $flag=false;
        }
    }else{                                                                              //GETで年月の選択が無かった場合
        $year =  $time["year"];                                                         //今月の情報を取得
        $month = $time["month"];
        $flag=true;                                                                     //$flgをtrue(編集機能ON)
    }
    
    $time_info=time_info_input($_GET["id"],$month,$year);                         //今月の勤怠状況取得
    $time_cl=time_calculation($time_info);                                              //総勤務時間算出
    $time_count=time_count($time_info);                                                 //出勤回数算出
    
    //ログから管理者が編集などを行っているか確認
    require_once '../../staff/logic/ad_function.php';
    $log_check=null;
    for($i=1; $i<=31; $i++){
        $KEY=$year.$month.$i."_".$_GET["id"];
        if(attendance_log_check($KEY)){
            $log_check[$i]=true;
        }
    }