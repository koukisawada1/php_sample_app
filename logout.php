<?php
session_start();

$_SESSION = array();
if (ini_set('session.use_cookies')) {
    // クッキーの情報を削除するための処理
    $params = session_get_cookie_params();
    // クッキーの有効期限を切ることで情報を削除する
    setcookie(session_name() . '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
}
session_destroy();

setcookie('email', '', time()-3600);

header('Location: login.php');
exit();
?>