<?php
//ログイン
require "./logic/login.php";
//勤務状況取得
require_once "./logic/time_input.php";       //今日の日付
require_once "./logic/time_info_input.php";  //今月の勤務状況一覧
require_once "./logic/time_calculation.php"; //今月の勤務状況
require_once "./logic/time_cont.php";        //今月の出勤回数
//セッション確認
var_dump($_SESSION);
//データベース切断
$stmt=null;
$pdo=null;
?>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/reset.css"><!--リセットCSS-->
    <link rel="stylesheet" href="../css/style2.css"><!--メイン用CSS-->
    <title>従業員・管理画面</title>
</head>
<body><a href="http://" target="_blank" rel="noopener noreferrer"></a>
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
                <h1>勤怠登録フォーム</h1>
                    <form action="attendance_delete_verification.php" method="POST">
                    <div>
                        <h2><?php echo $month ?>月勤怠状況</h2>
                        <p><?php echo $day ?>日現在</p>
                    </div>
<?php if($result): ?>     
                    <ul>
                        <li>
                            <dl class="pc_flex">
                                <dt><b>総勤務時間</b></dt>
                                <dd><?php echo $work_time; ?>時間<?php printf("%02d", $work_minutes); ?>分</dd>
                            </dl>
                            <dl class="pc_flex">
                                <dt><b>総時間外労働時間</b></dt>
                                <dd><?php echo $over_time; ?>時間<?php printf("%02d", $over_minutes); ?>分</dd>
                            </dl>
                            <dl class="pc_flex">
                                <dt><b>総深夜勤務時間</b></dt>
                                <dd><?php echo $midnight_time; ?>時間<?php printf("%02d", $midnight_minutes); ?>分</dd>
                            </dl>
                        </li>
                    </ul>
                    <table style="border-collapse: collapse;" id="time-info">
                        <tbody class="list">
                            <tr>
                                <th rowspan="2">勤務日</th><th rowspan="2">休日出勤</th><th colspan="3">勤務時間</th>
                                <th rowspan="2">勤務時間</th><th class="pc_only" rowspan="2">時間外</th><th class="pc_only" rowspan="2">深夜時間</th>
                                <th rowspan="2">編集</th><th rowspan="2">削除</th>
                            </tr>
                            <tr>
                                <th>開始</th><th>～</th><th>終了</th>
                                
                                
                            </tr>
<?php foreach($result as $key=>$value):?>
                            <tr>
                                <td><?php echo $value["day"]; ?>日</td>
                                <td><?php if($value["work_type"]==1)echo "*";?></td>
                                <td><?php echo $value["s_time"]; ?>:<?php printf("%02d", $value["s_minutes"]);?></td>
                                <td>～</td>
                                <td><?php echo $value["e_time"]; ?>:<?php printf("%02d", $value["e_minutes"]);?></td>
                                <td><?php echo $value["work_time"]; ?>時間<?php echo $value["work_minutes"]; ?>分</td>
                                <td class="pc_only"><?php echo $value["over_time"]; ?>時間<?php echo $value["over_minutes"]; ?>分</td>
                                <td class="pc_only"><?php echo $value["midnight_time"]; ?>時間<?php echo $value["midnight_minutes"]; ?>分</td>
                                <td class="delete"><a style="display:block;" href="attendance_form.php?id=<?php echo $value['keey'];?>">編集</a></td>
                                <td class="delete"><input type="checkbox" name="key[]" id="key" value="<?php echo $value['keey'];?>"></td>
                            </tr>   
<?php endforeach; ?>
                        </tbody>
                    </table>

                    <ul>
                        <li>選択した項目を一括削除</li>
                        <li><input type="submit" value="削除"></li>
                    </ul>
                </form>
<?php else: ?>
    <h2>取得エラー</h2>
    <p>情報がありませんでした。</p>
<?php endif; ?>
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