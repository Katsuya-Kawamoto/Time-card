<?php
//ログイン
require "../logic/staff_login.php";

//お知らせ情報取得
require_once "../logic/common_func.php";
$title=info_title();                                                            //件名取得
$info=info_input($_GET["id"]);                                                  //記事詳細取得

//勤務状況取得
require_once "../logic/time_input.php";
$time=Time_input();                                                             //現在の日付取得
$time_info=time_info_input($_SESSION["e-id"],$time["month"],$time["year"]);     //今月の勤怠状況取得
$time_cl=time_calculation($time_info);                                          //総勤務時間算出
$time_count=time_count($time_info);                                             //出勤回数算出

//セッション確認
//var_dump($_SESSION);

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
            <div><?php echo h($_SESSION["header-sei"]);?>さん、お疲れ様です。</div>
        </header>
        <main>
            <aside>
                <label class="title" for="box1" style="background-color:#333333; color:white;" >MENU</label>
                <input type="checkbox" id="box1" style="display:none;">
                <ul id="menu" class="toggle">
                    <li>勤怠管理</li>
                    <ul>
                        <li><a href="./attendance/a_form.php">登録</a></li>
                        <li><a href="./attendance/a_list.php">編集</a></li>
                    </ul>
                    <li>パスワード管理</li>
                    <ul>
                        <li><a href="./pass/pass_reset.php">変更</a></li>
                    </ul>
                    <li>メッセージ送信</li>
                    <ul>
                        <li><a href="./message/admin_form_top.php">一覧</a></li>
                        <li><a href="./message/admin_form.php">送信</a></li>
                    </ul>
                    <li>その他</li>
                    <ul>
                        <li>
                            <a href="../logic/logout.php">ログアウト</a>
                        </li>
                    </ul>
                </ul>
            </aside>
            <article>
                <section id="notification">
                    <h1>お知らせ</h1>
                    <ul class="info">
<?php if(!isset($result)&&!isset($title)):?>
                        <li>現在、新しい情報はありません。</li>
<?php else: ?>  
                        <li>
                            <dl style="display:flex">
                                <dt><b>件名:</b></dt>
                                <dd><?php echo h($info["title"]);?></dd>
                            </dl>
                        </li>
                        <li>
                            <dl>
                                <dt><b>内容</b></dt>
                                <dd><?php echo nl2br(h($info["contents"]));?></dd>
                            </dl>
                        </li>
                        <li>
                            <dl style="display:flex">
                                <dt><b>投稿者：</b></dt>
                                <dd><?php echo h($info["name"]);?></dd>
                            </dl>
                        </li>    
                        <li>
                            <dl style="display:flex">
                                <dt><b>date：</b></dt>
                                <dd><?php echo h($info["created_at"]);?></dd>
                            </dl>
                        </li>    
                    </ul>
                    <h2>その他の情報</h2>
                    <p>クリックすると詳細が見れます。</p>
                    <ul id="n-title">
<?php foreach($title as $key => $value) :?>
                        <li>
                            <a href="./info.php?id=<?php echo h($value["id"]);?>">
                                <?php echo h($value["title"]);?>
                                <span id="day">|<?php echo h($value["created_at"]);?></span>
                            </a>
                        </li>
<?php endforeach; ?>
                    </ul>
<?php endif; ?>
                </section>
                <section id="time">
                    <h1><?php echo (int)h($time["month"]); ?>月の勤務時間 (<?php echo (int)h($time["day"]); ?>日現在)</h1>
<?php if(isset($time_info)):?>                    
                    <ul>
                        <li>
                            <dl>
                                <dt>勤務日数</dt>
                                <dd><?php echo h($time_count["work_count"]); ?>日</dd>
                            </dl>
                        </li>
                        <li>
                            <dl>
                                <dt>勤務時間</dt>
                                <dd><?php echo h($time_cl["work_time"]); ?>時間<?php printf("%02d", h($time_cl["work_minutes"])); ?>分</dd>
                            </dl>
                        </li>
                        <li>
                            <dl>
                                <dt>時間外勤務日数</dt>
                                <dd class="over_time">
                                    <ul>
                                        <li><?php echo h($time_count["over_count"]); ?>日</li>
                                        <li style="border:1px solid #333333; margin-left:5px; background-color:#ffffdd; border-radius:5px;">
                                            <dl class="pc_flex">
                                                <dt><b>休日出勤日数</b></dt>
                                                <dd><?php echo h($time_count["holiday_count"]); ?>日</dd>
                                            </dl>
                                            <dl class="pc_flex">
                                                <dt><b>残業日数</b></dt>    
                                                <dd><?php echo h($time_count["OVER_count"]); ?>日</dd>
                                            </dl>
                                        </li>
                                    </ul>
                                </dd>
                            </dl>
                        </li>
                        <li>
                            <dl>
                                <dt>時間外勤務時間</dt>
                                <dd class="over_time">
                                    <ul>
                                        <li><?php echo h($time_cl["over_time"]); ?>時間<?php printf("%02d", h($time_cl["over_minutes"])); ?>分</li>
                                        <li style="border:1px solid #333333; margin-left:5px; background-color:#ffffdd; border-radius:5px;">
                                            <dl class="pc_flex">
                                                <dt><b>休日出勤時間</b></dt>
                                                <dd><?php echo h($time_cl["holiday_time"]); ?>時間<?php printf("%02d", h($time_cl["holiday_minutes"])); ?>分</dd>
                                            </dl>
                                            <dl class="pc_flex">
                                                <dt><b>残業時間</b></dt>    
                                                <dd><?php echo h($time_cl["OVER_time"]); ?>時間<?php printf("%02d", h($time_cl["OVER_minutes"])); ?>分</dd>
                                            </dl>
                                        </li>
                                    </ul> 
                                </dd>
                            </dl>
                        </li>
                        <li>
                            <dl>
                                <dt>深夜勤務日数</dt>
                                <dd><?php echo h($time_count["midnight_count"]); ?>日</dd>
                            </dl>
                        </li>
                        <li>
                            <dl>
                                <dt>深夜勤務時間</dt>
                                <dd><?php echo h($time_cl["midnight_time"]); ?>時間<?php printf("%02d", h($time_cl["midnight_minutes"])); ?>分</dd>
                            </dl>
                        </li>
                    </ul>  
<?php else: ?>
                    <p>入力された勤務情報がありませんでした。。</p>
<?php endif; ?>
                </section>
            </article>
        </main>
        <footer>
            <p><small>&copy;&nbsp;2021&nbsp;Katsuya&nbsp;Kawamoto*</small></p>
        </footer>
    </div>
</body>
</html>