<?php require('includes/db_config.php'); ?>

<?php
if(!isset($_SESSION['id_user'])) {
	header('Location: ' . $url_error);
	exit;
} else {
	$id_user = $_SESSION['id_user'];
	list( $food_emissions_per_year, $food_date ) = $database->get_row( "SELECT food_emissions_per_year, date FROM ".TABLE_DB_USER_EMISSIONS_FOOD." WHERE id_user=".$id_user." ORDER BY date DESC" );
	list( $water_emissions_per_year, $water_date ) = $database->get_row( "SELECT water_emissions_per_year, date FROM ".TABLE_DB_USER_EMISSIONS_HOME_WATER." WHERE id_user=".$id_user." ORDER BY date DESC" );
	list( $gas_emissions_per_year, $gas_date ) = $database->get_row( "SELECT gas_emissions_per_year, date FROM ".TABLE_DB_USER_EMISSIONS_HOME_GAS." WHERE id_user=".$id_user." ORDER BY date DESC" );
	list( $fuel_emissions_per_year, $fuel_date ) = $database->get_row( "SELECT fuel_emissions_per_year, date FROM ".TABLE_DB_USER_EMISSIONS_HOME_FUEL." WHERE id_user=".$id_user." ORDER BY date DESC" );
	list( $electricity_emissions_per_year, $electricity_date ) = $database->get_row( "SELECT electricity_emissions_per_year, date FROM ".TABLE_DB_USER_EMISSIONS_HOME_ELECTRIC." WHERE id_user=".$id_user." ORDER BY date DESC" );
	list( $motorcycle_emissions_per_year, $motorcycle_date ) = $database->get_row( "SELECT emissions_this_moto, date FROM ".TABLE_DB_USER_EMISSIONS_TRAVEL_MOTORCYCLE." WHERE id_user=".$id_user." ORDER BY date DESC" );
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

<h1>Emissions</h1>

<div style="border:1px solid grey; margin:2em; padding:2em; display:table; width:85%;">
<div style="width:40%;font-size:2em; float:left; margin:0 2em 0 0;">
food: <?php echo $food_emissions_per_year; ?>
</div>
<div style="float:left;">
<a href="<?php echo $url_emissions_food; ?>" title="View food emission">View food emission data</a><br />
<a href="<?php echo $url_emissions_food_insert; ?>" title="Insert food emission">Insert new food emission data</a>
</div>
<div style="float:right; color:grey;">
<?php echo $food_date; ?>
</div>
</div>

<div style="border:1px solid grey; margin:2em; padding:2em; display:table; width:85%;">
<div style="width:40%;font-size:2em; float:left; margin:0 2em 0 0;">
home water: <?php echo $water_emissions_per_year; ?>
</div>
<div style="float:left;">
<a href="<?php echo $url_emissions_home_water; ?>" title="View water home emission">View water home emission data</a><br />
<a href="<?php echo $url_emissions_home_water_insert; ?>" title="Insert water home emission">Insert new water home emission data</a>
</div>
<div style="float:right; color:grey;">
<?php echo $food_date; ?>
</div>
</div>

<div style="border:1px solid grey; margin:2em; padding:2em; display:table; width:85%;">
<div style="width:40%;font-size:2em; float:left; margin:0 2em 0 0;">
home gas: <?php echo $gas_emissions_per_year; ?>
</div>
<div style="float:left;">
<a href="<?php echo $url_emissions_home_gas; ?>" title="View gas home emission">View gas home emission data</a><br />
<a href="<?php echo $url_emissions_home_gas_insert; ?>" title="Insert gas home emission">Insert new gas home emission data</a>
</div>
<div style="float:right; color:grey;">
<?php echo $food_date; ?>
</div>
</div>

<div style="border:1px solid grey; margin:2em; padding:2em; display:table; width:85%;">
<div style="width:40%;font-size:2em; float:left; margin:0 2em 0 0;">
home fuel: <?php echo $fuel_emissions_per_year; ?>
</div>
<div style="float:left;">
<a href="<?php echo $url_emissions_home_fuel; ?>" title="View fuel home emission">View fuel home emission data</a><br />
<a href="<?php echo $url_emissions_home_fuel_insert; ?>" title="Insert fuel home emission">Insert new fuel home emission data</a>
</div>
<div style="float:right; color:grey;">
<?php echo $food_date; ?>
</div>
</div>

<div style="border:1px solid grey; margin:2em; padding:2em; display:table; width:85%;">
<div style="width:40%;font-size:2em; float:left; margin:0 2em 0 0;">
home electric: <?php echo $electricity_emissions_per_year; ?>
</div>
<div style="float:left;">
<a href="<?php echo $url_emissions_home_electric; ?>" title="View electric home emission">View electric home emission data</a><br />
<a href="<?php echo $url_emissions_home_electric_insert; ?>" title="Insert electric home emission">Insert new electric home emission data</a>
</div>
<div style="float:right; color:grey;">
<?php echo $food_date; ?>
</div>
</div>

<div style="border:1px solid grey; margin:2em; padding:2em; display:table; width:85%;">
<div style="width:40%;font-size:2em; float:left; margin:0 2em 0 0;">
travel motorcycle: <?php echo $motorcycle_emissions_per_year; ?>
</div>
<div style="float:left;">
<a href="<?php echo $url_emissions_travel_motorcycle; ?>" title="View motorcycle travel emission">View motorcycle travel emission data</a><br />
<a href="<?php echo $url_emissions_travel_motorcycle_insert; ?>" title="Insert motorcycle travele emission">Insert new motorcycle travel emission data</a>
</div>
<div style="float:right; color:grey;">
<?php echo $motorcycle_date; ?>
</div>
</div>

</div>
</div>
<!-- ends wrapper-content -->

<?php include("includes/footer.php"); ?>

</body>
</html>