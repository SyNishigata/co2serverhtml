<?php require('includes/db_config.php'); ?>

<?php
if(!isset($_SESSION['id_user'])) {
	header('Location: ' . $url_error);
	exit;
} else {
	$id_user = $_SESSION['id_user'];
	//$emissions_array = $database->get_results( "SELECT * FROM ".TABLE_DB_USER_EMISSIONS_FOOD." WHERE id_user=" . $id_user . " " );
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

<h1>Home emissions</h1>

<p style="text-align:center;"><a href="<?php echo $url_emissions_food_insert; ?>" title="Insert food emission">Insert new food emission data</a></p>

<p style="text-align:center;"><a href="<?php echo $url_emissions_home_water_insert; ?>" title="Insert home water emission">Insert new home water emission data</a></p>

</div>
</div>
<!-- ends wrapper-content -->

<?php include("includes/footer.php"); ?>

</body>
</html>