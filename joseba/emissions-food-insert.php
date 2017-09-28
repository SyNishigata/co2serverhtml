<?php require('includes/db_config.php'); ?>

<?php
if(!isset($_SESSION['id_user'])) {
	header('Location: ' . $url_error);
	exit;
} else {
	if(isset($_POST['send'])) {
		$id_user = $_SESSION['id_user'];
		$emissions_date = date("Y-m-d");
		$vegetarian = $_POST['vegetarian'];
		$emissions_food_lamb = $_POST['lamb'];
		$emissions_food_beef = $_POST['beef'];
		$emissions_food_pork = $_POST['pork'];
		$emissions_food_fish = $_POST['fish'];
		$emissions_food_poultry = $_POST['poultry'];
		$emissions_food_tones_var = $_POST['emissions_food_tones_var'];
		$emissions_data = array(
			'id_user' => $id_user,
			'date' => $emissions_date,
			'vegetarian' => $vegetarian,
			'lamb' => $emissions_food_lamb,
			'beef' => $emissions_food_beef,
			'pork' => $emissions_food_pork,
			'fish' => $emissions_food_fish,
			'poultry' => $emissions_food_poultry,
			'food_emissions_per_year' => $emissions_food_tones_var
		);
		$emissions_where = array(
			'id_user' => $id_user
		);
		$database->insert( TABLE_DB_USER_EMISSIONS_FOOD, $emissions_data, $emissions_where );
		if ($system_message == "") { $system_message = $system_message_well_done; }
	} else {
		$id_user = $_SESSION['id_user'];
		$vegetarian = '-1';
		$emissions_food_lamb = '';
		$emissions_food_beef = '';
		$emissions_food_pork = '';
		$emissions_food_fish = '';
		$emissions_food_poultry = '';
		$emissions_food_tones_var = '';
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

<h1>Food emissions</h1>

<?php if ($system_message != "") { echo "<div id='system_message'>".$system_message."</div>"; } ?>

<?php if ($system_message != $system_message_well_done) { ?>

<p style="text-align:center; padding:1em; border:1px solid orange; color:orange;">Your food consumption produces <span id="emissions_food_tones">0.00</span> tons of carbon a year</p>

<form name="emissions-food" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="form-class">
<p class="form-label">Are you vegetarian?:</p>
<p class="form-value"><input type="radio" name="vegetarian" value="1" onclick="javascript:EmissionsFood(1, 0);" <?php if($vegetarian==1) { echo "checked"; } ?> /> Yes <input type="radio" name="vegetarian" value="0" onclick="javascript:EmissionsFood(0, 0);" <?php if($vegetarian==0) { echo "checked"; } ?> /> No</p>
<div id="vegetarianNo" style="display:none;">
<br />
<p>How many times a week do you eat:</p>
<p class="form-label">Lamb:</p>
<p class="form-value"><input type="text" id="lamb" name="lamb" value="<?php echo $emissions_food_lamb; ?>" onchange="javascript:EmissionsFood('0', this.value);" /></p>
<p class="form-label">Beef:</p>
<p class="form-value"><input type="text" id="beef" name="beef" value="<?php echo $emissions_food_beef; ?>" onchange="javascript:EmissionsFood('0', this.value);" /></p>
<p class="form-label">Pork:</p>
<p class="form-value"><input type="text" id="pork" name="pork" value="<?php echo $emissions_food_pork; ?>" onchange="javascript:EmissionsFood('0', this.value);" /></p>
<p class="form-label">Fish:</p>
<p class="form-value"><input type="text" id="fish" name="fish" value="<?php echo $emissions_food_fish; ?>" onchange="javascript:EmissionsFood('0', this.value);" /></p>
<p class="form-label">Poultry:</p>
<p class="form-value"><input type="text" id="poultry" name="poultry" value="<?php echo $emissions_food_poultry; ?>" onchange="javascript:EmissionsFood('0', this.value);" /></p>
</div>
<p><input type="hidden" id="emissions_food_tones_var" name="emissions_food_tones_var" value="<?php echo $emissions_food_tones_var; ?>" /></p>
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