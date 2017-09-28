<?php require('includes/db_config.php'); ?>

<?php
if(!isset($_SESSION['id_user'])) {
	header('Location: ' . $url_error);
	exit;
} else {
	if(isset($_POST['send'])) {
		$id_user = $_SESSION['id_user'];
		$emissions_date = date("Y-m-d");
		$emissions_gas_use_per_year = $_POST['emissions_home_gas_usage'];
		$emissions_units = $_POST['emissions_home_gas_metric'];
		$emissions_lowest_use = $_POST['emissions_home_gas_lowest'];
		$emissions_highest_use = $_POST['emissions_home_gas_highest'];
		$emissions_gas_emissions_per_year = $_POST['emissions_home_gas_tones_var'];
		if ($emissions_units=='-1') {
			$system_message = "Don't forget to select a unit measurement.";
			$emissions_home_gas_people = $_POST['people'];
			$emissions_home_gas_usage = $emissions_gas_use_per_year;
			$emissions_home_gas_lowest = $emissions_lowest_use;
			$emissions_home_gas_highest = $emissions_highest_use;
			$emissions_home_gas_tones_var = $emissions_gas_emissions_per_year;
		} elseif ($emissions_gas_use_per_year=='') {
			$system_message = $system_message_check_data;
		} else {
			$emissions_data = array(
				'id_user' => $id_user,
				'date' => $emissions_date,
				'units' => $emissions_units,
				'lowest_use' => $emissions_lowest_use,
				'highest_use' => $emissions_highest_use,
				'gas_use_per_year' => $emissions_gas_use_per_year,
				'gas_emissions_per_year' => $emissions_gas_emissions_per_year
			);
			$emissions_where = array(
				'id_user' => $id_user
			);
			$database->insert( TABLE_DB_USER_EMISSIONS_HOME_GAS, $emissions_data, $emissions_where );
			$system_message = $system_message_well_done;
		}
	} else {
		$id_user = $_SESSION['id_user'];
		$emissions_home_gas_people = '';
		$emissions_home_gas_usage = '';
		$emissions_home_gas_metric = '-1';
		$emissions_home_gas_lowest = '';
		$emissions_home_gas_highest = '';
		$emissions_home_gas_tones_var = '';
	}
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US" >
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>Carbon Neutral Challenge - Plant trees and track your C02 rating</title>
<?php include("includes/head.php"); ?>
<script src="js/emissions.js" type="text/javascript"></script>
</head>
<body>
<?php include("includes/header.php"); ?>

<!-- start wrapper-content -->
<div id="wrapper-content">
<div id="content">

<h1>Home gas emissions</h1>

<?php if ($system_message != "") { echo "<div id='system_message'>".$system_message."</div>"; } ?>

<?php if ($system_message != $system_message_well_done) { ?>

<p style="text-align:center; padding:1em; border:1px solid orange; color:orange;">Your gas consumption produces <span id="emissions_gas_tones">0.00</span> tons of carbon a year</p>

<form name="emissions-food" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="form-class">
<p class="form-label">How many people are<br />in your household?:</p>
<p class="form-value"><input type="text" id="people" name="people" value="<?php echo $emissions_home_gas_people; ?>" onchange="EmissionsHomeGas();" /></p>
<br />
<p class="form-label">What is your yearly gas usage?:</p>
<p class="form-value"><input type="text" id="emissions_home_gas_usage" name="emissions_home_gas_usage" value="<?php echo $emissions_home_gas_usage; ?>" onchange="EmissionsHomeGas();" /></p>
<p class="form-label">Select a unit measurement:</p>
<p class="form-value"><select name="emissions_home_gas_metric" id="emissions_home_gas_metric" onchange="EmissionsHomeGas();">
<option value="-1">Select</option>
<option value="1">CcF</option>
<option value="2">McF</option>
<option value="3">Btu</option>
<option value="4">Therms</option>
<option value="5">kWh</option>
</select></p>
<br />
<p>Need Help Calculating Your Natural Gas Usage? <a href="#" onclick="showDIV('helper')">CLICK HERE</a></p>
<div id="helper" style="display:none;">
<br />
<p>From your gas bill, pick the lowest and highest months of consumption over the last year. Don't forget to select a unit measurement from the option above.</p>
<p class="form-label">Lowest Month</p>
<p class="form-value"><input type="text" id="emissions_home_gas_lowest" name="emissions_home_gas_lowest" value="<?php echo $emissions_home_gas_lowest; ?>" onchange="EmissionsHomeGas();" /></p>
<p class="form-label">Highest Month</p>
<p class="form-value"><input type="text" id="emissions_home_gas_highest" name="emissions_home_gas_highest" value="<?php echo $emissions_home_gas_highest; ?>" onchange="EmissionsHomeGas();" /></p>
</div>
<p><input type="hidden" id="emissions_home_gas_tones_var" name="emissions_home_gas_tones_var" value="<?php echo $emissions_home_gas_tones_var; ?>" /></p>
<div class="form-buttons">
<input type="submit" name="send" value="Send" />
</div>
</form>

<?php } ?>

</div>
</div>
<!-- ends wrapper-content -->

<?php include("includes/footer.php"); ?>

</body>
</html>