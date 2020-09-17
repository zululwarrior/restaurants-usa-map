<?php
// set up database connection, and load functions
include('db_connection.php');
include('db_functions.php');

$query = "SELECT LOWER(name) as name, count(*) FROM usa_restaurants ";

if (isset($_POST['state'])){
	if($_POST['state'] == 'All'){
		if(isset($_POST['category'])){
			$category = pg_escape_string($_POST['category']);
			$query .= " WHERE categories iLIKE '%$category%'";

			if(isset($_POST['name'])){
				$name = pg_escape_string($_POST['name']);
				$query .= " AND name iLIKE '%$name%'";
			}
		} else {
			if(isset($_POST['name'])){
				$name = pg_escape_string($_POST['name']);
				$query .= " AND name iLIKE '%$name%'";
			}
		}
	} else {
		$query .= " WHERE province = '$_POST[state]'";
		if(isset($_POST['category'])){
			$category = pg_escape_string($_POST['category']);
			$query .= " AND categories iLIKE '%$category%'";
		}
		if(isset($_POST['name'])){
			$name = pg_escape_string($_POST['name']);
			$query .= " AND name iLIKE '%$name%'";
		}
	}
} 

$query .= " GROUP BY name ORDER BY count DESC";
$results = db_assocArrayAll($dbh,$query);
echo json_encode($results,JSON_NUMERIC_CHECK);



?>