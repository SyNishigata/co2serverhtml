
<?php
include_once('classes/class.phpmailer.php');

$OdM_smtp_servidor = "smtpin.csic.es";
$OdM_smtp_username = "cmima_observadores";
$OdM_smtp_password = "odmdiv26";
$OdM_smtp_nom = "Observadors del Mar";
$OdM_smtp_email = "observadoresdelmar@icm.csic.es";
$text_email_altbody = "Per veure el missatge, per favor, utilitzi unes versions d'HTML i de correu electrònic compatibles!";

if(isset($_POST['enviar'])) {
	$remitent_nom = $_POST['nom']; 
	$remitent_email = $_POST['email'];
	$remitent_comentaris = nl2br($_POST['comentaris']);
	$email_copia = $_POST['email_copia'];
	
	if (empty($remitent_nom)) {
    	$missatge = $missatge01; 
    } elseif (empty($remitent_email)) { 
        $missatge = $missatge02; 
    } elseif (!valida_email($remitent_email)) { 
        $missatge = $missatge03;
	} else {
		$body = $remitent_nom . ": <br /><br />";
		$body .= $remitent_comentaris;
		$body .= "<br /><br /><hr>";
		$body .= $text_data.": ".date("Y-m-d H:i:s").".";
		$mail = new PHPMailer;
		$mail->isSMTP();
		$mail->SMTPDebug = 0;
		$mail->Host = $OdM_smtp_servidor;
		$mail->Port = 587;
		$mail->SMTPSecure = 'tls';
		$mail->SMTPAuth = true;
		$mail->Username = $OdM_smtp_username;
		$mail->Password = $OdM_smtp_password;
		$mail->addReplyTo($remitent_email, $remitent_nom);
		$mail->setFrom($OdM_smtp_email, $OdM_smtp_nom);
		$mail->addAddress($OdM_smtp_email, $OdM_smtp_nom);
		if($email_copia == "Si") { $mail->AddCC($remitent_email, $remitent_nom); }
		$mail->Subject = $text_email_assumpte;
		$mail->MsgHTML($body);
		$mail->AltBody = $text_email_altbody;
		if (!$mail->send()) {
			$missatge = $missatge_enviament_erroni; 
		} else {
			$missatge = $missatge_dades_correctes; 
		}
	}
} else {
	$remitent_nom = ""; 
    $remitent_email = ""; 
}
?> 

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $OdMidioma_codi; ?>" lang="<?php echo $OdMidioma_codi; ?>" >
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<?php echo $text_metas; ?>
	<title><?php echo $text_titol; ?></title>
</head>
<body>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="formulari">
<p class="label"><?php echo $text_nom; ?>: <span class="obligatori">*</span></p>
<p class="valor"><input type="text" name="nom" value="<?php echo $remitent_nom; ?>" /></p>
<p class="label"><?php echo $text_email; ?>: <span class="obligatori">*</span></p>
<p class="valor"><input type="text" name="email" value="<?php echo $remitent_email; ?>" /></p>
<p class="label"><?php echo $text_comentaris; ?>:</p>
<p class="valor"><textarea name="comentaris" id="comentaris" cols="45" rows="5"></textarea></p>
<p class="label">&nbsp;</p>
<p class="valor"><input name="email_copia" id="email_copia" type="checkbox" value="Si" /> <span style="font-weight:normal;"><?php echo $text_email_copia; ?></span></p>
<div class="formulari-botons">
<div class="g-recaptcha" data-sitekey="<?php echo $siteKey; ?>"></div>
<br />
<input type="submit" name="enviar" value="<?php echo $text_enviar; ?>" />
</div>
</form>

</body>
</html>