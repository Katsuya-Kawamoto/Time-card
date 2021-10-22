<?php
//ログイン
require_once "./logic/login.php";
//トークン生成
require_once '../logic/common_func.php';
//編集・投稿判定
$select_path="/timecard/php/admin/notification_list.php";//リストページ
$flag=edit_flag($select_path);
if($flag){//編集の条件に合ったらデータ取得
    require_once "../logic/common_func.php";
    $result=info_input($_GET["id"]);
    $title="編集";
}else{
    $title="投稿";
}

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
            <h1>お知らせ<?php echo $title; ?></h1>
                <form action="notification_verification.php<?php if(isset($_GET["id"])) echo "?id=".$_GET["id"]; ?>" method="POST">
                    <ul id="form">
                        <li>
                            <dl class="m-bottom5px">
                                <dt>件名</dt>
                                <dd>
                                    <ul>
                                        <li><input type="text" name="title" id="title" <?php if(isset($result['title'])) echo 'value="'.$result['title'].'"' ;?> required></li>
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
                                <dd>
                                    <textarea name="contents" id="contents" cols="50" rows="10"　style="border:1px solid black; margin-top:5px" required><?php if(isset($result['contents'])) echo $result['contents'] ;?></textarea>
                                </dd>
<?php if(isset($output["err-contents"])) :?>
                                <dd class="error"><?php echo $output["err-contents"]; ?></dd>
<?php endif; ?>
                            </dl>
                        </li>
                        <li>
                            <dl class="m-bottom5px">
                                <dt>登録者</dt>
<?php if(isset($result['name'])):?>
                                <dd><?php echo $result["name"] ;?></dd>
<?php else: ?>
                                <dd><?php echo $_SESSION["header-sei"] ;?></dd>
<?php endif;?>
<?php if(isset($output["err-name"])) :?>
                                <dd class="error"><?php echo $output["err-name"]; ?></dd>
<?php endif; ?>
                            </dl>
                        </li>

<!--パスワード入力一旦保留                        
                        <li>
                            <dl class="m-bottom5px">
                                <dt>登録用パスワード</dt>
                                <dd><input type="password" name="pass" id="pass" required></dd>
<?php if(isset($output["err-pass"])) :?>
                                <dd class="error"><?php echo $output["err-pass"]; ?></dd>
<?php endif; ?>
                            </dl>
                        </li>
                        <li>
                            <dl class="m-bottom5px">
                                <dt>登録用パスワード(確認)</dt>
                                <dd><input type="password" name="pass_conf" id="pass_conf" required></dd>
<?php if(isset($output["err-pass_conf"])) :?>
                                <dd class="error"><?php echo $output["err-pass_conf"]; ?></dd>
<?php endif; ?>
                            </dl>
                        </li>
-->

                        <li>
<?php if(isset($output["err-form"])) :?>
                                <p class="error"><?php echo $output["err-form"]; ?></p>
<?php endif; ?>
                            <input type="submit" value="確認">
                        </li>
                    </ul>
                    <input type="hidden" name="csrf_token" value="<?php echo h(setToken()); ?>">
<?php if(isset($result['name'])):?>
                    <input type="hidden" name="name" id="name" value="<?php echo $result["name"];?>" required>
<?php else: ?>
                    <input type="hidden" name="name" id="name" value="<?php echo $_SESSION["header-sei"];?>" required>
<?php endif; ?>
<?php if($flag):?>
                    <input type="hidden" name="id" id="id" value="<?php echo $_GET["id"];?>">
<?php endif; ?>

                </form>
            </article>
        </main>
        <footer>
            <p>&copy;&nbsp;2021&nbsp;Katsuya&nbsp;Kawamoto*</p>
        </footer>
    </div>
</body>
</html>