<?php require('includes/db_config.php'); ?>

<?php
if(isset($_POST['send'])) {
	$recover_password_email = $_POST['email'];
	if (empty($recover_password_email)) {
		$system_message = "Please, fill the email.";
	} else {
		list($id_user) = $database->get_row( "SELECT id_user FROM ".TABLE_DB_USERS." WHERE email = '" . $recover_password_email . "' " );
		if (!$id_user) {
			$system_message = "Please, verify your email.";
		} else {
			$new_password = substr(md5(rand()), 0, 10);
			$new_password_db = md5($new_password);
			$recover_password_data = array(
				'password' => $new_password_db
			);
			$recover_password_where = array(
				'id_user' => $id_user
			);
			$database->update( TABLE_DB_USERS, $recover_password_data, $recover_password_where );
			// send by email
			$system_message = "Your new password is: <em>".$new_password."</em>.<br /><br />This must be send by email.";
		}
	}
} else {
	$recover_password_email = "";
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US" >
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>Carbon Neutral Challenge - Plant trees and track your C02 rating</title>
<?php include("includes/head.php"); ?>
</head>
<body>
<?php include("includes/header.php"); ?>

<!-- start wrapper-content -->
<div id="wrapper-content">
<div id="content">

<h1>Recover password</h1>

<?php if ($system_message != "") { echo "<div id='system_message'>".$system_message."</div>"; } ?>

<?php if ($system_message != $system_message_well_done) { ?>

<p><span class="mandatory">*</span> Mandatory</p>
<form name="sign-up" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="form-class">
<p class="form-label">E-mail <span class="mandatory">*</span>:</p>
<p class="form-value"><input type="text" name="email" value="<?php echo $recover_password_email; ?>" /></p>
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