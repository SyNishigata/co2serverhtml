<?php
require('../../joseba/includes/db_config.php');
if(isset($_POST['make'])) {
	$car_make_post = $_POST['make'];
	$car_model_array = $database->get_results( "SELECT DISTINCT model FROM ".TABLE_DB_LIBRARY_CAR_MODELS." WHERE make='".$car_make_post."'" );
?>
<select id="car_model" name="car_model" onchange="get_car_years()">
	<option>Select a Model</option>
	<?php
	foreach( $car_model_array as $car_model ) {
		echo '<option value="' . $car_model["model"] . '">' . $car_model["model"] . '</option>';
	}
	?>
</select>
<?php } ?>