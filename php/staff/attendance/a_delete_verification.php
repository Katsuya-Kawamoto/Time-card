<?php
//ログイン
require "../../logic/staff_login.php";
//選択された勤務情報の取得
require_once "../logic/ad_function.php";
//エラーメッセージなどセッションに格納するもの
$_SESSION['csrf_token']=$output['csrf_token'];  //トークン
//前ページのURLを取得
if(!$_SESSION["HTTP"]=$_SERVER["HTTP_REFERER"]){
    $_SESSION["err-form"]="確認画面の直接アクセスは禁止です。";
    header('Location: /timecard/index.php');
    return;
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

//エラーがあった場合は前のページに戻る
if(isset($_SESSION["err-form"])){
    header('Location: '.$_SESSION["HTTP"]);
    return;
}
//データベース切断
$stmt=null;
$pdo=null;
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
                <h1>勤怠削除確認</h1>
                <p>以下の内容を削除します。</p>
                    <form action="a_delete.php" method="POST">
                    <table style="border-collapse: collapse;" id="time-info">
                        <tbody class="list">
                            <tr>
                                <th rowspan="2">勤務日</th><th colspan="3">勤務時間</th>
                                <th rowspan="2">勤務時間</th><th class="pc_only" rowspan="2">時間外</th><th class="pc_only" rowspan="2">深夜時間</th>
                            </tr>
                            <tr>
                                <th>開始</th><th>～</th><th>終了</th>
                                
                                
                            </tr>
<?php foreach($Output as $key=>$value):?>
                            <tr>
                                <td><?php echo h($value["day"]); ?>日</td>
                                <td><?php echo h($value["s_time"]); ?>:<?php printf("%02d", h($value["s_minutes"]));?></td>
                                <td>～</td>
                                <td><?php echo h($value["e_time"]); ?>:<?php printf("%02d", h($value["e_minutes"]));?></td>
                                <td><?php echo h($value["work_time"]); ?>時間<?php echo h($value["work_minutes"]); ?>分</td>
                                <td class="pc_only"><?php echo h($value["over_time"]); ?>時間<?php echo h($value["over_minutes"]); ?>分</td>
                                <td class="pc_only"><?php echo h($value["midnight_time"]); ?>時間<?php echo h($value["midnight_minutes"]); ?>分</td>
                            </tr>   
<?php endforeach; ?>
                        </tbody>
                    </table>
                    <ul>
                        <li>選択した項目を一括削除</li>
                        <li>
                            <input type="submit" value="削除">
                            <input type="button" value="戻る" onclick="history.go(-1)">
                        </li>
                    </ul>
<?php foreach($check as $value_a):?>
                                    <input type="hidden" name="key[]" value="<?php echo h($value_a);?>">
<?php endforeach; ?>
                    <input type="hidden" name="csrf_token" value="<?php echo h($_POST["csrf_token"]); ?>">
                    <!--ログ用-->
                    <input type="hidden" name="type" value="<?php echo "2";?>">
                    <input type="hidden" name="contributor_no" value="<?php echo h($_SESSION["e-id"]); ?>">
                    <input type="hidden" name="contributor" value="<?php echo ($_SESSION["e-id"]==$value["number"])? 0:1; ?>">
                </form>
            </article>
        </main>
        <footer>
            <p><small>&copy;&nbsp;2021&nbsp;Katsuya&nbsp;Kawamoto*</small></p>
        </footer>
    </div>
</body>
</html>