<?php

/*
    従業員登録フォームチェック①
    $_POSTから送られて来た値のチェック
*/

//変数へ代入（空欄が無いか確認）
    if(!isset($_POST["number"]) || !strlen($_POST["number"])){
        $output["err-number"]="社員ナンバーを入力してください";
    }else{
        $number=htmlspecialchars($_POST["number"],ENT_QUOTES,'UTF-8');
    }
    if(!isset($_POST["sei"]) || !strlen($_POST["sei"])){
        $output["err-sei"]="名字を入力してください";
    }else{
        $sei=htmlspecialchars($_POST["sei"],ENT_QUOTES,'UTF-8');
    }
    if(!isset($_POST["mei"]) || !strlen($_POST["mei"])){
        $output["err-mei"]="名前を入力してください";
    }else{
        $mei=htmlspecialchars($_POST["mei"],ENT_QUOTES,'UTF-8');
    }
    if(!isset($_POST["pass"]) || !strlen($_POST["pass"])){
        $output["err-pass"]="パスワードを入力してください";
    }else{
        $pass=htmlspecialchars($_POST["pass"],ENT_QUOTES,'UTF-8');
    }
    if(!isset($_POST["pass_conf"]) || !strlen($_POST["pass_conf"])){
        $output["err-pass_conf"]="確認用パスワードを入力してください";
    }else{
        $pass_conf=htmlspecialchars($_POST["pass_conf"],ENT_QUOTES,'UTF-8');
    }

    if(count($output)>0){
        //エラーがあった場合は戻す
        $output["admin"]=$_SESSION["admin"];
        $output["header-sei"]=$_SESSION["header-sei"];
        $output["e-id"]=$_SESSION['e-id'];
        $output["admin_id"]=$_SESSION['admin_id'];
        $output[]=$_SESSION;
        $_SESSION=$output;
        $pdo=null;
        $address="member_register.php";
        if(isset($_GET["id"])){
            $address.="?id=".$_GET["id"];
        }
        header('Location: '.$address);
        return;
    }

?>