<?php
//ログイン
require "../../logic/staff_login.php";
//メッセージ取得用
require_once '../../logic/message_func.php';
//前ページのURLを取得
if(!$_SESSION["HTTP"]=$_SERVER["HTTP_REFERER"]){
    $_SESSION["err-form"]="アクセスエラー：再度、フォームに情報を入力してください。";
}else{
    $path=parse_url($_SESSION["HTTP"]);
    $in_id  = filter_input(INPUT_POST, 'in_id'); 
    $admin  = filter_input(INPUT_POST, 'admin');
    $out_id = filter_input(INPUT_POST, 'out_id');

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
}

if(isset($_SESSION["err-form"])){
    header('Location: admin_form.php');
    return;
}

//データベース切断
$stmt=null;
$pdo=null;
//セッション確認
//var_dump($out_id);

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
                <form action="admin_submit.php<?php if(isset($_GET["id"])) echo "?id=".$_GET["id"]; ?>" method="POST">
                    <ul id="form">
                        <li>
                            <dl class="m-bottom5px">
                                <dt>宛先</dt>
                                <dd><?php echo ($out_id=="0")? "管理者":h(staff($out_id)); ?></dd>
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
                    <input type="hidden" name="csrf_token" value="<?php echo $_POST["csrf_token"]; ?>">
                    <input type="hidden" name="in_id" value="<?php echo h($in_id); ?>">
                    <input type="hidden" name="out_id" value="<?php echo h($out_id); ?>">
                    <input type="hidden" name="admin" value="<?php echo h($admin);?>">
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