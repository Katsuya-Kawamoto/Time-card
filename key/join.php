<?php
//DB初期設定
$dbname = 'mysql:host=localhost;dbname=attendance;charset=utf8';//ホストサーバー名
$id = 'root';//ID
$pw = '';//PASS

if($_SERVER["REQUEST_METHOD"]==="POST"){
    try {
        //DB取得処理
        $pdo = new PDO($dbname, $id, $pw,array(PDO::ATTR_ERRMODE => false));
    }catch (PDOException $e) {
        die('データベース接続失敗。'.$e->getMessage());
    }

    session_start();
    session_regenerate_id(true);
    /*********
    確認画面から取得された情報を
    issetやstrlenなどで、入力の有無を判定（再チェック）
    preg_matchで正規表現チェックをして、
    エラーが無い場合は確認画面の表示
    エラーがある場合は、入力フォームに戻りエラーの表示
    **********/

    /*-------
    フォーム入力画面から取得される（member_register.php)
    input name一覧

    name→ID名
    mail→メールアドレス
    pass→パスワード
    pass_conf→パスワード確認用
    tel→電話番号
    post→郵便番号
    sex→性別
    -------*/

    //エラーメッセージ
    $err=[];

    /*
    nameエラー判定
    ①IDが入力されているか。
    ②ID名が40文字以内か。
    ③正規表現チェック
    ④DBに重複するメールアドレスが無いか
    */
    if(!isset($_POST["id"]) || !strlen($_POST["id"])){
        $err["id"]="ユーザーIDを入力してください";
    }else if(strlen($_POST["id"])>6){
        $err["id"]="ユーザーIDは6文字以内で入力してください";
    }else{
            $id=htmlspecialchars($_POST['id'],ENT_QUOTES,'UTF-8');
    }

    /*
    ・パスワードのエラー判定
    ①パスワードが入力されているか。
    ②パスワードが6文字以上12文字以内か。（併せて正規表現チェック）
    ・パスワード確認のエラー判定
    ①上記のパスワードと一致しているか。
    */
    if(!isset($_POST["pass"]) || !strlen($_POST["pass"])){
        $err["pass"]="パスワードを入力してください";
    }else if(!preg_match("/^[a-zA-Z0-9]{6,12}$/",$_POST['pass']) && !preg_match("/[a-z]+/",$_POST['pass']) && !preg_match("/[A-Z]+/",$_POST['pass']) && !preg_match("/[0-9]+/",$_POST['pass'])){
        $err["pass"]='パスワードは英数字6文字以上１２文字以下にしてください。';
    }else{
        $pass=htmlspecialchars($_POST['pass'],ENT_QUOTES,'UTF-8');
        $pass_hash=password_hash($pass, PASSWORD_DEFAULT);
    }


    if(count($err)>0){
        //エラーがあった場合は戻す
        $_SESSION =$err;
        $pdo=null;
        header('Location: form.php');
        return;
    }else{
        //DBにフォームの内容を書き込む。
		$stmt = $pdo -> prepare("INSERT INTO `admin` (`id`,`pass`) VALUES (:id,:pass)");
		$stmt->bindParam(':id',$id);
		$stmt->bindParam(':pass',$pass_hash);
		$stmt->execute();
		$stmt=null;
        //データベース接続終了
        $pdo=null;
        $_SESSION['e-id']=$id;
    }
}
else{
    die("直接アクセス禁止です");
}
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
            <ul>
                <!-- エラー表記-->
                <?php if(count($err)>0) :?>
                <?php echo "<li id='error'><h1>登録エラー</li></li> "?> 
                <li><a href="./form.php">登録画面に戻る。</a></li>
                <?php foreach($err as $e) :?>
                <li><?php echo $e ?></li>
                <?php endforeach ?>
                <!--エラー表記END-->

                <!--登録完了-->
                <?php else: ?>
                <?php echo "<li><h1>登録完了</h1></li>"; ?> 
                <li>ユーザー登録が完了しました。</li>
                <?php echo "<li  style='background-color:black; color:white;'>Welcome!&nbsp;",$id,"さん！</li>" ?>
                <?php endif ?>
                <li><a href="./member_login.php">登録画面に戻る。</a></li>
            </ul>
        </div>
</body>
</html>