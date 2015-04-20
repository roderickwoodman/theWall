<?php
/*--------------------BEGINNING OF THE CONNECTION PROCESS------------------*/
//define constants for db_host, db_user, db_pass, and db_database
//adjust the values below to match your database settings
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'root'); //set DB_PASS as 'root' if you're using mac
define('DB_DATABASE', 'phpsql5_thewall'); //make sure to set your database
//connect to database host
$connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_DATABASE);

/*-------------------------END OF CONNECTION PROCESS!---------------------*/

//Make sure connection is good or die
if($connection->connect_errno)
{
	die("Failed to connect to MySQL: (" . $connection->connect_errno . ") " . $connection->connect_error);
}

/*----BELOW ARE THE CUSTOM FUNCTIONS WE HAVE PRE-WRITTEN YOU TO USE IN QUERYING YOUR DATABASES!----*/

//Use when expecting multiple or single result.
function fetch($query)
{
	$data = array();
	global $connection;

	// returns an object if valid query, false if not, and null if valid query but no results found
	$result = $connection->query($query);

	if($result !== false) {
		// if many results
		if($result->num_rows > 0) {
			foreach($result as $row){
				$data[] = $row;
			}

			//will return an associative array
			return $data;
		}
		
		/*
	  	 	If we get a single row from db, mysqli_fetch_assoc() function fetches the row
			and converts it to an associative array.
		*/
		return mysqli_fetch_assoc($result);			
	}

	//will return either null(no records found) or false(query is incorrect).
	return $result;
}

//use to run INSERT/DELETE/UPDATE, queries that don't return a value
function run_mysql_query($query)
{
	global $connection;

	$result = $connection->query($query);

	//Check if query is an 'insert' query
	if(preg_match("/insert/i", $query))
	{
		return $connection->insert_id;
	}

	//return boolean (true/false) if query update or delete / 
	return $result;
}
?>