<?php
//ログイン
require "../../logic/staff_login.php";
//トークン生成
require_once '../../logic/common_func.php';
//メッセージ取得用
require_once '../../logic/message_func.php';
$ms=new Message();

//件名取得
$count=5;
if(isset($_GET["page"])){
    $offset=($_GET["page"]-1)*$count;
}else{
    $offset=0;
}
$info_count=$ms->r_count($_GET["id"]);
$page=ceil((int)$info_count["COUNT(`title`)"]/$count);//ページ数取得

//件名取得
$title=$ms->title_in($_SESSION["e-id"],"0");
//詳細取得
$message=$ms->main_contents($_GET["id"]);
//詳細取得
$response=$ms->res_contents($_GET["id"],$offset,$count);


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
                <section id="notification">
                    <h1>メッセージ</h1>
                    <ul class="info" style="background-color:aliceblue;">
                        <ul>
                            <li>
                                <dl style="display:flex">
                                    <dt><b>送信者：</b></dt>
                                    <dd><?php echo staff(h($message["in_n"]));?></dd>
                                </dl>
                            </li>
                            <li>
                                <dl style="display:flex">
                                    <dt><b>宛先：</b></dt>
                                    <dd><?php echo ($message["out_n"]==0)? "管理者":staff(h($message["out_n"]));?></dd>
                                </dl>
                            </li>  
                        </ul>
                        <li>
                            <dl style="display:flex">
                                <dt><b>件名:</b></dt>
                                <dd><?php echo h($message["title"]);?></dd>
                            </dl>
                        </li>
                        <li>
                            <dl style="border:3px solid #dddddd; padding:5px;">
                                <dt><b>内容</b></dt>
                                <dd><?php echo nl2br(h($message["contents"]));?></dd>
                            </dl>
                        </li>      
                        <li>
                            <dl style="display:flex; font-size:15px;">
                                <dt><b>date：</b></dt>
                                <dd><?php echo h($message["created_at"]);?></dd>
                            </dl>
                        </li>    
                    </ul>
<?php if(isset($response)): ?>
                    <p><a style="border:2px solid #555555;border-radius:5px; box-shadow:1px 1px 1px #333333;" href="#response">▼返信をする。</a></p>
                    <hr>
<?php foreach($response as $value):?>
                        <ul class="info" style="background-color:<?php echo ($value["in_n"]==$_SESSION["e-id"])? "aliceblue":"#ffe5e9";?>">
                            <ul>
                                <li>
                                    <dl style="display:flex">
                                        <dt><b>送信者：</b></dt>
                                        <dd><?php echo staff(h($value["in_n"]));?></dd>
                                    </dl>
                                </li>
                                <li>
                                    <dl style="display:flex">
                                        <dt><b>宛先：</b></dt>
                                        <dd><?php echo ($value["out_n"]==0)? "管理者":staff(h($value["out_n"]));?></dd>
                                    </dl>
                                </li>  
                            </ul>
                            <li>
                                <dl style="display:flex">
                                    <dt><b>件名:</b></dt>
                                    <dd><?php echo h($value["title"]);?></dd>
                                </dl>
                            </li>
                            <li>
                                <dl style="border:3px solid #dddddd; padding:5px;">
                                    <dt><b>内容</b></dt>
                                    <dd><?php echo nl2br(h($value["contents"]));?></dd>
                                </dl>
                            </li>      
                            <li>
                                <dl style="display:flex; font-size:15px;">
                                    <dt><b>date：</b></dt>
                                    <dd><?php echo h($value["created_at"]);?></dd>
                                </dl>
                            </li>    
                        </ul>
<?php endforeach;?>
                        <ul id="page" style="margin-top:10px;">
                                                <li>Page:</li>
<?php for($i=1;$i<=$page;$i++): ?>
                                                <li><a href="admin_message.php?<?php if(isset($_GET["id"]))echo "id=".$_GET["id"];?>&page=<?php echo h($i); ?>"><?php echo h($i); ?></a></li>
<?php endfor;?>
                                            </ul>
<?php endif; ?>
                    <hr id="response">
                    <h2>返信</h2>
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
                                        <textarea name="contents" id="contents" cols="50" rows="10"　style="border:1px solid black; margin-top:5px" required><?php if(isset($result['contents'])) echo h($result['contents']) ;?></textarea>
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
                                    <dd><?php echo h($_SESSION["header-sei"]) ;?></dd>
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

<?php if(isset($_GET["id"])):?>
                        <input type="hidden" name="id" id="id" value="<?php echo h($_GET["id"]);?>">
<?php endif; ?>

                    </form>
                    <hr>
                    <h2>受信一覧</h2>
                    <p>クリックすると詳細が見れます。</p>
                    <ul id="n-title">
<?php if(!isset($title)):?>
                        <li style="padding:5px">現在、新しい情報はありません。</li>
<?php else: ?>  
<?php foreach($title as $key => $value) :?>
                        <li>
                            <ul style="display:flex;">
                                <li style="padding:5px;"><b><?php echo ($value["admin"]=="1")? "送信":"受信";?></b></li>
                                <li style="padding:5px;">
                                    <ul style="text-align:center;">
                                        <li>送信者</li>
                                        <li><b><?php echo staff(h($value["in_n"]));?></b></li>
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