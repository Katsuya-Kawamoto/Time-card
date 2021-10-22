<?php
//ログイン
require_once "./logic/login.php";
var_dump($_SESSION);

//お知らせ情報取得
require_once "./logic/db_access.php";
$db=new db();
$title=$db->info_title();
$info=$db->info_input($_GET["id"]);

//今日の日付取得
require "../staff/logic/time_input.php";
//今月の勤務状況一覧取得
require "../staff/logic/time_info_input.php";
//今月の勤務状況算出取得
require "../staff/logic/time_calculation.php";
//今月の出勤回収算出
require "../staff/logic/time_cont.php";

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
            <div><?php echo $_SESSION["header-sei"];?>さん、お疲れ様です。</div>
        </header>
        <main>
            <aside>
                <ul>
                    <li>スタッフ管理</li>
                    <ul>
                        <li><a href="member_register.php">従業員登録</a></li>
                        <li><a href="member_list.php">従業員編集</a></li>
                    </ul>
                    <li>お知らせ管理</li>
                    <ul>
                        <li><a href="notification_form.php">投稿</a></li>
                        <li><a href="notification_list.php">編集</a></li>
                    </ul>
                    <li>CSV出力</li>
                    <ul>
                        <li><a href="attendance_select.php">全従業員出力</a></li>
                        <li><a href="attendance_member_list.php">個別出力</a></li>
                    </ul>
                    <li><a href="../logic/logout.php">ログアウト</a></li>
                </ul>
            </aside>
            <article>
                <section id="notification">
                    <h1>お知らせ</h1>
                    <ul id="info">
<?php if(!isset($result)&&!isset($title)):?>
                        <li>現在、新しい情報はありません。</li>
<?php else: ?>  
                        <li>
                            <dl>
                                <dt><b>件名</b></dt>
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
                    <ul>
<?php foreach($title as $key => $value) :?>
                        <li><a href="./info.php?id=<?php echo $value["id"];?>"><?php echo $value["title"];?></a></li>
<?php endforeach; ?>
                    </ul>
<?php endif; ?>
                </section>
                <section id="time">
                    <h1><?php echo $month ?>月の勤務時間 (<?php echo $day ?>日現在)</h1>
                    <table><tbody>
                        <tr>
                            <th>勤務日数</th>
                            <td><?php echo $work_time_count; ?>日</td>
                        </tr>
                        <tr>
                            <th>勤務時間</th>
                            <td><?php echo $work_time; ?>時間<?php printf("%02d", $work_minutes); ?>分</td>
                        </tr>
                        <tr>
                            <th>残業日数</th>
                            <td><?php echo $over_time_count; ?>日</td>
                        </tr>
                        <tr>
                            <th>残業時間</th>
                            <td><?php echo $over_time; ?>時間<?php printf("%02d", $over_minutes); ?>分</td>
                        </tr>
                        <tr>
                            <th>深夜勤務日数</th>
                            <td><?php echo $midnight_time_count; ?>日</td>
                        </tr>
                        <tr>
                            <th>深夜勤務時間</th>
                            <td><?php echo $midnight_time; ?>時間<?php printf("%02d", $midnight_minutes); ?>分</td>
                        </tr>
                    </tbody></table>
                </section>
            </article>
        </main>
        <footer>
            <p>&copy;&nbsp;2021&nbsp;Katsuya&nbsp;Kawamoto*</p>
        </footer>
    </div>
</body>
</html>