<?php
//ログイン
require_once "./logic/login.php";
//フォーム内容確認
require_once "./logic/form_check.php";
//現在の日付取得
require_once "../logic/time_input.php";
$time=Time_input();                                             
//アップロード準備
require_once "./logic/db_access.php";
$db=new db();
if(!isset($_POST["id"])){//$_POST["id"]があるか
    //投稿
    $db->notification_insert($title,$contents,$name,$time["created_at"]);
}else{
    //更新
    $db->notification_update($_POST["id"],$title,$contents,$name,$time["created_at"]);
}

//トークン削除
unset($_SESSION['csrf_token']);
//データベース切断
$stmt=null;
$pdo=null;
//セッション確認
var_dump($_SESSION);

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
            <h1>お知らせ投稿</h1>
                    <ul id="form">
                        <li>
                            <dl class="m-bottom5px">
                                <dt>件名</dt>
                                <dd>
                                    <ul>
                                        <li><?php echo $title;?></li>
                                    </ul>
                                </dd>
<?php if(isset($output["err-title"])) :?>
                        <dd class="error"><?php echo $output["err-title"]; ?></dd>
<?php endif; ?>
                            </dl>
                        </li>
                        <li>
                            <dl class="m-bottom5px">
                                <dt>内容</dt>
                                <dd><?php echo nl2br($contents);?></dd>
<?php if(isset($output["err-contents"])) :?>
                                <dd class="error"><?php echo $output["err-contents"]; ?></dd>
<?php endif; ?>
                            </dl>
                        </li>
                        <li>
                            <dl class="m-bottom5px">
                                <dt>登録者</dt>
                                <dd><?php echo $name;?></dd>
<?php if(isset($output["err-name"])) :?>
                                <dd class="error"><?php echo $output["err-name"]; ?></dd>
<?php endif; ?>
                            </dl>
                        </li>
                        <li>
                            <dl class="m-bottom5px">
                                <dt>投稿時間</dt>
                                <dd><?php echo $time["created_at"];?></dd>
                            </dl>
                        </li>
                    </ul>
            </article>
        </main>
        <footer>
            <p>&copy;&nbsp;2021&nbsp;Katsuya&nbsp;Kawamoto*</p>
        </footer>
    </div>
</body>
</html>