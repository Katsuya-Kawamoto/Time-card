<?php
//ログイン
require_once "./logic/login.php";
//メンバー情報の取得
require_once "./logic/db_access.php";
$db=new db();
$result=$db->member_list();

foreach ($result as $row) {
    // データベースのフィールド名で出力
    $info[]=$row;
}
var_dump($_SESSION);
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
                    <li>
                        <a href="../logic/logout.php">ログアウト</a>
                    </li>
                </ul>
            </aside>
            <article>
                <h1>出力結果</h1>
                <form action="member_list.php" method="GET">
                    <ul id="form">
                        <li>
                            <dl class="m-bottom5px">
                                <dt>社員No.</dt>
                                <dd>
                                    <ul>
                                        <li><input type="text" name="number" id="number" value="<?php if(isset($_GET["number"])) echo $_GET["number"]; ?>"></li>
                                        <li>*半角英数6文字</li>
                                    </ul>
                                </dd>
<?php if(isset($output["err-number"])) :?>
                                <dd class="error"><?php echo $output["err-number"]; ?></dd>
<?php endif; ?>
                            </dl>
                        </li>
                        <li>
                            <input type="submit" value="検索">
                        </li>
                    </ul>
                </form>
                <hr>
<?php if(!isset($output)):?>
                <h2>出力エラー</h2>
                <p>出力出来る内容が見つかりませんでした。</p>
<?php else: ?>
                <form action="member_delete_verification.php" method="POST">
                <table style="width:100%;">
                    <tbody>
                        <tr>
                            <th>社員番号</th>
                            <th>姓</th>
                            <th>名</th>
                            <th>編集</th>
                            <th>削除</th>
                        </tr>
<?php foreach($info as $key => $value) :?>
                        <tr>
                            <td><?php echo $value["number"];?></td>
                            <td><?php echo $value["sei"];?></td>
                            <td><?php echo $value["mei"];?></td>
                            <td><a href="./member_register.php?id=<?php echo $value["number"];?>">編集</a></td>
                            <td class="delete"><input type="checkbox" name="checkbox[]" value="<?php echo $value["number"];?>"></td>
                        </tr>
<?php endforeach; ?>
                    </tbody>
                </table>
                <p style="text-align: right; margin-top:10px;">チェックした項目をまとめて削除<input type="submit" value="削除"></p>
                </form>
<?php endif; ?>
            </article>
        </main>
        <footer>
            <p>&copy;&nbsp;2021&nbsp;Katsuya&nbsp;Kawamoto*</p>
        </footer>
    </div>
</body>
</html>