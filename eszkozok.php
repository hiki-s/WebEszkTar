<?php
	session_start();
	/*
	if($_SESSION["felhnev"] != 'Admin'){
		session_destroy();
		header("Location: index.php");
		exit();
	}*/
	if($_SESSION["felhnev"] == NULL){
		session_destroy();
		header("Location: index.php");
		exit();
	}
	ob_start();
	
?>
<!DOCTYPE html>
<html>
<head>
<link rel = "icon" href ="https://img.icons8.com/android/24/000000/retro-tv.png" type = "image/x-icon">
<title>Eszközök</title>
<meta charset ="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="eszkozok.css">
<!-- 
	Hátterek forrása:https://wonderfulengineering.com/41-free-high-definition-blue-wallpapers-for-download/
	Ikon forrása: https://icons8.com/
-->
<style>
<?php if($_SESSION["felhnev"] == 'Admin'){ ?>
div.targyak {
	display: block;
	position:relative;
	float: left;
	border: 3px solid blue;
	margin-left:5%;
	margin-top:5%;
	background-color:cyan;
}
<?php }else{ ?>
div.targyak {
	display: block;
	position:relative;
	float: left;
	border: 3px solid blue;
	margin-left:25%;
	margin-top:5%;
	background-color:cyan;
}
<?php }?>
</style>
</head>
<body>

<div id="menu">
	<ul id="menuSor">
		<?php if($_SESSION["felhnev"] == 'Admin'){ ?>
		<li><a href="diakok.php">Diákok</a></li>
		<?php } ?>
		<li class="akt"><a href="eszkozok.php">Eszközök</a></li>
		<li class="kilepes"><a href="index.php">Kilépés</a></li>
		<?php if($_SESSION["felhnev"] == 'Admin'){ ?>
		<li><a href="igenyles.php">Igénylések</a></li>
		<?php }else{ ?>
		<li><a href="diakokIgenyles.php">Igénylések</a></li>
		<?php } ?>
		<?php if($_SESSION["felhnev"] == 'Admin'){ ?>
			<li class="kilepes"><a href="adminOldal.php">Vissza</a></li>
		<?php }else{ ?>
			<li class="kilepes"><a href="diakOldal.php">Vissza</a></li>
		<?php } ?>
		<li style="text-align:center;margin-top:10px; margin-right:5%; font-size: 25px;" class="kilepes"><?php echo $_SESSION["felhnev"];?></li>
	</ul> 
</div>


<?php
	$link = mysqli_connect("localhost", "root", "", "eszkoztar");

?>

<div class="container">

    <div class="targyak">
        <table id="table" border="1">
		<thead>
			<tr>
				<th>ID</th>
				<th>Név</th>
				<th>Típus</th>
				<th>Darab</th>
			</tr>
		</thead>
        <?php
			$sql = mysqli_query($link, "SELECT * FROM eszkozok ORDER BY id ASC");
			while($row = mysqli_fetch_array($sql)){ ?>
			<tr>	
				<td><?php echo $row['id']?></td>
				<td><?php echo $row['nev']?></td>
				<td><?php echo $row['tipus']?></td>
				<td><?php echo $row['darab']?></td>
			</tr>
		<?php } ?>
		</table>
    </div>
			
			
			
	<?php if($_SESSION["felhnev"] == 'Admin'){ ?>		
    <div class="modosit">
		<form action="<?=$_SERVER['PHP_SELF']?>" method="post">
			<h1>Módosítás</h1>
			<label>ID :</label><br><input type="text" name="id1" id="id1" ><br>
			<label>Név :</label><br><input type="text" name="nev1" id="nev1"><br>
			<label>Típus :</label><br><input type="text" name="tipus1" id="tipus1"><br>
			<label>Darab :</label><br><input type="number" name="darab1" id="darab1" min="1" value="1"><br>
			<br>
			<input type="submit" name="modify" value="Módosít">
			<input type="button" name="delete" value="Töröl" onclick="biztosTorli()"><br><br>
			
			<div id="tolriE" style="display:none; border:0.5px solid black; ">
			Biztosan törli?<br>
			<br>
				<input type="submit" name="igen" value="Igen">
				<input type="button" name="nem" value="Nem" onclick="biztosTorli()">
				<br><br>
			</div>
		</form>
    </div>

	<div class="ujEszk">
		<h1>Új Eszköz</h1>
		<form action="<?=$_SERVER['PHP_SELF']?>" method="post">
			<label>Eszköz neve :</label><br><input type="text" name="eNev"><br>
			<label>Típusa :</label><br><input type="text" name="eTipus"><br>
			<label>Darab :</label><br><input type="number" min="1" value="1" name="eDb"><br>
			<br>
			<input type="submit" name="add" value="Hozzáad"><br><br>
		</form>
	</div>
	<?php } ?>
	
	<div class="felvesz">
		<form action="<?=$_SERVER['PHP_SELF']?>" method="post">
			<h1>Igénylés</h1>
			<label>ID :</label><br><input type="text" name="id" id="id"><br>
			<label>Név :</label><br><input type="text" name="nev" id="nev"><br>
			<label>Típus :</label><br><input type="text" name="tipus" id="tipus"><br>
			<label>Darab :</label><br><input type="number" name="darab" id="darab" min="1" value="1"><br>
			<label>Ettől :</label><br><input style="max-width: 190px;" name="mikortol" id="mikortol" type="datetime-local" value="<?php echo date('Y-m-d h:m:s');?>"><br>
			<label>Eddig :</label><br><input style="max-width: 190px;" name="meddig" id ="meddig" type="datetime-local" value="<?php echo date('Y-m-d h:m:s');?>"><br>
			<label>Megjegyzés :</label><textarea name="megj" id="megj" maxlength="50"></textarea><br>
			<br>
			<input type="submit" name="igenyel" value="Igényel"><br><br>
		</form>
    </div>
	
	
	
	
	
	
</div>


<?php
	if(isset($_POST['igenyel'])){
		$id = $_POST['id'];
		$nev = $_POST['nev'];
		$tipus = $_POST['tipus'];
		$darab = $_POST['darab'];
		$mikortol = $_POST['mikortol'];
		$meddig = $_POST['meddig'];
		$megj = $_POST['megj'];
		$kicsoda = $_SESSION["felhnev"];
		
		if(empty($id) || empty($nev) || empty($tipus) || empty($darab) || empty($mikortol) || empty($meddig) || empty($kicsoda)){
			echo "<script>alert(\"Üres mező!\");</script>";
		}else{
		
		$sql = "SELECT * FROM eszkozok WHERE nev = '$nev' AND id ='$id' AND tipus='$tipus'";
		if($result = mysqli_query($link, $sql)){
			if(mysqli_num_rows($result) > 0){
				$add = "INSERT INTO igenyles (id, eszkozNev, tipus, mennyit, ettol, eddig, kicsoda, megjegyzes) VALUES ('$id', '$nev', '$tipus', '$darab', '$mikortol', '$meddig', '$kicsoda','$megj');";
				if (mysqli_query($link, $add)) {
					header('Refresh: 0');
					echo "<script>alert(\"Az igenylés sikeres!\");</script>";
				} else {
					echo "Error: Nem sikerült a frissítés: " . mysqli_error($link);
				}
			}else{
				echo "<script>alert(\"Rossza adatokat adott meg!\");</script>";
			}
			
		}else{
				echo "<script>alert(\"Rossz id név vagy típust adott meg\");";
			}
		}
	}
	
	

	
	if(isset($_POST['modify'])){
		$id = $_POST['id1'];
		$nev = $_POST['nev1'];
		$tipus = $_POST['tipus1'];
		$darab = $_POST['darab1'];
		
		if(empty($id) || empty($nev) || empty($tipus) || empty($darab)){
			echo "<script>alert(\"Üres mező!\");</script>";
		}else{
		
		$sql = "SELECT * FROM eszkozok WHERE id ='$id' ";
		if($result = mysqli_query($link, $sql)){
			if(mysqli_num_rows($result) > 0){
				$add = "UPDATE eszkozok SET nev='$nev', tipus='$tipus', darab='$darab' WHERE id ='$id'";
				if (mysqli_query($link, $add)) {
					header('Refresh: 0');
					echo "<script>alert(\"Az frissítés sikeres volt!\");</script>";
				} else {
					echo "Error: Nem sikerült a frissítés: " . mysqli_error($link);
				}
			}else{
				echo "<script>alert(\"Rossza adatokat adott meg!\");</script>";
			}
			
		}else{
				echo "<script>alert(\"Rossz id név vagy típust adott meg\");";
			}
		}
	}
	
	
	if(isset($_POST['igen'])){
		$id = $_POST['id1'];
		$nev = $_POST['nev1'];
		$tipus = $_POST['tipus1'];
		$darab = $_POST['darab1'];
		
		if(empty($id) || empty($nev) || empty($tipus) || empty($darab)){
			echo "<script>alert(\"Üres mező!\");</script>";
		}else{
			$sql = "SELECT * FROM eszkozok WHERE id ='$id' AND nev='$nev' AND tipus='$tipus' AND darab='$darab'";
			if($result = mysqli_query($link, $sql)){
				if(mysqli_num_rows($result) > 0){
					$delete = "DELETE FROM `eszkozok` WHERE id='$id' AND nev='$nev' AND tipus='$tipus' AND darab='$darab'";
					if (mysqli_query($link, $delete)) {
						header('Refresh: 0');
						echo "<script>alert(\"Az törlés sikeres volt!\");</script>";
					} else {
						echo "Error: Nem sikerült a törlés: " . mysqli_error($link);
					}
				}else{
					echo "<script>alert(\"Rossza adatokat adott meg!\");</script>";
				}
				
			}
		}
	}
	
	
	
	

	if(isset($_POST['add'])){
		$eNev = $_POST['eNev'];
		$eTipus = $_POST['eTipus'];
		$eDb = $_POST['eDb'];
		
		if(empty($eNev) ||empty($eTipus) || empty($eDb)){
			echo "<script>alert(\"Üres mező!\");</script>";
		}else if(!preg_match("/^[0-9]*$/", $eDb)){
			echo "A darabhoz számot írjon!";
		}else {
			
			$ujEszkoz = "INSERT INTO eszkozok (nev, tipus, darab) VALUES ('$eNev', '$eTipus','$eDb')";
			if (mysqli_query($link, $ujEszkoz)) {
				header('Refresh: 0');
				echo "Sikeres felvétel";
			} else {
				echo "Error: Nem sikerült a felvétel: " . mysqli_error($link);
			}
		}
	}
	

	
ob_end_flush();
?>

<script>
	function biztosTorli(){
		
		var x = document.getElementById("tolriE");
		if (x.style.display === "none") {
			x.style.display = "block";
		} else {
			x.style.display = "none";
		}
	}
	
</script>
<?php if($_SESSION["felhnev"] == 'Admin'){ ?>
	<script>
		var rIndex,
		table = document.getElementById("table");
		function selectedRowToInput(){
			for(var i = 1; i < table.rows.length; i++){
				table.rows[i].onclick = function(){
					rIndex = this.rowIndex;
					document.getElementById("id").value = this.cells[0].innerHTML;
					document.getElementById("id1").value = this.cells[0].innerHTML;
					document.getElementById("nev").value = this.cells[1].innerHTML;
					document.getElementById("nev1").value = this.cells[1].innerHTML;
					document.getElementById("tipus").value = this.cells[2].innerHTML;
					document.getElementById("tipus1").value = this.cells[2].innerHTML;
					document.getElementById("darab1").value = this.cells[3].innerHTML;
				};
			}
		}
	selectedRowToInput();
	</script>
<?php }else { ?>
	<script>
		var rIndex,
		table = document.getElementById("table");
		function selectedRowToInput(){
			for(var i = 1; i < table.rows.length; i++){
				table.rows[i].onclick = function(){
					rIndex = this.rowIndex;
					document.getElementById("id").value = this.cells[0].innerHTML;
					document.getElementById("nev").value = this.cells[1].innerHTML;
					document.getElementById("tipus").value = this.cells[2].innerHTML;
				};
			}
		}
	selectedRowToInput();
	</script>
<?php }?>

</body>
</html>