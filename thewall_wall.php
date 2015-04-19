<?php
// die();
// if(!isset($_SESSION) || is_null($_SESSION)) {
// 	session_start();
// }
// require_once('thewall_connection.php');
require_once('thewall_process.php');
var_dump($_SESSION);
if (!isset($_SESSION['user_id'])) {
	$_SESSION['user_id'] = 1;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>PHP & MYSQL - The Wall</title>
	<style type="text/css">
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
			border: 1px solid black;
			width: 500px;
		}
		.error {
			color: red;
		}
		.message_meta {
		}
		.message_text {
		}
	</style>
</head>
<body>
	<div id="wrapper">
		<div id="header">
			<h2>CodingDojo Wall</h2>
		</div>
		<form action="thewall_process.php" class="post_region" method="post">
			<input type="hidden" name="action" value="post_message">
			<label for="post_box">Post a message</label>
			<textarea cols=60 rows=3 name="post_box" cols="30" rows="10"></textarea>
			<input type="submit" value="Post a message">
		</form>
		<ul id="message_region">
			<?php 
			$msg_records = fetch_all_messages();

			if (count($msg_records) == 0) {
				echo "<li class='error'>ERROR: no msg records found</li>";
			}
			else if (count($msg_records) == 1) {
				foreach ($msg_records as $key1 => $message) {
					echo "<li>";
					echo "<p class='message_meta'>{$msg_records[$key1]['first_name']} {$msg_records[$key1]['last_name']} - {$msg_records[$key1]['created_at']}</p>";
					echo "<p class='message_text'>{$msg_records[$key1]['message']}";
					echo "</li>";
				}
			}
			else if (count($msg_records) >= 2) {
				foreach ($msg_records as $msg_key => $message) {
					echo "<li>";
					echo "<p class='message_meta'>{$msg_records[$msg_key]['first_name']} {$msg_records[$msg_key]['last_name']} - {$msg_records[$msg_key]['created_at']}</p>";
					echo "<p class='message_text'>{$msg_records[$msg_key]['message']}";
					$com_records = fetch_all_comments($message['id']);
					echo "<ul>";
					if (count($com_records) == 0) {
						echo "<li class='error'>ERROR: no com records found</li>";
					}
					else if (count($com_records) == 1) {
						foreach ($com_records as $key2 => $comment) {
								echo "<li>";
								$_SESSION['firstnamedebug_comrecords'] = $com_records;
								$_SESSION['firstnamedebug_comkey'] = $key2;
								echo "<p class='comment_meta'>{$com_records[$key2]['first_name']} {$com_records[$key2]['last_name']} - {$com_records[$key2]['created_at']}</p>";
								echo "<p class='comment_text'>{$com_records[$key2]['comment']}";
								echo "</li>";
							}
					}
					else if (count($com_records) >= 2) {

						foreach ($com_records as $com_key => $comment) {
							echo "<li>";
							$_SESSION['firstnamedebug_comrecords'] = $com_records;
							$_SESSION['firstnamedebug_comkey'] = $com_key;
							echo "<p class='comment_meta'>{$com_records[$com_key]['first_name']} {$com_records[$com_key]['last_name']} - {$com_records[$com_key]['created_at']}</p>";
							echo "<p class='comment_text'>{$com_records[$com_key]['comment']}";
							echo "</li>";
						}
					}
					echo "</ul>";
					add_comment_box();
					echo "</li>";
				}
			}
			?>
		</ul>

	</div>
</body>
</html>