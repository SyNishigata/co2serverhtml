<?php require('includes/db_config.php'); ?>

<?php
if(!isset($_SESSION['id_user'])) {
	header('Location: ' . $url_error);
	exit;
} else {
	if(isset($_POST['send'])) {
		$id_user = $_SESSION['id_user'];
		$emissions_date = date("Y-m-d");
		$emissions_units = $_POST['water_metric'];
		$emissions_lowest_use = $_POST['emissions_home_water_lowest'];
		$emissions_highest_use = $_POST['emissions_home_water_highest'];
		$emissions_water_use_per_year = $_POST['emissions_home_water_gallons'];
		$emissions_water_emissions_per_year = $_POST['emissions_home_water_tones_var'];
		if ($emissions_water_use_per_year=='') {
			$system_message = $system_message_check_data;
		} else {
			$emissions_data = array(
				'id_user' => $id_user,
				'date' => $emissions_date,
				'units' => $emissions_units,
				'lowest_use' => $emissions_lowest_use,
				'highest_use' => $emissions_highest_use,
				'water_use_per_year' => $emissions_water_use_per_year,
				'water_emissions_per_year' => $emissions_water_emissions_per_year
			);
			$emissions_where = array(
				'id_user' => $id_user
			);
			$database->insert( TABLE_DB_USER_EMISSIONS_HOME_WATER, $emissions_data, $emissions_where );
			$system_message = $system_message_well_done;
		}
	} else {
		$id_user = $_SESSION['id_user'];
		$emissions_home_water_people = '';
		$emissions_home_water_gallons = '';
		$emissions_home_water_metric = '-1';
		$emissions_home_water_lowest = '';
		$emissions_home_water_highest = '';
		$emissions_home_water_tones_var = '';
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

<h1>Home water emissions</h1>

<?php if ($system_message != "") { echo "<div id='system_message'>".$system_message."</div>"; } ?>

<?php if ($system_message != $system_message_well_done) { ?>

<p style="text-align:center; padding:1em; border:1px solid orange; color:orange;">Your water consumption produces <span id="emissions_water_tones">0.00</span> tons of carbon a year</p>

<form name="emissions-food" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="form-class">
<p class="form-label">How many people are<br />in your household?:</p>
<p class="form-value"><input type="text" id="people" name="people" value="<?php echo $emissions_home_water_people; ?>" onchange="EmissionsHomeWater();" /></p>
<br />
<p class="form-label">Total gallons of water<br />consumed in a year?:</p>
<p class="form-value"><input type="text" id="emissions_home_water_gallons" name="emissions_home_water_gallons" value="<?php echo $emissions_home_water_gallons; ?>" onchange="EmissionsHomeWater();" /><br /><input type="radio" name="water_metric" id="gals" value="1" onchange="EmissionsHomeWater();" <?php if($emissions_home_water_metric=="1") { echo "checked"; } ?> /> Gallons <input type="radio" name="water_metric" id="tgals" value="2" onchange="EmissionsHomeWater();" <?php if($emissions_home_water_metric=="2") { echo "checked"; } ?> /> Thousand gallons</p>
<br />
<p>Need Help Calculating Your Natural Gas Usage? <a href="#" onclick="showDIV('helper')">CLICK HERE</a></p>
<div id="helper" style="display:none;">
<br />
<p>From your water bill, pick the lowest and highest months of consumption over the last year. Don't forget to select a unit measurement from the option above.</p>
<p class="form-label">Enter total amount of water consumed from your lowest water bill?</p>
<p class="form-value"><input type="text" id="emissions_home_water_lowest" name="emissions_home_water_lowest" value="<?php echo $emissions_home_water_lowest; ?>" onchange="EmissionsHomeWater();" /></p>
<p class="form-label">Enter total amount of water consumed from your highest water bill?</p>
<p class="form-value"><input type="text" id="emissions_home_water_highest" name="emissions_home_water_highest" value="<?php echo $emissions_home_water_highest; ?>" onchange="EmissionsHomeWater();" /></p>
</div>
<p><input type="hidden" id="emissions_home_water_tones_var" name="emissions_home_water_tones_var" value="<?php echo $emissions_home_water_tones_var; ?>" /></p>
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