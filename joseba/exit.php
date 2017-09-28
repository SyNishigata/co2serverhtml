<?php
require('includes/db_config.php');
if (!isset($_SESSION['id_user'])) {
	header("Location: ".$url_error);
	exit;
} else {
	session_destroy();
    header("Location: ".$url_index);
}
?>