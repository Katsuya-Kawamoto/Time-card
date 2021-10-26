<?php
    //ログイン
    require_once "./logic/login.php";
    //削除確認
    require_once "./logic/nc_delete_check.php";
    //セッション確認
    var_dump($_SESSION);
    //セッション削除
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
                <ul id="menu">
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
                    <li>その他</li>
                    <ul>
                        <li>
                            <a href="../logic/logout.php">ログアウト</a>
                        </li>
                    </ul>
                </ul>
            </aside>
            <article>
                <h1>削除確認</h1>
                <p><b>以下の内容を削除します。</b></p>
                <form action="notification_delete.php" method="POST">
                    <div id="list_output">
                        <table style="width:100%;">
                            <tbody>
                                <tr>
                                    <th>id</th>
                                    <th>件名</th>
                                    <th>内容</th>
                                    <th>投稿者</th>
                                    <th>投稿日時</th>
                                </tr>
<?php foreach($info as $key => $value) :?>
                                <tr>
                                    <td><?php echo $value["id"];?></td>
                                    <td><?php echo $value["title"];?></td>
                                    <td><?php echo $value["contents"];?></td>
                                    <td><?php echo $value["name"];?></td>
                                    <td><?php echo $value["created_at"];?></td>
                                </tr>
<?php endforeach; ?>
                            </tbody>
                        </table>
                        <p>
                            <input type="submit" value="削除">
                            <input type="button" value="戻る" onclick="history.go(-1)">
                        </p>
                    </div>
<?php foreach($check as $value_a):?>
                    <input type="hidden" name="checkbox[]" value="<?php echo $value_a;?>">
<?php endforeach; ?>
                    <input type="hidden" name="csrf_token" value="<?php echo $token; ?>">
                    <input type="hidden" name="key" value="key"> 
                </form>
            </article>
        </main>
        <footer>
            <p>&copy;&nbsp;2021&nbsp;Katsuya&nbsp;Kawamoto*</p>
        </footer>
    </div>
</body>
</html>