<?php
require_once "settings.php";

$connection = new mysqli($sql_server, $sql_user, $sql_pw, $sql_db);

if (mysqli_connect_errno()) {
    printf("Could not connect to MySQL databse: %s\n", mysqli_connect_error());
    exit();
}

$query = "SELECT ID FROM feedback";
$result = mysqli_query($connection, $query);

if(empty($result)) {
  $query = "CREATE TABLE IF NOT EXISTS feedback (
            id INT NOT NULL AUTO_INCREMENT,
            name VARCHAR(32),
            datum DATE,
            zeit TIME,
            text TEXT,
            PRIMARY KEY (id)
            )";
  if (mysqli_query($connection, $query)) {
    echo "Tabelle wurde erfolgreich erstellt.";
  }
  else {
    echo "Fehler: " . $query . "<br>" . mysqli_error($connection);
  }
} else {
  echo "Tabelle existiert bereits. Abbruch.";
}

mysqli_close($connection);
?>
