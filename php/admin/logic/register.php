<?php

/*
    従業員登録フォームチェック2
    正規表現のチェック
*/
if(!isset($sei,$mei)){
    echo "出力出来ませんでした。";
    exit();
}
    //ログインされているか確認
    require_once "../connect.php";

    if(!isset($number) || !strlen($number)){
        $output["err-number"]="社員ナンバーを入力してください";
    }else if(!preg_match("/^[0-9]{6}$/",$number)){
        $output["err-number"]='社員ナンバーは半角数字で入力してください。';
    }else if(!(strlen($number)==6 || strlen($number)==7)){
        $output["err-number"]="社員ナンバーは10または11文字で入力してください";
    }else if(!$flag){
        //sqlに接続して、同じ社員番号があるかチェック
        $sql="SELECT * FROM `staff` WHERE `number`=:number";
        $stmt=connect()->prepare($sql);
        $stmt->bindParam(':number',$number);
        $stmt->execute();
        $result=$stmt->fetch();//結果があるか取得
        $stmt=null;

        if($result){
            //フォームの名前と上記で取得した名前が一致するか
            $output["err-number"]="同じ社員IDが既に存在します";
        }
    }

    if(!isset($sei) || !strlen($sei)){
        $output["err-sei"]="名字を入力してください";
    }else if(preg_match("/^[ぁ-んァ-ヶー々一-龠０-９a-zA-Z0-9]{40}$/u",$sei)){
        $output["err-sei"]="名字を正しく入力してください";
    }else if(strlen($sei)>40){
        $output["err-sei"]="名字は４０文字以内で入力してください";
    }

    if(!isset($mei) || !strlen($mei)){
        $output["err-mei"]="名前を入力してください";
    }else if(preg_match("/^[ぁ-んァ-ヶー々一-龠０-９a-zA-Z0-9]{40}$/u",$mei)){
        $output["err-mei"]="名前を正しく入力してください";
    }else if(strlen($mei)>40){
        $output["err-mei"]="名前は４０文字以内で入力してください";
    }

    if(!isset($pass) || !strlen($pass)){
        $output["err-pass"]="パスワードを入力してください";
    }else if(!preg_match("/^[0-9]{6}$/",$pass)){
        $output["err-pass"]='パスワードは英数字6文字にしてください。';
    }

    if(!isset($pass_conf) || !strlen($pass_conf)){
        $output["err-pass_conf"]="パスワードを入力してください";
    }else if(!preg_match("/^[0-9]{6}$/",$pass_conf)){
        $output["err-pass_conf"]='パスワードは英数字6文字にしてください。';
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

    $token = filter_input(INPUT_POST, 'csrf_token');
    //トークンがない、もしくは一致しない場合、処理を中止
    if (!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
        exit('不正なリクエスト');
    }

    if(count($output)>0){
        //エラーがあった場合は戻す
        $output["admin"]=$_SESSION["admin"];
        $output["header-sei"]=$_SESSION["header-sei"];
        $output["e-id"]=$_SESSION['e-id'];
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