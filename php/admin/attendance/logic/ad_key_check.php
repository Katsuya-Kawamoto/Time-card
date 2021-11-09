<?php
/*
    勤怠・勤務時間DBのキーの有無の確認
*/

    $number=$_POST["number"];
    $keey=$input["Year"].$input["Month"].$input["Day"]."_".$number;
    /**
    * 月ごとの勤務状況取得
    */
    $sql="  SELECT * FROM `working_hours` WHERE keey=:key";
    $stmt=connect()->prepare($sql);
    $stmt->bindParam(':key',$keey);
    $stmt->execute();
    $Result=$stmt->fetch();

    function back_page($input,$comment,$url){
        $output["err-form"]=$comment;
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
        back_url();
    }

    if(isset($_POST["id"])){
        if(!$Result){
            $comment="登録日のデータが存在しません。";
            back_page($input,$comment);
        }
    }else{
        if($Result){
            $comment="登録日のデータが既に存在します。";
            back_page($input,$comment,$url);
        }
    }
