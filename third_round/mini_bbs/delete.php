<?php
session_start();
require('dbconnect.php');
require('htmlspecialchars.php');

if (isset($_SESSION['id'])) {
  $id = $_REQUEST['id'];

  // 投稿を検査する
  $messages = $db->prepare('SELECT * FROM posts WHERE id=?');
  $messages->execute(array($id));
  $message = $messages->fetch();

  if ($message['member_id'] == $_SESSION['id']) {
    // 削除する
    $del = $db->prepare('DELETE FROM posts WHERE id=?');
    $del->execute(array($id));
  }
}

header('Location: index.php');
?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>ひとこと掲示板</title>

	<link rel="stylesheet" href="style.css">
</head>

<body>
<div id="wrap">
  <div id="head">
    <h1>ひとこと掲示板</h1>
  </div>
  <div id="content">
    <p>&laquo;<a href="index.php">一覧にもどる</a></p>
    <?php
    if($post = $posts->fetch()):
    ?>
    <div class="msg">
      <img src="member_picture/<?php echo h($post['picture']); ?>" width="48" height="48" alt="<?php echo h($post['name']); ?>">
      <p><?php echo h($post['message']); ?><span class="name">(<?php echo h($post['name']); ?>)</span></p>
      <p class="day"><?php echo h($post['created']); ?></p>
    </div>
    <?php
    else:
    ?>
      <p>その投稿は削除されたか、URLが間違えています</p>
    <?php
    endif; 
    ?>
  </div>
</div>
</body>
</html>
