<?php
//ログイン
require_once "../../logic/admin_login.php";
//リスト表示
$count=20;
if(isset($_GET["page"])){
    $offset=($_GET["page"]-1)*$count;
}else{
    $offset=0;
}
//メンバー情報の取得
require_once "../logic/db_access.php";
$db=new db();
$result=$db->member_list($offset,$count);
foreach ($result as $row) {
    // データベースのフィールド名で出力
    $info[]=$row;
}
//件数取得
$info_count=$db->member_count();
$page=ceil((int)$info_count["COUNT(`number`)"]/$count);//ページ数取得
//トークン生成
require_once '../../logic/common_func.php';
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
            <article>
                <h1>出力結果</h1>
                <section id="m_search">
                    <form action="member_list.php" method="GET">
                        <ul id="form">
                            <li>
                                <dl class="m-bottom5px">
                                    <dt>社員No.</dt>
                                    <dd>
                                        <ul>
                                            <li><input type="text" name="number" id="number" value="<?php if(isset($_GET["number"])) echo h($_GET["number"]); ?>"></li>
                                            <li>*半角英数6文字</li>
                                        </ul>
                                    </dd>
<?php if(isset($output["err-number"])) :?>
                                    <dd class="error"><?php echo h($output["err-number"]); ?></dd>
<?php endif; ?>
<?php if(isset($output["err-staff"])) :?>
                                    <dd class="error"><?php echo h($output["err-staff"]); ?></dd>
<?php endif; ?>
                                </dl>
                            </li>
                            <li>
                                <input type="submit" value="検索">
                            </li>
                        </ul>
                    </form>
                </section>
                <hr>
<?php if(!isset($info)):?>
                <h2>出力エラー</h2>
                <p>出力出来る内容が見つかりませんでした。</p>
<?php else: ?>
                <form action="m_delete_verification.php" method="POST">
                    <p><b>出力件数:<?php echo h($info_count["COUNT(`number`)"]);?>件</b></p>
                    <section id="list_output" style="overflow-x:auto;">
                        <table style="width:100%; min-width:500px;">
                            <tbody>
                                <tr>
                                    <th>社員<br>番号</th>
                                    <th>姓</th>
                                    <th>名</th>
                                    <th>管理者<br>権限</th>
                                    <th>編集</th>
                                    <th>削除</th>
                                </tr>
<?php foreach($info as $key => $value) :?>
                                <tr>
                                    <td><?php echo h($value["number"]);?></td>
                                    <td><?php echo h($value["sei"]);?></td>
                                    <td><?php echo h($value["mei"]);?></td>
                                    <td style="color:red;"><?php if($value["admin"]==1) echo "*";?></td>
                                    <td><a href="./m_form.php?id=<?php echo h($value["number"]);?>">編集</a></td>
                                    <td class="delete"><input style="margin:auto;" type="checkbox" name="checkbox[]" value="<?php echo h($value["number"]);?>"></td>
                                </tr>
<?php endforeach; ?>
                            </tbody>
                        </table>
                    </section>
                    <p style="text-align: center; margin:10px 0;">チェックした項目をまとめて削除<input type="submit" value="削除"></p>
<?php if(isset($output["err-form"])) :?>
                    <p class="error"><?php echo h($output["err-form"]); ?></ｐ>
<?php endif; ?>
                    <ul id="page">
                        <li>Page:</li>
<?php for($i=1;$i<=$page;$i++): ?>
                        <li><a href="me_list.php?page=<?php echo h($i); ?>"><?php echo h($i); ?></a></li>
<?php endfor;?>
                    </ul>
                    <input type="hidden" name="csrf_token" value="<?php echo h(setToken()); ?>">
                </form>
<?php endif; ?>
                
            </article>
        </main>
        <footer>
            <p><small>&copy;&nbsp;2021&nbsp;Katsuya&nbsp;Kawamoto*</small></p>
        </footer>
    </div>
</body>
</html>