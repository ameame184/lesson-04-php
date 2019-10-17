<?php
require('../dbconnect.php');
session_start();

if (!empty($_POST)) {
	// エラー項目の確認
	if ($_POST['name'] == '') {
		$error['name'] = 'blank';
	}
	if ($_POST['email'] == '') {
		$error['email'] = 'blank';
	}
	if (strlen($_POST['password']) < 4) {
		$error['password'] = 'length';
	}
	if ($_POST['password'] == '') {
		$error['password'] = 'blank';
	}
	$fileName = $_FILES['image']['name'];
	print_r($fileName);
	if (!empty($fileName)) {
		$ext = substr($fileName, -3);
		print_r($ext);
		if ($ext != 'jpg' && $ext != 'gif') {
			$error['image'] = 'type';
		}
	}

	//重複アカウントのチェック
	if (empty($error)) {
		$member = $db->prepare('SELECT COUNT(*) AS cnt FROM members WHERE email=?');
		$member->execute(array($_POST['email']));
		$record = $member->fetch();
		if ($record['cnt'] > 0) {
			$error['email'] = 'duplicate';
		}
	}

	if (empty($error)) {
		// 画像をアップロードする
		$image = date('YmdHis') . $_FILES['image']['name'];
		print_r($image);
		move_uploaded_file($_FILES['image']['tmp_name'], '../member_picture/' . $image);
		print_r($_FILES);

		$_SESSION['join'] = $_POST;
		$_SESSION['join']['image'] = $image;
		header('Location: check.php');
		exit();
	}
}

// 書き直し
if ($_REQUEST['action'] == 'rewrite') {  //「action」が「rewrite」だったら(index.php?action=rewrite)
	$_POST = $_SESSION['join']; //$_POSTに入れることで書き直し時に入力内容が空の状態を防ぐ
	$error['rewrite'] = true; //ファイルのアップロードは再現できないためこの変数を利用
}

//htmlspecialcharsのショートカット
function h($value) {
  return htmlspecialchars($value, ENT_QUOTES);
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>ひとこと掲示板</title>

	<link rel="stylesheet" href="../style.css">
</head>

<body>
<div id="wrap">
  <div id="head">
    <h1>会員登録</h1>
  </div>
  <div id="content">
		<p>次のフォームに必要事項をご記入ください。</p>
			<form action="" method="post" enctype="multipart/form-data">
			<dl>
				<dt>ニックネーム<span class="required">必須</span></dt>
				<dd><input type="text" name="name" size="35" maxlength="255" value="<?php echo h($_POST['name']); ?>"></dd>
				<?php if($error['name'] == 'blank'): ?>
				<p class="error">* ニックネームを入力してください</p>
				<?php endif; ?>
				<dt>メールアドレス<span class="required">必須</span></dt>
				<dd><input type="text" name="email" size="35" maxlength="255" value="<?php echo h($_POST['email']); ?>"></dd>
				<?php if($error['email'] == 'blank'): ?>
				<p class="error">* メールアドレスを入力してください</p>
				<?php endif; ?>
				<?php if($error['email'] == 'duplicate'): ?>
				<p class="error">* メールアドレスはすでに登録されています</p>
				<?php endif; ?>
				<dt>パスワード<span class="required">必須</span></dt>
				<dd><input type="password" name="password" size="10" maxlength="20" value="<?php echo h($_POST['password']); ?>"></dd>
				<?php if($error['password'] == 'blank'): ?>
				<p class="error">* パスワードを入力してください</p>
				<?php endif; ?>
				<?php if($error['password'] == 'length'): ?>
				<p class="error">* パスワードは4文字以上で入力してください</p>
				<?php endif; ?>
				<dt>写真など</dt>
				<dd><input type="file" name="image" size="35"></dd>
				<?php if($error['image'] == 'type'): ?>
				<p class="error">* 写真などは「.gif」または「.jpg」の画像を指定してください</p>
				<?php endif; ?>
				<?php if(!empty($error)): ?>
				<p>* 恐れ入りますが、画像を改めて指定してください</p>
				<?php endif; ?>
			</dl>
			<div><input type="submit" value="入力内容を確認する"></div>
		</form>
  </div>

</div>
</body>
</html>
