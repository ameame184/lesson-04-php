<?php
try {
  $db = new PDO('mysql:dbname=mydb;host=localhost;charaset=utf8', 'root', 'root');
} catch(PDOException $e) {
  echo 'DB接続エラー: ' . $e->getMessage();
}
?>