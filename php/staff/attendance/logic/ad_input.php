<?php

/*
    勤怠入力チェック①
    フォーム側の空白チェック
*/

    //フォーム情報を入れる箱
    $input=null;

    if(!isset($_POST["Year"]) || strlen($_POST["Year"]<0)){
        $output["err-year"]="勤務年を入力してください";
    }
    if(!isset($_POST["Month"]) || strlen($_POST["Month"]<0)){
        $output["err-month"]="勤務月を入力してください";
    }
    if(!isset($_POST["Day"]) || strlen($_POST["Day"]<0)){
        $output["err-day"]="勤務日を入力してください";
    }
    if(!isset($_POST["work_type"]) || strlen($_POST["work_type"]<0)){
        $output["err-work_type"]="勤務形態が選択されていません。";
    }
    if(!isset($_POST["s_time"]) || strlen($_POST["s_time"]<0)){
        $output["err-s_time"]="勤務開始時間（時）を入力してください";
    }
    if(!isset($_POST["s_minutes"]) || strlen($_POST["s_minutes"]<0)){
        $output["err-s_minutes"]="勤務開始時間（分）を入力してください";
    }
    if(!isset($_POST["e_time"]) || strlen($_POST["e_time"]<0)){
        $output["err-e_time"]="勤務終了時間（時）を入力してください";
    }
    if(!isset($_POST["e_minutes"]) || strlen($_POST["e_minutes"]<0)){
        $output["err-e_minutes"]="勤務終了時間（分）を入力してください";
    }
    if(!isset($_POST["break_time"]) || strlen($_POST["break_time"]<0)){
        $output["err-break_time"]="休憩終了時間（時）を入力してください";
    }
    if(!isset($_POST["break_minutes"]) || strlen($_POST["break_minutes"]<0)){
        $output["err-break_minutes"]="休憩終了時間（分）を入力してください";
    }
    if(!isset($_POST["midnight_time"]) || strlen($_POST["midnight_time"]<0)){
        $output["err-midnight_time"]="深夜終了時間（時）を入力してください";
    }
    if(!isset($_POST["midnight_minutes"]) || strlen($_POST["midnight_minutes"]<0)){
        $output["err-midnight_minutes"]="深夜終了時間（分）を入力してください";
    }
    if(!isset($_POST["work_time"]) || strlen($_POST["work_time"]<0)){
        $output["err-work_time"]="勤務時間（時）を入力してください";
    }
    if(!isset($_POST["work_minutes"]) || strlen($_POST["work_minutes"]<0)){
        $output["err-work_minutes"]="勤務時間（分）を入力してください";
    }
    if(!isset($_POST["over_time"]) || strlen($_POST["over_time"]<0)){
        $output["err-over_time"]="時間外労働時間（時）を入力してください";
    }
    if(!isset($_POST["over_minutes"]) || strlen($_POST["over_minutes"]<0)){
        $output["err-over_minutes"]="時間外労働時間（分）を入力してください";
    }
    /*パスワード保留

    if(!isset($_POST["pass"]) || !strlen($_POST["pass"])){
        $output["err-pass"]="パスワード入力してください";
    }else{
        $input["pass"]=h($_POST["pass"]);
    }
    if(!isset($_POST["pass_conf"]) || !strlen($_POST["pass_conf"])){
        $output["err-pass_conf"]="確認用パスワードを入力してください";
    }else{
        $input["pass_conf"]=h($_POST["pass_conf"]);
    }
    */

    foreach($_POST as $key => $value){
        $input[$key] = f($key);
    }

    if(count($output)>0){
        //エラーがあった場合は戻す
        $output["header-sei"]=$_SESSION["header-sei"];
        $output["e-id"]=$_SESSION['e-id'];
        if(isset($_SESSION["admin"])){
            $output["admin"]=$_SESSION["admin"];
        }
        //入力された情報を$outputに返す
        foreach($input as $key => $value){
            $output[$key]=$value;
        }
        $_SESSION=$output;
        
        $address='a_form.php';   
        if(isset($_GET["id"])){                     //GET値ある場合は付与
            $address.="?id=".$_POST["id"];
        }
        header('Location: '.$address);
        return;
    }
