<?php
// 入力内容をセッションに保存する
session_start();
require('../dbconnect.php');

if (!empty($_POST)) {
	if ($_POST['name'] === '') {
		$error['name'] = 'blank';
	}
	if ($_POST['email'] === '') {
		$error['email'] = 'blank';
	}
	// パスワードの文字数の確認 strlen指定された文字数の文字の長さを返してくれる
	if (strlen($_POST['password']) < 4) {
		$error['password'] = 'length';
	}
	if ($_POST['password'] === '') {
		$error['password'] = 'blank';
	}
	$fileName = $_FILES['image']['name'];
	if (!empty($fileName)) {
		// ファイルの後ろ３文字を切り取る(ファイルの拡張子を確認する)
		$ext = substr($fileName, -3);
		if ($ext != 'jpg' && $ext != 'gif' && $ext != 'png') {
			$error['image'] = 'type';
		}
	}
	// アカウントの重複登録の確認
	if(empty($error)) {
		$member = $db->prepare('SELECT COUNT(*) AS cnt FROM members WHERE email=?');
		$member->execute(array($_POST['email']));
		$recode = $member->fetch();
		if ($recode['cnt'] > 0) {
			$error['email'] = 'deplicate';
		}
	}

	// エラーがないときにcheck.phpにジャンプする
	if (empty($error)) {
		// アップロードするファイル名を作成　
		$image = date('ymdHis') . $_FILES['image']['name'];
		move_uploaded_file($_FILES['image']['tmp_name'],'../member_picture/' . $image);
		// エラーがないことを確認したら入力内容をセッションに保存する
		$_SESSION['join'] = $_POST;
		$_SESSION['join']['image'] = $image;
		header('Location: check.php');
		exit();
	}
	error_reporting(E_ALL & ~E_NOTICE);
}
// 確認ページで書き直すを選択した場合の処理(入力データの保持)
if ($_REQUEST['action'] == 'rewrite' && isset($_SESSION['join'])) {
	$_POST = $_SESSION['join'];
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
<p>次のフォームに必要事項をご記入ください。</p>
<!-- enctype="multipart/form-data"　ファイルのアップロード時に指定　inputタグにinput type="file"を指定する必要がある -->
<form action="" method="post" enctype="multipart/form-data">
	<dl>
		<dt>ニックネーム<span class="required">必須</span></dt>
		<dd>
        	<input type="text" name="name" size="35" maxlength="255" value="<?php print(htmlspecialchars($_POST['name'], ENT_QUOTES)); ?>" />
			<!-- エラーメッセージ -->
			<?php if ($error['name'] === 'blank'): ?>
				<p class="error">※ニックネームを入力してください</p>
			<?php endif; ?>
		</dd>
		<dt>メールアドレス<span class="required">必須</span></dt>
		<dd>
        	<input type="text" name="email" size="35" maxlength="255" value="<?php print(htmlspecialchars($_POST['email'], ENT_QUOTES)); ?>" />
			<?php if ($error['email'] === 'blank'): ?>
				<p class="error">※メールアドレスを入力してください</p>
			<?php endif; ?>
			<?php if ($error['email'] === 'deplicate'): ?>
				<p class="error">※指定されたメールアドレスは既に登録されています</p>
			<?php endif; ?>

		<dt>パスワード<span class="required">必須</span></dt>
		<dd>
        	<input type="password" name="password" size="10" maxlength="20" value="<?php print(htmlspecialchars($_POST['password'], ENT_QUOTES)); ?>" />
			<?php if ($error['password'] === 'length'): ?>
				<p class="error">※パスワードは４文字以上で入力してください</p>
			<?php endif; ?>
			<?php if ($error['password'] === 'blank'): ?>
				<p class="error">※パスワードを入力してください</p>
			<?php endif; ?>


        </dd>
		<dt>写真など</dt>
		<dd>
        	<input type="file" name="image" size="35" value="test"  />
			<?php if ($error['image'] === 'type'): ?>
				<p class="error">※写真などは『.gif』または『.jpg』『.png』の画像を指定してください。</p>
			<?php endif; ?>
			<?php if (empty($error)): ?>
				<p class="error">恐れ入りますが、画像を改めて指定してください。</p>
			<?php endif; ?>

        </dd>
	</dl>
	<div><input type="submit" value="入力内容を確認する" /></div>
</form>
</div>
</body>
</html>
