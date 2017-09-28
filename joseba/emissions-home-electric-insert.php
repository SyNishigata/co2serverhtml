<?php require('includes/db_config.php'); ?>

<?php
if(!isset($_SESSION['id_user'])) {
	header('Location: ' . $url_error);
	exit;
} else {
	if(isset($_POST['send'])) {
		$id_user = $_SESSION['id_user'];
		$emissions_date = date("Y-m-d");
		$emissions_lowest_use = $_POST['emissions_home_electric_lowest'];
		$emissions_highest_use = $_POST['emissions_home_electric_highest'];
		$emissions_electric_use_per_year = $_POST['emissions_home_electric_usage'];
		$emissions_electric_emissions_per_year = $_POST['emissions_home_electric_tones_var'];
		if ($emissions_electric_use_per_year=='') {
			$system_message = $system_message_check_data;
		} else {
			$emissions_data = array(
				'id_user' => $id_user,
				'date' => $emissions_date,
				'lowest_use' => $emissions_lowest_use,
				'highest_use' => $emissions_highest_use,
				'electricity_use_per_year' => $emissions_electric_use_per_year,
				'electricity_emissions_per_year' => $emissions_electric_emissions_per_year
			);
			$emissions_where = array(
				'id_user' => $id_user
			);
			$database->insert( TABLE_DB_USER_EMISSIONS_HOME_ELECTRIC, $emissions_data, $emissions_where );
			$system_message = $system_message_well_done;
		}
	} else {
		$id_user = $_SESSION['id_user'];
		$emissions_home_electric_people = '';
		$emissions_home_electric_usage = '';
		$emissions_home_electric_lowest = '';
		$emissions_home_electric_highest = '';
		$emissions_home_electric_tones_var = '';
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

<h1>Home electric emissions</h1>

<?php if ($system_message != "") { echo "<div id='system_message'>".$system_message."</div>"; } ?>

<?php if ($system_message != $system_message_well_done) { ?>

<p style="text-align:center; padding:1em; border:1px solid orange; color:orange;">Your electric consumption produces <span id="emissions_electric_tones">0.00</span> tons of carbon a year</p>

<form name="emissions-food" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="form-class">
<p class="form-label">How many people are<br />in your household?:</p>
<p class="form-value"><input type="text" id="people" name="people" value="<?php echo $emissions_home_electric_people; ?>" onchange="EmissionsHomeElectric();" /></p>
<br />
<p class="form-label">What is your yearly electricity usage in kWh?:</p>
<p class="form-value"><input type="text" id="emissions_home_electric_usage" name="emissions_home_electric_usage" value="<?php echo $emissions_home_electric_usage; ?>" onchange="EmissionsHomeElectric();" /></p>
<br />
<p>Need Help Calculating Your Electric Usage? <a href="#" onclick="showDIV('helper')">CLICK HERE</a></p>
<div id="helper" style="display:Xnone;">
<br />
<p>From your electricity bill, pick the lowest and highest months of the consumption over the last year. If you have solar panels, use "Net kWh."</p>
<p class="form-label">Enter the kWh from your lowest monthly bill?</p>
<p class="form-value"><input type="text" id="emissions_home_electric_lowest" name="emissions_home_electric_lowest" value="<?php echo $emissions_home_electric_lowest; ?>" onchange="EmissionsHomeElectric();" /></p>
<p class="form-label">Enter the kWh from your highest monthly bill?</p>
<p class="form-value"><input type="text" id="emissions_home_electric_highest" name="emissions_home_electric_highest" value="<?php echo $emissions_home_electric_highest; ?>" onchange="EmissionsHomeElectric();" /></p>
</div>
<p><input type="text" id="emissions_home_electric_tones_var" name="emissions_home_electric_tones_var" value="<?php echo $emissions_home_electric_tones_var; ?>" /></p>
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