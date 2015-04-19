<?php
if(!isset($_SESSION) || is_null($_SESSION)) {
	session_start();
}
require_once('thewall_connection.php');

if(isset($_POST['action']) && $_POST['action'] == 'post_message') {
	post_message($_POST);
}
else if(isset($_POST['action']) && $_POST['action'] == 'post_comment') {
	post_comment($_POST);
}
// else { // malicious navigation or someone trying to log off
// 	session_destroy();
// 	header("Location: thewall_index.php");
// 	die();
// }

function fetch_all_messages () {
	$query = "SELECT messages.id, first_name, last_name, message, messages.created_at FROM messages 
			LEFT JOIN users ON messages.user_id = users.id 
			ORDER BY created_at ASC";
	return fetch($query);
}

function fetch_all_comments ($message_id) {

	$query = "SELECT messages.id, first_name, last_name, comment, comments.created_at FROM comments LEFT JOIN messages ON comments.message_id = messages.id LEFT JOIN users ON messages.user_id = users.id WHERE messages.id = $message_id";
	return fetch($query);
}

function post_message($post) {
	$query = "INSERT INTO messages (message, user_id, created_at, updated_at)
			  VALUES ('{$post['post_box']}', '{$_SESSION['user_id']}', NOW(), NOW())";
	run_mysql_query($query);
	header("location: thewall_wall.php");
}

function add_comment_box () {
	echo '<form action="thewall_process.php" class="comment_region" method="post">';
		echo '<input type="hidden" name="action" value="post_comment">';
		echo '<label for="comment_box">Post a comment</label>';
		echo '<textarea cols=60 rows=3 name="comment_box" cols="30" rows="10"></textarea>';
		echo '<input type="submit" value="Post a comment">';
	echo '</form>';
}

function post_comment($post, $message_id) {
	$query = "INSERT INTO comments (comment, user_id, message_id, created_at, updated_at)
			  VALUES ('{$post['comment_box']}', '{$_SESSION['user_id']}', $message_id, NOW(), NOW())";
	var_dump($query);
	die();
	run_mysql_query($query);
	header("location: thewall_wall.php");
}

?>
