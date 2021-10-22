<?php
    //ログイン
    require_once "./logic/login.php";

    //編集or登録なのか確認
    $flag=false;
    if(isset($_POST["edit"]) && $_POST["edit"]=="true"){
        $flag=true;
        $title="変更";
    }else{
        $title="登録";
    }

    //エラーメッセージなどセッションに格納するもの
    $_SESSION['csrf_token']=$output['csrf_token'];
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
        header('Location: member_register.php');
        return;
    }
    
    //アップロード準備
    require_once "./logic/db_access.php";
    $db=new db();
    if($flag){
        //更新
        $db->menber_info_update($number,$sei,$mei);
    }else{
        //アップロード
        $db->member_insert($number,$sei,$mei);
    }
    //トークン削除
    unset($_SESSION['csrf_token']);
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
            <article id="form">
                    <h1><?php echo $title;?>完了</h1>
                    <p>以下の内容で<?php echo $title;?>しました。</p>
                    <ul id="form">
                        <li>
                            <dl class="m-bottom5px">
                                <dt>社員No.</dt>
                                <dd>
                                    <ul>
                                        <li><?php echo $number; ?></li>
                                    </ul>
                                </dt>
                            </dl>
                        </li>
                        <li>
                            <dl class="m-bottom5px">
                                <dt>名前</dt>
                                <dd><?php echo $sei." ".$mei; ?>さん</dt>
                            </dl>
                        </li>
<?php if(!$flag): ?>
                        <li>
                            <dl class="m-bottom5px">
                                <dt>初期パスワード</dt>
                                <dd>社員ナンバー</dd>
                            </dl>
                        </li>
<?php endif ;?>
                    </ul>
            </article>
        </main>
        <footer>
            <p>&copy;&nbsp;2021&nbsp;Katsuya&nbsp;Kawamoto*</p>
        </footer>
    </div>
</body>
</html>