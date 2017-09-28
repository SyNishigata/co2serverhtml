<?php
	/*
		Template Name: emissions-water

		
		Almost all of the following code was copied from \wp-content\plugins\moralab-co2\templates\forms\home-form.php
		
		Except for:
		The script src at the top is from the function add_header_scripts() in the file below:
			\wp-content\plugins\moralab-co2\includes\functions.php
		The functions carbon_water() and jQuery("#show_water_help").on("click", function() are from the file below:
			\wp-content\plugins\moralab-co2\includes\js\carbon.js
	*/



	/* 
		Notes:
			Need to add connection to a database because alot of this page requires $carbon_data which is 
			the carbon emissions from a specific user, which uses wp-db to find that info. 
	*/



	/*
		The following lines won't work because they use Wordpress specific functions:

		global $carbon_data; 

		$home = get_post_meta($carbon_data['ID'], 'home_data', true);
	*/

	/* Establish the connection with SQL database */
	$hostname = "localhost";
	$database = "carbon_neutrality";
	$username = "carbon_challenge";
	$password = "changeme";
	//$conn = mysqli_connect($hostname, $username, $password) or die('Can\'t create connection: '.mysql_error());
	//mysqli_select_db($conn, $database) or die('Can\'t access specified db: '.mysql_error());

	
	/* Declare formSubmit if document is just loading */
	/*
	if(empty($_POST['formSubmit'])){
		$formSubmit = '';
	} else {
		$formSubmit = $_POST['formSubmit'];
	}
	
	if($formSubmit == "Submit")
	{
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		} 

		$sql = "";
		$id_user = 1; 
		$date = date("Y-m-d");

		$household = $_POST["household"];   //not currently stored in db
		$units = $_POST["water_unit"];
		$lowest_use = $_POST["low_water"];
		$highest_use = $_POST["high_water"];
		$water = $_POST["water_usage"];
		$water_emissions = $_POST["water"];

		if($lowest_use == ""){
			$lowest_use = 0;
		}
		if($highest_use == ""){
			$highest_use = 0;
		}

		$result = mysqli_query($conn, "SELECT * FROM user_emissions_home_water WHERE id_user='$id_user'");
		$rows = $result->num_rows;
		
		if($rows == 0){
			$sql = "INSERT INTO user_emissions_home_water
				(id, id_user, date, units, lowest_use, highest_use, water_use_per_year, water_emissions_per_year) 
				VALUES ('0', $id_user, '$date', '$units', $lowest_use, $highest_use, $water, $water_emissions)";
		}
		else{
			$sql = "UPDATE user_emissions_home_water
				SET date='$date', units='$units', lowest_use='$lowest_use', highest_use='$highest_use',
				water_use_per_year='$water', water_emissions_per_year='$water_emissions' WHERE id_user=$id_user";
		}
	
		if ($conn->query($sql) === TRUE) {
			echo "New record created successfully";
		} else {
			echo "Error: " . $sql . "<br>" . $conn->error;
		}
	}
	*/

?>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>

<form action="http://128.171.9.198/carbon-functions/Home/emissions-water.php" method="post">
	<div class="form-group">
		<!-- Water Form -->
		<div class="input-group">
			<input name="water" id="water" value="<?php echo !empty($carbon_data) ? $home['water']:'';?>">   
			<br>
			<span class="input-group-addon electricity"> How many people are in your household? </span>
			<input type="text" name="household" onchange="carbon_water()" id="household" class="form-control" value="<?php echo !empty($carbon_data) ? $home['household']:'1';?>">
		</div>
		<div class="input-group">
			<span class="input-group-addon">Total gallons of water consumed in a year? </span>
			<input type="text" name="water_usage" onchange="carbon_water()" id="water_usage" value="<?php echo !empty($carbon_data) ? $home['water_usage']:'';?>">
			<select name="water_unit" id="water_unit" onchange="carbon_water()">
				<option value="gals" <?php echo (!empty($carbon_data) && $home['water_unit'] == 'gals') ? 'selected':'';?>>Gallons</option>
				<option value="tgals" <?php echo (!empty($carbon_data) && $home['water_unit'] == 'tgals') ? 'selected':'';?>>Thousand Gallons</option>
			</select>
		</div>

		<div class="input-group">
			<span class="input-group-addon"> Need Help Calculating Your Natural Gas Usage? </span>
			<div class="button" id="show_water_help" helper="off"><strong>Click Here</strong></div>
		</div>

		<div class="input-group" id="water_helper" style="display:none">
			<p> From your water bill, pick the lowest and highest months of consumption over the last year. Don't forget to select a unit measurement from the option above.</p>
			<span class="input-group-addon"> Enter total amount of water consumed from your lowest water bill? </span>
			<input type="text" name="low_water" onchange="carbon_water()" id="low_water" value="<?php echo !empty($carbon_data) ? $home['low_water']:'';?>">
		
			<span class="input-group-addon"> Enter total amount of water consumed from your highest water bill? </span>
			<input type="text" name="high_water" onchange="carbon_water()" id="high_water" value="<?php echo !empty($carbon_data) ? $home['high_water']:'';?>">
		</div>
		<!-- .Water Form -->
		<input type="submit" id="formSubmit" name="formSubmit" value="Submit" />
	</div>
</form>

<script type="text/javascript">
	
	function carbon_water() {
		var total_carbon_water = 0.0;
		var water_usage = isNaN(parseFloat(document.getElementById('water_usage').value)) ? 0.0:parseFloat(document.getElementById('water_usage').value);

		var units = (document.getElementById('water_unit').value);
		var low_water = isNaN(parseFloat(document.getElementById('low_water').value)) ? 0.0:parseFloat(document.getElementById('low_water').value);
		var high_water = isNaN(parseFloat(document.getElementById('high_water').value)) ? 0.0:parseFloat(document.getElementById('high_water').value);

		if (low_water > 0 || high_water > 0 ){
			water_usage = (low_water + high_water) * 6;
			jQuery('#water_usage').val(water_usage.toFixed(2));
		}


		//if (water_usage > 0){
			switch(units){
				case 'tgals':
					total_carbon_water = water_usage * 1000 * 4.082 * 0.000001;
					break;
				default:
					total_carbon_water = water_usage * 4.082 * 0.000001;
					break;
			}
		//}

		/*
			Sy's Edit: Added a household divider in the two lines below.
		*/
		var household_members = isNaN(parseInt(document.getElementById('household').value)) ? 1:parseInt(document.getElementById('household').value);
		total_carbon_water = total_carbon_water / household_members;
		
		jQuery('#water').val(total_carbon_water.toFixed(2));
		/* 
			Sy's Edit: This line/function has been commented out because it is used to update the box that shows
			how much co2 the user produces from their home as a total.  Added a household divider in its place.
		*/
		//total_carbon_housing();
	}
	
	jQuery("#show_water_help").on("click", function(){
		if (jQuery(this).attr("helper") == "off"){
			jQuery(this).attr("helper", "on");
			jQuery("#water_helper").show();
		}
		else {
			jQuery(this).attr("helper", "off");
			jQuery("#water_helper").hide();
		}
		
	});
</script>
