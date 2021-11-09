<?php
//ログイン
require "../../logic/staff_login.php";
//選択された勤務情報の削除
require_once "../logic/ad_function.php";
//filter関数
require_once '../../logic/common_func.php';
$_SESSION['csrf_token']=$output['csrf_token'];  //トークン

//前ページのURLを取得
if($_SERVER["REQUEST_METHOD"]!=="POST"){        //POSTからのaccess
    $_SESSION["err-form"]="再度、削除する日時を選択してください。";
}else if(!isset($_POST["key"])){ 
    $_SESSION["err-form"]="削除する日時の選択がされていません。";
}else if(!$token = f('csrf_token')){
    $_SESSION["err-form"]="トークンエラー：再度入力を行ってください。";
}else if (!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
    $_SESSION["err-form"]="トークンエラー：再度入力を行ってください。";
}else{
    $check=$_POST["key"];                                               
    $Output=ad_check($check);                                           //選択された情報の取得
    if(!isset($Output)){                                                //情報無い場合はエラーで返す
        $_SESSION["err-form"]="選択された情報が取得出来ませんでした。";
    }
}

if(!isset($_SESSION["err-form"])){
    ad_delete();                                                        //削除の実行
    $_SESSION["delete"]="削除を完了しました。";                           //メッセージの取得
    require_once "../../logic/time_input.php";
    $time=Time_input();                                                 //現在の日付取得
    require_once "../../logic/common_func.php";
    foreach($check as $key_value){                                      //１件ずつKEYを取得して
        log_insert($key_value,$time);                                   //ログ書き込み
    }
}

//データベース切断
$stmt=null;
$pdo=null;
unset($_SESSION['csrf_token']);   

if(isset($output['HTTP'])){
    header('Location: '.$output['HTTP']);                                   //リストに戻る
    return;
}else{
    $_SESSION["err-form"] = "直接アクセス禁止";
    header('Location: /timecard/index.php');
    return;
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../css/reset.css"><!--リセットCSS-->
    <link rel="stylesheet" href="../../css/style2.css"><!--メイン用CSS-->
    <title>従業員・管理画面</title>
</head>
<body>
    <div id="wrapper">
        <header>
            <h1><a href="../staff_top.php">従業員・管理画面</a></h1>
            <div><?php echo h($_SESSION["header-sei"]);?>さん、お疲れ様です。</div>
        </header>
        <main>
            <aside>
                <label class="title" for="box1" style="background-color:#333333; color:white;" >MENU</label>
                <input type="checkbox" id="box1" style="display:none;">
                <ul id="menu" class="toggle">
                    <li>勤怠管理</li>
                    <ul>
                        <li><a href="a_form.php">登録</a></li>
                        <li><a href="a_list.php">編集</a></li>
                    </ul>
                    <li>パスワード管理</li>
                    <ul>
                        <li><a href="../pass/pass_reset.php">変更</a></li>
                    </ul>
                    <li>メッセージ送信</li>
                    <ul>
                        <li><a href="../message/admin_form_top.php">一覧</a></li>
                        <li><a href="../message/admin_form.php">送信</a></li>
                    </ul>
                    <li>その他</li>
                    <ul>
                        <li>
                            <a href="../../logic/logout.php">ログアウト</a>
                        </li>
                    </ul>
                </ul>
            </aside>
            <article>
                <h1>勤怠削除完了</h1>
                <p>指定された項目の削除を完了しました。</p>
            </article>
        </main>
        <footer>
            <p><small>&copy;&nbsp;2021&nbsp;Katsuya&nbsp;Kawamoto*</small></p>
        </footer>
    </div>
</body>
</html>