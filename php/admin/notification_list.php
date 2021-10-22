<?php
    //ログイン
    require_once "./logic/login.php";

    //リスト表示
    $count=20;
    if(isset($_GET["page"])){
        $offset=($_GET["page"]-1)*$count;
    }else{
        $offset=0;
    }

    //お知らせ情報取得
    require_once "./logic/db_access.php";
    $db=new db();
    $result=$db->info_title($offset,$count);
    foreach ($result as $row) {
        // データベースのフィールド名で出力
        $info[]=$row;
    }

    //件数取得
    $info_count=$db->info_count();
    $page=ceil((int)$info_count["COUNT(`id`)"]/$count);//ページ数取得

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
                    <li><a href="../logic/logout.php">ログアウト</a></li>
                </ul>
            </aside>
            <article>
                <h1>出力結果</h1>
                <p>表示件数：<?php echo (int)$info_count["COUNT(`id`)"];?>件</p>
<?php if(!isset($info)):?>
                    <h2>出力エラー</h2>
                    <p>出力出来る内容が見つかりませんでした。</p>
<?php else: ?>
                <form action="notification_delete_verification.php" method="POST">
                    <table style="width:100%;">
                        <tbody>
                            <tr>
                                <th>id</th>
                                <th>件名</th>
                                <th class="pc_only">内容</th>
                                <th>投稿者</th>
                                <th class="pc_only">投稿日時</th>
                                <th>編集</th>
                                <th>削除</th>
                            </tr>   
<?php foreach($info as $key => $value) :?>
                            <tr>
                                <td><?php echo $value["id"];?></td>
                                <td><?php echo $value["title"];?></td>
<?php if(strlen($value["contents"])>30): ?>
                                <td class="pc_only"><?php echo substr($value["contents"], 0, 30)."...";?></td>
<?php else: ?>
                                <td class="pc_only"><?php echo $value["contents"];?></td>
<?php endif; ?>
                                <td><?php echo $value["name"];?></td>
                                <td class="pc_only"><?php echo substr($value["created_at"],0,10);?></td>
                                <td><a href="./notification_form.php?id=<?php echo $value["id"];?>">編集</a></td>
                                <td class="delete"><input type="checkbox" name="checkbox[]" value="<?php echo $value["id"];?>"></td>
                            </tr>
<?php endforeach; ?>
                        </tbody>
                    </table>
                    <p style="text-align: right; margin-top:10px;">チェックした項目をまとめて削除<input type="submit" value="削除"></p>
                    <ul id="page" style="display:flex;">
                        <li>Page:</li>
<?php for($i=1;$i<=$page;$i++): ?>
                        <li style="padding-left:10px;"><a href="notification_list.php?page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
<?php endfor;?>
                    </ul>
                </form>
<?php endif; ?>
            </article>
        </main>
        <footer>
            <p>&copy;&nbsp;2021&nbsp;Katsuya&nbsp;Kawamoto*</p>
        </footer>
    </div>
</body>
</html>