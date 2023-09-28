
<?php
require("includes/config.inc.php");
require("includes/common.inc.php");
require("includes/conn.inc.php");

$keys = ["VN","NN"];
for($i=0; $i<count($keys); $i++) {
	if(!isset($_POST[$keys[$i]])) { $_POST[$keys[$i]] = ""; }
}
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
		<h1>Rezeptübersicht je User</h1>
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
			<label>
				Vorname:
				<input type="text" name="VN" value="<?php echo($_POST['VN']); ?>">
			</label>
            <label>
				Nachname:
				<input type="text" name="NN" value="<?php echo($_POST['NN']); ?>">
			</label>
			<input type="submit" value="filtern">
		</form>
        <ul>
        <?php
        $w="";
		if(count($_POST)>0){
            $arr = [];
            if(strlen($_POST["VN"])>0) {
                $arr[] = "Vorname LIKE '%" . $_POST["VN"] . "%'";
		    }
            if(strlen($_POST["NN"])>0) {
                $arr[] = "Nachname LIKE '%" . $_POST["NN"] . "%'";
		    }
			if(count($arr)>0) {
				$w= "
					WHERE(
						" . implode(" AND ",$arr) . "
					)
				";
			}
        }
        $sql1="
        SELECT
            tbl_user.Vorname,
            tbl_user.Nachname,
            tbl_user.Emailadresse,
            tbl_rezepte.Titel,
            tbl_rezepte.Beschreibung
        FROM tbl_user
        LEFT JOIN tbl_rezepte ON tbl_user.IDUser = tbl_rezepte.FIDUser
        " . $w . "
        ORDER BY Nachname ASC, Vorname ASC";
        ta($sql1);
        $rezepte = $conn->query($sql1) or die("Fehler in der Query: " . $conn->error . "<br>" . $sql1);
        while($rezept = $rezepte->fetch_object()) {
            echo('
                <li>'. $rezept->Vorname .' '. $rezept->Nachname .' ('. $rezept->Emailadresse .'):<br>
                '. $rezept->Titel .': '. $rezept->Beschreibung .'</p>
                </li>
            ');
        }
        ?>
        </ul>
	</body>
</html>