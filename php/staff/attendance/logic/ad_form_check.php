<?php 
/*
    勤怠入力フォームチェック
*/
    //前ページデータの取得
    if(isset($_SERVER["HTTP_REFERER"]) && strlen($_SERVER["HTTP_REFERER"])>0){
        $url=$_SERVER["HTTP_REFERER"];
        $path=parse_url($url);                          //pathの取得
    }else{
        $_SESSION["err-form"]="アクセスエラー：再度、フォームに情報を入力してください。";
        back_url();
    }
    //エラーメッセージなどセッションに格納するもの
    $_SESSION['csrf_token']=$output['csrf_token'];  //トークン
    $output=[];                                     //エラー表示リセット
    require_once "../../logic/time_input.php";
    $time=Time_input();                             //現在の日付取得

    //フォーム入力内容確認
    //①直接アクセスでは無く、POSTの値があること。
    //*不正アクセスはフォームへreturn
    //②フォームに空白の情報が無い事
    //③正規表現チェック
    if($_SERVER["REQUEST_METHOD"]==="POST"){        //POSTからのaccess
        require_once "ad_input.php";                //空欄有無check
        require_once "ad_verification.php";         //正規表現チェック
        require_once "ad_key_check.php";            //登録日重複check
    }else{
        //どちらにも当てはまらない場合はフォームに戻る。
        $_SESSION["err-form"]="再度、フォームに情報を入力してください。";
        back_url();
    }

    function back_url(){
        $address='a_form.php';                          //戻るaddress
        if(isset($_GET["id"])){                         //GET値ある場合は付与
            $address.="?id=".$_POST["id"];
        }
        header('Location: '.$address);
        return;
    }