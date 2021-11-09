<?php 
/*
    勤怠入力フォームチェック
*/
    //前ページデータの取得
    if($url=$_SERVER["HTTP_REFERER"]){
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
        $output["admin"]=$_SESSION["admin"];
        $output["header-sei"]=$_SESSION["header-sei"];
        $output["e-id"]=$_SESSION['e-id'];
        back_url();
    }

    /**
     * 勤怠情報DBへ書き込み
     */
    function ad_submit($input,$time,$over_time_flag=false){
        $number=$_POST["number"];
        $key=$input["Year"].$input["Month"].$input["Day"]."_".$number;
    
        //DBにフォームの内容を書き込む。
        //勤務日
        $sql="  INSERT INTO `working_hours` (`keey`,`number`,`year`,`month`,`day`,`work_type`) 
                VALUES (:keey,:number,:year,:month,:day,:work_type)";
        $stmt = connect() -> prepare($sql);
        $stmt->bindParam(':keey',$key);
        $stmt->bindParam(':number',$number);
        $stmt->bindParam(':year',$input["Year"]);
        $stmt->bindParam(':month',$input["Month"]);
        $stmt->bindParam(':day',$input["Day"]);
        $stmt->bindParam(':work_type',$input["work_type"]);
        $stmt->execute();
    
        //勤務時間
        $sql="  INSERT INTO `working_time` (`keey`,`s_time`,`s_minutes`,`e_time`,`e_minutes`,`created_at`) 
                VALUES (:keey,:s_time,:s_minutes,:e_time,:e_minutes,:created_at)";
        $stmt = connect() -> prepare($sql);
        $stmt->bindParam(':keey',$key);
        $stmt->bindParam(':s_time',$input["s_time"]);
        $stmt->bindParam(':s_minutes',$input["s_minutes"]);
        $stmt->bindParam(':e_time',$input["e_time"]);
        $stmt->bindParam(':e_minutes',$input["e_minutes"]);
        $stmt->bindParam(':created_at',$time["created_at"]);
        $stmt->execute();
    
        //勤務情報
        $sql="  INSERT INTO `working_info` (`keey`,`work_time`,`work_minutes`,`break_time`,`break_minutes`,`midnight_time`,`midnight_minutes`,`over_time`,`over_minutes`) 
                VALUES (:keey,:work_time,:work_minutes,:break_time,:break_minutes,:midnight_time,:midnight_minutes,:over_time,:over_minutes)";
        $stmt = connect() -> prepare($sql);
        $stmt->bindParam(':keey',$key);
        $stmt->bindParam(':work_time',$input["work_time"]);
        $stmt->bindParam(':work_minutes',$input["work_minutes"]);
        $stmt->bindParam(':break_time',$input["break_time"]);
        $stmt->bindParam(':break_minutes',$input["break_minutes"]);
        $stmt->bindParam(':midnight_time',$input["midnight_time"]);
        $stmt->bindParam(':midnight_minutes',$input["midnight_minutes"]);
        $stmt->bindParam(':over_time',$input["over_time"]);
        $stmt->bindParam(':over_minutes',$input["over_minutes"]);
        $stmt->execute();
    
        if($over_time_flag){
            //勤務情報
            $sql="  INSERT INTO `over_time_reason` (`keey`,`over_time_reason`) 
                    VALUES (:keey,:over_time_reason)";
            $stmt = connect() -> prepare($sql);
            $stmt->bindParam(':keey',$key);
            $stmt->bindParam(':over_time_reason',$input["over_time_reason"]);
            $stmt->execute();
        }
    }

    /**
     * 勤怠情報DBへ更新
     */
    function ad_update($input,$time,$over_time_flag=false){
        $number=$_POST["number"];
        $key=$input["Year"].$input["Month"].$input["Day"]."_".$number;

        $sql="  UPDATE `working_hours` 
                SET `number`=:number,`year`=:year,`month`=:month,`day`=:day,`work_type`=:work_type
                WHERE `keey`=:keey";

        $stmt = connect() -> prepare($sql);
        $stmt->bindParam(':keey',$key);
        $stmt->bindParam(':number',$number);
        $stmt->bindParam(':year',$input["Year"]);
        $stmt->bindParam(':month',$input["Month"]);
        $stmt->bindParam(':day',$input["Day"]);
        $stmt->bindParam(':work_type',$input["work_type"]);
        $stmt->execute();

        //勤務時間
        $sql="  UPDATE `working_time` 
                SET `s_time`=:s_time,`s_minutes`=:s_minutes,
                    `e_time`=:e_time,`e_minutes`=:e_minutes,`created_at`=:created_at 
                WHERE `keey`=:keey";
                    
        $stmt = connect() -> prepare($sql);
        $stmt->bindParam(':keey',$key);
        $stmt->bindParam(':s_time',$input["s_time"]);
        $stmt->bindParam(':s_minutes',$input["s_minutes"]);
        $stmt->bindParam(':e_time',$input["e_time"]);
        $stmt->bindParam(':e_minutes',$input["e_minutes"]);
        $stmt->bindParam(':created_at',$time["created_at"]);
        $stmt->execute();

        //勤務情報
        $sql="  UPDATE `working_info` 
                SET `work_time`=:work_time,`work_minutes`=:work_minutes,
                    `break_time`=:break_time,`break_minutes`=:break_minutes,`midnight_time`=:midnight_time,
                    `midnight_minutes`=:midnight_minutes,`over_time`=:over_time,`over_minutes`=:over_minutes
                WHERE `keey`=:keey";
        $stmt = connect() -> prepare($sql);
        $stmt->bindParam(':keey',$key);
        $stmt->bindParam(':work_time',$input["work_time"]);
        $stmt->bindParam(':work_minutes',$input["work_minutes"]);
        $stmt->bindParam(':break_time',$input["break_time"]);
        $stmt->bindParam(':break_minutes',$input["break_minutes"]);
        $stmt->bindParam(':midnight_time',$input["midnight_time"]);
        $stmt->bindParam(':midnight_minutes',$input["midnight_minutes"]);
        $stmt->bindParam(':over_time',$input["over_time"]);
        $stmt->bindParam(':over_minutes',$input["over_minutes"]);
        $stmt->execute();

        if($over_time_flag){
            //勤務情報
            $sql="SELECT * FROM `over_time_reason` WHERE `keey`=:keey";
            $stmt = connect() -> prepare($sql);
            $stmt->bindParam(':keey',$key);
            $stmt->execute();
            $result=$stmt->fetch();
            if($result){
                    $sql="  UPDATE `over_time_reason`
                            SET `over_time_reason`=:over_time_reason
                            WHERE `keey`=:keey"; 
                    $stmt = connect() -> prepare($sql);
                    $stmt->bindParam(':keey',$key);
                    $stmt->bindParam(':over_time_reason',$input["over_time_reason"]);
                    $stmt->execute();
            }else{
                //勤務情報
                $sql="  INSERT INTO `over_time_reason` (`keey`,`over_time_reason`) 
                        VALUES (:keey,:over_time_reason)";
                $stmt = connect() -> prepare($sql);
                $stmt->bindParam(':keey',$key);
                $stmt->bindParam(':over_time_reason',$input["over_time_reason"]);
                $stmt->execute();
            }
        }
    }

    function back_url(){
        $address='a_form.php';                          //戻るaddress
        if(isset($_GET["id"])){                         //GET値ある場合は付与
            $address.="?id=".$_POST["id"];
        }
        header('Location: '.$address);
        return;
    }