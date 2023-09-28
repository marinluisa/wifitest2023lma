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
		<h1>Zutaten und Rezepte</h1>
        <p>Bitte wählen Sie aus der folgenden Navigation.</p>
		<nav>
			<ul>
				<li><a href="index.html">Startseite</a></li>
				<li><a href="rezeptdarstellung.php">Rezeptdarstellung</a></li>
				<li><a href="rezeptübersicht.php">Rezeptübersicht je User</a></li>
				<li><a href="zutaten.php">Zutaten und Rezepte</a></li>
			</ul>
		</nav>
        <form method="post">
            <label for="zutaten">Zutat:</label>
            <select name="zutaten" id="zutaten">
                <option value="waehlen">Bitte Wählen:</option>
                <?php
                    $sql1="
                    SELECT *
                    FROM tbl_zutaten
                    ORDER BY Bezeichnung
                    ";
                    $zutaten = $conn->query($sql1) or die("Fehler in der Query: " . $conn->error . "<br>" . $sql1);
                    while($zutat = $zutaten->fetch_object()) {
                        echo('<option value="'. $zutat->IDZutat .'">'. $zutat->Bezeichnung .'</option>');
                    }
                ?>
            </select>
            <script type="text/javascript">
                document.getElementById('zutaten').value = "<?php echo $_POST['zutaten'];?>";
            </script>
            <input type="submit" value="Filtern">
        </form>
        <ul>
        <?php
        $w="";
        	if(count($_POST)>0 && strlen($_POST["zutaten"])>0){
                $w="WHERE tbl_rezepte_zutaten.FIDZutat = ". $_POST["zutaten"];
            }
            $sql2="
            SELECT
                tbl_rezepte.Titel,
                tbl_rezepte.Beschreibung,
                tbl_rezepte.AnzahlPersonen,
                tbl_user.Vorname,
                tbl_user.Nachname,
                tbl_rezepte_zutaten.FIDZutat
            FROM tbl_rezepte
            LEFT JOIN tbl_user ON tbl_rezepte.FIDUser = tbl_user.IDUser
            LEFT JOIN tbl_rezepte_zutaten ON tbl_rezepte.IDRezept = tbl_rezepte_zutaten.FIDRezept
            ". $w ."
            GROUP BY tbl_rezepte_zutaten.FIDRezept
            ORDER BY Titel ASC
            ";
            $rezepte = $conn->query($sql2) or die("Fehler in der Query: " . $conn->error . "<br>" . $sql2);
            while($rezept = $rezepte->fetch_object()) {
                echo('
                    <li>'. $rezept->Titel .'(von '. $rezept->Vorname .' '. $rezept->Nachname .', für '. $rezept->AnzahlPersonen .' Personen): <br>
                    '. $rezept->Beschreibung.'</li>
                ');
            }
        ?>
        </ul>
	</body>
</html>