<!doctype html>
<html lang="ja">
<head>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="../css/style.css">

<title>よくわかるPHPの教科書</title>
</head>
<body>
<header>
<h1 class="font-weight-normal">よくわかるPHPの教科書</h1>    
</header>

<main>
<h2>Practice</h2>
<pre>
<?php
require('dbconnect.php');

  $statememt = $db->prepare('INSERT INTO memos SET memo=?, created_at=NOW()');
  $statememt->bindParam(1, $_POST['memo']);
  $statememt->execute();
  echo 'メッセージが登録されました';
?>
</pre>
</main>
</body>
</html>