<?php
    $token = filter_input(INPUT_POST, 'csrf_token');
    //トークンがない、もしくは一致しない場合、処理を中止
    if (!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
        $output["err-form"]="トークンエラー：再度入力を行ってください。";
    }
    //配列に入っていた時間を引数に代入
    $year   = $time["year"];
    $month  = $time["month"];
    $day    = $time["day"];

    $over_time_flag=false;
    if(!isset($Year) || !strlen($Year)){
        $output["err-year"]="勤務年を入力してください";
    }else if(!preg_match("/^[0-9]{4}$/",$Year)){
        $output["err-year"]='勤務年は半角数字で入力してください。';
    }else if(strlen($Year)!==4){
        $output["err-year"]="勤務年は西暦4文字で入力してください";
    }else if($Year>$year){
        $output["err-year"]="現在の年以降の値は入力出来ません。";
    }

    if(!isset($Month) || !strlen($Month)){
        $output["err-month"]="勤務月を入力してください";
    }else if(!preg_match("/^[0-9]{1,2}$/",$Month)){
        $output["err-month"]='勤務月は半角数字にしてください。';
    }else if(strlen($Month)>2){
        $output["err-month"]="勤務月は2文字で入力してください";
    }else if($Month>$month){
        $output["err-month"]="現在の月以降の値は入力出来ません。";
    }

    if(!isset($Day) || !strlen($Day)){
        $output["err-day"]="勤務日を入力してください";
    }else if(!preg_match("/^[0-9]{1,2}$/",$Day)){
        $output["err-day"]='勤務日は半角数字にしてください。';
    }else if(strlen($Day)>2){
        $output["err-day"]="勤務日は2文字で入力してください";
    }else if($Day>$day){
        $output["err-day"]="現在の日以降の値は入力出来ません。";
    }
    
    if(!isset($work_type) || !strlen($work_type)){
        $output["err-work_type"]="勤務形態を入力してください";
    }else if(!preg_match("/^[0-9]{1}$/",$work_type)){
        $output["err-work_type"]='勤務形態読み込みエラー';
    }

    if(!isset($s_time) || !strlen($s_time)){
        $output["err-s_time"]="勤務開始時間（時）を入力してください";
    }else if(!preg_match("/^[0-9]{1,2}$/",$s_time)){
        $output["err-s_time"]='勤務開始時間（時）は半角数字にしてください。';
    }else if(strlen($s_time)>2){
        $output["err-s_time"]="勤務開始時間（時）は2文字で入力してください";
    }else if($Year>=$year && $Month>=$month && $Day>=$day && $s_time>$n_time){
        $output["err-s_time"]="勤務開始時間（時）は現在の時刻以前にしてください。";
    }

    if(!isset($s_minutes) || !strlen($s_minutes)){
        $output["err-s_minutes"]="勤務開始時間（分）を入力してください";
    }else if(!preg_match("/^[0-9]{1,2}$/",$s_minutes)){
        $output["err-s_minutes"]='勤務開始時間（分）は半角数字にしてください。';
    }else if(strlen($s_minutes)>2){
        $output["err-s_minutes"]="勤務開始時間（分）は2文字で入力してください";
    }else if($Year>=$year && $Month>=$month && $Day>=$day && $s_time>$n_time && $s_minutes>$n_minutes){
        $output["err-s_time"]="勤務開始時間（分）は現在の時刻以前にしてください。";
    }

    if(!isset($e_time) || !strlen($e_time)){
        $output["err-e_time"]="勤務終了時間（時）を入力してください";
    }else if(!preg_match("/^[0-9]{1,2}$/",$e_time)){
        $output["err-e_time"]='勤務終了時間（時）は半角数字にしてください。';
    }else if(strlen($e_time)>2){
        $output["err-e_time"]="勤務終了時間（時）は2文字で入力してください";
    }else if($Year>=$year && $Month>=$month && $Day>=$day && $e_time>$n_time){
        $output["err-s_time"]="勤務終了時間（時）は現在の時刻以前にしてください。";
    }

    if(!isset($e_minutes) || !strlen($e_minutes)){
        $output["err-e_minutes"]="勤務終了時間（分）を入力してください";
    }else if(!preg_match("/^[0-9]{1,2}$/",$e_minutes)){
        $output["err-e_minutes"]='勤務終了時間（分）は半角数字にしてください。';
    }else if(strlen($e_minutes)>2){
        $output["err-e_minutes"]="勤務終了時間（分）は2文字で入力してください";
    }else if($Year>=$year && $Month>=$month && $Day>=$day && $e_time>$n_time && $e_minutes>$n_minutes){
        $output["err-s_time"]="勤務終了時間（分）は現在の時刻以前にしてください。";
    }

    if(!isset($midnight_time) || !strlen($midnight_time)){
        $output["err-midnight_time"]="深夜時間（時）を入力してください";
    }else if(!preg_match("/^[0-9]{1,2}$/",$midnight_time)){
        $output["err-midnight_time"]='深夜時間（時）は半角数字にしてください。';
    }else if(strlen($midnight_time)>2){
        $output["err-midnight_time"]="深夜時間（時）は2文字で入力してください";
    }

    if(!isset($midnight_minutes) || !strlen($midnight_minutes)){
        $output["err-midnight_minutes"]="深夜時間（分）を入力してください";
    }else if(!preg_match("/^[0-9]{1,2}$/",$midnight_minutes)){
        $output["err-midnight_minutes"]='深夜時間（分）は半角数字にしてください。';
    }else if(strlen($midnight_minutes)>2){
        $output["err-midnight_minutes"]="深夜時間（分）は2文字で入力してください";
    }

    if(!isset($work_time) || !strlen($work_time)){
        $output["err-work_time"]="勤務（時）を入力してください";
    }else if(!preg_match("/^[0-9]{1,2}$/",$work_time)){
        $output["err-work_time"]='勤務（時）は半角数字にしてください。';
    }else if(strlen($work_time)>2){
        $output["err-work_time"]="勤務（時）は2文字で入力してください";
    }

    if(!isset($work_minutes) || !strlen($work_minutes)){
        $output["err-work_minutes"]="勤務（分）を入力してください";
    }else if(!preg_match("/^[0-9]{1,2}$/",$work_minutes)){
        $output["err-work_minutes"]='勤務（分）は半角数字にしてください。';
    }else if(strlen($work_minutes)>2){
        $output["err-work_minutes"]="勤務（分）は2文字で入力してください";
    }else 
    

    if(!isset($break_time) || !strlen($break_time)){
        $output["err-break_time"]="休憩時間（時）を入力してください";
    }else if(!preg_match("/^[0-9]{1,2}$/",$break_time)){
        $output["err-break_time"]='休憩時間（時）は半角数字にしてください。';
    }else if(strlen($break_time)>2){
        $output["err-break_time"]="休憩時間（時）は2文字で入力してください";
    }else if($work_time>=8 && $break_time<=0){
        $output["err-break_time"]="8時間を超える勤務は1時間以上の休憩が必要です。";
    }

    if(!isset($break_minutes) || !strlen($break_minutes)){
        $output["err-break_minutes"]="休憩時間（分）を入力してください";
    }else if(!preg_match("/^[0-9]{1,2}$/",$break_minutes)){
        $output["err-break_minutes"]='休憩時間（分）は半角数字にしてください。';
    }else if(strlen($break_minutes)>2){
        $output["err-break_minutes"]="休憩時間（分）は2文字で入力してください";
    }else if($work_time>=6 && $break_time<=0 && $break_minutes<45){
        $output["err-break_time"]="6時間を超える勤務は45分以上の休憩が必要です。";
    }

    if(!isset($over_time) || !strlen($over_time)){
        $output["err-over_time"]="時間外労働時間（時）を入力してください";
    }else if(!preg_match("/^[0-9]{1,2}$/",$over_time)){
        $output["err-over_time"]='時間外労働時間（時）は半角数字にしてください。';
    }else if(strlen($over_time)>2){
        $output["err-over_time"]="時間外労働時間（時）は2文字で入力してください";
    }

    if(!isset($over_minutes) || !strlen($over_minutes)){
        $output["err-over_minutes"]="時間外労働時間（分）を入力してください";
    }else if(!preg_match("/^[0-9]{1,2}$/",$over_minutes)){
        $output["err-over_minutes"]='時間外労働時間（分）は半角数字にしてください。';
    }else if(strlen($over_minutes)>2){
        $output["err-over_minutes"]="時間外労働時間（分）は2文字で入力してください";
    }

    if($work_type==0){
        if((int)$work_time==0 && (int)$work_minutes==0){
            $output["err-work_minutes"]="勤務時間（分）を1分以上にしてください。";
        }
    }else{
        if((int)$over_time==0 && (int)$over_minutes==0){
            $output["err-over_minutes"]="時間外勤務（分）を1分以上にしてください。";
        }
    }

    if((int)$over_time>0 || (int)$over_minutes>0){
        if(!isset($_POST["over_time_reason"]) || !strlen($_POST["over_time_reason"])){
            $output["err-over_time_reason"]="時間外勤務内容を入力してください";
        }else if(preg_match("/^[ぁ-んァ-ヶー々一-龠０-９a-zA-Z0-9]{500}$/u",$_POST["over_time_reason"])){
            $output["err-over_time_reason"]="時間外勤務内容を正しく入力してください。";
        }else{
            $over_time_reason=htmlspecialchars($_POST["over_time_reason"],ENT_QUOTES,'UTF-8');
            $over_time_flag=true;
        }
    }

/*
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
        $sql="SELECT * FROM `staff` WHERE `number`=:number";
        $stmt=connect()->prepare($sql);
        $stmt->bindParam(':number',$_SESSION["e-id"]);
        $stmt->execute();
        $result=$stmt->fetch();
        
        if(!password_verify($pass, $result["pass"])){
            $output["err-pass"]='パスワードが違います。';
        }
        $stmt=null;
    }
*/
    if(count($output)>0){
        //エラーがあった場合は戻す
        $output["header-sei"]=$_SESSION["header-sei"];
        $output["e-id"]=$_SESSION['e-id'];
        $_SESSION=$output;
        $address='attendance_form.php'; 
        if(isset($_GET["id"])){                     //GET値ある場合は付与
            $address.="?id=".$_POST["id"];
        }
        header('Location: '.$address);
        return;
    }
?>
