<?php
session_start();
session_regenerate_id(true);
$output=$_SESSION;
unset($_SESSION["name"],$_SESSION["mail"],$_SESSION["pass"],$_SESSION["pass_conf"],$_SESSION["tel"],$_SESSION["post"],$_SESSION["sex"]);
//リロード時にエラー項目削除
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style type="text/css">
        body,
        select,
        input{
            font-size: 25px;
        }
		body{
			background-color: rgb(255, 225, 242);
            margin: 0;
            padding: 0;
		}
        #wrapper{
			background-color: white;
            border:1px solid black;			
            border-radius: 10px;
            width: 1000px;
            margin: 0 auto;
            min-height:95vh;
        }
        li{
            list-style: none;
            padding: 5px;
        }
        header>ul{
            display: flex;

        }
        header>ul>li{
            padding: 5px 10px;
        }
		main>h1{
			text-align: center;
		}
		main>form>ul{
            margin:5px;
		}
        main>form>ul>li:nth-last-child(1){
            text-align: center;
        }
        main>div#form>form>ul>li:nth-last-of-type(1){
            text-align: center;
        }
        main>form>ul>li>dl>dt{
            font-weight: bold;
            margin-bottom: 3px;
            padding-left: 5px;
        }
        main>textarea{
            width: calc(100% - 10px);
            margin: 0 auto;
        }
        .error{
            color:red;
        }

    </style>
</head>
<body>
    <div id="wrapper">
        <header>
<?php if(isset($output['e-id'])):
            echo "<p style='background-color:black; color:white;'><b>Hello!&nbsp;",$output['e-id'],"さん！</b></p>",PHP_EOL;
endif; ?>
                <p><a href="member_login.php">ログイン画面</a></p>
            </header>
            <main>
                <form action="join.php" method="post">
                <ul>
                    <li> <h1>Password_hash</h1></li>
                    <li>
                        <dl>
                            <dd>ユーザーID</dd>
                            <dt><input type="text" name="id" id="id" size="60" required></dt>
<?php if(isset($output["id"])) :?>
                            <dd class="error"><?php echo $output["id"]; ?></dd>
<?php endif; ?>
                        </dl>
                    </li>
                    <li>
                        <dl>
                            <dd>パスワード</dd>
                            <dt><input type="password" name="pass" id="pass" size="60" required></dt>
<?php if(isset($output["pass"])) :?>
                            <dd class="error"><?php echo $output["pass"]; ?></dd>
<?php endif; ?>
                        </dl>
                    </li>
                    <li>
                        <input type="submit" value="送信">
                    </li>
                </ul>
            </form>
        </main>
    </div>
</body>
</html>