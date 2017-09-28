<?php require('includes/db_config.php'); ?>

<?php
if(!isset($_SESSION['id_user'])) {
	header('Location: ' . $url_error);
	exit;
} else {
	if(isset($_POST['send'])) {
		$id_user = $_SESSION['id_user'];
		$user_given_name = htmlspecialchars($_POST['given_name']);
		$user_last_name = htmlspecialchars($_POST['last_name']);
		$user_email = $_POST['hidden_email'];
		$user_new_password = htmlspecialchars($_POST['password']);
		$user_password = $_POST['hidden_password'];
		$user_new_password_bis = htmlspecialchars($_POST['passwordbis']);
		$user_photo = $_POST['hidden_photo'];
		$user_date_creation = $_POST['hidden_date_creation'];
		$user_date_modification = date("Y-m-d H:i:s");
		if (!empty($user_new_password)) {
			if ((strlen($user_new_password) < $password_min_length) || (strlen($user_new_password) > $password_max_length)) {
				$system_message = "Please, the password must be between 6 and 12 characters.";
			} elseif ($user_new_password != $user_new_password_bis) {
				$system_message = "Please, verify your password.";
			} else {
				$user_password = md5($user_new_password);
			}
		}
		$file_photo_name = $_FILES['photo']['name'];
		if (!empty($file_photo_name)) {
			require_once('classes/ImageManipulator.php');
			$file_photo_extensions = strrchr($file_photo_name, ".");
			if (in_array($file_photo_extensions, $valid_photo_extensions)) {
				$file_photo_tmp_name = $_FILES['photo']['tmp_name'];
				$manipulator = new ImageManipulator($file_photo_tmp_name);
				$manipulator = $manipulator->resample('600', '600');
				$width 	= $manipulator->getWidth();
				$height = $manipulator->getHeight();
				$centreX = round($width / 2);
				$centreY = round($height / 2);
				$x1 = $centreX - 150; // 300 / 2
				$y1 = $centreY - 150; // 300 / 2
				$x2 = $centreX + 150; // 300 / 2
				$y2 = $centreY + 150; // 300 / 2
				$newImage = $manipulator->crop($x1, $y1, $x2, $y2);
				$photo_name = time().'_'.$file_photo_name;
				$manipulator->save('pictures/300x300/'.$photo_name);
				move_uploaded_file($file_photo_tmp_name, 'pictures/original/'.$photo_name);
				if ($user_photo!='') {
					unlink('pictures/300x300/'.$user_photo);
					unlink('pictures/original/'.$user_photo);
				}
				$user_photo = $photo_name;
			} else {
				$system_message = "You must upload a JPG, GIF or PNG image.";
			}
		}
		$user_data = array(
			'given_name' => $user_given_name,
			'last_name' => $user_last_name,
			'password' => $user_password,
			'photo' => $user_photo,
			'date_modification' => $user_date_modification
		);
		$user_where = array(
			'id_user' => $id_user
		);
		$database->update( TABLE_DB_USERS, $user_data, $user_where );
		if ($system_message == "") { $system_message = $system_message_well_done; }
	} else {
		$id_user = $_SESSION['id_user'];
		$user_array = $database->get_results( "SELECT * FROM ".TABLE_DB_USERS." WHERE id_user=" . $id_user . " " );
		foreach( $user_array as $user ) {
			$user_given_name = $user['given_name'];
			$user_last_name = $user['last_name'];
			$user_email = $user['email'];
			$user_password = $user['password'];
			$user_photo = $user['photo'];
			$user_date_creation = $user['date_creation'];
			$user_date_modification = $user['date_modification'];
		}
	}
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

<h1>Account details</h1>

<?php if ($system_message != "") { echo "<div id='system_message'>".$system_message."</div>"; } ?>

<?php if ($system_message != $system_message_well_done) { ?>

<form name="account-details" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" class="form-class">
<p class="form-label">Given name:</p>
<p class="form-value"><input type="text" name="given_name" value="<?php echo $user_given_name; ?>" /></p>
<p class="form-label">Last name:</p>
<p class="form-value"><input type="text" name="last_name" value="<?php echo $user_last_name; ?>" /></p>
<p class="form-label">E-mail:</p>
<p class="form-value"><input type="text" name="email" value="<?php echo $user_email; ?>" disabled="disabled" /></p>
<input type="hidden" name="hidden_email" value="<?php echo $user_email; ?>" />
<br />
<p class="form-label">New password:</p>
<p class="form-value"><input type="password" name="password" maxlength="15" /></p>
<input type="hidden" name="hidden_password" value="<?php echo $user_password; ?>" />
<p class="form-label">Confirm new password:</p>
<p class="form-value"><input type="password" name="passwordbis" maxlength="15" /></p>
<br />
<?php if ($user_photo!='') { ?>
<p class="form-label">&nbsp;</p>
<p class="form-value"><img src="pictures/300x300/<?php echo $user_photo; ?>" /></p>
<?php } ?>
<p class="form-label">Photo:</p>
<p class="form-value"><input type="file" name="photo"><br /><span class="form-small-text">300x300 small picture will be generated.</span></p>
<input type="hidden" name="hidden_photo" value="<?php echo $user_photo; ?>" />
<br />
<p class="form-label">Date creation:</p>
<p class="form-value"><input type="text" name="date_creation" value="<?php echo $user_date_creation; ?>" disabled="disabled" /></p>
<input type="hidden" name="hidden_date_creation" value="<?php echo $user_date_creation; ?>" />
<?php if ($user_date_creation != $user_date_modification) { ?>
<p class="form-label">Last visit:</p>
<p class="form-value"><input type="text" name="date_modification" value="<?php echo $user_date_modification; ?>" disabled="disabled" /></p>
<?php } ?>
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