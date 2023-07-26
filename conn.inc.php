<?php
//versuche verbindung zur Datenbank aufbauen
$conn = new MySQLi(DB["host"], DB["user"], DB["pwd"], DB["name"]);
//kontrollieren ob es Fehler im Verbindungsaufbau zur Datenbank gibt
if($conn->connect_errno>0){
    if(TESTMODS){
        //wenn der Testmodus aktiv ist dann gebe ich Fehler aus
        die("Fehler im Verbindungsaufbau: " . $conn->connect_error);
    }
    else{
        //wenn der Testmodus inactiv ist dann leite ich auf eine seite welche über einen Fehler informiert
        //Handhabung im Realbetrieb
        header("Location: errors/connect.html");
    }
}
//Standart_Client_Zeichensatz ändern auf utf8mb4
$conn->set_charset("utf8mb4");
?>