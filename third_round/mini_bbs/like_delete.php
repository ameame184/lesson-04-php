<?php
session_start();
require('dbconnect.php');

if (isset($_SESSION['id'])){
	$post_id = $_REQUEST['id']; // いいねを削除しようとしているメッセージのID
	$user_id = $_SESSION['login_id']; // いいねを削除しようとしてるメンバーのID
	// いいね済かどうかのチェック
	$likes = $db->prepare('SELECT COUNT(*) AS cnt FROM likes WHERE liked_post_id=? AND clicked_member_id=?');
	$likes->execute(array(
		$post_id,
		$user_id));
	$like = $likes->fetch();

	// いいねが空でないか確認
	if (!empty($like['cnt'])) {
		// いいねしようとしているメンバーのIDが空でない場合、いいねを削除する
		if (!empty($user_id)) {
			$like = $db->prepare('DELETE FROM likes WHERE liked_post_id=? AND clicked_member_id=?');
			$like->execute(array(
				$post_id,
				$user_id
			));
		}
	}
}

// $now = $_SERVER['HTTP_REFERER'];
// header('Location: ' .$now);
// exit();

header('Location: index.php');
exit();


?>