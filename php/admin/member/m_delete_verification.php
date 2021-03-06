<?php
//ログイン
require_once "../logic/admin_login.php";
//削除確認と実行
require_once "./logic/mb_delete_check.php";
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
    <title>管理者・管理画面</title>
</head>
<body>
    <div id="wrapper">
        <header>
            <h1><a href="../admin_top.php">管理者・管理画面</a></h1>
            <div><?php echo h($_SESSION["header-sei"]);?>さん、お疲れ様です。</div>
        </header>
        <main>
            <aside>
                <label class="title" for="box1" style="background-color:#333333; color:white;" >MENU</label>
                <input type="checkbox" id="box1" style="display:none;">
                <ul id="menu" class="toggle">
                    <li>スタッフ管理</li>
                    <ul>
                        <li><a href="./m_form.php">従業員登録</a></li>
                        <li><a href="./m_list.php">従業員編集</a></li>
                    </ul>
                    <li>勤怠管理</li>
                    <ul>
                        <li><a href="../attendance/a_form.php">勤怠登録</a></li>
                        <li><a href="../attendance/mb_ad_list.php">勤務状況一覧</a></li>
                    </ul>
                    <li>お知らせ管理</li>
                    <ul>
                        <li><a href="../notification/n_form.php">投稿</a></li>
                        <li><a href="../notification/n_list.php">編集</a></li>
                    </ul>
                    <li>メッセージ送信</li>
                    <ul>
                        <li><a href="../message/staff_form.php">投稿</a></li>
                        <li><a href="../message/staff_form_top.php">一覧</a></li>
                    </ul>
                    <li>CSV出力</li>
                    <ul>
                        <li><a href="../csv/attendance_select.php">全従業員出力</a></li>
                        <li><a href="../csv/attendance_member_list.php">個別出力</a></li>
                    </ul>
                    <li>その他</li>
                    <ul>
                        <li>
                            <a href="../../logic/logout.php">ログアウト</a>
                        </li>
                    </ul>
                </ul>
            </aside>
            <article id="list_output">
                <h1>削除確認</h1>
                <form action="member_delete.php" method="POST">
                    <h2>以下の内容を削除します。</h2>
                    <p style="color:red">一度、削除すると削除される方の勤怠情報も削除されます。</p>
                    <table style="width:100%;">
                        <tbody>
                            <tr>
                                <th>社員No.</th>
                                <th>姓</th>
                                <th>名</th>
                            </tr>
<?php foreach($info as $key => $value) :?>
                            <tr>
                                <td><?php echo h($value["number"]);?></td>
                                <td><?php echo h($value["sei"]);?></td>
                                <td><?php echo h($value["mei"]);?></td>
                            </tr>
<?php endforeach; ?>
                        </tbody>
                    </table>
                    <p>
                        <input type="submit" value="削除">
                        <input type="button" value="戻る" onclick="history.go(-1)">
                    </p>

<?php foreach($check as $value_a):?>
                    <input type="hidden" name="checkbox[]" value="<?php echo h($value_a);?>">
<?php endforeach; ?>
                    <input type="hidden" name="csrf_token" value="<?php echo h($_POST['csrf_token']);?>">
                    <input type="hidden" name="key" value="key"> 
                                    
                </form>
            </article>
        </main>
        <footer>
            <p><small>&copy;&nbsp;2021&nbsp;Katsuya&nbsp;Kawamoto*</small></p>
        </footer>
    </div>
</body>
</html>