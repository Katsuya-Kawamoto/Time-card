<?php
    //ログイン
    require_once "../../logic/admin_login.php";
    //トークン生成
    require_once '../../logic/common_func.php';
    //リスト表示
    $count=20;
    if(isset($_GET["page"])){
        $offset=($_GET["page"]-1)*$count;
    }else{
        $offset=0;
    }

    $result=info_title($offset,$count);
    foreach ($result as $row) {
        // データベースのフィールド名で出力
        $info[]=$row;
    }

    //件数取得
    $info_count=info_count();
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
                        <li><a href="../member_register.php">従業員登録</a></li>
                        <li><a href="../member_list.php">従業員編集</a></li>
                    </ul>
                    <li>勤怠管理</li>
                    <ul>
                        <li><a href="../attendance/a_form.php">勤怠登録</a></li>
                        <li><a href="../attendance/mb_ad_list.php">勤務状況一覧</a></li>
                    </ul>
                    <li>お知らせ管理</li>
                    <ul>
                        <li><a href="./n_form.php">投稿</a></li>
                        <li><a href="./n_list.php">編集</a></li>
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
            <article>
                <h1>出力結果</h1>
                <p>表示件数：<?php echo (int)h($info_count["COUNT(`id`)"]);?>件</p>
<?php if(!isset($info)):?>
                    <h2>出力エラー</h2>
                    <p>出力出来る内容が見つかりませんでした。</p>
<?php else: ?>
                <div id="list_output">
                    <form action="n_delete_verification.php" method="POST">
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
                                    <td><?php echo h($value["id"]);?></td>
                                    <td><?php echo h($value["title"]);?></td>
<?php if(strlen($value["contents"])>30): ?>
                                    <td class="pc_only"><?php echo substr($value["contents"], 0, 30)."...";?></td>
<?php else: ?>
                                    <td class="pc_only"><?php echo h($value["contents"]);?></td>
<?php endif; ?>
                                    <td><?php echo h($value["name"]);?></td>
                                    <td class="pc_only"><?php echo substr($value["created_at"],0,10);?></td>
                                    <td><a href="./n_form.php?id=<?php echo h($value["id"]);?>">編集</a></td>
                                    <td class="delete"><input type="checkbox" name="checkbox[]" value="<?php echo h($value["id"]);?>"></td>
                                </tr>
<?php endforeach; ?>
                            </tbody>
                        </table>
<?php if(isset($output["err-form"])) :?>
                        <p class="error"><?php echo h($output["err-form"]); ?></ｐ>
<?php endif; ?>
                        <p>チェックした項目をまとめて削除<input type="submit" value="削除"></p>
                        <ul id="page">
                            <li>Page:</li>
<?php for($i=1;$i<=$page;$i++): ?>
                            <li><a href="n_list.php?page=<?php echo h($i); ?>"><?php echo h($i); ?></a></li>
<?php endfor;?>
                        </ul>
                        <input type="hidden" name="csrf_token" value="<?php echo h(setToken()); ?>">
                    </form>
                </div>
<?php endif; ?>
            </article>
        </main>
        <footer>
            <p><small>&copy;&nbsp;2021&nbsp;Katsuya&nbsp;Kawamoto*</small></p>
        </footer>
    </div>
</body>
</html>