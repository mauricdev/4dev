<?php
header('Content-type: application/json');
require_once('php-mailer/PHPMailerAutoload.php'); // Include PHPMailer

$mail = new PHPMailer();
$emailTO = $emailBCC =  $emailCC = array(); $formEmail = '';

### Enter Your Sitename 
$sitename = '4Dev SpA';

### Enter your email addresses: @required
$emailTO[] = array( 'email' => 'comunicaciones@4dev.cl', 'name' => '4Dev SpA' ); 

### Enable bellow parameters & update your BCC email if require.
//$emailBCC[] = array( 'email' => 'email@yoursite.com', 'name' => 'Your Name' );

### Enable bellow parameters & update your CC email if require.
//$emailCC[] = array( 'email' => 'email@yoursite.com', 'name' => 'Your Name' );

### Enter Email Subject
$subject = "Confirmacion Contacto" . ' - ' . $sitename; 

### If your did not recive email after submit form please enable below line and must change to your correct domain name. eg. noreply@example.com
//$formEmail = 'noreply@yoursite.com';

### Success Messages
$msg_success = "Hemos <strong>recibido</strong> su mensaje Satisfactoriamente. Nuestro equipo se pondrá en contacto lo mas pronto posible.";

if( $_SERVER['REQUEST_METHOD'] == 'POST') {
	if (isset($_POST["contact-email"]) && $_POST["contact-email"] != '' && isset($_POST["contact-name"]) && $_POST["contact-name"] != '') {
		### Form Fields
		$cf_email = $_POST["contact-email"];
		$cf_name = $_POST["contact-name"];
		$cf_message = isset($_POST["contact-message"]) ? $_POST["contact-message"] : '';

		$honeypot 	= isset($_POST["form-anti-honeypot"]) ? $_POST["form-anti-honeypot"] : 'bot';
		$bodymsg = '';
		
		if ($honeypot == '' && !(empty($emailTO))) {
			$mail->IsHTML(true);
			$mail->CharSet = 'UTF-8';

			$mail->From = ($formEmail !='') ? $formEmail : $cf_email;
			$mail->FromName = $cf_name . ' - ' . $sitename;
			$mail->AddReplyTo($cf_email, $cf_name);
			$mail->Subject = $subject;
			
			foreach( $emailTO as $to ) {
				$mail->AddAddress( $to['email'] , $to['name'] );
			}
			
			### if CC found
			if (!empty($emailCC)) {
				foreach( $emailCC as $cc ) {
					$mail->AddCC( $cc['email'] , $cc['name'] );
				}
			}
			
			### if BCC found
			if (!empty($emailBCC)) {
				foreach( $emailBCC as $bcc ) {
					$mail->AddBCC( $bcc['email'] , $bcc['name'] );
				}				
			}

			### Include Form Fields into Body Message
			$bodymsg .= isset($cf_name) ? "Contact Name: $cf_name<br><br>" : '';
			$bodymsg .= isset($cf_email) ? "Contact Email: $cf_email<br><br>" : '';
			$bodymsg .= isset($cf_message) ? "Message: $cf_message<br><br>" : '';
			$bodymsg .= $_SERVER['HTTP_REFERER'] ? '<br>---<br><br>Este Correo fue enviado desde 4Dev.cl: ' . $_SERVER['HTTP_REFERER'] : '';
			
			// Protect Submission from outside
			if ( preg_match("/themenio.com/", $_SERVER['HTTP_REFERER'])) {
				$mail->MsgHTML( $bodymsg );
				$is_emailed = $mail->Send();
				$msg_error = $mail->ErrorInfo;
			} else {
				$is_emailed = false;
				$msg_error = "<strong>Error.</strong>! No se saben los detalles, intentalo nuevamente.";
			}
			
			if( $is_emailed === true ) {
				$response = array ('result' => "success", 'message' => $msg_success);
			} else {
				$response = array ('result' => "error", 'message' => $msg_error);
			}
			echo json_encode($response);
			
		} else {
			echo json_encode(array ('result' => "error", 'message' => "Bot <strong>Detectado</strong>.! Error Critico.!"));
		}
	} else {
		echo json_encode(array ('result' => "error", 'message' => "Por Favor <strong>Revisa,</strong> Todos los campos son requeridos, prueba denuevo."));
	}
}