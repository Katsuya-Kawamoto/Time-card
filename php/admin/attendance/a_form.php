<?php
//ログイン
require_once "../../logic/admin_login.php";
//トークン生成
require_once '../../logic/common_func.php';
//編集判定（データの読み込みなど）
require_once './logic/ad_form.php';
$list_json = json_encode( $list , JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
//セッション確認
//var_dump($_SESSION);
//データベース切断
$stmt=null;
$pdo=null;
//ログ配列
$log_arr=array("登録","編集","削除");
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
            <h1>勤怠登録フォーム</h1>
                <form action="a_verification.php<?php if(isset($_GET["id"])) echo "?id=".$_GET["id"];?>" method="POST">
                    <ul id="form" class="number">
                        <li>
                            <dl class="m-bottom10px">
                                <dt>社員No.</dt>
                                <dd>
                                    <ul>
<?php if($flag):?>
                                        <li><?php echo $input["number"];?><span id="outputname" style="padding-left:5px;"></span></li>
                                        <input type="hidden" name="number" id="number" value="<?php echo $input["number"];?>" required>
                                        
<?php else:?>
<?php if(isset($m_number)): ?>
                                        <li>
                                            <select name="number" id="number">
<?php foreach($m_number as $value): ?>
                                                <option value="<?php echo $value["number"];?>" <?php if($value["number"]==$_SESSION["e-id"]) echo "selected";?>><?php echo $value["number"];?></option>
<?php endforeach ;?>
                                            </select>
                                            <span id="outputname" style="padding-left:5px;"></span>
                                        </li>
<?php else:?>
                                        <li>
                                            <input type="text" name="number" id="number" required>
                                        </li>
<?php endif; ?>
                                    </ul>
<?php endif; ?>
                                </dd>
<?php if(isset($output["err-number"])) :?>
                        <dd class="error"><?php echo $output["err-number"]; ?></dd>
<?php endif; ?>
                            </dl>
                        </li>
                        <li>
                            <dl class="m-bottom10px">
                                <dt>勤務日</dt>
                                <dd>年:<input type="text" name="Year" id="year" size="10" value="<?php echo ($flag)?(int)$input["year"]:(int)$time["year"];?>" required>年</dd>
                                <dd>月:<input type="text" name="Month" id="month" size="10" value="<?php echo ($flag)?(int)$input["month"]:(int)$time["month"];?>" required>月</dd>
                                <dd>日:<input type="text" name="Day" id="day" size="10" value="<?php echo ($flag)?(int)$input["day"]:(int)$time["day"];?>" required>日</dd>
<?php if(isset($output["err-year"])) :?>
                                <dd class="error"><?php echo $output["err-year"]; ?></dd>
<?php endif; ?>
<?php if(isset($output["err-month"])) :?>
                                <dd class="error"><?php echo $output["err-month"]; ?></dd>
<?php endif; ?>
<?php if(isset($output["err-day"])) :?>
                                <dd class="error"><?php echo $output["err-day"]; ?></dd>
<?php endif; ?>
                            </dl>
                        </li>
                        <li>
                        <dl class="m-bottom10px">
                                <dt>勤務形態</dt>
                                <dd>
                                    <select name="work_type" id="work_type">
                                        <option value="0" <?php if(isset($input["work_type"])&&(int)$input["work_type"]===0) echo "selected";?>>通常勤務</option>
                                        <option value="1" <?php if(isset($input["work_type"])&&(int)$input["work_type"]===1) echo "selected";?>>休日出勤</option>
                                    </select>
                                </dd>
<?php if(isset($output["err-work_type"])) :?>
                                <dd class="error"><?php echo $output["err-work_type"]; ?></dd>
<?php endif; ?>
                        </li>
                        <li>
                            <dl class="m-bottom10px">
                                <dt>勤務時間</dt>
                                <dd>
                                    <ul style="display:flex">
                                        <li style="margin-right:5px; margin-bottom:5px;">開始時間：
                                            <select name="s_time" id="s_time">
<?php for($i=8;$i<24;$i++):?>
                                                <option value="<?php echo (int)$i;?>" <?php if(isset($input["s_time"])&&(int)$input["s_time"]===(int)$i) echo "selected";?>><?php echo (int)$i;?>時</option>
<?php endfor; ?>
<?php for($i=0;$i<8;$i++):?>
                                                <option value="<?php echo (int)$i;?>" <?php if(isset($input["s_time"])&&(int)$input["s_time"]===(int)$i) echo "selected";?>><?php echo (int)$i;?>時</option>
<?php endfor; ?>
                                            </select>
                                        </li>
                                        <li>
                                            <select name="s_minutes" id="s_minutes">
<?php for($i=0;$i<60;$i+=15):?>
                                                <option value="<?php echo (int)$i;?>" <?php if(isset($input["s_minutes"])&&(int)$input["s_minutes"]===(int)$i) echo "selected";?>><?php echo (int)$i;?>分</option>
<?php endfor; ?>
                                            </select>
                                        </li>
                                        <li  id="next_s" style="display:none">
                                            <span style="color:red; margin-left:5px;">(翌日)</span>
                                        </li>
                                    </ul>
                                    <ul style="display:flex">
                                        <li style="margin-right:5px;">終了時間：
                                            <select name="e_time" id="e_time">
<?php for($i=8;$i<24;$i++):?>
                                                <option value="<?php echo (int)$i;?>" <?php if(isset($input["e_time"])&&(int)$input["e_time"]===(int)$i) echo "selected";?>><?php echo (int)$i;?>時</option>
<?php endfor; ?>
<?php for($i=0;$i<8;$i++):?>
                                                <option value="<?php echo (int)$i;?>" <?php if(isset($input["e_time"])&&(int)$input["e_time"]===(int)$i) echo "selected";?>><?php echo (int)$i;?>時</option>
<?php endfor; ?>
                                            </select>
                                        </li>
                                        <li>
                                            <select name="e_minutes" id="e_minutes">
<?php for($i=0;$i<60;$i+=15):?>
                                                <option value="<?php echo (int)$i;?>" <?php if(isset($input["e_minutes"])&&(int)$input["e_minutes"]===(int)$i) echo "selected";?>><?php echo (int)$i;?>分</option>
<?php endfor; ?>
                                            </select>
                                        </li>
                                        <li id="next_e" style="display:none">
                                            <span style="color:red; margin-left:5px;">(翌日)</span>
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
                                <dd>拘束時間:
                                    <span id="stay_time">0</span>時間
                                    <span id="stay_minutes">0</span>分
                                </dd>
                            </dl>
                        </li>
                        <li>
                            <dl class="m-bottom10px">
                                <dt>勤務時間</dt>
                                <dd>
                                    <span class="over" style="display:none" style="color:red;">(休日出勤なので時間外でカウント)<br></span>
                                    <span id="work_time"><?php echo ($flag)?(int)$input["work_time"]:0;?></span>時間
                                    <span id="work_minutes"><?php echo ($flag)?(int)$input["work_minutes"]:0;?></span>分
                                </dd>
<?php if(isset($output["err-work_time"])) :?>
                                <dd class="error"><?php echo $output["err-work_time"]; ?></dd>
<?php endif; ?>
<?php if(isset($output["err-work_minutes"])) :?>
                                <dd class="error"><?php echo $output["err-work_minutes"]; ?></dd>
<?php endif; ?>
                                <dt>休憩時間</dt>                      
                                <dd style="display:flex;flex-wrap: wrap;">
                                    <li><input type="text" name="break_times" id="break_time" size="10" value="<?php echo ($flag)?(int)$input["break_time"]:0;?>">時間</li>
                                    <li><input type="text" name="break_minutes" id="break_minutes" size="10" value="<?php echo ($flag)?(int)$input['break_minutes']:0; ?>" required>分</li>
                                </dd>
<?php if(isset($output["err-break_time"])) :?>
                                <dd class="error"><?php echo $output["err-break_time"]; ?></dd>
<?php endif; ?>
<?php if(isset($output["err-break_minutes"])) :?>
                                <dd class="error"><?php echo $output["err-break_minutes"]; ?></dd>
<?php endif; ?>

                                <dt>深夜勤務時間</dt>
                                <dd style="display:flex;flex-wrap: wrap;">
                                    <li><input type="text" name="midnight_times" id="midnight_time" size="10" value="<?php echo ($flag)?(int)$input["midnight_time"]:0;?>" required>時間</li>
                                    <li><input type="text" name="midnight_minutess" id="midnight_minutes" size="10" value="<?php echo ($flag)?(int)$input["midnight_minutes"]:0;?>" required>分</li>
                                </dd>
<?php if(isset($output["err-midnight_time"])) :?>
                                <dd class="error"><?php echo $output["err-midnight_time"]; ?></dd>
<?php endif; ?>
<?php if(isset($output["err-midnight_minutes"])) :?>
                                <dd class="error"><?php echo $output["err-midnight_minutes"]; ?></dd>
<?php endif; ?>
                                <dt>時間外労働</dt>
                                <dd>
                                    <span class="over" style="display:none" style="color:red;">(休日出勤)</span>
                                    <span id="over_time"><?php echo ($flag)?(int)$input["over_time"]:0;?></span>時間
                                    <span id="over_minutes"><?php echo ($flag)?(int)$input["over_minutes"]:0;?></span>分
                                </dd>
                                <dd style="display:none;" id="over_time_reason">
                                    <ul>
                                        <li><b>時間外業務内容</b></li>
                                        <li>
                                            <textarea name="over_time_reason" style="border:1px solid black; margin-top:5px" rows="10" cols="50" placeholder="時間外の業務内容（引き継ぎ・残務処理など）"><?php if(isset($input["over_time_reason"])) echo $input["over_time_reason"];?></textarea>
                                        </li>
                                    </ul>
                                </dd>
<?php if(isset($output["err-over_time_reason"])) :?>
                                <dd class="error"><?php echo $output["err-over_time_reason"]; ?></dd>
<?php endif; ?>
<?php if(isset($output["err-over_time"])) :?>
                                <dd class="error"><?php echo $output["err-over_time"]; ?></dd>
<?php endif; ?>
<?php if(isset($output["err-over_minutes"])) :?>
                                <dd class="error"><?php echo $output["err-over_minutes"]; ?></dd>
<?php endif; ?>  
                            </dl>
                        </li>
<!--パスワード確認一旦、保留
                        <li>
                            <dl class="m-bottom10px">
                                <dt>パスワード</dt>
                                <dd><input type="password" name="pass" id="pass" required></dd>
<?php if(isset($output["err-pass"])) :?>
                                <dd class="error"><?php echo $output["err-pass"]; ?></dd>
<?php endif; ?>
                            </dl>
                        </li>
                        <li>
                            <dl class="m-bottom10px">
                                <dt>パスワード(確認)</dt>
                                <dd><input type="password" name="pass_conf" id="pass_conf"></dd>
<?php if(isset($output["err-pass_conf"])) :?>
                                <dd class="error"><?php echo $output["err-pass_conf"]; ?></dd>
<?php endif; ?>
                            </dl>
                        </li>
-->
                        <li>
<?php if(isset($output["err-form"])) :?>
                                <dd class="error"><?php echo $output["err-form"]; ?></dd>
<?php endif; ?>
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
                    <!--ログ用-->
                    <input type="hidden" name="type" value="<?php echo ($flag)?1:0;?>">
                    <input type="hidden" name="contributor_no" value="<?php echo $_SESSION["e-id"]; ?>">
<?php if($flag): ?>
                    <input type="hidden" name="id" value="<?php echo $_GET["id"]; ?>">
<?php endif; ?> 
                </form>
<?php if(isset($log) && count($log)>0): ?>
                <hr>
                <table id="log"><tbody>
                    <tr>
                        <th>投稿日</th>
                        <th>投稿タイプ</th>
                        <th>投稿者</th>
                        <th>社員番号</th>
                    </tr>
<?php foreach($log as $log_r): ?>
                    <tr>
                        <td><?php echo $log_r["created_at"];?></td>
                        <td><?php echo $log_arr[$log_r["type"]];?></td>
                        <td><?php echo ($log_r["contributor"]==1)? "管理者":"本人";?></td>
                        <td><?php echo $log_r["contributor_no"];?></td>
                    </tr>
<?php endforeach; ?>
                </tbody></table>
<?php endif; ?>
            </article>
        </main>
        <footer>
            <p><small>&copy;&nbsp;2021&nbsp;Katsuya&nbsp;Kawamoto*</small></p>
        </footer>
    </div>
    <script type="text/javascript">
        let     number      = document.getElementById("number");
        let     outputname  = document.getElementById("outputname");
        let     list        = JSON.parse('<?php echo $list_json; ?>');

        number.addEventListener('change', (event) => {
        change();});

        function change(){
            let GET_number = number;
            let NUMBER     = GET_number.value-1+1;
            let result     = list[NUMBER];
                outputname.textContent=result;
        }
        change();
    </script>
    <script type="text/javascript" src="./js/calculation.js"></script>
</body>
</html>