<?php
require("includes/config.inc.php");
require("includes/common.inc.php");
require("includes/conn.inc.php");
?>

<!doctype html>
<html lang="de">
	<head>
		<title>Rezepte</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, height=device-height, initial-scale=1">
		<link rel="stylesheet" href="css/common.css">
	</head>
	<body>
		<h1>Rezeptdarstellung</h1>
        <p>Bitte wählen Sie aus der folgenden Navigation.</p>
		<nav>
			<ul>
				<li><a href="index.html">Startseite</a></li>
				<li><a href="rezeptdarstellung.php">Rezeptdarstellung</a></li>
				<li><a href="rezeptübersicht.php">Rezeptübersicht je User</a></li>
				<li><a href="zutaten.php">Zutaten und Rezepte</a></li>
			</ul>
		</nav>
        <?php
        $sql1 = "
        SELECT
            tbl_rezepte.IDRezept,
            tbl_rezepte.Titel,
            tbl_rezepte.Beschreibung,
            tbl_rezepte.DauerVorbereitung,
            tbl_rezepte.DauerZubereitung,
            tbl_rezepte.DauerRuhen,
            tbl_rezepte.AnzahlPersonen,
            tbl_user.Vorname,
            tbl_user.Nachname,
            tbl_schwierigkeitsgrade.Titel AS Tit,
            tbl_schwierigkeitsgrade.Beschreibung AS Bes
        FROM tbl_rezepte
        LEFT JOIN tbl_user ON tbl_rezepte.FIDUser = tbl_user.IDUser
        LEFT JOIN tbl_schwierigkeitsgrade ON tbl_rezepte.FIDSchwierigkeitsgrad = tbl_schwierigkeitsgrade.IDSchwierigkeitsgrad
        ORDER BY Titel ASC
    ";
    ta($sql1);
    $rezepte = $conn->query($sql1) or die("Fehler in der Query: " . $conn->error . "<br>" . $sql1);
    while($rezept = $rezepte->fetch_object()) {
        echo('
            <article>
                <h1>'. $rezept->Titel .'</h1>
                <p>(von '. $rezept->Vorname .' '. $rezept->Nachname .')</p>
                <p>Allgemeine Beschreibung: '. $rezept->Beschreibung.'</p>
                <dl>
                    <dt>Zeiten:</dt>
                    <dd>Vorbereitungszeit: '. $rezept->DauerVorbereitung .'</dd>
                    <dd>Zubereitungszeit: '. $rezept->DauerZubereitung .'</dd>
                    <dd>Nachbereitungs- oder Ruhezeit: '. $rezept->DauerRuhen .'</dd>
                </dl>
                <p>Für '. $rezept->AnzahlPersonen .' Personen</p>
                <p>Schwierigkeitsgrad: '. $rezept->Tit .' - '. $rezept->Bes. '</p>
                <h3>Zutaten:</h3>
                <ul>
                ');
                $sql2 = "
                SELECT
                    tbl_rezepte_zutaten.Anzahl,
                    tbl_einheiten.Bezeichnung As Einheit,
                    tbl_zutaten.Bezeichnung AS Zutat
                FROM tbl_rezepte_zutaten
                LEFT JOIN tbl_einheiten ON tbl_rezepte_zutaten.FIDEinheit = tbl_einheiten.IDEinheit
                LEFT JOIN tbl_zutaten ON tbl_rezepte_zutaten.FIDZutat = tbl_zutaten.IDZutat
                WHERE tbl_rezepte_zutaten.FIDRezept = ". $rezept->IDRezept;
            $zutaten = $conn->query($sql2) or die("Fehler in der Query: " . $conn->error . "<br>" . $sql2);
            while($zutat = $zutaten->fetch_object()) {
                echo('
                    <li>'. $zutat->Anzahl .' '. $zutat->Einheit .' '. $zutat->Zutat .'</li>
                ');
            }
                echo('
                </ul>
                <h3>Zubereitungsschritte:</h3>
                <ol>
                ');
                $sql3 = "
                SELECT *
                FROM tbl_zubereitungsschritte
                WHERE tbl_zubereitungsschritte.FIDRezept = ". $rezept->IDRezept . "
                ORDER BY Reihenfolge";
            $schritte = $conn->query($sql3) or die("Fehler in der Query: " . $conn->error . "<br>" . $sql3);
            while($schrit = $schritte->fetch_object()) {
                if($schrit->Titel != null){
                    echo('
                    <li>'. $schrit->Titel .': '. $schrit->Beschreibung .'</li>
                ');
                }
                else{
                    echo('
                    <li>'. $schrit->Beschreibung .'</li>
                ');
                }
            }
                echo('
                </ol>
            </article>
        ');
    }
        ?>
	</body>
</html>