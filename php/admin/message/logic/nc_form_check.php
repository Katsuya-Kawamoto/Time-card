<?php
/*
    フォームチェック（お知らせ）
*/

    //エラーメッセージなどセッションに格納するもの
    $_SESSION['csrf_token']=$output['csrf_token'];
    $output=[];

    //フォーム入力内容確認
    //①直接アクセスでは無く、POSTの値があること。
    //*不正アクセスはフォームへreturn
    //②フォームに空白の情報が無い事
    //③正規表現チェック
    if($_SERVER["REQUEST_METHOD"]==="POST"){
        //$_POST情報確認（空の項目有無）
        $input=nc_input_check($output,$_SESSION["HTTP"]);
        //正規表現チェック
        $input=nc_match_check($output,$input);
    }else{
        //どちらにも当てはまらない場合はログインに戻る。
        $_SESSION["err-form"]="再度、フォームに情報を入力してください。";
        header('Location: '.$_SESSION["HTTP"]);
        return;
    }

    /**
     *  お知らせフォームチェック①
     *  $_POSTから送られてきたのを変換
     *
     * @param           $output => セッション情報やエラー情報
     * @return  array   $input  => title    件名
     *                          => contents 内容
     *                          => name     投稿者
     * ＊但し、途中で入力などのエラーが出た場合は、
     *   エラーの内容+フォーム情報が$outputで返される。  
     */
    function nc_input_check($output){

        //フォーム情報を入れる箱
        $input=null;

        //変数へ代入（空欄が無いか確認）
        if(!isset($_POST["title"]) || strlen(!$_POST["title"])){
            $output["err-title"]="件名を入力してください";
        }else{
            $input["title"]=h($_POST["title"]);
        }
        if(!isset($_POST["contents"]) || !strlen($_POST["contents"])){
            $output["err-contents"]="内容を入力してください";
        }else{
            $input["contents"]=h($_POST["contents"]);
        }
        if(!isset($_POST["name"]) || !strlen($_POST["name"])){
            $output["err-name"]="投稿者を入力してください";
        }else{
            $input["name"]=h($_POST["name"]);
        }

        if(isset($_POST["id"])){
            $input["id"]=h(filter_input(INPUT_POST, "id")); 
            //選択されたidがあるか確認
            $sql="SELECT * FROM `notification` WHERE `id`=:id";
            $stmt=connect()->prepare($sql);
            $stmt->bindParam(':id',$input["id"]);
            $stmt->execute();
            $result=$stmt->fetch();//結果があるか取得
            $stmt=null;
            if(!isset($result)){
                $output["err-form"]="選択されたidが見つかりません。";
            }
        }

        /*

        パス確認は保留
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

        */
        back_page($output,$input);
        return $input;
    }

    /**
     * お知らせフォームチェック②
     * 正規表現チェック
     * @param   array   $output =>  セッション情報など
     * @param   array   $input  =>  下記参照
     * @return  array   $input  =>  title    件名
     *                          =>  contents 内容
     *                          =>  name     投稿者
     *   ＊但し、途中で入力などのエラーが出た場合は、
     *   エラーの内容+フォーム情報が$outputで返される。  
     */
    function nc_match_check($output,$input){

        if(!isset($input["title"],$input["contents"])){
            $output["err-form"]="出力エラーが発生しました。";
        }

        if(!isset($input["title"]) || !strlen($input["title"])){
            $output["err-title"]="件名を入力してください";
        }else if(preg_match("/^[ぁ-んァ-ヶー々一-龠０-９a-zA-Z0-9]{40}$/u",$input["title"])){
            $output["err-title"]="件名を正しく入力してください";
        }else if(strlen($input["title"])>255){
            $output["err-title"]="件名は255文字以内で入力してください";
        }

        if(!isset($input["contents"]) || !strlen($input["contents"])){
            $output["err-contents"]="内容を入力してください";
        }else if(preg_match("/^[ぁ-んァ-ヶー々一-龠０-９a-zA-Z0-9]{15000}$/u",$input["contents"])){
            $output["err-contents"]="内容を正しく入力してください";
        }else if(strlen($input["contents"])>15000){
            $output["err-contents"]="内容は15000文字以内で入力してください";
        }

        if(!isset($input["name"]) || !strlen($input["name"])){
            $output["err-name"]="名前を入力してください";
        }else if(preg_match("/^[ぁ-んァ-ヶー々一-龠０-９a-zA-Z0-9]{40}$/u",$input["name"])){
            $output["err-name"]="名前を正しく入力してください";
        }else if(strlen($input["name"])>30){
            $output["err-name"]="名前は30文字以内で入力してください";
        }

        $token = filter_input(INPUT_POST, 'csrf_token');
        //トークンがない、もしくは一致しない場合、処理を中止
        if (!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
            $output["err-form"]="トークンエラー：再度入力してください。";
        }

        /*

        パス確認は保留
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

        */
        back_page($output,$input);
        return $input;
    }

    function back_page($output,$input){
        if(count($output)>0){
            //エラーがあった場合は戻す
            $output["admin"]=$_SESSION["admin"];
            $output["header-sei"]=$_SESSION["header-sei"];
            $output["e-id"]=$_SESSION['e-id'];
            $output["HTTP"]=$_SESSION["HTTP"];
            //フォームに入っている情報を返す
            foreach($input as $key => $value){
                $output[$key]=$value;
            }
            $output[]=$_SESSION;
            $_SESSION=$output;
            header('Location: '.$_SESSION["HTTP"]);
            return;
        }else{
            return false;
        }
    }