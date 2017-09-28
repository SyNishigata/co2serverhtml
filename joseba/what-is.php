<?php require('includes/db_config.php'); ?>

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

<h1>What is Carbon Neutrality?</h1>

<div class="content-text">
<p>The Carbon Neutrality Project is an initiative from the MoraLab, which aims to generate a mechanism for people like you or me to pay our ecological debt with Earth. The premise is to calculate how much CO2 you generate, estimate the number of trees necessary to sequester those emissions, plant the trees, and then climate change solve. Simple right!</p>
<p class="center-text"><a href="<?php echo $url_sign_up; ?>" title="Sign up">Sign up</a> / <a href="<?php echo $url_login; ?>" title="Login">Login</a></p>
</div>

</div>
</div>
<!-- ends wrapper-content -->

<?php include("includes/footer.php"); ?>

</body>
</html>