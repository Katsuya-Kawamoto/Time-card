<?php
//セッションスタート
require_once "./php/logic/session.php";
$session=new session();
$session->start();//セッションスタート
$output=$_SESSION;//変数に一旦、セッション情報を代入
$session->destroy();//セッション情報の削除

//トークン生成
$session->start();//セッションスタート
require_once './php/logic/common_func.php';
//var_dump($_SESSION);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>勤怠管理システム</title>
    <link rel="stylesheet" href="css/reset.css"><!--リセットCSS-->
    <link rel="stylesheet" href="css/style.css"><!--メイン用CSS-->
</head>
<body>
    <div id="wrapper">
        <header>
            <h1>勤怠管理-管理者用</h1>
            <nav>
                <ul id="menu">
                    <li>TOP</li>
                    <li><a href="index.php">スタッフ用</a></li>
                </ul>
            </nav>
<?php if(isset($output["log_out"])) :?>
                        <dd class="error"><?php echo $output["log_out"]; ?></dd>
<?php endif; ?>
        </header>
        <main>
            <form action="./php/admin/admin_top.php" method="POST">
            <ul>
                <li>
                    <dl class="m-bottom5px">
                        <dt><label>管理者ID</label></dt>
                        <dd><input type="text" name="id" id="id" required></dd>
<?php if(isset($output["err-id"])) :?>
                        <dd class="error"><?php echo $output["err-id"]; ?></dd>
<?php endif; ?>
                    </dl>
                </li>
                <li>
                    <dl class="m-bottom5px">
                        <dt><label>社員No.</label></dt>
                        <dd><input type="text" name="number" id="number" required></dd>
<?php if(isset($output["err-number"])) :?>
                        <dd class="error"><?php echo $output["err-number"]; ?></dd>
<?php endif; ?>
                    </dl>
                </li>
                <li>
                    <dl class="m-bottom5px">
                        <dt><label>パスワード</label></dt>
                        <dd><input type="password" name="pass" id="pass" required></dd>
<?php if(isset($output["err-pass"])) :?>
                        <dd class="error"><?php echo $output["err-pass"]; ?></dd>
<?php endif; ?>
                    </dl>
                </li>
                <li>
<?php if(isset($output["err-login"])) :?>
                        <p class="error"><?php echo $output["err-login"]; ?></p>
<?php endif; ?>
                    <input type="hidden" name="csrf_token" value="<?php echo h(setToken()); ?>">
                    <button type="submit">ログイン</button>
                </li>
            </ul>
            </form>
        </main>
        <footer>
            <p><small>&copy;&nbsp;2021&nbsp;Katsuya&nbsp;Kawamoto*</small></p>
        </footer>
    </div>
</body>
</html>