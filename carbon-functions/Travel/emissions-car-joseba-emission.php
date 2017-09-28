<?php
require('../../joseba/includes/db_config.php');
if(isset($_POST['year'])) {
	$car_year_post = $_POST['year'];
	$car_model_post = $_POST['model'];
	$car_make_post = $_POST['make'];
	list( $car_mpg, $car_kpg ) = $database->get_row( "SELECT mpg, kpg FROM ".TABLE_DB_LIBRARY_CAR_MODELS." WHERE model='".$car_model_post."' AND make='".$car_make_post."' AND year='".$car_year_post."'" );
	$car_emission = $car_mpg + $car_kpg;
	?>
    <script>document.getElementById('hidden_car_emission').value=<?php echo $car_emission; ?>;</script>
<?php } ?>