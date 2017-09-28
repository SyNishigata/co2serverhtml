<?php require('includes/db_config.php'); ?>

<?php
if(isset($_POST['send'])) {
	$login_email = $_POST['email'];
	$login_password = $_POST['password'];
	if (empty($login_email)) {
		$system_message = "Please, fill the email.";
	} elseif (empty($login_password)) {
		$system_message = "Please, fill the password.";
	} else {
		$login_password = htmlspecialchars($login_password); 
		$login_password = md5($login_password);
		list($id_user) = $database->get_row( "SELECT id_user FROM ".TABLE_DB_USERS." WHERE email = '" . $login_email . "' AND password = '" . $login_password . "' " );
		if (!$id_user) {
			$system_message = "Please, verify your email and password.";
		} else {
			$date_modification = date("Y-m-d H:i:s");
			$login_data = array(
				'date_modification' => $date_modification
			);
			$login_where = array(
				'id_user' => $id_user
			);
			$database->update( TABLE_DB_USERS, $login_data, $login_where );
            $_SESSION['id_user'] = $id_user;
			header('Location: ' . $url_account_details);
		}
	}
} else {
	$login_email = "";
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

<h1>Login</h1>

<?php if ($system_message != "") { echo "<div id='system_message'>".$system_message."</div>"; } ?>

<p><span class="mandatory">*</span> Mandatory</p>
<form name="login" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="form-class">
<p class="form-label">E-mail <span class="mandatory">*</span>:</p>
<p class="form-value"><input type="text" name="email" value="<?php echo $login_email; ?>" /></p>
<p class="form-label">Password <span class="mandatory">*</span>:</p>
<p class="form-value"><input type="password" name="password" maxlength="15" /><br /><a href="<?php echo $url_recover_password; ?>" class="form-small-text">I forgot my password.</a></p>
<div class="form-buttons">
<input type="submit" name="send" value="Send" />
</div>
</form>

</div>
</div>
<!-- ends wrapper-content -->

<?php include("includes/footer.php"); ?>

</body>
</html>