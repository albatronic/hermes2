<?php
require_once 'lib/swift_required.php';

// Create the Transport
$transport = Swift_SmtpTransport::newInstance('mail.albatronic.com', 25)
  ->setUsername('info@albatronic.com')
  ->setPassword('i1234o')
  ;

/*
You could alternatively use a different transport such as Sendmail or Mail:

// Sendmail
$transport = Swift_SendmailTransport::newInstance('/usr/sbin/sendmail -bs');

// Mail
$transport = Swift_MailTransport::newInstance();
*/

// Create the Mailer using your created Transport
$mailer = Swift_Mailer::newInstance($transport);

// Create a message
$message = Swift_Message::newInstance('Swift')
  ->setFrom(array('info@albatronic.com' => 'Albatronic'))
  ->setTo(array('sergio.perez@albatronic.com'))
  ->setBody('Probando envío de correo, si te llega reenvíamelo por favor')
  ->attach(Swift_Attachment::fromPath('../banners/caja.jpg'));

// Send the message
$result = $mailer->send($message,$fallos);
echo "Resultado ",$result,"<br/>";
echo "Fallos:";
print_r($fallos);
?>
