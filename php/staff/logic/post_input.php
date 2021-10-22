<?php


//変数へ代入（空欄が無いか確認）
    if(!isset($_POST["title"]) || !strlen($_POST["title"])){
        $output["err-title"]="件名を入力してください";
    }else{
        $title=htmlspecialchars($_POST["title"],ENT_QUOTES,'UTF-8');
    }
    if(!isset($_POST["contents"]) || !strlen($_POST["contents"])){
        $output["err-contents"]="内容を入力してください";
    }else{
        $contents=htmlspecialchars($_POST["contents"],ENT_QUOTES,'UTF-8');
    }
    if(!isset($_POST["name"]) || !strlen($_POST["name"])){
        $output["err-name"]="投稿者を入力してください";
    }else{
        $name=htmlspecialchars($_POST["name"],ENT_QUOTES,'UTF-8');
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
        $output[]=$_SESSION;
        $_SESSION=$output;
        $pdo=null;
        header('Location: form.php');
        return;
    }
    ?>