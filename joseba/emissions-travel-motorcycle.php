<?php require('includes/db_config.php'); ?>

<?php
if(!isset($_SESSION['id_user'])) {
	header('Location: ' . $url_error);
	exit;
} else {
	$id_user = $_SESSION['id_user'];
	$emissions_motorcycle_emissions_per_year = "0.00";
	$emissions_array = $database->get_results( "SELECT * FROM ".TABLE_DB_USER_EMISSIONS_TRAVEL_MOTORCYCLE." WHERE id_user=" . $id_user . " " );
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

<h1>Travel motorcycle emissions</h1>

<script src="http://canvasjs.com/assets/script/canvasjs.min.js"></script>
<script type="text/javascript">

window.onload = function () {
	var chart = new CanvasJS.Chart("chartContainer", {
		theme: "theme2",//theme1
		animationEnabled: false,   // change to true
		data: [              
		{
			// Change type to "bar", "area", "spline", "pie",etc.
			type: "column",
			dataPoints: [
			<?php
			$i = 0;
			$emissions_lenght = count($emissions_array);
			foreach( $emissions_array as $emission ) {
				$emissions_date = $emission['date'];
				$emissions_motorcycle_emissions_per_year = $emission['emissions_this_moto'];
				echo "{ label: \"".$emissions_date."\",  y: ".$emissions_motorcycle_emissions_per_year."}";
				if ($i != $emissions_lenght - 1) { echo ", "; }
				$i++;
			}
			?>
			]
		}
		]
	});
	chart.render();
}
</script>
<div id="chartContainer" style="height: 300px; width: 100%;"></div>

<p>&nbsp;</p>

<p style="text-align:center; padding:1em; border:1px solid orange; color:orange;">Your motorcycle consumption produces <span id="emissions_travel_motorcycle_tones"><?php echo $emissions_motorcycle_emissions_per_year; ?></span> tons of carbon a year</p>

<p>&nbsp;</p>

<table style="width:100%;">
<tr style="padding:1em;border-bottom:1px dotted grey;">
<td>Date</td>
<td>Year mileage</td>
<td>CC</td>
<td>Oil check mileage</td>
<td>Current mileage</td>
<td>Date last check</td>
<td>Date current check</td>
<td>&nbsp;</td>
<?php
	foreach( $emissions_array as $emission ) {
		$emissions_id = $emission['id'];
		$emissions_date = $emission['date'];
		$emissions_year_mileage = $emission['milage_this_moto'];
		if ($emissions_cc==1) {
			$emissions_cc = "<125";
		} elseif ($emissions_cc==2) {
			$emissions_cc = "125-500";
		} elseif ($emissions_cc==3) {
			$emissions_cc = ">500";
		}
		$emissions_oil_check_mileage = $emission['last_milage'];
		$emissions_current_mileage = $emission['current_milage'];
		$emissions_date_last_check = $emission['date_last_milage'];
		$emissions_date_current_check = $emission['date_current_milage'];
		$emissions_travel_motorcycle_emissions_per_year = $emission['emissions_this_moto'];
		echo "<tr style=\"padding:1em;border-bottom:1px dotted grey;\">";
		echo "<td>".$emissions_date."</td>";
		echo "<td>".$emissions_year_mileage."</td>";
		echo "<td>".$emissions_cc."</td>";
		echo "<td>".$emissions_oil_check_mileage."</td>";
		echo "<td>".$emissions_current_mileage."</td>";
		echo "<td>".$emissions_date_last_check."</td>";
		echo "<td>".$emissions_date_current_check."</td>";
		echo "<td>".$emissions_travel_motorcycle_emissions_per_year."</td>";
		echo "<td><a href=\"".$url_emissions_travel_motorcycle_delete."?emissions_id=".$emissions_id."\">Delete</a></td>";
		echo "</tr>";
	}
?>
</table>

<p>&nbsp;</p>

<p style="text-align:center;"><a href="<?php echo $url_emissions_travel_motorcycle_insert; ?>" title="Insert travel motorcycle emission">Insert new travel motorcycle emission data</a></p>
</div>
</div>
<!-- ends wrapper-content -->

<?php include("includes/footer.php"); ?>

</body>
</html>