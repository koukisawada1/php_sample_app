<?php
// 確認ページ
session_start();
// 入力情報が正しく入力されていないときに確認ページに遷移した場合の処理
if (!isset($_SESSION['join'])) {
	// $_SESSION['join']に値が保存されていなかった場合、強制的にトップページに遷移させる
	header('Location: index.php');
	exit();
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>会員登録</title>

	<link rel="stylesheet" href="../style.css" />
</head>
<body>
<div id="wrap">
<div id="head">
<h1>会員登録</h1>
</div>

<div id="content">
<p>記入した内容を確認して、「登録する」ボタンをクリックしてください</p>
<form action="" method="post">
	<input type="hidden" name="action" value="submit" />
	<dl>
		<dt>ニックネーム</dt>
		<dd>
		<?php print(htmlspecialchars($_SESSION['join']['name'], ENT_QUOTES)); ?>
        </dd>
		<dt>メールアドレス</dt>
		<?php print(htmlspecialchars($_SESSION['join']['email'],ENT_QUOTES)); ?>
		<dd>
        </dd>
		<dt>パスワード</dt>
		<dd>
		【表示されません】
		</dd>
		<dt>写真など</dt>
		<dd>
		<?php if ($_SESSION['join']['image'] !== ''): ?>
			<img src="../member_picture/<?php print(htmlspecialchars($_SESSION['join']['image'], ENT_QUOTES)); ?>">
		<?php endif; ?>
		</dd>
	</dl>
	<div><a href="index.php?action=rewrite">&laquo;&nbsp;書き直す</a> | <input type="submit" value="登録する" /></div>
</form>
</div>

</div>
</body>
</html>
