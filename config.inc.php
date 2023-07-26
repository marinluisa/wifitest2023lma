<?php
//definieren einer boolischen variable, um abhängig ob diese true oder false ist in verschiedenen szenarien entsprechend zu handeln
define("TESTMODUS",true);
//definieren eines arrays in welches nötige informationen für den Verbindungsaufbau mit der Datenbank gespeichert wird
define("DB", [
    "host" => "localhost",
    "user" => "root",
    "pwd" => "",
    "name" => "db_name"
]);

if(TESTMODUS){
    error_reporting(E_ALL); //sämtliche Fehler werden protokolliert
    ini_set("display_errors",1); //zeigt die auftretenden und zu protokollierenden Fehler auch tatsächlich an
}
else{
    //Realbetrieb
    error_reporting(0);
    ini_set("display_errors",0);
}
?>