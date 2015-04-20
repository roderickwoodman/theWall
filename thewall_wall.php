<?php
session_start();
include('thewall_process.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>PHP & MYSQL - The Wall</title>
	<style type="text/css">
		* {
			margin: 0;
			padding: 0;
			font-family: sans-serif;
		}
		ul {
			list-style: none;
		}
		label {
			display: block;
		}
		textarea {
			display: block;
		}
		li {
			border-top: 1px solid black;
			width: 500px;
			padding: 10px;
		}
		form {
			margin: 10px 0;
		}
		.error {
			color: red;
		}
		.message_meta, .comment_meta {
			font-weight: 700;
			margin: 10px 0;
		}
		.message_text, .comment_text {
			margin: 10px 0;
		}
		input[type="submit"] {
			display: inline-block;
			border: 2px solid black;
			border-radius: 5px;		
			font-weight: 700;
			margin-top: 5px;
			padding: 5px 10px;	
		}
		#header {
			background-color: rgb(216,219,212);
			border-bottom: 5px solid black;
			margin-bottom: 30px;
			padding: 10px;
		}
		#header_title {
			width: 30%;
			text-align: left;
			display: inline-block;
			padding: 5px 10px;
			font-weight: 700;
			font-size: 1.5em;
		}
		#header_user {
			width: 55%;
			text-align: right;
			vertical-align: bottom;
			display: inline-block;
			padding: 0 5px 12px 5px;
		}
		#header form {
			display: inline-block;
			width: 10%;
			text-align:;
		}
		#nonheader {
			padding: 20px;
		}
		.header_region input[type="submit"] {
			/*width: 35%;*/
		}
		.message_region input[type="submit"] {
			background-color: rgb(36,140,216);
		}
		.comment_region input[type="submit"] {
			background-color: rgb(186,226,88);
			color: black;
		}
		.indent {
			margin-left: 50px;
		}
	</style>
</head>
<body>
	<div id="wrapper">
		<div id="header">
			<p id="header_title">CodingDojo Wall</p>
			<p id="header_user">Welcome, <?= $_SESSION['first_name'] ?></p>
			<form action="thewall_process.php" action="thewall_process.php" method="post">
				<input type="hidden" name="action" value="logout">
				<input type="submit" value="log out">
			</form>
		</div>
		<div id="nonheader">
			<form class="message_region" action="thewall_process.php" method="post">
				<input type="hidden" name="action" value="post_message">
				<label for="post_box">Post a message</label>
				<textarea cols=60 rows=3 name="post_box" cols="30" rows="10"></textarea>
				<input type="submit" value="Post a message">
			</form>
			<ul>
				<?php 
				$msg_records = fetch_all_messages();

				if (count($msg_records) == 0) {
					echo "<li class='error'>ERROR: no msg records found</li>";
				}
				else if (count($msg_records) == 1) {
					foreach ($msg_records as $message) {
						echo "<li>";
						echo "<p class='message_meta'>{$msg_records['first_name']} {$msg_records['last_name']} - {$msg_records['created_at']}</p>";
						echo "<p class='message_text'>{$msg_records['message']}";
						echo "</li>";
						// FIXME: THIS CODE WILL NOT CHECK COMMENTS IF MESSAGES ARE ONE?
					}
				}
				else if (count($msg_records) >= 2) {
					foreach ($msg_records as $msg_key => $message) {
						echo "<li>";
						echo "<p class='message_meta'>{$msg_records[$msg_key]['first_name']} {$msg_records[$msg_key]['last_name']} - {$msg_records[$msg_key]['created_at']}</p>";
						echo "<p class='message_text'>{$msg_records[$msg_key]['message']}";
						$com_records = fetch_all_comments($message['id']);
						// echo "<h1>ALL COMMENTS FETCHED</h1>";
						// var_dump($com_records);
						// die();
						// NO MATCHES
						if (is_null($com_records)) {
							continue;
						}
						echo "<ul>";
						// ONE MATCH => 1-D associative array
						if (isset($com_records['id'])) {
							foreach ($com_records as $comment) {
								echo "ONE COMMENT RECORD!!<br>";
								// die();
								echo "<li class='indent'>";
								echo "<p class='comment_meta'>{$com_records['first_name']} {$com_records['last_name']} - {$com_records['created_at']}</p>";
								echo "<p class='comment_text'>{$com_records['comment']}";
								echo "</li>";
							}
						}
						// TWO+ MATCHES => array of associative arrays
						else {
							echo "TWO+ COMMENT RECORDS!!<br>";
							// die();
							// var_dump($com_records);
							foreach ($com_records as $com_key => $comment) {
								// echo "<h1>ALL COMMENTS for com_key=".$com_key."</h1>";
								// var_dump($com_records);
								// die();
								echo "<li class='indent'>";
								echo "<p class='comment_meta'>{$com_records[$com_key]['first_name']} {$com_records[$com_key]['last_name']} - {$com_records[$com_key]['created_at']}</p>";
								echo "<p class='comment_text'>{$com_records[$com_key]['comment']}";
								echo "</li>";
							}
						}
						echo "</ul>";
						add_comment_box($msg_records[$msg_key]['id']);
						echo "</li>";
					} // end foreach messages
				}
				?>
			</ul>
			</div>
	</div>
</body>
</html>