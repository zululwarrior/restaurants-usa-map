<!DOCTYPE html>
<html>
	<head>
		<title>Fast food restaurants in the USA</title>
		<meta charset="UTF-8">

		<!-- leafletjs -->
		<link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css"
			integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ=="
			crossorigin=""/>
		<script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js"
			integrity="sha512-gZwIG9x3wUXg2hdXF6+rVkLF/0Vi9U8D2Ntg4Ga5I5BZpVkVxlJWbSQtXPSiUTtC0TjtGOmxa1AJPuV0CPthew=="
			crossorigin=""></script>

		<!-- styling -->
		<link rel="stylesheet" type="text/css" href="style.css">

		<!-- jquery -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
		
		<!-- php file to get states -->
		<?php include("./states.php");?>

		<!-- javascript file for the logic of the map -->
		<script src="./maplogic.js"></script>
	</head>
	
	<!-- body of the website -- has some css -->
	<body onload="initialise()">
		<h1 style = "text-align:center">10000 Fast food restaurants in the USA</h1>
		<div id="outer">
			<div id="mapid"></div>
			<div id="extra" style="display:flex; flex-direction:column;">
				<div id="filters" >
					<p>States</p>
					<select name='filter_state' id='filter_state'>
						<option value='All'>All</option>
						<!-- get all the states and put it in the options input -->
						<?php  foreach($states as $s){
							echo "<option value=$s[province]>$s[province]</option>";
						}?>
					</select>
					<input type="search" name="filter_name" id="filter_name" class="filter_typing" placeholder="Restaurant Name">
					<input type="search" name="filter_category" id="filter_category" class="filter_typing" placeholder="Category">
					<p id="result">Number of results: 500</p>
					<div class="slidecontainer" >
						<input type="range" min="0" max="10000" value="500" id="range_slider" class="slider" >
					</div>
				</div>
				<div style="display:flex; flex-direction:column; flex:1; padding-top: 30px; padding-left:20px;">
					<div style="border:2px solid white; flex:1;   border-radius:15px; border-color:#ababab;">
						<h1 style="text-align:center">Top 5 restaurants<h1>
						<div id = "query_result" style="padding-left: 15px; text-align:center;font-size:0.9em"></div>
					</div>
					<div id = "returned_result">Query returned 0 results. Currently showing 0 results.</div>
				</div>
			</div>
		</div>
		<div style="text-align:center;">
			<h1>This web application is used to display up to 10000 restaurants currently located in America</h1>
			<h1>Note: Please wait until the map fully loads before using it!</h1>
		</div>
	</body>
</html>