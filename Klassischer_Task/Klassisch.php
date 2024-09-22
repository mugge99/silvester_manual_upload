<?php 
  session_start();
  if(!isset($_SESSION["username"])){
    header("Location: ../Login/Spieler_einschreiben.php");
    exit;
  }

  //echo $_SESSION["color"];
  require("../Login/einschreiben.php");
  $stmt = $mysql->prepare("SELECT * FROM current_task WHERE username = '{$_SESSION['username']}'");
  $stmt->execute();
  $count = $stmt->rowCount();
  if($count == 1){
    //Spieler war schonmal auf der Spieloberfläche eingeloggt, hat einen Task zugewiesen bekommen und damit Eintrag in Table current_task
    $row = $stmt->fetch();
    $_SESSION["task_id"] = $row["current_task"];
    if($_SESSION["task_id"] == 0){
      $_SESSION["text"] = "Es gibt keine weiteren Tasks mehr zu spielen. Du wurdest automatisch ausgeloggt.";
      header("Location: ../Login/Spieler_einschreiben.php");
      exit;
    }
  } else{
    //Erster Aufruf (Erster Login) der Spieloberfläche eines Spielers überhaupt benötigt Task
    $stmt = $mysql->prepare("SELECT * FROM tasks");
    $stmt->execute();
    $count = $stmt->rowCount();
    $task_id = random_int(1, $count);
    $data_array = $stmt->fetchAll();

    foreach($data_array AS $data){
      if($data["ID"] == $task_id){
        $_SESSION["task_id"] = $data["ID"];
        break;
      }
    }

    //Erster Aufruf des Spiels von einem Spieler überhaupt benötigt Eintrag über aktuellen Task in Table current_task
    $stmt = $mysql->prepare("INSERT INTO current_task (username, current_task) VALUES (:username, :current_task)");
    $stmt->bindParam(":username", $_SESSION["username"]);
    $stmt->bindParam(":current_task", $_SESSION["task_id"]);
    $stmt->execute();
  }

  if(isset($_POST["task_done"])){
    //Abgeschlossenen Task in Table tasks_historie einfügen
    $stmt = $mysql->prepare("INSERT INTO tasks_historie (ID_task, user) VALUES (:ID_task, :user)");
    $stmt->bindParam(":ID_task", $_SESSION["task_id"]);
    $stmt->bindParam(":user", $_SESSION["username"]);
    $stmt->execute();
    unset($_SESSION["task_id"]);

    //Array aus allen verfügbaren (noch nicht vom Spieler abgeschlossenen) Tasks holen
    $stmt = $mysql->prepare("SELECT * FROM 
    (SELECT t.ID, t.titel, t.text, t.singletask, t.referee, h.ID_task, h.user
    FROM tasks AS t LEFT OUTER JOIN 
    (SELECT ID_task, user FROM tasks_historie WHERE user = '{$_SESSION['username']}') AS h
    ON (t.ID = h.ID_task)) AS e WHERE e.user IS NULL");
    $stmt->execute();
    $count = $stmt->rowCount();
    if($count == 0){
      $_SESSION["text"] = "Es gibt keine weiteren Tasks mehr zu spielen. Du wurdest automatisch ausgeloggt.";
      $_SESSION["task_id"] = 0;
      $stmt = $mysql->prepare("UPDATE `current_task` SET `current_task` = :current_task WHERE `current_task`.`username` = :user");
      $stmt->bindParam(":current_task", $_SESSION["task_id"]);
      $stmt->bindParam(":user", $_SESSION["username"]);
      $stmt->execute();
      header("Location: ../Login/Spieler_einschreiben.php");
      exit;
    } else{
        //Zufälligen Task durch zufällig generierter Zahl zuweisen
        $data_array = $stmt->fetchAll();
        $array = array();
        foreach($data_array AS $data){
          array_push($array, $data["ID"]);
          /*if($data["ID"] == $task_id){
            $_SESSION["task_id"] = $data["ID"];
            break;*/
        }
      
      $key = array_rand($array, 1);
      $task_id = $array[$key];
      $_SESSION["task_id"] = $task_id;

      //Table-Eintrag aus Table current task mit dem neu zugewiesenen Task überschreiben
      $stmt = $mysql->prepare("UPDATE `current_task` SET `current_task` = :current_task WHERE `current_task`.`username` = :user");
      $stmt->bindParam(":current_task", $_SESSION["task_id"]);
      $stmt->bindParam(":user", $_SESSION["username"]);
      $stmt->execute();
    }
  }

  $stmt = $mysql->prepare("SELECT * FROM tasks WHERE ID = '{$_SESSION['task_id']}'");
  $stmt->execute();
  $row = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="de-de">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--Fontawesome für verschiedene Icons-->
    <script src="https://kit.fontawesome.com/7ed22e9cbd.js" crossorigin="anonymous"></script>
    <!--Stylesheet um CSS auszulagern-->
    <link rel="stylesheet" type="text/css" href="Klassisch.css">
    <!--<link rel="stylesheet" type="text/css" href="../style.php">-->
    <title>Klassischer-Task</title>
  </head>
  <body>
      <header>
        <nav class="navbar navbar-dark bg-info top-leiste">
          <div class="container-fluid">
            <a class="navbar-brand" href="#" id="name">
              <?php echo $_SESSION["username"]; ?></a>
            <a class="navbar-brand" href="#">Silvester 2024</a>
            <a class="navbar-brand" id="logout" href="../Logout/logout.php"><i class="fas fa-sign-out-alt"></i></a>
          </div>
        </nav>
      </header>
      <section class="aufgabenbereich">
        <div class="container">
          <h2 ><?php echo $row["titel"];?></h2>
          <p><?php echo $row["text"] . " " . $row["hinweise"] ?></p>
          <br>
          <a class="player_button" href="#"><button type="submit" name="skip_task" onclick="skipTask()">Aufgabe ändern (3/3)</button></a>
          <form method="POST">
            <a class="player_button"><button type="submit" name="task_done">Aufgabe erledigt</button></a>
          </form >
        </div>
      </section>
  </body>
  <script>
    function skipTask(){

    }

  </script>
  
</html>
