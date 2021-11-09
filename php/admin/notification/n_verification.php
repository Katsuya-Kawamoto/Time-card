<?php
//ログイン
require_once "../../logic/admin_login.php";
//htmlspecialchars関数
require_once '../../logic/common_func.php';

//前ページのURLを取得
if(!$_SESSION["HTTP"]=$_SERVER["HTTP_REFERER"]){
    $_SESSION["err-form"]="再度、フォームに情報を入力してください。";
    header('Location: ./n_form.php');
    return;
}else{
    //フォーム内容確認
    require_once "./logic/nc_form_check.php";
}
//データベース切断
$stmt=null;
$pdo=null;
//セッション確認
//var_dump($_SESSION);
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
                        <li><a href="../member_register.php">従業員登録</a></li>
                        <li><a href="../member_list.php">従業員編集</a></li>
                    </ul>
                    <li>勤怠管理</li>
                    <ul>
                        <li><a href="../attendance/a_form.php">勤怠登録</a></li>
                        <li><a href="../attendance/mb_ad_list.php">勤務状況一覧</a></li>
                    </ul>
                    <li>お知らせ管理</li>
                    <ul>
                        <li><a href="./n_form.php">投稿</a></li>
                        <li><a href="./n_list.php">編集</a></li>
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
            <article>
            <h1>お知らせ投稿</h1>
            <p>以下の内容で登録します。</p>
                <form action="n_submit.php<?php if(isset($_GET["id"])) echo "?id=".h($_GET["id"]); ?>" method="POST">
                    <ul id="form">
                        <li>
                            <dl class="m-bottom5px">
                                <dt>件名</dt>
                                <dd>
                                    <ul>
                                        <li><?php echo h($input["title"]);?></li>
                                    </ul>
                                </dd>
<?php if(isset($output["err-title"])) :?>
                        <dd class="error"><?php echo h($output["err-title"]); ?></dd>
<?php endif; ?>
                            </dl>
                        </li>
                        <li>
                            <dl class="m-bottom5px">
                                <dt>内容</dt>
                                <dd><?php echo nl2br(h($input["contents"]));?></dd>
<?php if(isset($output["err-contents"])) :?>
                                <dd class="error"><?php echo h($output["err-contents"]); ?></dd>
<?php endif; ?>
                            </dl>
                        </li>
                        <li>
                            <dl class="m-bottom5px">
                                <dt>登録者</dt>
                                <dd><?php echo h($input["name"]);?></dd>
<?php if(isset($output["err-name"])) :?>
                                <dd class="error"><?php echo h($output["err-name"]); ?></dd>
<?php endif; ?>
                            </dl>
                        </li>
                        <li>
                            <input type="submit" value="確認">
                            <input type="button" value="戻る" onclick="history.go(-1)">
                        </li>
                    </ul>
                    <input type="hidden" name="title" value="<?php echo h($input["title"]);?>">
                    <input type="hidden" name="contents" value="<?php echo h($input["contents"]);?>">
                    <input type="hidden" name="name" value="<?php echo h($input["name"]);?>">
                    <input type="hidden" name="csrf_token" value="<?php echo h($_POST["csrf_token"]); ?>">
<?php if(isset($_POST["id"])):?>
                    <input type="hidden" name="id" id="id" value="<?php echo h($_POST["id"]);?>">
<?php endif; ?>
                </form>
            </article>
        </main>
        <footer>
            <p><small>&copy;&nbsp;2021&nbsp;Katsuya&nbsp;Kawamoto*</small></p>
        </footer>
    </div>
</body>
</html>