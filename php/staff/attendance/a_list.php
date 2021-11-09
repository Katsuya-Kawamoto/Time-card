<?php
    //ログイン
    require "../../logic/staff_login.php";
    //トークン生成
    require_once '../../logic/common_func.php';
    //勤務状況取得
    require_once "../../logic/time_input.php";
    $time =  Time_input();                                                              //現在の日付取得
    if(isset($_GET["month"])&&isset($_GET["year"])){
        $year = $_GET["year"];
        $month = $_GET["month"];
        if($year==$time["year"] && $month == $time["month"]){                           //GETの年月が今月だった場合
            $flag=true;                                                                 //$flgをtrue(編集機能ON)
        }else{
            $flag=false;
        }
    }else{                                                                              //GETで年月の選択が無かった場合
        $year =  $time["year"];                                                         //今月の情報を取得
        $month = $time["month"];
        $flag=true;                                                                     //$flgをtrue(編集機能ON)
    }
    $time_info=time_info_input($_SESSION["e-id"],$month,$year);                         //今月の勤怠状況取得
    $time_cl=time_calculation($time_info);                                              //総勤務時間算出
    $time_count=time_count($time_info);                                                 //出勤回数算出
    
    //ログから管理者が編集などを行っているか確認
    require_once '../logic/ad_function.php';
    $log_check=null;
    for($i=1; $i<=31; $i++){
        $KEY=$year.$month.$i."_".$_SESSION["e-id"];
        if(attendance_log_check($KEY)){
            $log_check[$i]=true;
        }
    }
    //var_dump($log_check);
    
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
                        <li><a href="a_form.php">登録</a></li>
                        <li><a href="a_list.php">編集</a></li>
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
                                                <dd><?php echo h($time_cl["OVER_time"]); ?>時間<?php printf("%02d", h($time_cl["OVER_minutes"])); ?>分</dd>
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
                                    <th rowspan="2">勤務日</th><th rowspan="2">休出</th><th colspan="3">勤務時間</th>
                                    <th rowspan="2">勤務時間</th><th class="pc_only" rowspan="2">時間外</th><th class="pc_only" rowspan="2">深夜時間</th>
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
<?php if($flag==true && $value["day"]+1>=$time["day"]): ?>
                                    <td class="delete"><a href="a_form.php?id=<?php echo h($value['keey']);?>">編集</a></td>
                                    <td class="delete"><input type="checkbox" name="key[]" id="key" value="<?php echo h($value['keey']);?>"></td>
<?php else: ?>
                                    <td class="delete"></td>
                                    <td class="delete"></td>
<?php endif; ?>
                                    <td  style="color:red;"><b><?php if(isset($log_check[$value["day"]]))echo "*";?></b></td>
                                </tr>   
<?php endforeach; ?>
                            </tbody>
                        </table>
                        <p style="color:red;">*前日以前の勤怠内容を編集する場合は管理者に依頼してください。</p>
                        <ul style="margin:10px 0;">
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
                <section id="m_search">
                    <form action="attendance_list.php<?php if(isset($_GET["month"]))echo "?year=".$_GET["year"]."&month=".$_GET["month"];?>">
                        <h1><b>取得年月変更</b></h1>
                        <p>年月を選択してください。</p>
                        <select name="year" id="year">
<?php for($i=$time["year"]-1;$i<=$time["year"]+1;$i++):?>
                            <option value="<?php echo (int)$i;?>" <?php if((int)$time["year"]===(int)$i) echo "selected";?>><?php echo (int)$i;?>年</option>
<?php endfor; ?>
                        </select>
                        <select name="month" id="month">
<?php for($i=1;$i<=12;$i++):?>
                            <option value="<?php echo (int)$i;?>" <?php if((int)$time["month"]===(int)$i) echo "selected";?>><?php echo (int)$i;?>月</option>
<?php endfor; ?>
                        </select>
                        <input type="submit" value="取得">
                    </form>
                </section>
            </article>
        </main>
        <footer>
            <p><small>&copy;&nbsp;2021&nbsp;Katsuya&nbsp;Kawamoto*</small></p>
        </footer>
    </div>
</body>
</html>