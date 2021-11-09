<?php
    //ログイン
    require_once "../../logic/admin_login.php";
    //ログイン
    require_once "./logic/mb_ad_time.php";
    //トークン生成
    require_once '../../logic/common_func.php';
    //セッション確認
    //var_dump($_SESSION);
    //データベース切断
    $stmt=null;
    $pdo=null;
?>
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
                        <li><a href="../member/m_form.php">従業員登録</a></li>
                        <li><a href="../member/m_list.php">従業員編集</a></li>
                    </ul>
                    <li>勤怠管理</li>
                    <ul>
                        <li><a href="a_form.php">勤怠登録</a></li>
                        <li><a href="mb_ad_list.php">勤務状況一覧</a></li>
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
                <section id="time">
                    <h1><?php echo (int)h($month); ?>月の勤務時間 <?php if(!isset($_GET["month"]))echo "(".(int)h($time["day"])."日現在)"; ?></h1>
<?php if(isset($output["delete"])) :?>
                    <p class="error" style="margin:10px 0;"><?php echo h($output["delete"]); ?></p>
<?php endif; ?>  
<?php if($time_info): ?> 
                    <ul>
                        <li>
                            <dl>
                                <dt>勤務時間</dt>
                                <dd><?php echo h($time_cl["work_time"]); ?>時間<?php printf("%02d", h($time_cl["work_minutes"])); ?>分</dd>
                            </dl>
                        </li>
                        <li>
                            <dl>
                                <dt>時間外勤務時間</dt>
                                <dd class="over_time">
                                    <ul>
                                        <li><?php echo h($time_cl["over_time"]); ?>時間<?php printf("%02d", h($time_cl["over_minutes"])); ?>分</li>
                                        <li style="border-radius:10px; border:1px solid #333333; margin-left:5px; background-color:#ffffdd;">
                                            <dl class="pc_flex">
                                                <dt><b>休日出勤時間</b></dt>
                                                <dd><?php echo h($time_cl["holiday_time"]); ?>時間<?php printf("%02d", h($time_cl["holiday_minutes"])); ?>分</dd>
                                            </dl>
                                            <dl class="pc_flex">
                                                <dt><b>残業時間</b></dt>    
                                                <dd><?php echo h($time_cl["OVER_time"]); ?>時間<?php printf("%02d",h($time_cl["OVER_minutes"])); ?>分</dd>
                                            </dl>
                                        </li>
                                    </ul> 
                                </dd>
                            </dl>
                        </li>
                        <li>
                            <dl>
                                <dt>深夜勤務時間</dt>
                                <dd><?php echo h($time_cl["midnight_time"]); ?>時間<?php printf("%02d", h($time_cl["midnight_minutes"])); ?>分</dd>
                            </dl>
                        </li>
                    </ul> 
                </section>
                <section id="list">
                    <form action="a_delete_verification.php" method="POST">
                        <table id="time-info">
                            <tbody class="list">
                                <tr>
                                    <th rowspan="2">勤務日</th><th rowspan="2" style="writing-mode: vertical-rl;">休出</th><th colspan="3">勤務時刻</th>
                                    <th rowspan="2">勤務<br>時間</th><th class="pc_only" rowspan="2">時間外</th><th class="pc_only" rowspan="2">深夜<br>時間</th>
                                    <th rowspan="2">編集</th><th rowspan="2" style="writing-mode: vertical-rl;">削除</th><th rowspan="2" style="writing-mode: vertical-rl;">管理者</th>
                                </tr>
                                <tr>
                                    <th colspan="3">
                                        <ul class="flex-warp">
                                            <li>開始</li>
                                            <li>~</li>
                                            <li>終了</li>
                                        </ul>
                                    </th>
                                </tr>
<?php foreach($time_info as $key=>$value):?>
                                <tr <?php if($value["work_type"]==1) echo "style='background-color:#faffb1'";?>>
                                    <td><?php echo h($value["day"]); ?>日</td>
                                    <td style="color:red;"><b><?php if($value["work_type"]==1)echo "*";?></b></td>
                                    <td colspan="3">
                                        <ul class="flex-warp">
                                            <li><?php echo h($value["s_time"]); ?>:<?php printf("%02d", h($value["s_minutes"]));?></li>
                                            <li>~</li>
                                            <li><?php echo h($value["e_time"]); ?>:<?php printf("%02d", h($value["e_minutes"]));?></li>
                                        </ul>
                                    </td>
                                    <td>
                                        <ul class="pc_only">
                                            <li><?php echo h($value["work_time"]); ?>時間</li>
                                            <li><?php echo h($value["work_minutes"]); ?>分</li>
                                        </ul>
                                    </td>
                                    <td class="pc_only">
                                        <ul>
                                            <li><?php echo h($value["over_time"]); ?>時間</li>
                                            <li><?php echo h($value["over_minutes"]); ?>分</li>
                                        </ul>    
                                    </td>
                                    <td class="pc_only">
                                        <ul>
                                            <li><?php echo h($value["midnight_time"]); ?>時間</li>
                                            <li><?php echo h($value["midnight_minutes"]); ?>分</li>
                                        </ul>
                                    </td>
                                    <td class="delete"><a href="a_form.php?id=<?php echo h($value['keey']);?>">編集</a></td>
                                    <td class="delete"><input type="checkbox" name="key[]" id="key" value="<?php echo h($value['keey']);?>"></td>
                                    <td  style="color:red;"><b><?php if(isset($log_check[$value["day"]]))echo "*";?></b></td>
                                </tr>   
<?php endforeach; ?>
                            </tbody>
                        </table>
                        <ul>
                            <li>選択した項目を一括削除</li>
                            <li><input type="submit" value="削除"></li>
<?php if(isset($output["err-form"])) :?>
                            <li class="error" style="margin:10px 0;"><?php echo h($output["err-form"]); ?></li>
<?php endif; ?>  
                        </ul>
                    <input type="hidden" name="csrf_token" value="<?php echo h(setToken()); ?>">
                    <!--ログ用-->
                    <input type="hidden" name="type" value="<?php echo "2";?>">
                    <input type="hidden" name="contributor_no" value="<?php echo h($_SESSION["e-id"]); ?>">
                    </form>
                </section>
<?php else: ?>
                    <h2>取得エラー</h2>
                    <p>情報がありませんでした。</p>
                </section>
<?php endif; ?>
                <hr>
            </article>
        </main>
        <footer>
            <p><small>&copy;&nbsp;2021&nbsp;Katsuya&nbsp;Kawamoto*</small></p>
        </footer>
    </div>
</body>
</html>