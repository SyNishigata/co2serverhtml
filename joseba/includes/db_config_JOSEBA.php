<?php
define( 'DB_HOST', 'localhost' ); // set database host
define( 'DB_USER', 'root' ); // set database user
define( 'DB_PASS', '' ); // set database password
define( 'DB_NAME', 'carbon_neutrality' ); // set database name
define( 'SEND_ERRORS_TO', 'info@aterkia.com' ); //set email notification email address
define( 'DISPLAY_DEBUG', true ); //display db errors?

define('__ROOT__', dirname(dirname(__FILE__)));
require_once(__ROOT__.'/classes/class.db.php');
require_once(__ROOT__.'/includes/db_names.php');
require_once(__ROOT__.'/includes/file_names.php');

// DON'T TOUCH
session_start();
$database = new DB();

$system_message = '';
$system_message_well_done = "Well done!";

$password_min_length = '5';
$password_max_length = '13';
$valid_photo_extensions = array('.jpg', '.jpeg', '.gif', '.png');
?>