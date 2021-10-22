<?php
//ログイン
require_once "./logic/login.php";
//フォームチェック
require_once "./logic/ad_form_check.php";

$worktype_arr=array("通常勤務","休日出勤");
//セッション確認
var_dump($_SESSION);
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
    <link rel="stylesheet" href="../../css/reset.css"><!--リセットCSS-->
    <link rel="stylesheet" href="../css/style2.css"><!--メイン用CSS-->
    <title>従業員・管理画面</title>
</head>
<body>
    <div id="wrapper">
        <header>
            <h1><a href="./staff_top.php">従業員・管理画面</a></h1>
            <div><?php echo $_SESSION["header-sei"];?>さん、お疲れ様です。</div>
        </header>
        <main>
        <aside>
                <ul>
                    <li>勤怠管理</li>
                    <ul>
                        <li><a href="attendance_form.php">登録</a></li>
                        <li><a href="attendance_list.php">編集</a></li>
                    </ul>
                    <li>パスワード管理</li>
                    <ul>
                        <li><a href="pass_reset.php">変更</a></li>
                    </ul>
                </ul>
            </aside>
            <article>
            <h1>勤怠登録確認</h1>
            <p>以下の内容で登録します。</p>
                <form action="attendance_submit.php" method="POST">
                    <ul id="form" class="number">
                        <li>
                            <dl class="m-bottom5px">
                                <dt>社員No.</dt>
                                <dd>
                                    <ul>
                                        <li><?php echo $_SESSION['e-id'];?></li>
                                    </ul>
                                    <input type="hidden" name="number" id="number" value="<?php echo $_SESSION['e-id'];?>">
                                </dd>
<?php if(isset($output["err-number"])) :?>
                        <dd class="error"><?php echo $output["err-number"]; ?></dd>
<?php endif; ?>
                            </dl>
                        </li>
                        <li>
                            <dl class="m-bottom5px">
                                <dt>勤務日</dt>
                                <dd><?php echo $Year;?>年<?php echo $Month;?>月<?php echo $Day;?>日</dd>
                            </dl>
                        </li>
                        <li>
                        <dl class="m-bottom5px">
                                <dt>勤務形態</dt>
                                <dd><?php echo $worktype_arr[$work_type];?></dd>
                        </li>
                        <li>
                            <dl class="m-bottom5px">
                                <dt>勤務時間</dt>
                                <dd>
                                    <ul style="display:flex">
                                        <li style="margin-right:5px;">開始時間：<?php echo $s_time;?>時</li>
                                        <li><?php echo $s_minutes;?>分</li>
                                    </ul>
                                    <ul style="display:flex">
                                        <li style="margin-right:5px;">終了時間：<?php echo $e_time;?>時</li>
                                        <li><?php echo $e_minutes;?>分</li>
                                    </ul>
                                </dd>
                            </dl>
                        </li>
                        <li>
                            <dl class="m-bottom5px">
                                <dt>勤務時間</dt>
                                <dd><?php echo $work_time;?>時間<?php echo $work_minutes;?>分</dd>
                                <dt>休憩時間</dt>
                                <dd><?php echo $break_time;?>時間<?php echo $break_minutes;?>分</dd>
                                <dt>深夜勤務時間</dt>
                                <dd><?php echo $midnight_time;?>時間<?php echo $midnight_minutes;?>分</dd>
                                <dt>時間外労働</dt>
                                <dd><?php echo $over_time;?>時間<?php echo $over_minutes;?>分</dd>
<?php if(isset($over_time_reason)) :?>
                                <dt>時間申請理由</dt>
                                <dd><?php echo nl2br($over_time_reason);?></dd>
<?php endif; ?>
                            </dl>
                        </li>
                        <li>
                            <input type="submit" value="送信">
                            <input type="button" value="戻る" onclick="history.go(-1)">
                        </li>
                    </ul>
                    <input type="hidden" name="year" id="year" value="<?php echo $Year;?>">
                    <input type="hidden" name="month" id="month" value="<?php echo $Month;?>">
                    <input type="hidden" name="day" id="day" value="<?php echo $Day;?>">
                    <input type="hidden" name="work_type" id="work_type" value="<?php echo $work_type;?>">
                    <input type="hidden" name="s_time" id="s_time" value="<?php echo $s_time;?>">
                    <input type="hidden" name="e_time" id="e_time" value="<?php echo $e_time;?>">
                    <input type="hidden" name="s_minutes" id="s_minutes" value="<?php echo $s_minutes;?>">
                    <input type="hidden" name="e_minutes" id="e_minutes" value="<?php echo $e_minutes;?>">
                    <input type="hidden" name="work_time" id="work_time" value="<?php echo $work_time;?>">
                    <input type="hidden" name="work_minutes" id="work_minutes" value="<?php echo $work_minutes;?>">
                    <input type="hidden" name="break_time" id="break_time" value="<?php echo $break_time;?>">
                    <input type="hidden" name="break_minutes" id="break_minutes" value="<?php echo $break_minutes;?>">
                    <input type="hidden" name="midnight_time" id="midnight_time" value="<?php echo $midnight_time;?>">
                    <input type="hidden" name="midnight_minutes" id="midnight_minutes" value="<?php echo $midnight_minutes;?>">
                    <input type="hidden" name="over_time" id="over_time" value="<?php echo $over_time;?>">
                    <input type="hidden" name="over_minutes" id="over_minutes" value="<?php echo $over_minutes;?>">
                    <input type="hidden" name="over_time_reason" id="over_time_reason" value="<?php echo $over_time_reason;?>">
                    <input type="hidden" name="pass" id="pass" value="<?php echo $pass;?>">
                    <input type="hidden" name="pass_conf" id="pass_conf" value="<?php echo $pass_conf;?>">
                    <input type="hidden" name="csrf_token" value="<?php echo $_POST["csrf_token"]; ?>">
<?php if(isset($_POST["id"])): ?>
                    <input type="hidden" name="id" value="<?php echo $_POST["id"]; ?>">
<?php endif; ?>
                </form>
            </article>
        </main>
        <footer>
            <nav>
                <p><a href="./logic/logout.php">ログアウト</a></p>
            </nav>
        </footer>
    </div>
</body>
</html>