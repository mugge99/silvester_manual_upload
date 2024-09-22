        <!--Stylesheet um CSS auszulagern-->
        <link rel="stylesheet" type="text/css" href="./admin_style/admin_style.css">

        <?php 
        require "admin_navigation/before.php";
        require "admin_navigation/navigation.php";
        require("../Login/einschreiben.php");

        $stmt = $mysql->prepare("SELECT current_task FROM current_task WHERE username = :user");
        $stmt->bindParam(":user", $_SESSION["username"]);
        $stmt->execute();
        $row = $stmt->fetch();
        $_SESSION["admin_spielstand_counter"] = $row["current_task"];

        //$_SESSION["timeout"] = false;
        
        /*$_SESSION["countnew"] = 0;
        if($_SESSION["timeout"] == false){
            $stmt = $mysql->prepare("SELECT count(*) FROM tasks_historie");
            $stmt->execute();
            $count = $stmt->fetch();
            $_SESSION["countold"] = $_SESSION["countnew"];
            $_SESSION["countnew"] = $count[0];
            $newentries = $_SESSION["countnew"] - $_SESSION["countold"];
            if($_SESSION["countnew"] > $_SESSION["countold"]){
                $stmt = $mysql->prepare("SELECT ID_task, user FROM tasks_historie LIMIT :newentries OFFSET :lastentrybefore");
                $stmt->bindParam(":newentries", $newentries, PDO::PARAM_INT);
                $stmt->bindParam(":lastentrybefore", $_SESSION["countold"], PDO::PARAM_INT);
                $stmt->execute();
                $data_array = $stmt->fetchAll();

                foreach($data_array AS $data){
                    $stmt = $mysql->prepare("SELECT titel, text FROM tasks WHERE ID = :id");
                    $stmt->bindParam(":id", $data["ID_task"]);
                    $stmt->execute();
                    $row = $stmt->fetch();
                    $ausgabe = $data["user"] . " hat gerade folgenden Task beendet: " . $row["titel"] . ", " . $row["text"];
                    echo $ausgabe;
                    /*'<script type="text/javascript">
                    popup.classList.add("open-popup");
                    audio.play();
                    setTimeout(()=>{popup.classList.remove("open-popup")}, 15000);
                    } 
                    </script>';
                    sleep(2);
                }
            }
            $timeout = true;
        }
        */

        //sleep(10);
        //$timeout = false;
        ?>
    
        <title>Current Game Stats</title>
    </head>
    <body>
        <span>
            <!--<button type="submit" class="btn" onclick="openPopup()">Submit</button>-->
            <div class="popup" id="popup">
                <h3 id="popuptext">Matze hat eine Aufgabe erledigt!</h3>
                <audio id="audio" 
                        src="../lib/Task_done.mp3" 
                        type="audio/mp3" 
                    ></audio>
            </div>
        </span>

        <script>
            let popuptext = document.getElementById("popuptext");
            let popup = document.getElementById("popup");
            let audio = document.getElementById("audio");

            function loadDBContent(){
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function(){
                    if (this.readyState == 4 && this.status == 200 && this.responseText != "0") {
                        audio.currentTime = 0;
                        popuptext.innerHTML = this.responseText;
                        popup.classList.add("open-popup");
                        audio.play();
                        setTimeout(()=>{popup.classList.remove("open-popup")}, 15000);
                    }
                };
                xhttp.open("GET", "historie_lesen.php", true);
                xhttp.send();
                timeout();
            }

            function openPopup(){
                popup.classList.add("open-popup");
                audio.play();
                setTimeout(()=>{popup.classList.remove("open-popup")}, 15000);
            }

            function timeout(){
                //muss hinzugefügt werden, wenn historie_lesen gefixed
                setTimeout(()=>{loadDBContent()}, 20000);
            }

            window.onload = loadDBContent();
        </script>

        <?php 
        require "admin_navigation/after.php";
        ?>