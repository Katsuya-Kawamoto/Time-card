<?php
//ログイン
require_once "../logic/admin_login.php";

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
    <title>管理者・管理画面</title>
</head>
<body>
    <div id="wrapper">
        <header>
            <h1><a href="admin_top.php">管理者・管理画面</a></h1>
            <div><?php echo h($_SESSION["header-sei"]);?>さん、お疲れ様です。</div>
        </header>
        <main>
            <aside>
                <label class="title" for="box1" style="background-color:#333333; color:white;" >MENU</label>
                <input type="checkbox" id="box1" style="display:none;">
                <ul id="menu" class="toggle">
                    <li>スタッフ管理</li>
                    <ul>
                        <li><a href="./member/m_form.php">従業員登録</a></li>
                        <li><a href="./member/m_list.php">従業員編集</a></li>
                    </ul>
                    <li>勤怠管理</li>
                    <ul>
                        <li><a href="./attendance/a_form.php">勤怠登録</a></li>
                        <li><a href="./attendance/mb_ad_list.php">勤務状況一覧</a></li>
                    </ul>
                    <li>お知らせ管理</li>
                    <ul>
                        <li><a href="./notification/n_form.php">投稿</a></li>
                        <li><a href="./notification/n_list.php">編集</a></li>
                    </ul>
                    <li>メッセージ送信</li>
                    <ul>
                        <li><a href="./message/staff_form.php">投稿</a></li>
                        <li><a href="./message/staff_form_top.php">一覧</a></li>
                    </ul>
                    <li>CSV出力</li>
                    <ul>
                        <li><a href="./csv/attendance_select.php">全従業員出力</a></li>
                        <li><a href="./csv/attendance_member_list.php">個別出力</a></li>
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
                                <dd><?php echo $info["title"];?></dd>
                            </dl>
                        </li>
                        <li>
                            <dl>
                                <dt><b>内容</b></dt>
                                <dd><?php echo nl2br($info["contents"]);?></dd>
                            </dl>
                        </li>
                        <li>
                            <dl style="display:flex">
                                <dt><b>投稿者：</b></dt>
                                <dd><?php echo $info["name"];?></dd>
                            </dl>
                        </li>    
                        <li>
                            <dl style="display:flex">
                                <dt><b>date：</b></dt>
                                <dd><?php echo $info["created_at"];?></dd>
                            </dl>
                        </li>    
                    </ul>
                    <h2>その他の情報</h2>
                    <p>クリックすると詳細が見れます。</p>
                    <ul id="n-title">
<?php foreach($title as $key => $value) :?>
                        <li>
                            <a href="./info.php?id=<?php echo $value["id"];?>">
                                <?php echo $value["title"];?>
                                <span id="day">|<?php echo $value["created_at"];?></span>
                            </a>
                        </li>
<?php endforeach; ?>
                    </ul>
<?php endif; ?>
                </section>
                <section id="time">
                    <h1><?php echo (int)$time["month"] ?>月の勤務時間 (<?php echo (int)$time["day"] ?>日現在)</h1>
<?php if(isset($time_info)):?>                    
                    <ul>
                        <li>
                            <dl>
                                <dt>勤務日数</dt>
                                <dd><?php echo $time_count["work_count"]; ?>日</dd>
                            </dl>
                        </li>
                        <li>
                            <dl>
                                <dt>勤務時間</dt>
                                <dd><?php echo $time_cl["work_time"]; ?>時間<?php printf("%02d", $time_cl["work_minutes"]); ?>分</dd>
                            </dl>
                        </li>
                        <li>
                            <dl>
                                <dt>時間外勤務日数</dt>
                                <dd class="over_time">
                                    <ul>
                                        <li><?php echo $time_count["over_count"]; ?>日</li>
                                        <li style="border:1px solid #333333; margin-left:5px; background-color:#ffffdd; border-radius:5px;">
                                            <dl class="pc_flex">
                                                <dt><b>休日出勤日数</b></dt>
                                                <dd><?php echo $time_count["holiday_count"]; ?>日</dd>
                                            </dl>
                                            <dl class="pc_flex">
                                                <dt><b>残業日数</b></dt>    
                                                <dd><?php echo $time_count["OVER_count"]; ?>日</dd>
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
                                        <li><?php echo $time_cl["over_time"]; ?>時間<?php printf("%02d", $time_cl["over_minutes"]); ?>分</li>
                                        <li style="border:1px solid #333333; margin-left:5px; background-color:#ffffdd; border-radius:5px;">
                                            <dl class="pc_flex">
                                                <dt><b>休日出勤時間</b></dt>
                                                <dd><?php echo $time_cl["holiday_time"]; ?>時間<?php printf("%02d", $time_cl["holiday_minutes"]); ?>分</dd>
                                            </dl>
                                            <dl class="pc_flex">
                                                <dt><b>残業時間</b></dt>    
                                                <dd><?php echo $time_cl["OVER_time"]; ?>時間<?php printf("%02d", $time_cl["OVER_minutes"]); ?>分</dd>
                                            </dl>
                                        </li>
                                    </ul> 
                                </dd>
                            </dl>
                        </li>
                        <li>
                            <dl>
                                <dt>深夜勤務日数</dt>
                                <dd><?php echo $time_count["midnight_count"]; ?>日</dd>
                            </dl>
                        </li>
                        <li>
                            <dl>
                                <dt>深夜勤務時間</dt>
                                <dd><?php echo $time_cl["midnight_time"]; ?>時間<?php printf("%02d", $time_cl["midnight_minutes"]); ?>分</dd>
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