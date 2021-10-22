<?php
if(!isset($title,$contents)){
    echo "出力出来ませんでした。";
    exit();
}
    //DB接続情報
    require "connect.php";

    if(!isset($title) || !strlen($title)){
        $output["err-title"]="件名を入力してください";
    }else if(preg_match("/^[ぁ-んァ-ヶー々一-龠０-９a-zA-Z0-9]{40}$/u",$title)){
        $output["err-title"]="件名を正しく入力してください";
    }else if(strlen($title)>255){
        $output["err-title"]="件名は255文字以内で入力してください";
    }

    if(!isset($contents) || !strlen($contents)){
        $output["err-contents"]="内容を入力してください";
    }else if(preg_match("/^[ぁ-んァ-ヶー々一-龠０-９a-zA-Z0-9]{15000}$/u",$contents)){
        $output["err-contents"]="内容を正しく入力してください";
    }else if(strlen($contents)>15000){
        $output["err-contents"]="内容は15000文字以内で入力してください";
    }

    if(!isset($name) || !strlen($name)){
        $output["err-name"]="名前を入力してください";
    }else if(preg_match("/^[ぁ-んァ-ヶー々一-龠０-９a-zA-Z0-9]{40}$/u",$name)){
        $output["err-name"]="名前を正しく入力してください";
    }else if(strlen($name)>30){
        $output["err-name"]="名前は30文字以内で入力してください";
    }

    if(!isset($old_pass) || !strlen($old_pass)){
        $output["err-old_pass"]="現在のパスワードを入力してください";
    }else if(!preg_match("/^[0-9]{6}$/",$old_pass)){
        $output["err-old_pass"]='現在のパスワードは数字6文字にしてください。';
    }
    if(!isset($pass) || !strlen($pass)){
        $output["err-pass"]="パスワードを入力してください";
    }else if(!preg_match("/^[0-9]{6}$/",$pass)){
        $output["err-pass"]='パスワードは数字6文字にしてください。';
    }

    if(!isset($pass_conf) || !strlen($pass_conf)){
        $output["err-pass_conf"]="パスワードを入力してください";
    }else if(!preg_match("/^[0-9]{6}$/",$pass_conf)){
        $output["err-pass_conf"]='パスワードは数字6文字にしてください。';
    }

    if($pass!==$pass_conf){
        $output["err-pass_conf"]='パスワードと確認パスワードが一致しません。';
    }else{
        //sqlに接続して、同じアドレスがあるかチェック
        $sql="SELECT * FROM `admin` WHERE `id`=:id";
        $stmt=connect()->prepare($sql);
        $stmt->bindParam(':id',$_SESSION["e-id"]);
        $stmt->execute();
        $result=$stmt->fetch();
        
        if(!password_verify($pass, $result["pass"])){
            $output["err-pass"]='パスワードが違います。';
        }
        $stmt=null;
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