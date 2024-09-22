<?php
//Für individuelle Website-Farbe
//header("Content-type: text/css");

session_start();

$background = $_SESSION["color"];
?>

html {
    background-color: <?=$background?>;
}

.aufgabenbereich{
    background-color: <?=$background?>;
}

.navbar{
    background-color: <?=$background?>;
}