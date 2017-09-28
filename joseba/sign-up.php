<?php require('includes/db_config.php'); ?>

<?php
if(isset($_POST['send'])) {
	$sign_up_given_name = htmlspecialchars($_POST['given_name']);
	$sign_up_last_name = htmlspecialchars($_POST['last_name']);
	$sign_up_email = htmlspecialchars($_POST['email']);
	$sign_up_password = htmlspecialchars($_POST['password']);
	$sign_up_password_bis = htmlspecialchars($_POST['passwordbis']);
	$sign_up_photo = '';
	if (empty($sign_up_email)) {
		$system_message = "Please, fill the email.";
	} elseif (!filter_var($sign_up_email, FILTER_VALIDATE_EMAIL)) {
		$system_message = "Please, verify your email.";
	} elseif (empty($sign_up_password)) {
		$system_message = "Please, fill the password.";
	} elseif ((strlen($sign_up_password) < $password_min_length) || (strlen($sign_up_password) > $password_max_length)) {
		$system_message = "Please, the password must be between 6 and 12 characters.";
	} elseif (!empty($sign_up_password) && ($sign_up_password != $sign_up_password_bis)) {
		$system_message = "Please, verify your password.";
	} else {
		list($id_user) = $database->get_row( "SELECT id_user FROM ".TABLE_DB_USERS." WHERE email = '" . $sign_up_email . "' " );
		if ($id_user) {
			$system_message = "This email is registered, try another one or login as user.";
		} else {
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
					$sign_up_photo = $photo_name;
				} else {
					$system_message = "You must upload a JPG, GIF or PNG image.";
				}
			}
			$sign_up_date = date("Y-m-d H:i:s");
			$sign_up_data = array(
				'given_name' => $sign_up_given_name,
				'last_name' => $sign_up_last_name,
				'email' => $sign_up_email,
				'password' => md5($sign_up_password),
				'photo' => $sign_up_photo,
				'date_creation' => $sign_up_date,
				'date_modification' => $sign_up_date
			);
			$database->insert( TABLE_DB_USERS, $sign_up_data );
			$system_message = $system_message_well_done;
		}
	}
} else {
	$sign_up_given_name = "";
	$sign_up_last_name = "";
	$sign_up_email = "";
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

<h1>Sign up</h1>

<?php if ($system_message != "") { echo "<div id='system_message'>".$system_message."</div>"; } ?>

<?php if ($system_message != $system_message_well_done) { ?>

<p><span class="mandatory">*</span> Mandatory</p>
<form name="sign-up" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" class="form-class">
<p class="form-label">Given name:</p>
<p class="form-value"><input type="text" name="given_name" value="<?php echo $sign_up_given_name; ?>" /></p>
<p class="form-label">Last name:</p>
<p class="form-value"><input type="text" name="last_name" value="<?php echo $sign_up_last_name; ?>" /></p>
<p class="form-label">E-mail <span class="mandatory">*</span>:</p>
<p class="form-value"><input type="text" name="email" value="<?php echo $sign_up_email; ?>" /></p>
<br />
<p class="form-label">Password <span class="mandatory">*</span>:</p>
<p class="form-value"><input type="password" name="password" maxlength="15" /></p>
<p class="form-label">Confirm password <span class="mandatory">*</span>:</p>
<p class="form-value"><input type="password" name="passwordbis" maxlength="15" /></p>
<br />
<p class="form-label">Photo:</p>
<p class="form-value"><input type="file" name="photo"><br /><span class="form-small-text">300x300 small picture will be generated.</span></p>
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