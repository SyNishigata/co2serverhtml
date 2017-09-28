<?php require('includes/db_config.php'); ?>

<?php
if(!isset($_SESSION['id_user'])) {
	header('Location: ' . $url_error);
	exit;
} else {
	if(isset($_GET['emissions_id'])) {
		$id_user = $_SESSION['id_user'];
		$emissions_id = $_GET['emissions_id'];
		$emissions_where = array( 'id' => $emissions_id, 'id_user' => $id_user );
		$database->delete( TABLE_DB_USER_EMISSIONS_TRAVEL_MOTORCYCLE, $emissions_where, 1 );
		header('Location: ' . $url_emissions_travel_motorcycle);
	} else {
		header('Location: ' . $url_error);
		exit;
	}
}
?>