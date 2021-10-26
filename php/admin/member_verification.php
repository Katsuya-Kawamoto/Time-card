<?php
    //ログイン
    require_once "./logic/login.php";
    //form内容確認
    require_once "./logic/mb_formcheck.php";
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
            <article id="form">
                <form action="member_submit.php" method="POST">
                    <h1><?php echo $title;?>確認</h1>
                    <ul id="form">
                        <li>
                            <dl class="m-bottom5px">
                                <dt>社員No.</dt>
                                <dd>
                                    <ul>
                                        <li><?php echo $input["number"]; ?></li>
                                    </ul>
                                </dd>
                            </dl>
                        </li>
                        <li>
                            <dl class="m-bottom5px">
                                <dt>名前</dt>
                                <dd><?php echo "性:".$input["sei"];?></dd>
                                <dd><?php echo "名:".$input["mei"];?></dd>
                            </dl>
                        </li>
                        <li>
                            <p>以上の内容で登録します。</p>
                            <input type="submit" value="登録">
                            <input type="button" value="戻る" onclick="history.go(-1)">
                        </li>
                    </ul>
                    <input type="hidden" name="number" value="<?php echo $input["number"]; ?>">
                    <input type="hidden" name="sei" value="<?php echo $input["sei"]; ?>">
                    <input type="hidden" name="mei" value="<?php echo $input["mei"]; ?>">
                    <input type="hidden" name="pass" value="<?php echo $input["pass"]; ?>">
                    <input type="hidden" name="pass_conf" value="<?php echo $input["pass_conf"]; ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo $token; ?>">
<?php if($flag):?>
                    <input type="hidden" name="edit" value="true">
<?php endif ?>
                </form>
            </article>
        </main>
        <footer>
            <p>&copy;&nbsp;2021&nbsp;Katsuya&nbsp;Kawamoto*</p>
        </footer>
    </div>
</body>
</html>