<?php
require_once "settings.php";
require "PHPMailerAutoload.php";

// define variables and set to empty values
$name = $text = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (empty($_POST["name"]) || empty($_POST["text"])) {
    echo '<html><body style="font-family:sans-serif;">Keine Daten angegeben!</body></html>';
  } else {
    $name = test_input($_POST["name"]);
    $text = test_input($_POST["text"]);
    $datum = date("Y-m-d");
    $uhrzeit = date("H:i");
    insert($name, $datum, $uhrzeit, $text, $sql_server, $sql_user, $sql_pw, $sql_db);
    send($name, $text, $mail_server, $mail_port, $mail_user, $mail_pw, $sender_mail, $sender_friendlyname, $recipient_mail, $mail_subject);
  }
} else {
  echo '<html><body style="font-family:sans-serif;">Keine Daten vom Formular erhalten!</body></html>';
}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = filter_var($data, FILTER_SANITIZE_STRING);
  return $data;
}

function insert($name, $datum, $uhrzeit, $text, $sql_server, $sql_user, $sql_pw, $sql_db) {
  // open db connection...
  $mysqli = new mysqli($sql_server, $sql_user, $sql_pw, $sql_db);
  if ($mysqli->connect_errno) {
    echo "Verbindung zur Datenbank fehlgeschlagen!";
    echo "Fehlernummer: " . $mysqli->connect_errno . "\n";
    die();
  }
  if (!mysqli_query($mysqli, "SELECT 1 FROM feedback LIMIT 1;")) {
    echo "<html><body style='font-family:sans-serif;'>Tabelle nicht in Datenbank gefunden! Bitte Datenbank-Setup ausführen (siehe README). <br /> Fehlerbeschreibung: " . mysqli_error($mysqli) . "</body></html>";
    die();
  }
  // avoid sql injection...
  $statement = $mysqli->prepare("INSERT INTO feedback (name, datum, zeit, text) VALUES (?, ?, ?, ?)");
  // set the variables...
  $statement->bind_param('ssss', $name,$datum,$uhrzeit,$text);
  // save...
  $statement->execute();
  // and close.
  $mysqli->close();
}

function send($name, $text, $mail_server, $mail_port, $mail_user, $mail_pw, $sender_mail, $sender_friendlyname, $recipient_mail, $mail_subject) {
  // create new mailer class
  $mail = new PHPMailer();
  $mail->IsSMTP();
  $mail->CharSet = 'UTF-8';

  // set credentials from settings.php
  $mail->Host = $mail_server;
  $mail->Port = $mail_port;
  $mail->Username = $mail_user;
  $mail->Password = $mail_pw;

  // set the mail headers
  $mail->setFrom($sender_mail, $sender_friendlyname);
  $mail->addAddress($recipient_mail);

  $mail->Subject = $mail_subject;
  $mail->Body = "Feedback von: " . $name . "\n" . $text;
  // and send the message.
  if(!$mail->send()) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
  }
  echo '<html><body style="font-family:sans-serif;">Deine Nachricht wurde erfolgreich gesendet. Danke!</body></html>';
}
?>
