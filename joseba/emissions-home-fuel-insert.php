<?php require('includes/db_config.php'); ?>

<?php
if(!isset($_SESSION['id_user'])) {
	header('Location: ' . $url_error);
	exit;
} else {
	if(isset($_POST['send'])) {
		$id_user = $_SESSION['id_user'];
		$emissions_date = date("Y-m-d");
		$emissions_fuel_use_per_year = $_POST['emissions_home_fuel_usage'];
		if (isset($_POST['fuel_cooking'])) { $emissions_fuel_cooking = $_POST['fuel_cooking']; } else { $emissions_fuel_cooking = 0; }
		if (isset($_POST['fuel_drying'])) { $emissions_fuel_drying = $_POST['fuel_drying']; } else { $emissions_fuel_drying = 0; }
		if (isset($_POST['fuel_water_heating'])) { $emissions_fuel_water_heating = $_POST['fuel_water_heating']; } else { $emissions_fuel_water_heating = 0; }
		$emissions_fuel_emissions_per_year = $_POST['emissions_home_fuel_tones_var'];
		if ($emissions_fuel_use_per_year=='') {
			$system_message = $system_message_check_data;
			$emissions_home_fuel_people = $_POST['people'];
			$emissions_home_fuel_usage = $emissions_fuel_use_per_year;
			$emissions_home_fuel_cooking = $emissions_fuel_cooking;
			$emissions_home_fuel_drying = $emissions_fuel_drying;
			$emissions_home_fuel_water_heating = $emissions_fuel_water_heating;
			$emissions_home_fuel_tones_var = $emissions_fuel_emissions_per_year;
		} else {
			$emissions_data = array(
				'id_user' => $id_user,
				'date' => $emissions_date,
				'fuel_use_per_year' => $emissions_fuel_use_per_year,
				'cooking' => $emissions_fuel_cooking,
				'drying' => $emissions_fuel_drying,
				'water_heating' => $emissions_fuel_water_heating,
				'fuel_emissions_per_year' => $emissions_fuel_emissions_per_year
			);
			$emissions_where = array(
				'id_user' => $id_user
			);
			$database->insert( TABLE_DB_USER_EMISSIONS_HOME_FUEL, $emissions_data, $emissions_where );
			$system_message = $system_message_well_done;
		}
	} else {
		$id_user = $_SESSION['id_user'];
		$emissions_home_fuel_people = '';
		$emissions_home_fuel_usage = '';
		$emissions_home_fuel_cooking = '';
		$emissions_home_fuel_drying = '';
		$emissions_home_fuel_water_heating = '';
		$emissions_home_fuel_tones_var = '';
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

<h1>Home fuel emissions</h1>

<?php if ($system_message != "") { echo "<div id='system_message'>".$system_message."</div>"; } ?>

<?php if ($system_message != $system_message_well_done) { ?>

<p style="text-align:center; padding:1em; border:1px solid orange; color:orange;">Your fuel consumption produces <span id="emissions_fuel_tones">0.00</span> tons of carbon a year</p>

<form name="emissions-food" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="form-class">
<p class="form-label">How many people are<br />in your household?:</p>
<p class="form-value"><input type="text" id="people" name="people" value="<?php echo $emissions_home_fuel_people; ?>" onchange="EmissionsHomeFuel();" /></p>
<br />
<p class="form-label">Total gallons of propane or<br />other fuels used in a year?:</p>
<p class="form-value"><input type="text" id="emissions_home_fuel_usage" name="emissions_home_fuel_usage" value="<?php echo $emissions_home_fuel_usage; ?>" onchange="EmissionsHomeFuel();" /></p>
<br />
<p>Need Help Calculating Your Propane or Other Fuels Usage? <a href="#" onclick="showDIV('helper')">CLICK HERE</a></p>
<div id="helper" style="display:none;">
<br />
<p>Do you use propane or other fuels for:</p>
<p class="form-label">&nbsp;</p>
<p class="form-value"><input type="checkbox" name="fuel_cooking" id="fuel_cooking" value="1" onchange="EmissionsHomeFuel();" <?php if($emissions_home_fuel_cooking=="1") { echo "checked"; } ?> /> Cooking <input type="hidden" id="fuel_cooking_var" name="fuel_cooking_var" value="0" /></p>
<p class="form-label">&nbsp;</p>
<p class="form-value"><input type="checkbox" name="fuel_drying" id="fuel_drying" value="2" onchange="EmissionsHomeFuel();" <?php if($emissions_home_fuel_drying=="1") { echo "checked"; } ?> /> Drying <input type="hidden" id="fuel_drying_var" name="fuel_drying_var" value="0" /></p>
<p class="form-label">&nbsp;</p>
<p class="form-value"><input type="checkbox" name="fuel_water_heating" id="fuel_water_heating" value="3" onchange="EmissionsHomeFuel();" <?php if($emissions_home_fuel_water_heating=="1") { echo "checked"; } ?> /> Water Heating <input type="hidden" id="fuel_water_heating_var" name="fuel_water_heating_var" value="0" /></p>
</div>
<p><input type="hidden" id="emissions_home_fuel_tones_var" name="emissions_home_fuel_tones_var" value="<?php echo $emissions_home_fuel_tones_var; ?>" /></p>
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