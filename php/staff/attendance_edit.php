<?php
//ログイン
require_once "./logic/login.php";
//トークン生成
require_once '../admin/logic/functions.php';
//勤怠詳細取得
require_once './logic/ad_functions.php';
$_SESSION["key"]=$_GET["key"];
staff_time_key($_GET["key"]);

//セッション確認
var_dump($_SESSION);
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
    <link rel="stylesheet" href="../../css/reset.css"><!--リセットCSS-->
    <link rel="stylesheet" href="../css/style2.css"><!--メイン用CSS-->
    <title>従業員・管理画面</title>
</head>
<body>
    <div id="wrapper">
        <header>
            <h1><a href="./staff_top.php">従業員・管理画面</a></h1>
            <div><?php echo $_SESSION["header-sei"];?>さん、お疲れ様です。</div>
        </header>
        <main>
        <aside>
                <ul>
                    <li>勤怠管理</li>
                    <ul>
                        <li><a href="attendance_form.php">登録</a></li>
                        <li><a href="attendance_list.php">編集</a></li>
                    </ul>
                    <li>パスワード管理</li>
                    <ul>
                        <li><a href="pass_reset.php">変更</a></li>
                    </ul>
                </ul>
            </aside>
            <article>
            <h1>勤怠登録フォーム</h1>
                <form action="attendance_verification.php" method="POST">
                    <ul id="form" class="number">
                        <li>
                            <dl class="m-bottom5px">
                                <dt>社員No.</dt>
                                <dd>
                                    <ul>
                                        <li><?php echo $_SESSION['e-id'];?></li>
                                    </ul>
                                    <input type="hidden" name="number" id="number" value="<?php echo $_SESSION['e-id'];?>" required>
                                </dd>
<?php if(isset($output["err-number"])) :?>
                        <dd class="error"><?php echo $output["err-number"]; ?></dd>
<?php endif; ?>
                            </dl>
                        </li>
                        <li>
                            <dl class="m-bottom5px">
                                <dt>勤務日</dt>
                                <dd>年:<input type="text" name="year" id="year" size="10" value="<?php echo $result["year"];?>" required>年</dd>
<?php if(isset($output["err-year"])) :?>
                                <dd class="error"><?php echo $output["err-year"]; ?></dd>
<?php endif; ?>
                                <dd>月:<input type="text" name="month" id="month" size="10" value="<?php echo $result["month"];?>" required>月</dd>
<?php if(isset($output["err-month"])) :?>
                                <dd class="error"><?php echo $output["err-month"]; ?></dd>
<?php endif; ?>
                                <dd>年:<input type="text" name="day" id="day" size="10" value="<?php echo $result["day"];?>" required>日</dd>
<?php if(isset($output["err-day"])) :?>
                                <dd class="error"><?php echo $output["err-day"]; ?></dd>
<?php endif; ?>
                            </dl>
                        </li>
                        <li>
                        <dl class="m-bottom5px">
                                <dt>勤務形態</dt>
                                <dd>
                                    <select name="work_type" id="work_type">
                                            <option value="0" <?php if((int)$result["work_type"]===0) echo "selected";?>>通常勤務</option>
                                            <option value="1" <?php if((int)$result["work_type"]===1) echo "selected";?>>休日出勤</option>
                                    </select>
                                </dd>
<?php if(isset($output["err-work_type"])) :?>
                                <dd class="error"><?php echo $output["err-work_type"]; ?></dd>
<?php endif; ?>
                        </li>
                        <li>
                            <dl class="m-bottom5px">
                                <dt>勤務時間</dt>
                                <dd>
                                    <ul style="display:flex">
                                        <li style="margin-right:5px;">開始時間：
                                            <select name="s_time" id="s_time">
<?php for($i=8;$i<24;$i++):?>
                                                <option value="<?php echo (int)$i;?>" <?php if((int)$result["s_time"]===(int)$i) echo "selected";?>><?php echo (int)$i;?>時</option>
<?php endfor; ?>
<?php for($i=0;$i<8;$i++):?>
                                                <option value="<?php echo (int)$i;?>" <?php if((int)$result["s_time"]===(int)$i) echo "selected";?>><?php echo (int)$i;?>時</option>
<?php endfor; ?>
                                            </select>
                                        </li>
                                        <li>
                                            <select name="s_minutes" id="s_minutes">
<?php for($i=0;$i<60;$i+=15):?>
                                                <option value="<?php echo (int)$i;?>" <?php if((int)$result["s_minutes"]===(int)$i) echo "selected";?>><?php echo (int)$i;?>分</option>
<?php endfor; ?>
                                            </select>
                                        </li>
                                    </ul>
                                    <ul style="display:flex">
                                        <li style="margin-right:5px;">終了時間：
                                            <select name="e_time" id="e_time">
<?php for($i=8;$i<24;$i++):?>
                                                <option value="<?php echo (int)$i;?>" <?php if((int)$result["e_time"]===(int)$i) echo "selected";?>><?php echo (int)$i;?>時</option>
<?php endfor; ?>
<?php for($i=0;$i<8;$i++):?>
                                                <option value="<?php echo (int)$i;?>" <?php if((int)$result["e_time"]===(int)$i) echo "selected";?>><?php echo (int)$i;?>時</option>
<?php endfor; ?>
                                            </select>
                                        </li>
                                        <li>
                                            <select name="e_minutes" id="e_minutes">
<?php for($i=0;$i<60;$i+=15):?>
                                                <option value="<?php echo (int)$i;?>" <?php if((int)$result["e_minutes"]===(int)$i) echo "selected";?>><?php echo (int)$i;?>分</option>
<?php endfor; ?>
                                            </select>
                                        </li>
                                    </ul>
                                </dd>
<?php if(isset($output["err-s_time"])) :?>
                                <dd class="error"><?php echo $output["err-s_time"]; ?></dd>
<?php endif; ?>
<?php if(isset($output["err-e_time"])) :?>
                                <dd class="error"><?php echo $output["err-e_time"]; ?></dd>
<?php endif; ?>
<?php if(isset($output["err-s_minutes"])) :?>
                                <dd class="error"><?php echo $output["err-s_minutes"]; ?></dd>
<?php endif; ?>
<?php if(isset($output["err-e_minutes"])) :?>
                                <dd class="error"><?php echo $output["err-e_minutes"]; ?></dd>
<?php endif; ?>
                                <dd>拘束時間:<span id="stay_time">0</span>時間<span id="stay_minutes">0</span>分</dd>
                            </dl>
                        </li>
                        <li>
                            <dl class="m-bottom5px">
                                <dt>勤務時間</dt>
                                <dd>
                                    <span class="over" style="display:none" style="color:red;">(休日出勤なので時間外でカウント)<br></span>
                                    <span id="work_time"><?php echo (int)$result["work_time"];?></span>時間
                                    <span id="work_minutes"><?php echo (int)$result["work_minutes"];?></span>分
                                </dd>
<?php if(isset($output["err-work_time"])) :?>
                                <dd class="error"><?php echo $output["err-work_time"]; ?></dd>
<?php endif; ?>
                                <dt>休憩時間</dt>
<?php if(isset($result["break_time"])) :?>                                
                                <dd><input type="text" name="break_times" id="break_time" size="10" value="<?php echo (int)$result["break_time"];?>">時間<input type="text" name="break_minutes" id="break_minutes" size="10" value="<?php echo (int)$result['break_minutes']; ?>" required>分</dd>
<?php else:?>
                                <dd><input type="text" name="break_times" id="break_time" size="10" required>時間<input type="text" name="break_minutess" id="break_minutes" size="10" required>分</dd>
<?php endif;?>
<?php if(isset($output["err-break_time"])) :?>
                                <dd class="error"><?php echo $output["err-break_time"]; ?></dd>
<?php endif; ?>
                                <dt>深夜勤務時間</dt>
                                <dd><input type="text" name="midnight_times" id="midnight_time" size="10" value="<?php echo (int)$result["midnight_time"];?>" required>時間<input type="text" name="midnight_minutess" id="midnight_minutes" size="10" value="<?php echo (int)$result["midnight_minutes"];?>" required>分</dd>
<?php if(isset($output["err-midnight_time"])) :?>
                                <dd class="error"><?php echo $output["err-midnight_time"]; ?></dd>
<?php endif; ?>
                                <dt>時間外労働</dt>
                                <dd>
                                    <span class="over" style="display:none" style="color:red;">(休日出勤)</span>
                                    <span id="over_time"><?php echo (int)$result["over_time"];?></span>時間
                                    <span id="over_minutes"><?php echo (int)$result["over_minutes"];?></span>分
                                </dd>
                                <dd style="display:none;" id="over_time_reason">
                                    <ul>
                                        <li><b>時間外業務内容</b></li>
                                        <li>
                                            <textarea name="over_time_reason" style="border:1px solid black; margin-top:5px" rows="10" cols="50" placeholder="時間外の業務内容（引き継ぎ・残務処理など）"><?php if(isset($result["over_time_reason"])) echo $result["over_time_reason"];?></textarea>
                                        </li>
                                    </ul>
                                </dd>
<?php if(isset($output["err-over_time_reason"])) :?>
                                <dd class="error"><?php echo $output["err-over_time_reason"]; ?></dd>
<?php endif; ?>
<?php if(isset($output["err-over_time"])) :?>
                                <dd class="error"><?php echo $output["err-over_time"]; ?></dd>
<?php endif; ?> 
                            </dl>
                        </li>
<!--
                        <li>
                            <dl class="m-bottom5px">
                                <dt>パスワード</dt>
                                <dd><input type="password" name="pass" id="pass" required></dd>
<?php if(isset($output["err-pass"])) :?>
                                <dd class="error"><?php echo $output["err-pass"]; ?></dd>
<?php endif; ?>
                            </dl>
                        </li>
                        <li>
                            <dl class="m-bottom5px">
                                <dt>パスワード(確認)</dt>
                                <dd><input type="password" name="pass_conf" id="pass_conf"></dd>
<?php if(isset($output["err-pass_conf"])) :?>
                                <dd class="error"><?php echo $output["err-pass_conf"]; ?></dd>
<?php endif; ?>
                            </dl>
                        </li>
-->
                        <li>
                            <input type="submit" value="確認">
                        </li>
                    </ul>
                    <input type="hidden" name="work_time" id="work_time_ip" value="">
                    <input type="hidden" name="work_minutes" id="work_minutes_ip" value="">
                    <input type="hidden" name="over_time" id="over_time_ip" value="">
                    <input type="hidden" name="over_minutes" id="over_minutes_ip" value="">
                    <input type="hidden" name="break_time" id="break_time_ip" value="">
                    <input type="hidden" name="break_minutes" id="break_minutes_ip" value="">
                    <input type="hidden" name="midnight_time" id="midnight_time_ip" value="">
                    <input type="hidden" name="midnight_minutes" id="midnight_minutes_ip" value="">
                    <input type="hidden" name="csrf_token" value="<?php echo h(setToken()); ?>">
                </form>
            </article>
        </main>
        <footer>
            <nav>
                <p><a href="./logic/logout.php">ログアウト</a></p>
            </nav>
        </footer>
    </div>
    <script type="text/javascript" src="calculation.js"></script>

</body>
</html>