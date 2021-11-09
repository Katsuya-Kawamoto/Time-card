<?php
//ログイン
require_once "../../logic/staff_login.php";
//トークン生成
require_once '../../logic/common_func.php';
//フォームチェック
require_once "./logic/ad_form_check.php";

//HTML表示の為、勤務体系 数値→文字に変換
$worktype_arr=array("通常勤務","休日出勤");
//セッション確認
//var_dump($_SESSION);
//var_dump($_POST);
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
            <h1>勤怠登録確認</h1>
            <p>以下の内容で登録します。</p>
                <form action="a_submit.php" method="POST">
                    <ul id="form" class="number">
                        <li>
                            <dl class="m-bottom5px">
                                <dt>社員No.</dt>
                                <dd>
                                    <ul>
                                        <li><?php echo h($_SESSION['e-id']);?></li>
                                    </ul>
                                    <input type="hidden" name="number" id="number" value="<?php echo h($_SESSION['e-id']);?>">
                                </dd>
<?php if(isset($output["err-number"])) :?>
                        <dd class="error"><?php echo h($output["err-number"]); ?></dd>
<?php endif; ?>
                            </dl>
                        </li>
                        <li>
                            <dl class="m-bottom5px">
                                <dt>勤務日</dt>
                                <dd><?php echo h($input["Year"]);?>年<?php echo h($input["Month"]);?>月<?php echo h($input["Day"]);?>日</dd>
                            </dl>
                        </li>
                        <li>
                        <dl class="m-bottom5px">
                                <dt>勤務形態</dt>
                                <dd><?php echo $worktype_arr[h($input["work_type"])];?></dd>
                        </li>
                        <li>
                            <dl class="m-bottom5px">
                                <dt>勤務時間</dt>
                                <dd>
                                    <ul style="display:flex">
                                        <li style="margin-right:5px;">開始時間：<?php echo h($input["s_time"]);?>時</li>
                                        <li><?php echo $input["s_minutes"];?>分</li>
                                    </ul>
                                    <ul style="display:flex">
                                        <li style="margin-right:5px;">終了時間：<?php echo h($input["e_time"]);?>時</li>
                                        <li><?php echo $input["e_minutes"];?>分</li>
                                    </ul>
                                </dd>
                            </dl>
                        </li>
                        <li>
                            <dl class="m-bottom5px">
                                <dt>勤務時間</dt>
                                <dd><?php echo h($input["work_time"]);?>時間<?php echo h($input["work_minutes"]);?>分</dd>
                                <dt>休憩時間</dt>
                                <dd><?php echo h($input["break_time"]);?>時間<?php echo h($input["break_minutes"]);?>分</dd>
                                <dt>深夜勤務時間</dt>
                                <dd><?php echo h($input["midnight_time"]);?>時間<?php echo h($input["midnight_minutes"]);?>分</dd>
                                <dt>時間外労働</dt>
                                <dd><?php echo h($input["over_time"]);?>時間<?php echo h($input["over_minutes"]);?>分</dd>
<?php if(isset($input["over_time_reason"])) :?>
                                <dt>時間申請理由</dt>
                                <dd><?php echo nl2br(h($input["over_time_reason"]));?></dd>
<?php endif; ?>
                            </dl>
                        </li>
                        <li>
                            <input type="submit" value="送信">
                            <input type="button" value="戻る" onclick="history.go(-1)">
                        </li>
                    </ul>
                    <input type="hidden" name="Year" id="year" value="<?php echo h($input["Year"]);?>">
                    <input type="hidden" name="Month" id="month" value="<?php echo h($input["Month"]);?>">
                    <input type="hidden" name="Day" id="day" value="<?php echo h($input["Day"]);?>">
                    <input type="hidden" name="work_type" id="work_type" value="<?php echo h($input["work_type"]);?>">
                    <input type="hidden" name="s_time" id="s_time" value="<?php echo h($input["s_time"]);?>">
                    <input type="hidden" name="e_time" id="e_time" value="<?php echo h($input["e_time"]);?>">
                    <input type="hidden" name="s_minutes" id="s_minutes" value="<?php echo h($input["s_minutes"]);?>">
                    <input type="hidden" name="e_minutes" id="e_minutes" value="<?php echo h($input["e_minutes"]);?>">
                    <input type="hidden" name="work_time" id="work_time" value="<?php echo h($input["work_time"]);?>">
                    <input type="hidden" name="work_minutes" id="work_minutes" value="<?php echo h($input["work_minutes"]);?>">
                    <input type="hidden" name="break_time" id="break_time" value="<?php echo h($input["break_time"]);?>">
                    <input type="hidden" name="break_minutes" id="break_minutes" value="<?php echo h($input["break_minutes"]);?>">
                    <input type="hidden" name="midnight_time" id="midnight_time" value="<?php echo h($input["midnight_time"]);?>">
                    <input type="hidden" name="midnight_minutes" id="midnight_minutes" value="<?php echo h($input["midnight_minutes"]);?>">
                    <input type="hidden" name="over_time" id="over_time" value="<?php echo h($input["over_time"]);?>">
                    <input type="hidden" name="over_minutes" id="over_minutes" value="<?php echo h($input["over_minutes"]);?>">
                    <input type="hidden" name="over_time_reason" id="over_time_reason" value="<?php if(isset($input["over_time_reason"]))echo h($input["over_time_reason"]);?>">
                    <!--<input type="hidden" name="pass" id="pass" value="<?php //echo h($input["pass"]);?>">-->
                    <!--<input type="hidden" name="pass_conf" id="pass_conf" value="<?php //echo h($input["pass_conf"]);?>">-->
                    <input type="hidden" name="csrf_token" value="<?php echo $_POST["csrf_token"]; ?>">
                    <!--ログ用-->
                    <input type="hidden" name="type" value="<?php echo isset($_POST["id"])?1:0;?>">
                    <input type="hidden" name="contributor_no" value="<?php echo h($_SESSION["e-id"]); ?>">
                    <input type="hidden" name="contributor" value="<?php echo ($_SESSION["e-id"]==$number)? 0:1; ?>">
<?php if(isset($_POST["id"])): ?>
                    <input type="hidden" name="id" value="<?php echo h($_POST["id"]); ?>">
<?php endif; ?>
                </form>
            </article>
        </main>
        <footer>
            <p><small>&copy;&nbsp;2021&nbsp;Katsuya&nbsp;Kawamoto*</small></p>
        </footer>
    </div>
</body>
</html>