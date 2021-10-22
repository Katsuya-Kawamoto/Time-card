<?php
    //ログイン
    require_once "./logic/login.php";

    //編集or登録なのか確認
    $flag=false;
    if(isset($_POST["edit"]) && $_POST["edit"]=="true"){
        $flag=true;
        $title="確認";
    }else{
        $title="登録";
    }
    
    //エラーメッセージなどセッションに格納するもの
    if(isset($output['csrf_token'])){
    $_SESSION['csrf_token']=$output['csrf_token'];
    }
    $output=[];
    

    //フォーム入力内容確認
    //①直接アクセスでは無く、POSTの値があること。
    //*不正アクセスはフォームへreturn
    //②フォームに空白の情報が無い事
    //③正規表現チェック
    if($_SERVER["REQUEST_METHOD"]==="POST"){
        //変数へ代入（空欄が無いか確認）
        require_once "./logic/post_input.php";
        //入力内容のチェック
        require_once "./logic/register.php";
    }else{
        //どちらにも当てはまらない場合はログインに戻る。
        $_SESSION["err-form"]="再度、フォームに情報を入力してください。";
        $address="member_register.php";
        if(isset($_GET["id"])){
            $address.="?id=".$_GET["id"];
        }
        header('Location: '.$address);
        return;
    }

    $stmt=null;
    $pdo=null;
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
            <article id="form">
                <form action="member_submit.php" method="POST">
                    <h1><?php echo $title;?>確認</h1>
                    <ul id="form">
                        <li>
                            <dl class="m-bottom5px">
                                <dt>社員No.</dt>
                                <dd>
                                    <ul>
                                        <li><?php echo $number; ?></li>
                                    </ul>
                                </dd>
                            </dl>
                        </li>
                        <li>
                            <dl class="m-bottom5px">
                                <dt>名前</dt>
                                <dd><?php echo "性:".$sei;?></dd>
                                <dd><?php echo "名:".$mei;?></dd>
                            </dl>
                        </li>
                        <li>
                            <p>以上の内容で登録します。</p>
                            <input type="submit" value="登録">
                            <input type="button" value="戻る" onclick="history.go(-1)">
                        </li>
                    </ul>
                    <input type="hidden" name="number" value="<?php echo $number; ?>">
                    <input type="hidden" name="sei" value="<?php echo $sei; ?>">
                    <input type="hidden" name="mei" value="<?php echo $mei; ?>">
                    <input type="hidden" name="pass" value="<?php echo $pass; ?>">
                    <input type="hidden" name="pass_conf" value="<?php echo $pass_conf; ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo $token; ?>">
<?php if($flag):?>
                    <input type="hidden" name="edit" value="true">
<?php endif ?>
                </form>
            </article>
        </main>
        <footer>
            <p>&copy;&nbsp;2021&nbsp;Katsuya&nbsp;Kawamoto*</p>
        </footer>
    </div>
</body>
</html>