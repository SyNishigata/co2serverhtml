<?php
require('../../joseba/includes/db_config.php');
if(isset($_POST['model'])) {
	$car_model_post = $_POST['model'];
	$car_make_post = $_POST['make'];
	$car_year_array = $database->get_results( "SELECT DISTINCT year FROM ".TABLE_DB_LIBRARY_CAR_MODELS." WHERE model='".$car_model_post."' AND make='".$car_make_post."'" );
?>
<select id="car_year" name="car_year" onchange="get_car_efficiency()">
	<option>Select a Year</option>
	<?php
	foreach( $car_year_array as $car_year ) {
		echo '<option value="' . $car_year["year"] . '">' . $car_year["year"] . '</option>';
	}
	?>
</select>
<?php } ?>