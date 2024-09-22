<!DOCTYPE html>
<html lang="de-de" style="text-align: center;">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!--Fontawesome für verschiedene Icons-->
        <script src="https://kit.fontawesome.com/7ed22e9cbd.js" crossorigin="anonymous"></script>
        <!--Stylesheet um CSS auszulagern-->
        <link rel="stylesheet" type="text/css" href="einschreiben.css">
        <!--<script src="../lib/jquery-3.7.1.js"></script>-->
    <title>Login</title>
    </head>
    <body>
        <h1 id="title">Silvester 2024</h1>
        <br><br>

        <div>
            <form method="POST" action="Spieler_einschreiben.php"> <!--Auf Seite bleiben, außer Anmeldedaten korrekt (siehe php-Code)-->
                <input class="eingabe" type="text" name="name" placeholder="Username" required>
                <br><br>
                <input class="eingabe" type="password" name="passwort" placeholder="Passwort" required>
                <br><br>
                <!--<p>Wähle dein Team</p>
                <input type="radio" name="männlich" id="männlich"> Team Boys<br>
                <input type="radio" name="weiblich" id="weiblich"> Team Girls<br><br>
                <p>Wähle die Farbe, die dich den Abend über begleiten soll.<br>
                (Hintergrundfarbe)</p>
                <input name="favcolor" type="color">-->
                <br>
                <button type="submit" name="submit">Lass uns loslegen!</button>
            </form>
            <br>
            <?php 
                if(isset($_POST["submit"])){
                    require("einschreiben.php");
                    $stmt = $mysql->prepare("SELECT * FROM user WHERE username = :user");
                    $stmt->bindParam(":user", $_POST["name"]);
                    $stmt->execute();
                    $count = $stmt->rowCount();
                    if($count == 1){
                        $row = $stmt->fetch();
                        if(password_verify($_POST["passwort"], $row["password"])){
                            session_start(); //Ab hier können Sessionvariablen deklariert werden?!
                            $_SESSION["username"] = $row["username"];
                            //$_SESSION["color"] = $_POST["favcolor"];
                            if($_SESSION["username"] == "Admin"){
                                header("Location: ../admin/admin_page.php");
                            } else{
                            header("Location: ../Klassischer_Task/Klassisch.php");
                            }
                        } else {
                            echo "Die Anmeldedaten waren nicht korrekt.";
                        }
                    } else {
                        echo "Die Anmeldedaten waren nicht korrekt.";
                    }
                }
                
                session_start();
                if(isset($_SESSION["text"])){
                    echo "<div style='color:red;'>" . $_SESSION["text"] . "</div>";
                    session_destroy();
                }
            ?>
        </div>
        
        <!--JavaScript-Datei für Logik nicht nötig 
        <script src="einschreiben.js"></script>-->
        
    </body>
</html>