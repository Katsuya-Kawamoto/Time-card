<?php

function connect()
{
    $host = 'mysql57.green-green.sakura.ne.jp';
    $db   = 'green-green_db';
    $user = 'green-green';//ID
    $pass = 'love2000';//PASS

    $dsn =    $dsn = "mysql:host=$host;dbname=$db;charset=utf8";
    try {
        $pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
        return $pdo;
    }catch (PDOException $e) {
        echo '接続失敗です！'. $e->getMessage();
        exit();
    }

}
$id="123456";
$pass="123456";

$pass_hash=password_hash($pass, PASSWORD_DEFAULT);

$sql="INSERT INTO `admin` (`id`,`pass`) VALUES (:id,:pass)";
$stmt = connect() -> prepare($sql);
$stmt->bindParam(':id',$id);
$stmt->execute();
$stmt=null;
?>
出力完了