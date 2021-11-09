<?php
/*
    従業員ログインチェック
*/

    //セッションスタート
    require_once "session.php";
    $session=new session();
    $session->start();
    require_once "connect.php";//サーバー情報取得
    require_once "common_func.php";//filter_input関数

    //ログイン情報の確認
    //1-1.$_SESSION["e-id"]の有無
    //1-2.ログイン情報（POST）の確認
    //
    //上の二つに該当する場合はログイン状態にする。
    if(!isset($_SESSION["e-id"])){
        if($_SERVER["REQUEST_METHOD"]==="POST"){ //1-2.
            //ログイン情報の確認
            $login = new Login();
            $login -> login_check();
        }else{
            //どちらにも当てはまらない場合はログインに戻る。
            $_SESSION["err-login"]="ログインをし直してください。";
            header('Location: /timecard/');
            return;
        }
    }else{
        //セッション情報リセット
        $output = $_SESSION; //セッション情報をoutputに格納
        $session->reset();//ログイン情報を残して削除
    }

    class Login{
        /**
         * ログインcheck
         * @param array     $_POST 
         *          "id"        ->  管理者ID
         *          "pass"      ->  パスワード
         *          "number"    ->  社員番号
         */
        function login_check(){
            $input = $this->input_check();
            $input = $this->number_check($input);
            $this->staff_check($input);
        }
        
        /**
         * formの内容の確認
         * @param array     $_POST 
         *          "id"        ->  管理者ID
         *          "pass"      ->  パスワード
         *          "number"    ->  社員番号
         * @return array    $input(上記と同じ)         
         */
        function input_check(){
        $err=[];
        $input=[];
        //フォーム内容チェック
        if(!$input["s_number"] = f('s_number')) {
            $err['err-number'] = 'ユーザーIDを入力してください';
        }
        if(!$input["s_pass"] = f('s_pass')) {
            $err['err-pass'] = 'パスワードを入力してください';
        }

        $token = f('csrf_token');
        //トークンがない、もしくは一致しない場合、処理を中止
        if (!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
            $err['err-id'] = 'トークンエラー：再度ログインし直してください。';
        }
        unset($_SESSION['csrf_token']);//トークン削除
        $this->err_check($err);
        return $input;
        }

        /**
         * 社員番号の確認
         * ①社員番号の有無確認
         * ②管理者権限の有無確認
         *  @param array     $_POST 
         *  "id"        ->  管理者ID
         *  "pass"      ->  パスワード
         *  "number"    ->  社員番号
         * @return 無し
         */
        function number_check($input){
            $err=[];
            $sql="SELECT `pass`,`sei` FROM `staff` WHERE `number`=:number";
            $stmt= connect()->prepare($sql);
            $stmt->bindParam(':number',$input["s_number"]);
            $stmt->execute();
            $result=$stmt->fetch();

            if(!$result){
                $err["err-number"]="社員番号が一致しません。";
            }else if(!password_verify($input["s_pass"], $result["pass"])){
                $err["err-pass"]='パスワードが違います。';
            }else{
                $input["sei"]=$result["sei"];
            }
            $stmt=null;
            $this->err_check($err);
            return $input;
        }

        /**
         * admin付与の確認
         *  @param array     $_POST 
         *  "id"        ->  管理者ID
         *  "pass"      ->  パスワード
         *  "number"    ->  社員番号
         *  "sei"       ->  姓
         */
        function staff_check($input){
            $_SESSION['e-id']=$input["s_number"];
            $_SESSION["header-sei"]=$input["sei"];
            //管理者からログインしている場合は"admin"付与。
            return $_SESSION;
        }

        /**
         * formの内容の確認
         * @param array $err -> 確認時に発生したエラー
         * @return bool       
         */
        function err_check($err){
            if(count($err)>0){
                $_SESSION = $err;
                header('Location: /timecard/');
                return;
            }
            return false;
        }
    }