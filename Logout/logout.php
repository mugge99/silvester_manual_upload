<!--PHP DONE-->

<?php 
session_start();
$_SESSION["text"] = "Logout erfolgreich";
header("Location: ../Login/Spieler_einschreiben.php");
?>
