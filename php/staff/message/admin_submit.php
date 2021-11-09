<?php
//ログイン
require "../../logic/staff_login.php";
//メッセージ取得用
require_once '../../logic/message_func.php';
//トークン生成
require_once '../../logic/common_func.php';

//前ページのURLを取得
if(isset($output['HTTP'])){
    $_SESSION["HTTP"]=$output['HTTP'];                                     //path取得
    //フォーム内容確認
    $path=parse_url($_SESSION["HTTP"]);
    $in_id  = filter_input(INPUT_POST, 'in_id');
    $out_id = filter_input(INPUT_POST, 'out_id'); 
    $admin  = filter_input(INPUT_POST, 'admin');

    require_once "./logic/nc_form_check.php";                               //フォームチェック
    if(isset($path["path"]) &&                                              //前アクセスがメッセージの場合
    $path["path"]=="/timecard/php/staff/message/admin_form.php"||
    $path["path"]=="/timecard/php/staff/message/admin_message.php"){
        if(!isset($out_id) || strlen($out_id)<=0){                                        //送信先が指定されているか
            $_SESSION["err-form"]="宛先が選択されてません。";
        }
        if(!isset($in_id) || strlen($in_id)<=0){
            $_SESSION["err-form"]="送信元の情報がありません";
        }
        if(!isset($admin) || strlen($admin)<=0){
            $_SESSION["err-form"]="送信先が選択されてません。";
        }
    }else{
        $_SESSION["err-form"]="再度情報を入力してください。";
    }
}else{
    $_SESSION["err-form"]="再度情報を入力してください。";
}

if(isset($_SESSION["err-form"])){
    header('Location: admin_form.php');
    return;
}

//現在の日付取得
require_once "../../logic/time_input.php";
$time=Time_input(); 
require_once '../../logic/message_func.php';
$message=new Message();

if(!isset($_POST["id"])){
    $message->message_insert($input["title"],$input["contents"],$in_id,$out_id,$time["created_at"],$admin);
}else{
    $message->response_insert($_POST["id"],$input["title"],$input["contents"],$in_id,$out_id,$time["created_at"],$admin);
}
//メッセージ一覧取得
$result=$message->title_in($_SESSION["e-id"],"0");

//トークン削除
unset($_SESSION['csrf_token']);
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
                <h1>お知らせ投稿</h1>
                <p>以下の内容で投稿しました。</p>
                    <ul id="form">
                        <li>
                            <dl class="m-bottom5px">
                                <dt>宛先</dt>
                                <dd><?php echo ($out_id=="0")? "管理者":h($out_id);?></dd>
<?php if(isset($output["err-name"])) :?>
                                <dd class="error"><?php echo h($output["err-name"]); ?></dd>
<?php endif; ?>
                            </dl>
                        </li>
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
                                <dt>送信者</dt>
                                <dd><?php echo h(staff($in_id));?></dd>
<?php if(isset($output["err-name"])) :?>
                                <dd class="error"><?php echo h($output["err-name"]); ?></dd>
<?php endif; ?>
                            </dl>
                        </li>
                        <li>
                            <dl class="m-bottom5px">
                                <dt>投稿時間</dt>
                                <dd><?php echo h($time["created_at"]);?></dd>
                            </dl>
                        </li>
                    </ul>
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