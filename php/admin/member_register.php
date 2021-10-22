<?php
//ログイン
require_once "./logic/login.php";
//社員情報の取得
require_once "./logic/db_access.php";
$member=new db();

//トークン生成
require_once './logic/functions.php';
//編集・投稿判定
$select_path="/timecard/php/admin/member_list.php";//リストページ
$flag=edit_flag($select_path);
if($flag){//編集の条件に合ったらデータ取得
    $result=$member->member_info_input();
    $title="編集";
}else{
    $title="登録";
    $max_no=(int)$member->member_no_max()+1;//社員番号の最大値
}

$stmt=null;
$pdo=null;

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
                    <li>
                        <a href="../logic/logout.php">ログアウト</a>
                    </li>
                </ul>
            </aside>
            <article>
            <h1>従業員情報<?php echo $title ;?></h1>
                <form action="member_verification.php<?php if(isset($_GET['id'])) echo '?id='.$_GET['id'];?>" method="POST">
                    <ul id="form">
                        <li>
                            <dl class="m-bottom5px">
                                <dt>社員No.</dt>
                                <dd>
                                    <ul>
<?php if($flag):?>
                                        <li><?php echo $result["number"];?></li>
<?php else: ?>
                                        <li><input type="text" name="number" id="number" value="<?php echo $max_no ;?>"></li>
<?php endif; ?>                          
                                    </ul>
                                </dd>
<?php if(isset($output["err-number"])) :?>
                        <dd class="error"><?php echo $output["err-number"]; ?></dd>
<?php endif; ?>
                            </dl>
                        </li>
                        <li>
                            <dl class="m-bottom5px">
                                <dt>名前</dt>
                                <dd>性:<input type="text" name="sei" id="sei" value="<?php if($flag)echo $result["sei"];?>" required></dt>
                                <dd>名:<input type="text" name="mei" id="mei" value="<?php if($flag)echo $result["mei"];?>" required></dd>
<?php if(isset($output["err-sei"])) :?>
                                <dd class="error"><?php echo $output["err-sei"]; ?></dd>
<?php endif; ?>
<?php if(isset($output["err-mei"])) :?>
                                <dd class="error"><?php echo $output["err-mei"]; ?></dd>
<?php endif; ?>
                            </dl>
                        </li>
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
                        <li>
<?php if(isset($output["err-form"])) :?>
                                <dd class="error"><?php echo $output["err-form"]; ?></dd>
<?php endif; ?>
                            <input type="submit" value="変更">
                        </li>
                    </ul>
                    <input type="hidden" name="csrf_token" value="<?php echo h(setToken()); ?>">
<?php if($flag):?>
                    <input type="hidden" name="number" value="<?php echo $result["number"];?>">
                    <input type="hidden" name="edit" value="true">
<?php endif;?>
                </form>
            </article>
        </main>
        <footer>
            <p>&copy;&nbsp;2021&nbsp;Katsuya&nbsp;Kawamoto*</p>
        </footer>
    </div>
</body>
</html>