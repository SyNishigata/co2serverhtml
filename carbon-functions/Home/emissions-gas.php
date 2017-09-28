<?php
	/*
		Template Name: emissions-gas
		
		Majority of the following code was copied from \wp-content\plugins\moralab-co2\templates\forms\home-form.php
	*/

	/* Establish the connection with SQL database */
	$hostname = "localhost";
	$database = "carbon_neutrality";
	$username = "carbon_challenge";
	$password = "changeme";
	$conn = mysqli_connect($hostname, $username, $password) or die('Can\'t create connection: '.mysql_error());
	mysqli_select_db($conn, $database) or die('Can\'t access specified db: '.mysql_error());
	
	/* Variables */
	$id_user = 1; 
	if (isset($_POST['editing'])){
		$editing = $_POST["editing"];
	}
	if (isset($_POST['editing2'])){
		$editing2 = $_POST["editing2"];
	}
	
	/* Fetch all records of gas emissions for the user */
	$result = mysqli_query($conn, "SELECT * FROM user_emissions_home_gas WHERE id_user='$id_user' ORDER BY date_data_entry ASC");
	while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
		$home_records[] = $row;
	}
	$result = mysqli_query($conn, "SELECT * FROM user_emissions WHERE id_user='$id_user' AND item_name = 'home_gas' ORDER BY date_data_entry ASC");
	while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
		$home_records2[] = $row;
	}
	
	/* Calculate Yearly CO2 */
	include '../Calculations/functions.php';
	//fetch the most recent record of home emissions for the user
	$result = mysqli_query($conn, "SELECT * FROM user_emissions WHERE id_user='$id_user' AND item_name = 'home_gas' ORDER BY date_data_entry DESC");			
	$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
	
	if($row !== null){
		$yearly_co2 = calculate_yearly("home_gas", $row)[0];
	}
	else{
		$yearly_co2 = 0.0;
	}
	
	/* Begin database operations once user clicks 'Submit' */
	if (isset($_POST['formSubmit']))
	{
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		} 

		$sql = "";
		$current_date = date("Y-m-d");
		
		$emission_date = $_POST["emission_date"];
		$household = $_POST["household"];   //not currently stored in db
		$units = $_POST["gas_unit"];
		$lowest_use = $_POST["low_ngas"];
		$highest_use = $_POST["high_ngas"];
		$gas = $_POST["ngas_usage"];
		$gas_emissions = $_POST["gas"];

		if($lowest_use == ""){
			$lowest_use = 0;
		}
		if($highest_use == ""){
			$highest_use = 0;
		}
		if($editing == 0){
			$sql = "INSERT INTO user_emissions_home_gas
				(id, id_user, date_data_entry, date_modification, units, lowest_use, highest_use, gas_usage, emissions_this_month) 
				VALUES ('0', $id_user, '$emission_date', '$current_date', '$units', $lowest_use, $highest_use, $gas, $gas_emissions)";
			$sql2 = "INSERT INTO user_emissions (id, id_user, date_data_entry, date_modification, item_name, monthly_co2_emissions) 
				VALUES ('0', $id_user, '$emission_date', '$current_date', 'home_gas', $gas_emissions)";
		}
		else{
			$sql = "UPDATE user_emissions_home_gas
				SET date_data_entry='$emission_date', date_modification='$current_date', units='$units', lowest_use='$lowest_use', highest_use='$highest_use',
				gas_usage = '$gas', emissions_this_month='$gas_emissions' WHERE id_user=$id_user AND id=$editing";
			$sql2 = "UPDATE user_emissions
				SET date_data_entry='$emission_date', date_modification='$current_date', monthly_co2_emissions='$gas_emissions' 
				WHERE id_user=$id_user AND id=$editing2";
		}
		
		/* Check if query processed correctly */
		if (($conn->query($sql) === TRUE) && ($conn->query($sql2) === TRUE)) {
			/* Start storage into user_deficits table */
			$total_emissions = carbon_ranking()[0];
			$total_tree_sequestration = carbon_ranking()[1];
			$carbon_deficit = $total_emissions - $total_tree_sequestration;
			
			$result = mysqli_query($conn, "SELECT * FROM user_deficits WHERE id_user='$id_user'");
			$rows = $result->num_rows;
			if($rows == 0){
				$sql = "INSERT INTO user_deficits (id, id_user, date, total_emissions, total_tree_sequestration, carbon_deficit) 
					VALUES ('0', $id_user, '$current_date', $total_emissions, $total_tree_sequestration, $carbon_deficit)";
			}
			else{
				$sql = "UPDATE user_deficits
				SET date='$current_date',  total_emissions='$total_emissions', total_tree_sequestration='$total_tree_sequestration', 
				carbon_deficit='$carbon_deficit' WHERE id_user=$id_user";
			}
			if ($conn->query($sql) === TRUE){
				echo("<script>location.href = '/carbon-functions'</script>");
			}
			else{
				echo "Error: " . $sql . "<br>" . $conn->error;
			}
		} else {
			echo "Error: " . $sql . "<br>" . $conn->error;
		}
	}
?>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>

<form action="http://128.171.9.198/carbon-functions/Home/emissions-gas.php" method="post">
	<div class="form-group">
		<!-- Natural Gas Form -->
		<div class="input-group">
			<div id="home_form_holder">
				<input type="hidden" name="editing" id="editing" value="0">
				<input type="hidden" name="editing2" id="editing2" value="0">
				<input type="hidden" name="gas" id="gas" value="<?php echo !empty($carbon_data) ? $home['gas']:'';?>">                  

				Your yearly gas consumption is 
				<div style="display:inline"> <?php echo $yearly_co2; ?></div>
				tons of carbon
				<br><br>
				
				Your gas consumption for this month produces 
				<div style="display:inline" name="gas2" id="gas2" value="0">0.0</div>
				tons of carbon
				<br><br>
				
				<span> Enter the date for this emission </span>
				<input type="text" name="emission_date" id="emission_date" value="<?php echo date("Y-m-d")?>">
				<br><br>
			</div>
			
			<span class="input-group-addon electricity"> How many people are in your household? </span>
			<input type="text" name="household" onchange="carbon_natural_gas()" id="household" class="form-control" value="<?php echo !empty($carbon_data) ? $home['household']:'1';?>">
		</div>
		<div class="input-group">
			<span class="input-group-addon electricity"> What is your yearly gas usage? </span>
			<div class="" style="display:inline">
				<input type="text" name="ngas_usage" onchange="carbon_natural_gas()" id="ngas_usage" value="<?php echo !empty($carbon_data) ? $home['ngas_usage']:'';?>">
				
				<select name="gas_unit" id="gas_unit" onchange="carbon_natural_gas()">
					<option> Select a unit measurement</option>
					<option value="ccf" <?php echo (!empty($carbon_data) && $home['gas_unit'] == 'ccf') ? 'selected':'';?>>100 cubic feet (CcF)</option>
					<option value="mcf" <?php echo (!empty($carbon_data) && $home['gas_unit'] == 'mcf') ? 'selected':'';?>>1000 cubic feet (McF)</option>
					<option value="btu" <?php echo (!empty($carbon_data) && $home['gas_unit'] == 'btu') ? 'selected':'';?>>British Termal Units (Btu)</option>
					<option value="therms" <?php echo (!empty($carbon_data) && $home['gas_unit'] == 'therms') ? 'selected':'';?>>Therms</option>
					<option value="kwh" <?php echo (!empty($carbon_data) && $home['gas_unit'] == 'kwh') ? 'selected':'';?>>kWh</option>
				</select>
			</div>
		</div>

		<div class="input-group">
			<span class="input-group-addon electricity"> Need Help Calculating Your Natural Gas Usage? </span>
			<div class="button" id="show_gas_help" helper="off"><strong>Click Here</strong></div>
		</div>

		<div class="input-group" id="gas_helper" style="display:none">
			<p> From your gas bill, pick the lowest and highest months of consumption over the last year. Don't forget to select a unit measurement from the option above.</p>
			<span class="input-group-addon electricity"> Lowest Month </span>
			<input type="text" name="low_ngas" onchange="carbon_natural_gas()" id="low_ngas" value="<?php echo !empty($carbon_data) ? $home['low_ngas']:'';?>">
		
			<span class="input-group-addon electricity"> Highest Month </span>
			<input type="text" name="high_ngas" onchange="carbon_natural_gas()" id="high_ngas" value="<?php echo !empty($carbon_data) ? $home['high_ngas']:'';?>">
		</div>

		<!-- .Natural Gas Form -->
		<input type="submit" id="formSubmit" name="formSubmit" value="Submit" />
		<a style="display:inline" href="/carbon-functions" class="button"><strong>Cancel</strong></a> 
		<input type="button" value="Edit" name="edit_home" id="edit_home"></input>
	</div>
	<div class="row" name="home_list" id="home_list"> </div>
</form>

<script type="text/javascript">

	/* Function containing the calculations of carbon */
	function carbon_natural_gas() {
		var total_carbon_natural_gas = parseFloat(document.getElementById('gas').value);
		var units = document.getElementById('gas_unit').value;
		var natural_gas = isNaN(parseFloat(document.getElementById('ngas_usage').value)) ? 0.0:parseFloat(document.getElementById('ngas_usage').value);
		var low_ngas = isNaN(parseFloat(document.getElementById('low_ngas').value)) ? 0.0:parseFloat(document.getElementById('low_ngas').value);
		var high_ngas = isNaN(parseFloat(document.getElementById('high_ngas').value)) ? 0.0:parseFloat(document.getElementById('high_ngas').value);
		
		if ( (low_ngas > 0.0 ) || (high_ngas > 0.0) ){
			natural_gas = (low_ngas + high_ngas) * 6;
			jQuery("#ngas_usage").val(natural_gas.toFixed(4));
		}
		
		switch(units){
			case 'mcf':
				natural_gas = natural_gas * 0.1;
				break;
			case 'btu':
				natural_gas = natural_gas * 102800;
				break;
			case 'therms':
				natural_gas = natural_gas * 1.028;
				break;
			case 'kwh':
				natural_gas = natural_gas * 29.31;
				break;          
			default:
				break;
		}
		
		total_carbon_natural_gas = (natural_gas / 100) * 54.7 * 1.14 * 0.000001;
		var household_members = isNaN(parseInt(document.getElementById('household').value)) ? 1:parseInt(document.getElementById('household').value);
		total_carbon_natural_gas = total_carbon_natural_gas / household_members;
		total_carbon_natural_gas = total_carbon_natural_gas / 12;
		
		jQuery('#gas').val(total_carbon_natural_gas.toFixed(4));
		document.getElementById("gas2").innerHTML = total_carbon_natural_gas.toFixed(4);
	}
	
	/* Function that displays helper when clicked by user */
	jQuery("#show_gas_help").on("click", function(){
		if (jQuery(this).attr("helper") == "off"){
			jQuery(this).attr("helper", "on");
			jQuery("#gas_helper").show();
		}
		else {
			jQuery(this).attr("helper", "off");
			jQuery("#gas_helper").hide();
		}
	});
	
	/* Function that creates the edit buttons for the user */
	jQuery("#edit_home").on("click", function(){
		var home_records = <?php if(!empty($home_records)){echo json_encode($home_records);}else{echo "''";} ?>;
		var home_records2 = <?php if(!empty($home_records2)){echo json_encode($home_records2);}else{echo "''";} ?>;
		var size = <?php if(!empty($home_records)){echo sizeof($home_records);}else{echo "''";} ?>;
		   
		var home_list = '<hr><span> Click on a date to edit from this list of your recorded home emissions: </span><br>';
		home_list += '<div class="row">';
		if(size != 0){
			for(i = 0; i < size; i++){
				home_list += '<input type="button" id="home_record' + i + '" value="' + home_records[i].date_data_entry + '" ';
				home_list += 'onclick="edit_home_record(' + home_records[i].id + ',' + home_records2[i].id + ')"></input>';
			}
		}
		home_list += '</div>';

		$("#home_list").html(home_list);
	});

	/* Function that calls edit-home.php when an edit button is clicked */
	function edit_home_record(row_id, row_id2){
		var row_id = row_id;
		var row_id2 = row_id2;
		
		$.when(ajax1(row_id, row_id2)).done(function(a1){
			var home_record = JSON.parse(document.getElementById('home_record').textContent);
			
			document.getElementById("gas2").innerHTML = home_record.emissions_this_month;
			document.getElementById("gas_unit").value = home_record.units;
			//document.getElementById("household").value = home_record.people_in_home;
			document.getElementById("ngas_usage").value = home_record.gas_usage;
			document.getElementById("low_ngas").value = home_record.lowest_use;
			document.getElementById("high_ngas").value = home_record.highest_use;
		});
	}
	
	/* Function containing the ajax necessary for the function edit_home_record */
	function ajax1(row_id, row_id2) {
		var home_type = "gas";
		var data = 'row_id=' + row_id + '&row_id2=' + row_id2 + '&home_type=' + home_type;
		return $.ajax({
			type: "POST",
			url: "edit-home.php",
			data: data,
			success: function(html){
				$("#home_form_holder").html(html);
			},
			error: function (e) {
				 alert("Server Error : " + e.state());
			}
		});
	}
	
</script>
