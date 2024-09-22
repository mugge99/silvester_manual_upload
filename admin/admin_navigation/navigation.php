    <?php 
    if(!$_SESSION["username"] == "Admin"){
        header("Location: ../Login/Spieler_einschreiben.php");
        exit;
    }
    ?>

    <!--Stylesheet um CSS auszulagern-->
    <title>Navigation</title>
  </head>
  <body>
    <header>
      <nav class="navbar navbar-dark bg-info top-leiste">
        <div class="container-fluid">
          <a class="navbar-brand" href="register_user.php">Neuen User anlegen</a>
          <a class="navbar-brand" href="admin_page.php">ADMIN Hauptmenü</a>
          <a class="navbar-brand" href="admin_game_stats.php">Statistiken ansehen</a>
          <a class="navbar-brand" href="../Logout/logout.php"><i class="fas fa-sign-out-alt"></i></a>
        </div>
      </nav>
    </header>
