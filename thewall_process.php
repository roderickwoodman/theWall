<?php
if(!isset($_SESSION)) {
	session_start();
}
// var_dump($_SESSION);
require('thewall_connection.php');
// var_dump($_POST);
if((isset($_POST['action']) && $_POST['action'] == 'register')) {
	register_user($_POST);
}
else if((isset($_POST['action']) && $_POST['action'] == 'login')) {
	login_user($_POST);
}
else if((isset($_POST['action']) && $_POST['action'] == 'logout')) {
	logout_user();
}
else if((isset($_POST['action']) && $_POST['action'] == 'post_message')) {
	post_message($_POST);
}
else if((isset($_POST['action']) && $_POST['action'] == 'post_comment')) {
	post_comment($_POST);
}

function register_user($post) {
	//-------------- begin validation checks --------------
	$_SESSION['errors'] = array();

	if(empty($post['first_name'])) {
		$_SESSION['errors'][] = "first name can't be blank!";
	}
	if(empty($post['last_name'])) {
		$_SESSION['errors'][] = "last name can't be blank!";
	}
	if(empty($post['password'])) {
		$_SESSION['errors'][] = "password field is required!";
	}
	if($post['password'] !== $post['confirm_password']) {
		$_SESSION['errors'][] = "passwords must match!";
	}
	if(filter_var(!$post['email'], FILTER_VALIDATE_EMAIL)) {
		$_SESSION['errors'][] = "please use a valid email address!";
	}
	//--------------- end validation checks ---------------

	if(count($_SESSION['errors']) > 0) {
		header("Location: thewall_index.php");
		die();
	}
	else {
		$query = "INSERT INTO users (first_name, last_name, password, email, created_at, updated_at)
				  VALUES ('{$post['first_name']}', '{$post['last_name']}', '{$post['password']}', '{$post['email']}',
				  	NOW(), NOW())";
		run_mysql_query($query);
		$_SESSION['success_message'] = "User successfully created!";
		header("Location: thewall_index.php");
		die();
	}
}

function login_user($post) {
	$query = "SELECT * FROM users WHERE users.password = '{$post['password']}'
	          AND users.email = '{$post['email']}'";
	$user = fetch($query);
	if(count($user) > 0) {
		$_SESSION['user_id'] = $user[0]['id'];
		$_SESSION['first_name'] = $user[0]['first_name'];
		$_SESSION['last_name'] = $user[0]['last_name'];
		$_SESSION['logged_in'] = TRUE;
		header("Location: thewall_wall.php");
		die();
	}
	else {
		$_SESSION['errors'][] = "can't find a user with those credentials";
		header("Location: thewall_index.php");
		die();
	}
}

function logout_user() {
	session_destroy();
	header("Location: thewall_index.php");
	die();
}

function add_header_region() {

	$style_header = "min-height:50px; background-color:rgb(216,219,212); border-bottom: 5px solid black; margin-bottom:30px; padding:10px;";
	$style_header_title = "width:30%; text-align:left; display:inline-block; padding: 15px 10px 5px 10px; font-weight:700; font-size:1.5em";
	$style_header_user = "width: 55%; text-align:right; vertical-align:bottom; display: inline-block; padding: 0 15px 3px 5px;";
	$style_header_form = "display:inline-block; width: 10%;";

	echo '<div id="header" style="'.$style_header.'">';
		echo '<p id="header_title" style="'.$style_header_title.'">CodingDojo Wall</p>';
		if ( isset($_SESSION['logged_in']) && ($_SESSION['logged_in'] == 'TRUE') ) {
			echo '<p id="header_user" style="'.$style_header_user.'">Welcome, '.$_SESSION['first_name'].'</p>';
			echo '<form action="thewall_process.php" action="thewall_process.php" method="post" style="'.$style_header_form.'">';
				echo '<input type="hidden" name="action" value="logout">';
				echo '<input type="submit" value="log out">';
			echo '</form>';
		}
	echo '</div>';
}

function fetch_all_messages () {
	$query = "SELECT messages.id, first_name, last_name, message, messages.created_at FROM messages 
			LEFT JOIN users ON messages.user_id = users.id 
			ORDER BY created_at DESC";
	return fetch($query);
}

function fetch_all_comments ($message_id) {

	$query = "SELECT messages.id, first_name, last_name, comment, comments.created_at FROM comments LEFT JOIN messages ON comments.message_id = messages.id LEFT JOIN users ON comments.user_id = users.id WHERE messages.id = $message_id ORDER BY created_at ASC";
	return fetch($query);
}

function post_message($post) {
	$query = "INSERT INTO messages (message, user_id, created_at, updated_at)
			  VALUES ('{$post['post_box']}', '{$_SESSION['user_id']}', NOW(), NOW())";
	run_mysql_query($query);
	header("location: thewall_wall.php");
}

function add_comment_box ($message_id) {
	echo '<form class="comment_region indent" action="thewall_process.php" method="post">';
		echo '<input type="hidden" name="action" value="post_comment">';
		echo '<input type="hidden" name="message_id" value="'.$message_id.'">';
		echo '<label for="comment_box">Post a comment</label>';
		echo '<textarea cols=100 rows=5 name="comment_box" cols="30" rows="10"></textarea>';
		echo '<input type="submit" value="Post a comment">';
	echo '</form>';
}

function post_comment($post) {
	$query = "INSERT INTO comments (comment, user_id, message_id, created_at, updated_at)
			  VALUES ('{$post['comment_box']}', '{$_SESSION['user_id']}', '{$post['message_id']}', NOW(), NOW())";
	run_mysql_query($query);
	header("location: thewall_wall.php");
}

?>
