<!--HTML-Code done-->
        <!--Stylesheet um CSS auszulagern-->
        <link rel="stylesheet" type="text/css" href="admin_style/admin_style.css">
        <?php 
        require "admin_navigation/before.php";
        ?>
        
        <title>ADMIN</title>
    </head>
    <body>
        <h1 id="title">ADMIN OVERVIEW</h1>
        <br>

        <div>
        <a class="adm_button" href="register_user.php"><button type="submit" name="submit_to_user">Neuen User anlegen</button></a>
        <a class="adm_button" href="admin_game_stats.php"><button type="submit" name="submit_to_overview">Übersicht Spielstand</button></a>
        <br>
        <a class="adm_button_2" href="../Logout/logout.php"><button type="submit" name="submit_to_logout"><i class="fas fa-sign-out-alt"></button></i></a>
        </div>

        <?php 
        require "admin_navigation/after.php";
        ?>