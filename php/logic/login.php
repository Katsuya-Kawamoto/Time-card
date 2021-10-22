<?php

//ログインされているか確認
require "../connect.php";

//エラーメッセージ
$err=[];
    //フォーム内容チェック
    if(!isset($_POST["id"]) || !strlen($_POST["id"])){
        $err["err-id"]="管理者IDを入力してください";
    }else{
        //sqlに接続して、同じアドレスがあるかチェック
        $sql="SELECT * FROM `admin` WHERE `id`=:id";
        $stmt= connect()->prepare($sql);
        $stmt->bindParam(':id',$_POST["id"]);
        $stmt->execute();
        $result=$stmt->fetch();
        
        if(!$result){
            $err["err-id"]="管理者IDが違います。";
        }else if(!isset($_POST["pass"]) || !strlen($_POST["pass"])){
            $err["err-pass"]="パスワードを入力してください";
        }else if(!password_verify($_POST["pass"], $result["pass"])){
                $err["err-pass"]='パスワードが違います。';
        }
        $stmt=null;
    }

    if(!isset($_POST["number"]) || !strlen($_POST["number"])){
        $err["err-number"]="ユーザーIDを入力してください";
    }else{
        //sqlに接続して、同じアドレスがあるかチェック
        $sql="SELECT * FROM `staff` WHERE `number`=:number";
        $stmt= connect()->prepare($sql);
        $stmt->bindParam(':number',$_POST["number"]);
        $stmt->execute();
        $result=$stmt->fetch();
        
        if(!$result){
            $err["err-number"]="社員番号が一致しません。";
        }
        $stmt=null;
    }


    if(count($err)>0){
        //エラーがあった場合は戻す
        $_SESSION =$err;
        header('Location: ../../administrator.php');
        return;
    }else{
        $_SESSION['admin']="admin";
        $_SESSION['e-id']=$_POST["id"];
        $_SESSION["header-sei"]=$result["sei"];
    }

?>