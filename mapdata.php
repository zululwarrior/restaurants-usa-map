<?php
// set up database connection, and load functions
include('db_connection.php');
include('db_functions.php');

//setup initial query
$query = "SELECT * FROM usa_restaurants";

/**this will alter the query depending on the request 
 * examples:
 * SELECT * FROM usa_restaurants WHERE categories AND nameiLIKE chicken
 * SELECT * FROM usa_restaurants WHERE name iLIKE chicken
 **/
if (isset($_POST['state'])){
	if($_POST['state'] == 'All'){
		if(isset($_POST['category'])){
			//add category filter to query if the request provides information
			$category = pg_escape_string($_POST['category']);
			$query .= " WHERE categories iLIKE '%$category%'";

			//add name filter to query if the request provides information
			if(isset($_POST['name'])){
				$name = pg_escape_string($_POST['name']);
				$query .= " AND name iLIKE '%$name%'";
			}
		} else {
			//add name filter to query if the request provides information
			if(isset($_POST['name'])){
				$name = pg_escape_string($_POST['name']);
				$query .= " WHERE name iLIKE '%$name%'";
			}
		}
		//get results
		$results = db_assocArrayAll($dbh,$query);
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
		$results = db_assocArrayAll($dbh,$query);
	}
} 

//return a json object
echo json_encode($results,JSON_NUMERIC_CHECK);
?>