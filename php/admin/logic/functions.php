<?php

/*
    関数（その他）
*/

/**
 * XSS対策：エスケープ処理
 * 
 * @param string $str 対象の文字列
 * @return string 処理された文字列
 */
function h($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

/**
 * CSRF対策
 * @param void
 * @return string $csrf_token
 */
function setToken() {
  // トークンを生成
  // フォームからそのトークンを送信
  // 送信後の画面でそのトークンを照会
  // トークンを削除
    $csrf_token = bin2hex(random_bytes(32));
    $_SESSION['csrf_token'] = $csrf_token;

    return $csrf_token;
}

//投稿と編集のページの区分け
function edit_flag($select_path){
  if(isset($_SERVER['HTTP_REFERER'])){
    $motourl = $_SERVER['HTTP_REFERER'];//前ページデータの取得
    $get_path=parse_url($motourl);//pathの取得
    //データ取得の条件
    //①飛んできたページがリストページであること
    //②GET値を取得していること。
    if($get_path["path"]==$select_path && isset($_GET["id"])){
      return true;
    }
  }
  return false;
}

?>