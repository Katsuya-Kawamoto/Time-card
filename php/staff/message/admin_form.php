<?php
//ログイン
require "../../logic/staff_login.php";
//トークン生成
require_once '../../logic/common_func.php';
//メッセージ取得用
require_once '../../logic/message_func.php';
$message=new Message();
//メッセージ一覧取得
$result=$message->title_in($_SESSION["e-id"],"0");

//セッション確認
//var_dump($_SESSION);
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
    <title>従業員・管理画面</title>
</head>
<body>
    <div id="wrapper">
        <header>
            <h1><a href="../staff_top.php">従業員・管理画面</a></h1>
            <div><?php echo h($_SESSION["header-sei"]);?>さん、お疲れ様です。</div>
        </header>
        <main>
            <aside>
                <label class="title" for="box1" style="background-color:#333333; color:white;" >MENU</label>
                <input type="checkbox" id="box1" style="display:none;">
                <ul id="menu" class="toggle">
                    <li>勤怠管理</li>
                    <ul>
                        <li><a href="../attendance/a_form.php">登録</a></li>
                        <li><a href="../attendance/a_list.php">編集</a></li>
                    </ul>
                    <li>パスワード管理</li>
                    <ul>
                        <li><a href="../pass/pass_reset.php">変更</a></li>
                    </ul>
                    <li>メッセージ送信</li>
                    <ul>
                        <li><a href="../message/admin_form_top.php">一覧</a></li>
                        <li><a href="../message/admin_form.php">送信</a></li>
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
                <h1>管理者・メッセージ送信</h1>
                <form action="admin_verification.php" method="POST">
                    <ul id="form">
                        <li>
                            <dl class="m-bottom5px">
                                <dt>件名</dt>
                                <dd>
                                    <ul>
                                        <li><input type="text" name="title" id="title" <?php if(isset($result['title'])) echo 'value="'.h($result['title']).'"' ;?> required></li>
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
                                <dd>
                                    <textarea name="contents" id="contents" cols="50" rows="10"　style="border:1px solid black; margin-top:5px" required><?php if(isset($result['contents'])) echo H($result['contents']) ;?></textarea>
                                </dd>
<?php if(isset($output["err-contents"])) :?>
                                <dd class="error"><?php echo h($output["err-contents"]); ?></dd>
<?php endif; ?>
                            </dl>
                        </li>
                        <li>
                            <dl class="m-bottom5px">
                                <dt>送信者</dt>
<?php if(isset($result['name'])):?>
                                <dd><?php echo h($result["name"]) ;?></dd>
<?php else: ?>
                                <dd>管理者</dd>
<?php endif;?>
<?php if(isset($output["err-name"])) :?>
                                <dd class="error"><?php echo h($output["err-name"]); ?></dd>
<?php endif; ?>
                            </dl>
                        </li>
<!--パスワード入力一旦保留                        
                        <li>
                            <dl class="m-bottom5px">
                                <dt>登録用パスワード</dt>
                                <dd><input type="password" name="pass" id="pass" required></dd>
<?php if(isset($output["err-pass"])) :?>
                                <dd class="error"><?php echo h($output["err-pass"]); ?></dd>
<?php endif; ?>
                            </dl>
                        </li>
                        <li>
                            <dl class="m-bottom5px">
                                <dt>登録用パスワード(確認)</dt>
                                <dd><input type="password" name="pass_conf" id="pass_conf" required></dd>
<?php if(isset($output["err-pass_conf"])) :?>
                                <dd class="error"><?php echo h($output["err-pass_conf"]); ?></dd>
<?php endif; ?>
                            </dl>
                        </li>
-->
                        <li>
<?php if(isset($output["err-form"])) :?>
                                <p class="error"><?php echo h($output["err-form"]); ?></p>
<?php endif; ?>
                            <input type="submit" value="確認">
                        </li>
                    </ul>
                    <input type="hidden" name="csrf_token" value="<?php echo h(setToken()); ?>">
                    <input type="hidden" name="in_id" value="<?php echo h($_SESSION["e-id"]); ?>">
                    <input type="hidden" name="out_id" value="0">
                    <input type="hidden" name="admin" value="1">
<?php if(isset($result['name'])):?>
                    <input type="hidden" name="name" id="name" value="<?php echo h($result["name"]);?>" required>
<?php else: ?>
                    <input type="hidden" name="name" id="name" value="<?php echo h($_SESSION["header-sei"]);?>" required>
<?php endif; ?>
<!--
<?php if($flag):?>
                    <input type="hidden" name="id" id="id" value="<?php echo h($_GET["id"]);?>">
<?php endif; ?>
-->
                </form>
                <hr>
                <section id="notification">
                    <h2>受信一覧</h2>
                    <p>クリックすると詳細が見れます。</p>
                    <ul id="n-title">
<?php if(!isset($result)):?>
                        <li style="padding:5px">現在、新しい情報はありません。</li>
<?php else: ?>  
<?php foreach($result as $key => $value) :?>
                        <li>
                            <ul style="display:flex;">
                                <li style="padding:5px;"><b><?php echo ($value["admin"]=="1")? "送信":"受信";?></b></li>
                                <li style="padding:5px;">
                                    <ul style="text-align:center;">
                                        <li>送信者</li>
                                        <li><b><?php echo h(staff($value["in_n"]));?></b></li>
                                    </ul>
                                </li>
                                <li style="flex:1;">                        
                                    <a href="./admin_message.php?id=<?php echo h($value["id"]);?>" style="display: flex; height:100%; align-items: center;">
                                        <?php echo h($value["title"]);?>
                                        <span id="day">|<?php echo h($value["created_at"]);?></span>
                                    </a>
                                </li>
                            </ul>
                        </li>
<?php endforeach; ?>
                    </ul>
<?php endif; ?>
                </section>
            </article>
        </main>
        <footer>
            <p><small>&copy;&nbsp;2021&nbsp;Katsuya&nbsp;Kawamoto*</small></p>
        </footer>
    </div>
</body>
</html>