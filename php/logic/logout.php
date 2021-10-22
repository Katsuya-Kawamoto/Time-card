<?php
	session_start();
	//セッション情報の削除処理
    $_SESSION = array();
	if (isset($_COOKIE[session_name()])) {
		setcookie(session_name(), '', time()-1000);
	}
	session_destroy();

    //ログイン画面にログアウト処理情報の表示
    session_start();
    $_SESSION["log_out"]='ログアウトしました。';
    header('Location: ../../administrator.php');
    return;
?>