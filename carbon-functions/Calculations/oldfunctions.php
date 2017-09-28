<?php
	/**
	 * Template Name: functions.php
	 *
	 * Author: Sy Nishigata
	 * Date: October 6, 2016
	 */

	/** 
	 *	This function calculates and stores all co2 emissions for each item by calling calculate_yearly 
	 *	for each item that the user has inputted at least one record.  It first gets the oldest record
	 *  and passes it to the calculate_yearly function with the optional param graph = true.  This makes
	 *  the calculate_yearly function store every emission into an array to be plotted onto the graph
	 */
	function monthly_progress_graph(){
	/* START: Connecting to database */
		/* Establish the connection with SQL database */
		$hostname = "localhost";
		$database = "carbon_neutrality";
		$username = "carbon_challenge";
		$password = "changeme";
		$conn = mysqli_connect($hostname, $username, $password) or die('Can\'t create connection: '.mysql_error());
		mysqli_select_db($conn, $database) or die('Can\'t access specified db: '.mysql_error());
		
	/* START: Storage into arrays: $recent_records $oldest_records*/
		/* Variables */
		$id_user = 1; 
		$tables = array("food", "home_electric", "home_fuel", "home_gas", "home_water", "waste", 
						"travel_bus", "travel_car", "travel_motorcycle", "travel_plane", "travel_train");
		
		/* Fetch the most recent and the oldest emission records for each table and store them in an array */
		for ($i = 0; $i < count($tables); $i++){
			//if the table is not a travel table, then store the most recent and the oldest records from that table
			if(strpos($tables[$i], "travel") === false ){
				$old = mysqli_query($conn, "SELECT * FROM user_emissions_" . $tables[$i] . " WHERE id_user='$id_user' ORDER BY date_data_entry ASC");
				$old_records[$tables[$i]] = mysqli_fetch_array($old, MYSQLI_ASSOC);
			}
			//if the table is a travel table, then store records that are distinct by their respective item distinctions
			else{
				if($tables[$i] == "travel_car"){
					$old = mysqli_query($conn, "SELECT * FROM (SELECT * FROM user_emissions_" . $tables[$i] . " WHERE id_user='$id_user' ORDER BY date_data_entry ASC) AS tbl GROUP BY tbl.id_car_model");
					while($row = mysqli_fetch_array($old, MYSQLI_ASSOC)) {
						$old_records[$tables[$i] . ":" . $row['id_car_model']] = $row;
					}
				}
				elseif($tables[$i] == "travel_motorcycle"){
					$old = mysqli_query($conn, "SELECT * FROM (SELECT * FROM user_emissions_" . $tables[$i] . " WHERE id_user='$id_user' ORDER BY date_data_entry ASC) AS tbl GROUP BY tbl.id_motorcycle");
					while($row = mysqli_fetch_array($old, MYSQLI_ASSOC)) {
						$old_records[$tables[$i] . ":" . $row['id_motorcycle']] = $row;
					}
				}
				else{
					$old = mysqli_query($conn, "SELECT * FROM (SELECT * FROM user_emissions_" . $tables[$i] . " WHERE id_user='$id_user' ORDER BY date_data_entry ASC) AS tbl GROUP BY tbl.route_from, tbl.route_to");
					while($row = mysqli_fetch_array($old, MYSQLI_ASSOC)) {
						$old_records[$tables[$i] . ":" . $row['route_from'] . $row['route_to']] = $row;
					}
				}
			}
		}
		
	/* START: Storing monthly_co2 values for each $old_records item in array: $graph */
		foreach ($old_records as $key => $value){
			$graph[$key] = calculate_yearly($key, $value, true)[1];
		}
	/* START: Printing the structure of array: $graph */
		echo "Array: graph <br>";
		foreach ($graph as $key => $value){
			echo "Key: $key; Value: $value<br />";
			foreach ($value as $key => $value){
				echo "&nbsp;&nbsp;&nbsp;|-->Key: $key; Value: $value<br />";
				foreach ($value as $key => $value){
					echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|-->Key: $key; Value: $value<br />";
				}
			}
			echo "<br>";
		}
	}
	
	
	
	/** 
	 *	This function calculates all yearly co2 emissions for each item by calling calculate_yearly 
	 *	for each item that the user has inputted at least one record.  It also calculates the co2
	 *	sequested by the user's trees.  It then returns both values.
	 */
	function carbon_ranking(){
	/* START: Connecting to database */
		/* Establish the connection with SQL database */
		$hostname = "localhost";
		$database = "carbon_neutrality";
		$username = "carbon_challenge";
		$password = "changeme";
		$conn = mysqli_connect($hostname, $username, $password) or die('Can\'t create connection: '.mysql_error());
		mysqli_select_db($conn, $database) or die('Can\'t access specified db: '.mysql_error());
		
	/* START: Storage into array: $recent_records */
		/* Variables */
		$id_user = 1; 
		$tables = array("food", "home_electric", "home_fuel", "home_gas", "home_water", "waste", 
						"travel_bus", "travel_car", "travel_motorcycle", "travel_plane", "travel_train");
		
		/* Fetch the most recent emission records for each table and store them in an array */
		for ($i = 0; $i < count($tables); $i++){
			//if the table is not a travel table, then store the most recent record from that table
			if(strpos($tables[$i], "travel") === false ){
				$result = mysqli_query($conn, "SELECT * FROM user_emissions_" . $tables[$i] . " WHERE id_user='$id_user' ORDER BY date_data_entry DESC");
				$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
				$recent_records[$tables[$i]] = $row;
			}
			//if the table is a travel table, then store records that are distinct by their respective item distinctions
			else{
				if($tables[$i] == "travel_car"){
					$result = mysqli_query($conn, "SELECT * FROM (SELECT * FROM user_emissions_" . $tables[$i] . " WHERE id_user='$id_user' ORDER BY date_data_entry DESC) AS tbl GROUP BY tbl.id_car_model");
					while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
						$recent_records[$tables[$i] . ":" . $row['id_car_model']] = $row;
					}
				}
				elseif($tables[$i] == "travel_motorcycle"){
					$result = mysqli_query($conn, "SELECT * FROM (SELECT * FROM user_emissions_" . $tables[$i] . " WHERE id_user='$id_user' ORDER BY date_data_entry DESC) AS tbl GROUP BY tbl.id_motorcycle");
					while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
						$recent_records[$tables[$i] . ":" . $row['id_motorcycle']] = $row;
					}
				}
				else{
					$result = mysqli_query($conn, "SELECT * FROM (SELECT * FROM user_emissions_" . $tables[$i] . " WHERE id_user='$id_user' ORDER BY date_data_entry DESC) AS tbl GROUP BY tbl.route_from, tbl.route_to");
					while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
						$recent_records[$tables[$i] . ":" . $row['route_from'] . $row['route_to']] = $row;
					}
				}
			}
		}
		
	/* START: Calculating total yearly_co2 for each item in array: $recent_records */
		$yearly_co2 = 0;
		
		foreach ($recent_records as $key => $value){
			$yearly_co2 += calculate_yearly($key, $value)[0];
		}
		
	/* START: Calculating co2 sequestered by the user's trees */	
		$sequestered_co2 = 0;
		
		$result = mysqli_query($conn, "SELECT * FROM user_trees WHERE id_user='$id_user'");
		while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
			$sequestered_co2 += $row['co2_sequestered'];
		}
		
	/* START: Return array[$yearly_co2, $sequestered_co2] */
		$return_values = array($yearly_co2, $sequestered_co2);
		return $return_values;
	}
	
	
	
	/** 
	 *	This function calculates yearly co2 emissions for an item by adding up each month if that month was
	 *	entered (up to 11 months prior).  It fills the blank months with the nearest month to that blank month.
	 *  If this function is called with third parameter set to true, then it will store emissions into an array
	 *  that will be returned and be used to plot a monthly_progress_graph. 
	 */
	function calculate_yearly($key, $value, $graph = false){
	/* START: Connecting to database */
		/* Establish the connection with SQL database */
		$hostname = "localhost";
		$database = "carbon_neutrality";
		$username = "carbon_challenge";
		$password = "changeme";
		$conn = mysqli_connect($hostname, $username, $password) or die('Can\'t create connection: '.mysql_error());
		mysqli_select_db($conn, $database) or die('Can\'t access specified db: '.mysql_error());

	/* START: Storage into array: $all_records */
		/* Variables */
		$id_user = 1; 
		$tables = array("food", "home_electric", "home_fuel", "home_gas", "home_water", "waste", 
						"travel_bus", "travel_car", "travel_motorcycle", "travel_plane", "travel_train");
		
		/* Fetch all emission records for the user and store them in an array */
		for ($i = 0; $i < count($tables); $i++){
			//if the table is not a travel table, then store all records from that table
			if(strpos($tables[$i], "travel") === false ){
				$all = mysqli_query($conn, "SELECT * FROM user_emissions_" . $tables[$i] . " WHERE id_user='$id_user' ORDER BY date_data_entry DESC");
				while($row = mysqli_fetch_array($all, MYSQLI_ASSOC)) {
					$all_records[$tables[$i]][] = $row;
				}
			}
			//table is a travel table
			else{
				//if table is travel_car, then store all records that are distinct (by id_car_model) from that table
				if($tables[$i] == "travel_car"){
					$all = mysqli_query($conn, "SELECT * FROM user_emissions_" . $tables[$i] . " WHERE id_user='$id_user' ORDER BY date_data_entry DESC");
					while($row = mysqli_fetch_array($all, MYSQLI_ASSOC)) {
						$all_records[$tables[$i] . ":" . $row['id_car_model']][] = $row;
					}
				}
				//if table is travel_motorcycle, then store all records that are distinct (by id_motorcycle) from that table
				elseif($tables[$i] == "travel_motorcycle"){
					$all = mysqli_query($conn, "SELECT * FROM user_emissions_" . $tables[$i] . " WHERE id_user='$id_user' ORDER BY date_data_entry DESC");
					while($row = mysqli_fetch_array($all, MYSQLI_ASSOC)) {
						$all_records[$tables[$i] . ":" . $row['id_motorcycle']][] = $row;
					}
				}
				//if table is any other travel table, then store all records that are distinct (by route_from AND route_to) from that table
				else{
					$all = mysqli_query($conn, "SELECT * FROM user_emissions_" . $tables[$i] . " WHERE id_user='$id_user' ORDER BY date_data_entry DESC");
					while($row = mysqli_fetch_array($all, MYSQLI_ASSOC)) {
						$all_records[$tables[$i] . ":" . $row['route_from'] . $row['route_to']][] = $row;
					}
				}
			}
		}   
		
	/* START: Calculating yearly_co2 of the item  */
		/* Variables */
		$yearly_co2 = $value['emissions_this_month'];
		$emission_date = substr($value['date_data_entry'], 0, 7);
		$prev_date = $emission_date;
		$current_date = date("Y-m");
		$records = $all_records[$key]; 
		$timelapse = 11;
		
		/* If this is calculating for the graph, then find the difference in months between
			the oldest date and today's date */
		if($graph === true){
			$timelapse = 1;
			$min_date = strtotime($value['date_data_entry']);
			$max_date = strtotime($current_date);
			while (($min_date = strtotime("+1 MONTH", $min_date)) <= $max_date) {
				$timelapse++;
			}
			//set prev_date to current_date +1 month, so it will start calculating backwards from current_date
			$prev_date = strtotime(date("Y-m", strtotime($current_date)) . " +1 month");
			$prev_date = date("Y-m", $prev_date);
		}
	
		/* Store dates of each record into array: $record_dates */
		for($i = 0; $i < sizeof($records); $i++){
			$record_dates[] = $records[$i]['date_data_entry'];
		}
		
//		echo "Start Item: $key <br>";
//		echo $emission_date . ": " . $yearly_co2 . " start date <br>";
		
		/* loop over the past 11 (or timelapse) months from emission date */
		for ($i = 0; $i < $timelapse; $i++){
			$prev_date = strtotime(date("Y-m", strtotime($prev_date)) . " -1 month");
			$prev_date = date("Y-m", $prev_date);
			$match = false;
			
			//loop over all_records checking if a record with that month/year exists
			for($j = 0; $j < sizeof($records); $j++){
				if(strpos($records[$j]['date_data_entry'], $prev_date) !== false){
					$match = true;
					$yearly_co2 += $records[$j]['emissions_this_month'];
					
					//graph plot
					$graph_points[date("Y-m", strtotime($prev_date))] = $records[$j]['emissions_this_month'];
					
//					echo $prev_date . ": " . $yearly_co2 . " match <br>";
				}
			}
			
			//there was no record for that specific month/year
			if($match == false){
				unset($interval);
				//find the nearest date to this empty month
				foreach($record_dates as $record_date){
					$interval[] = abs(strtotime($prev_date) - strtotime($record_date));
				}
				asort($interval);
				$closest = key($interval);
				$yearly_co2 += $records[$closest]['emissions_this_month'];
				
				//graph plot
				$graph_points[date("Y-m", strtotime($prev_date))] = $records[$closest]['emissions_this_month'];
				
//				echo $prev_date . ": " . $yearly_co2 . " no match, closest=$record_dates[$closest] <br>";
			}
			
		}
//		echo "End Item: $key <br><br>";
		//graph plot : push the final (oldest) entry to the end of the array
		$graph_points[date("Y-m", strtotime($value['date_data_entry']))] = $value['emissions_this_month'];
				
	/* START: Return array[$yearly_co2, $sequestered_co2] */
		$return_values = array($yearly_co2, $graph_points);
		return $return_values;
	}
	
	
	
	/** 
	 *	This function prints out the array structure of either $all_records or $recent_records
	 */
	function print_array(){
	/* START: Connecting to database */
		/* Establish the connection with SQL database */
		$hostname = "localhost";
		$database = "carbon_neutrality";
		$username = "carbon_challenge";
		$password = "changeme";
		$conn = mysqli_connect($hostname, $username, $password) or die('Can\'t create connection: '.mysql_error());
		mysqli_select_db($conn, $database) or die('Can\'t access specified db: '.mysql_error());
		
	/* START: Storage into arrays: $all_records and $recent_records */
		/* Variables */
		$id_user = 1; 
		$tables = array("food", "home_electric", "home_fuel", "home_gas", "home_water", "waste", 
						"travel_bus", "travel_car", "travel_motorcycle", "travel_plane", "travel_train");
		
		/* Fetch all emission records and most recent emission records and store them in two arrays */
		for ($i = 0; $i < count($tables); $i++){
			//not a travel table
			if(strpos($tables[$i], "travel") === false ){
				//recent
				$result = mysqli_query($conn, "SELECT * FROM user_emissions_" . $tables[$i] . " WHERE id_user='$id_user' ORDER BY date_data_entry DESC");
				$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
				$recent_records[$tables[$i]] = $row;
				//all
				$all = mysqli_query($conn, "SELECT * FROM user_emissions_" . $tables[$i] . " WHERE id_user='$id_user' ORDER BY date_data_entry DESC");
				while($row = mysqli_fetch_array($all, MYSQLI_ASSOC)) {
					$all_records[$tables[$i]][] = $row;
				}
			}
			//table is a travel table
			else{
				if($tables[$i] == "travel_car"){
					//recent
					$result = mysqli_query($conn, "SELECT * FROM (SELECT * FROM user_emissions_" . $tables[$i] . " WHERE id_user='$id_user' ORDER BY date_data_entry DESC) AS tbl GROUP BY tbl.id_car_model");
					while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
						$recent_records[$tables[$i] . ":" . $row['id_car_model']] = $row;
					}
					//all
					$all = mysqli_query($conn, "SELECT * FROM user_emissions_" . $tables[$i] . " WHERE id_user='$id_user' ORDER BY date_data_entry DESC");
					while($row = mysqli_fetch_array($all, MYSQLI_ASSOC)) {
						$all_records[$tables[$i] . ":" . $row['id_car_model']][] = $row;
					}
				}
				elseif($tables[$i] == "travel_motorcycle"){
					//recent
					$result = mysqli_query($conn, "SELECT * FROM (SELECT * FROM user_emissions_" . $tables[$i] . " WHERE id_user='$id_user' ORDER BY date_data_entry DESC) AS tbl GROUP BY tbl.id_motorcycle");
					while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
						$recent_records[$tables[$i] . ":" . $row['id_motorcycle']] = $row;
					}
					//all
					$all = mysqli_query($conn, "SELECT * FROM user_emissions_" . $tables[$i] . " WHERE id_user='$id_user' ORDER BY date_data_entry DESC");
					while($row = mysqli_fetch_array($all, MYSQLI_ASSOC)) {
						$all_records[$tables[$i] . ":" . $row['id_motorcycle']][] = $row;
					}
				}
				else{
					//recent
					$result = mysqli_query($conn, "SELECT * FROM (SELECT * FROM user_emissions_" . $tables[$i] . " WHERE id_user='$id_user' ORDER BY date_data_entry DESC) AS tbl GROUP BY tbl.route_from, tbl.route_to");
					while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
						$recent_records[$tables[$i] . ":" . $row['route_from'] . $row['route_to']] = $row;
					}
					//all
					$all = mysqli_query($conn, "SELECT * FROM user_emissions_" . $tables[$i] . " WHERE id_user='$id_user' ORDER BY date_data_entry DESC");
					while($row = mysqli_fetch_array($all, MYSQLI_ASSOC)) {
						$all_records[$tables[$i] . ":" . $row['route_from'] . $row['route_to']][] = $row;
					}
				}
			}
		}
	/* START: Printing out arrays */
		/* Uncomment which array is to be printed */
		
		//print structure of all_records array 
		echo "Array: all_records <br>";
		foreach ($all_records as $key => $value){
			echo "Key: $key; Value: $value<br />";
			foreach ($value as $key => $value){
				echo "&nbsp;&nbsp;&nbsp;|-->Key: $key; Value: $value<br />";
				foreach ($value as $key => $value){
					echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|-->Key: $key; Value: $value<br />";
				}
			}
			echo "<br>";
		}

		//print structure of recent_records array 
		/*
		echo "Array: recent_records <br>";
		foreach ($recent_records as $key => $value){
			echo "Key: $key; Value: $value<br />";
			foreach ($value as $key => $value){
				echo "|-->Key: $key; Value: $value<br />";
			}
			echo "<br>";
		}
		*/
	}

?>