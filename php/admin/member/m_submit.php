<?php
    //ログイン
    require_once "../../logic/admin_login.php";
    //form内容確認
    require_once "./logic/mb_formcheck.php";
    
    //アップロード準備
    require_once "../logic/db_access.php";
    $db=new db();
    if($flag){
        //更新
        $db->menber_info_update($input["number"],$input["sei"],$input["mei"],$input["admin"]);
    }else{
        //アップロード
        $db->member_insert($input["number"],$input["sei"],$input["mei"],$input["admin"]);
    }
    //トークン削除
    unset($_SESSION['csrf_token']);
    //データベース切断
    $stmt=null;
    $pdo=null;
    //管理者権限
    $admin_arr=array("無し","有り");
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../css/reset.css"><!--リセットCSS-->
    <link rel="stylesheet" href="../../css/style2.css"><!--メイン用CSS-->
    <title>管理者・管理画面</title>
</head>
<body>
    <div id="wrapper">
        <header>
            <h1><a href="../admin_top.php">管理者・管理画面</a></h1>
            <div><?php echo h($_SESSION["header-sei"]);?>さん、お疲れ様です。</div>
        </header>
        <main>
            <aside>
                <label class="title" for="box1" style="background-color:#333333; color:white;" >MENU</label>
                <input type="checkbox" id="box1" style="display:none;">
                <ul id="menu" class="toggle">
                    <li>スタッフ管理</li>
                    <ul>
                        <li><a href="./m_form.php">従業員登録</a></li>
                        <li><a href="./m_list.php">従業員編集</a></li>
                    </ul>
                    <li>勤怠管理</li>
                    <ul>
                        <li><a href="../attendance/a_form.php">勤怠登録</a></li>
                        <li><a href="../attendance/mb_ad_list.php">勤務状況一覧</a></li>
                    </ul>
                    <li>お知らせ管理</li>
                    <ul>
                        <li><a href="../notification/n_form.php">投稿</a></li>
                        <li><a href="../notification/n_list.php">編集</a></li>
                    </ul>
                    <li>メッセージ送信</li>
                    <ul>
                        <li><a href="../message/staff_form.php">投稿</a></li>
                        <li><a href="../message/staff_form_top.php">一覧</a></li>
                    </ul>
                    <li>CSV出力</li>
                    <ul>
                        <li><a href="../csv/attendance_select.php">全従業員出力</a></li>
                        <li><a href="../csv/attendance_member_list.php">個別出力</a></li>
                    </ul>
                    <li>その他</li>
                    <ul>
                        <li>
                            <a href="../../logic/logout.php">ログアウト</a>
                        </li>
                    </ul>
                </ul>
            </aside>
            <article id="form">
                    <h1><?php echo h($title);?>完了</h1>
                    <p>以下の内容で<?php echo h($title);?>しました。</p>
                    <ul id="form">
                        <li>
                            <dl class="m-bottom5px">
                                <dt>社員No.</dt>
                                <dd>
                                    <ul>
                                        <li><?php echo h($input["number"]); ?></li>
                                    </ul>
                                </dt>
                            </dl>
                        </li>
                        <li>
                            <dl class="m-bottom5px">
                                <dt>名前</dt>
                                <dd><?php echo h($input["sei"])." ".h($input["mei"]); ?>さん</dt>
                            </dl>
                        </li>
                        <li>
                            <dl class="m-bottom5px">
                                <dt>管理者権限</dt>
                                <dd><?php echo $admin_arr[h($input["admin"])];?></dd>
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
            <p><small>&copy;&nbsp;2021&nbsp;Katsuya&nbsp;Kawamoto*</small></p>
        </footer>
    </div>
</body>
</html>