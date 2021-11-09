<?php
//ログイン
require "../../logic/staff_login.php";
//トークン生成
require_once '../../logic/common_func.php';
//メッセージ取得用
require_once '../../logic/message_func.php';
$message=new Message();
//件名取得
$count=5;
if(isset($_GET["page"])){
    $offset=($_GET["page"]-1)*$count;
}else{
    $offset=0;
}
$title=$message->title_in($_SESSION["e-id"],"0",$offset,$count);
$info_count=$message->m_count($_SESSION["e-id"],"0");
$page=ceil((int)$info_count["COUNT(`title`)"]/$count);//ページ数取得

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
                <p><b><a style="border:1px solid #333333; border-radius:5px; background-color:papayawhip;" href="admin_form.php">メッセージ送信</a></b></p>
                <hr>
                <section id="notification">
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
                    <ul id="page" style="margin-top:10px;">
                        <li>Page:</li>
<?php for($i=1;$i<=$page;$i++): ?>
                        <li><a href="admin_form_top.php?page=<?php echo h($i); ?>"><?php echo h($i); ?></a></li>
<?php endfor;?>
                    </ul>
                </section>
            </article>
        </main>
        <footer>
            <p><small>&copy;&nbsp;2021&nbsp;Katsuya&nbsp;Kawamoto*</small></p>
        </footer>
    </div>
</body>
</html>