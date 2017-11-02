# Feedback-Script

## Installation
### SMTP server
- put in the credentials to your mail server into the `settings.php` file:
```
$mail_server = "<url to smtp server>";
$mail_user = "username";
$mail_port = "25";
$mail_pw = "<mail password>";
```
Note: If you upload this to a server inside the University of Tübingen, you can simply use "smtpserv.uni-tuebingen.de" with an arbitrary username and an empty password.

### Mail headers
- Specify the recipient and sender of the mail as well as the subject line inside the `settings.php`:
```
$sender_friendlyname = "Feedback-Mail";
$sender_mail = "";
$recipient_mail = "";
$mail_subject = "[Feedback] neuer Eintrag";
```

### MySQL credentials
- also inside the `settings.php` file, put the credentials to your MySQL database:
```
$sql_server = "localhost";
$sql_user = "<username>";
$sql_db = "<database>";
$sql_pw = "<password>";
```

### DB Initialization
- Upload everything to a PHP-enabled directory of your choice and navigate your browser to the `_initdb.php` file.
- Once the table is set up, you can (and probably should) delete this file.

## Usage
- Use the link to the `index.html` file to give your students the opportunity to leave constructive feedback.
- Once a new entry is received, it is recorded to the MySQL database, along with a timestamp and a pseudonym, if the student chose one.
- Also, you will receive an e-mail once there is a new entry.
