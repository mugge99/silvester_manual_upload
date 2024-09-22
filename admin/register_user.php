        <!--Stylesheet um CSS auszulagern-->
        <link rel="stylesheet" type="text/css" href="admin_style/admin_style.css">

        <?php 
        require "admin_navigation/before.php";
        require "admin_navigation/navigation.php";
        ?>
    
        <title>Registrieren</title>
    </head>
    <body>
        <h1 id="title">ADMIN / REGISTRIERUNG</h1>
        <br><br>

        <div>
            <form method="POST" action="register_user.php">
                <input class="eingabe" type="text" name="name" placeholder="Username" required>
                <br><br>
                <input class="eingabe" type="text" name="passwort" placeholder="Passwort" required>
                <br><br>
                <input class="eingabe" type="text" name="passwort2" placeholder="Passwort wiederholen" required>
                <br><br>
                <br>
                <button type="submit" name="submit">Erstellen</button>
            </form>
            <br>
            <?php 
                if(isset($_POST["submit"])){
                    require("../Login/einschreiben.php");
                    $stmt = $mysql->prepare("SELECT * FROM user WHERE username = :user");
                    $stmt->bindParam(":user", $_POST["name"]);
                    $stmt->execute();
                    $count = $stmt->rowCount();
                    if($count == 0){
                        if($_POST["passwort"] == $_POST["passwort2"]){
                            $stmt = $mysql->prepare("INSERT INTO user (username, password) VALUES (:user, :pw)");
                            $stmt->bindParam(":user", $_POST["name"]);
                            $hash = password_hash($_POST["passwort"], PASSWORD_BCRYPT);
                            $stmt->bindParam(":pw", $hash);
                            $stmt->execute();
                            echo "Der User " . $_POST["name"] . " wurde angelegt.";
                        } else {
                            echo "Die Passwörter stimmen nicht überein.";
                        }
                    } else {
                        echo "Es existiert bereits ein Account mit diesem Namen.";
                    }
                }
            ?>
        </div>
        
        <?php 
        require "admin_navigation/after.php";
        ?>