<?php
        require("../Login/einschreiben.php");
        session_start();
        
        //$_SESSION["countnew"] = 0;
            $stmt = $mysql->prepare("SELECT count(*) FROM tasks_historie");
            $stmt->execute();
            $count = $stmt->fetch();
            $_SESSION["countold"] = $_SESSION["admin_spielstand_counter"];
            $_SESSION["countnew"] = $count[0];

            //Für jeden Durchlauf hier muss nur ein (der nächste neue) Eintrag gefetcht werden.
            //Wenn also countnew > countold, dann countold+1 und dieser Eintrag wird sich geschnappt.
            //Danach ist countnew einfach der Wert von countold (also alter Wert + 1) und in 15 Sekunden wird dann 
            //sowieso erneut die Datenbanktabelle nach neuen Einträgen abgefragt.
            //Wenn dann noch ein Eintrag von vorher aussteht (weil 2 Personen innerhalb eines Durchlaufes eine Aufgabe beendet haben),
            //Wird dieser noch nicht ausgegebene Wert noch gefetcht.
            if($_SESSION["countnew"] == 0){
                echo "0";
            } else if($_SESSION["countnew"] <= $_SESSION["countold"]){
                echo "0";
            }else { //countnew muss >= countold sein, was bedeutet, dass mindestens ein neuer Eintrag in tasks_historie enthalten ist.
                $_SESSION["admin_spielstand_counter"] = $_SESSION["admin_spielstand_counter"] + 1;
                $stmt = $mysql->prepare("UPDATE current_task SET current_task = :current_task WHERE username = :user");
                $stmt->bindParam(":current_task", $_SESSION["admin_spielstand_counter"]);
                $stmt->bindParam(":user", $_SESSION["username"]);
                $stmt->execute();

                $stmt = $mysql->prepare("SELECT ID_task, user FROM tasks_historie LIMIT 1 OFFSET :lastentrybefore");
                $stmt->bindParam(":lastentrybefore", $_SESSION["countold"], PDO::PARAM_INT);
                $stmt->execute();
                $data = $stmt->fetch();

                $stmt = $mysql->prepare("SELECT titel, text FROM tasks WHERE ID = :id");
                $stmt->bindParam(":id", $data["ID_task"]);
                $stmt->execute();
                $row = $stmt->fetch();
                $ausgabe = $data["user"] . " hat gerade folgenden Task beendet: " . '<br><br>' . $row["titel"] . "<br>" . $row["text"];
                echo $ausgabe;
            } 

            /*$newentries = $_SESSION["countnew"] - $_SESSION["countold"];
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
                    //'<script type="text/javascript">
                    //popup.classList.add("open-popup");
                    //audio.play();
                    //setTimeout(()=>{popup.classList.remove("open-popup")}, 15000);
                    //} 
                    //</script>';
                }
            }*/