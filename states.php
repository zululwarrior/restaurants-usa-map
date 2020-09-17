<?php
// set up database connection, and load functions
include('db_connection.php');
include('db_functions.php');

//setup queries, get query result and store it in a variable
$query = "SELECT DISTINCT province FROM usa_restaurants ORDER BY province ASC";
$states = db_assocArrayAll($dbh, $query);
?>