<?php
//ログイン
require "./logic/login.php";
//勤務状況取得
require_once "./logic/time_input.php";       //今日の日付
require_once "./logic/time_info_input.php";  //今月の勤務状況一覧
require_once "./logic/time_calculation.php"; //今月の勤務状況
require_once "./logic/time_cont.php";        //今月の出勤回数
//お知らせ情報取得
require_once "../admin/logic/db_access.php";
$db=new db();
$result=$db->info_title();                          //件名
//セッション確認
var_dump($_SESSION);
//セッション切断
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
                <section id="notification">
                    <h1>お知らせ</h1>
                    <p>クリックすると詳細が見れます。</p>
                    <ul>
<?php if(!isset($result)):?>
                        <li>現在、新しい情報はありません。</li>
<?php else: ?>  
<?php foreach($result as $key => $value) :?>
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
            <nav>
                <p><a href="./logic/logout.php">ログアウト</a></p>
            </nav>
        </footer>
    </div>
</body>
</html>