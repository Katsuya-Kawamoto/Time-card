<?php
//ログイン
require_once "../../logic/admin_login.php";
//一覧情報の取得
require_once "./logic/mb_ad_list.php";
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
                <h1>出力結果</h1>
                <section id="m_search">                
                    <div>
                        <form action="mb_ad_list.php<?php if(isset($_GET["month"]))echo "?year=".h($_GET["year"])."&month=".h($_GET["month"]);?>">
                            <ul id="form">
                                <li>
                                    <dl class="m-bottom5px">
                                        <dt>
                                            <h1><b>取得年月変更</b></h1>
                                        </dt>
                                        <dd>
                                            <p>年月を選択してください。</p>
                                            <select name="year" id="year">
<?php for($i=$time["year"]-1;$i<=$time["year"]+1;$i++):?>
                                                <option value="<?php echo (int)h($i);?>" <?php if((int)$time["year"]===(int)$i) echo "selected";?>><?php echo (int)h($i);?>年</option>
<?php endfor; ?>
                                            </select>
                                            <select name="month" id="month">
<?php for($i=1;$i<=12;$i++):?>
                                                <option value="<?php echo (int)h($i);?>" <?php if((int)$time["month"]===(int)$i) echo "selected";?>><?php echo (int)h($i);?>月</option>
<?php endfor; ?>
                                            </select>
                                            <input type="submit" value="取得">
                                        </dd>
                                    </dl>
                                </li>
                            </ul>
                        </form>
                    </div>
                    <div>    
                        <form action="mb_ad_list.php" method="GET">
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
                    </div>
                </section>
                <hr>
                <section id="m_result">
<?php if(!isset($info)):?>
                    <h2>出力エラー</h2>
                    <p>出力出来る内容が見つかりませんでした。</p>
<?php else: ?>
                    <h1><?php echo h($year); ?>年<?php echo (int)h($month); ?>月の勤務時間 <?php if(!isset($_GET["month"]))echo "(".(int)h($time["day"])."日現在)"; ?></h1>
                        <div id="list_output" style="overflow-x:scroll; max-width:720px;">
                            <table style="width:1000px">
                                <tbody>
                                    <tr>
                                        <th rowspan="4">社員番号</th>
                                        <th rowspan="4">姓</th>
                                        <th rowspan="4">名</th>
                                        <th rowspan="4">詳細</th>
                                        <th colspan="10">日数・時間</th>
                                    </tr>
                                    <tr>
                                        <!--社員番号-->
                                        <!--姓-->
                                        <!--名-->
                                        <th rowspan="2" colspan="2">勤務</th>
                                        <th colspan="6">時間外</th>
                                        <!--休出-->
                                        <!--残業-->
                                        <th rowspan="2" colspan="2">深夜</th>
                                        <!--編集-->
                                        <!--削除-->
                                    </tr>
                                    <tr>
                                        <!--社員番号-->
                                        <!--姓-->
                                        <!--名-->
                                        <!--勤務-->
                                        <th colspan="2" style="color:#ffffdd;">合計</th>
                                        <th colspan="2">休出</th>
                                        <th colspan="2">残業</th>
                                        <!--深夜-->
                                        <!--編集-->
                                        <!--削除-->
                                    </tr>
                                    <tr>
                                        <!--社員番号-->
                                        <!--姓-->
                                        <!--名-->
                                        <th>日数</th>
                                        <th>時間</th>
                                        <th style="color:#ffffdd;">日数</th>
                                        <th style="color:#ffffdd;">時間</th>
                                        <th>日数</th>
                                        <th>時間</th>
                                        <th>日数</th>
                                        <th>時間</th>
                                        <th>日数</th>
                                        <th>時間</th>
                                        <!--編集-->
                                        <!--削除-->
                                    </tr>
<?php foreach($member_info as $value) :?>
<?php if(($year==$time["year"] && $month == $time["month"])|| $value["work_count"]+$value["over_count"]>0) :?>
                                <tr>
                                    <td><?php echo h($value["number"]);?></td>
                                    <td><?php echo h($value["sei"]);?></td>
                                    <td><?php echo h($value["mei"]);?></td>
<?php if($value["work_count"]+$value["over_count"]>0) :?>
                                    <td><a href="./mb_ad_time.php?id=<?php echo h($value["number"]);?><?php if(isset($_GET["month"]))echo"&month=".h($_GET["month"]); ?><?php if(isset($_GET["year"]))echo"&year=".h($_GET["year"]); ?>">詳細</a></td>
<?php else: ?>
                                    <td></td>
<?php endif; ?>
                                    <td><?php echo h($value["work_count"]);?>日</td>
                                    <td><?php echo h($value["work_time"]);?>時間<?php echo h($value["work_minutes"]);?>分</td>
                                    <td style="background-color:#ffffdd;"><?php echo h($value["over_count"]);?>日</td>
                                    <td style="background-color:#ffffdd;"><?php echo h($value["over_time"]);?>時間<?php echo h($value["over_minutes"]);?>分</td>
                                    <td><?php echo h($value["holiday_count"]);?>日</td>
                                    <td><?php echo h($value["holiday_time"]);?>時間<?php echo h($value["holiday_minutes"]);?>分</td>
                                    <td><?php echo h($value["OVER_count"]);?>日</td>
                                    <td><?php echo h($value["OVER_time"]);?>時間<?php echo h($value["OVER_minutes"]);?>分</td>
                                    <td><?php echo h($value["midnight_count"]);?>日</td>
                                    <td><?php echo h($value["work_time"]);?>時間<?php echo h($value["work_minutes"]);?>分</td>
                                </tr>
<?php endif; ?>
<?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
<?php if(isset($output["err-form"])) :?>
                    <p class="error"><?php echo h($output["err-form"]); ?></ｐ>
<?php endif; ?>
                    <ul id="page">
                        <li>Page:</li>
<?php for($i=1;$i<=$page;$i++): ?>
                        <li><a href="mb_ad_list.php?page=<?php echo h($i); ?>"><?php echo h($i); ?></a></li>
<?php endfor;?>
                    </ul>
                    <input type="hidden" name="csrf_token" value="<?php echo h(setToken()); ?>">
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