<?php
	session_start();
	
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
<title>Igénylések</title>
<link rel = "icon" href ="https://img.icons8.com/android/24/000000/retro-tv.png" type = "image/x-icon">
<meta charset ="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="igenyles.css">
<!-- 
	Hátterek forrása:https://wonderfulengineering.com/41-free-high-definition-blue-wallpapers-for-download/
	Ikon forrása: https://icons8.com/
-->
</head>
<body>
<div id="menu">
	<ul id="menuSor">
		<?php if($_SESSION["felhnev"] == 'Admin'){ ?>
		<li><a href="diakok.php">Diákok</a></li>
		<?php } ?>
		<li><a href="eszkozok.php">Eszközök</a></li>
		<li class="akt"><a href="diakokIgenyles.php">Igénylések</a></li>
		<li class="kilepes"><a href="index.php">Kilépés</a></li>
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
	$userObj = mysqli_query($link, "SELECT * FROM igenyles ORDER BY mikor ASC");

?>


<div class="container">
    <div id="igenylesek">
		<table id="table" border="1">
			<thead>
				<tr>
				<th>Igényelte:</th>
				<th>Igénylés dátuma:</th>
				<th>Eszköz:</th>
				<th>Típus:</th>
				<th>Elvitt db:</th>
				<th>Ettől:</th>
				<th>Eddig:</th>
				<th>Megjegyzés:</th>
				<th>Ellvitte:</th>
				</tr>
			</thead>
		<?php
			while($row = mysqli_fetch_array($userObj)){ 
				if(strtotime($row['eddig']) <= time()){
					$color = "red";
				}else{
					$color="";
				}
				?>
				<tr>	
				<td><?php echo $row['kicsoda'];?></td>
				<td><?php echo $row['mikor'];?></td>
				<td><?php echo $row['eszkozNev'];?></td>
				<td><?php echo $row['tipus'];?></td>
				<td><?php echo $row['mennyit'];?></td>
				<td><?php echo $row['ettol'];?></td>
				<td style='background-color: <?php echo $color ?>;'><?php echo $row['eddig'];?></td>
				<td style="display:block; ; word-wrap: break-word;"><?php echo  $row['megjegyzes'];?></td>
				<td><?php
					if($row['elvitte'] == 0){
						echo "Nem";
					}else if($row['elvitte'] == 1){
						echo "Igen";
					}?>
				</td>
				<td style="display:none"><?php echo $row['id'];?></td>
				<td style="display:none"><?php echo $row['igId'];?></td>
				</tr>
			<?php } ?>
		</table>
    </div>
			
			
			
    <div id="felvesz">
		<form action="<?=$_SERVER['PHP_SELF']?>" method="post">
			<br>
			<label class="login">Elvitt db :</label><br><input type="number" name="elvittdb" id="elvittdb" min="1"><br>
			<label class="login">Ettől :</label><br><input style="max-width: 170px;" name="mikortol" id="mikortol" type="text"><br>
			<label class="login">Eddig :</label><br><input style="max-width: 170px;" name="meddig" id ="meddig" type="text"><br>
			<label class="login">Megjegyzés :</label><br><textarea type="text" name="megj" id="megj" maxlength="50"></textarea><br>
			<input style="display:none" type="text" name="id" id="id">
			<input style="display:none" type="text" name="igID" id="igID" >
			<input style="display:none" type="text" name="mikor" id="mikor" ><br>
			
			<input class="gomb" type="submit" name="update" value="Módosít">
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
</div>
		
		
<?php





	if(isset($_POST['igen'])){
		$mikor = $_POST['mikor'];
		$elvittdb = $_POST['elvittdb'];
		$mikortol = $_POST['mikortol'];
		$meddig = $_POST['meddig'];
		$megjegyz = $_POST['megj'];
		$id = $_POST['id'];
		$igID = $_POST['igID'];
		$darab;
		$felh = $_SESSION["felhnev"];
			
		$sql = mysqli_query($link, "SELECT * FROM igenyles WHERE igId='$igID' AND kicsoda='$felh'");
			while($row = mysqli_fetch_array($sql)){ 
				$elvitteE = $row['elvitte'];
				$kie = $row['kicsoda'];
			}	
		if(mysqli_num_rows($sql) <= 0){
			echo "<script>alert(\"Csak a saját eszközödet törölheted!\");</script>";
			header('Refresh: 0');
			exit();
		}
			
		if(empty($mikor) || empty($elvittdb) || empty($mikortol) || empty($meddig)){
			echo "<script>alert(\"Üres mező!\");</script>";
			header('Refresh: 0');
			exit();
		}else if($elvitteE == 1){
			echo "<script>alert(\"Ez az eszköz már ki van adva, nem törölheted!\");</script>";
			header('Refresh: 0');
			exit();
		}else if($kie != $felh){
			echo "<script>alert(\"Csak a saját eszközeidet törölheted!\");</script>";
			header('Refresh: 0');
			exit();
		}else{
			
			$delete = "DELETE FROM igenyles WHERE igId = '$igID' AND mikor='$mikor' AND kicsoda='$felh'";
			mysqli_query($link, $delete);
			header('Refresh: 0');
			exit();
		}
		
	}


	
	
	
	
	
if(isset($_POST['update'])){
	$mikor = $_POST['mikor'];
	$elvittdb = $_POST['elvittdb'];
	$mikortol = $_POST['mikortol'];
	$meddig = $_POST['meddig'];
	$megjegyz = $_POST['megj'];
	$id = $_POST['id'];
	$igID = $_POST['igID'];
	$darab;
	$felh = $_SESSION["felhnev"];
		
	$sql = mysqli_query($link, "SELECT * FROM igenyles WHERE igId='$igID' AND kicsoda='$felh'");
	if(mysqli_num_rows($sql) > 0){
		while($row = mysqli_fetch_array($sql)){ 
			$elvitteE = $row['elvitte'];
			$kie = $row['kicsoda'];
		}
	}else {
		echo "<script>alert(\"Csak a saját eszközeidet módosíthatod!\");</script>";
		header('Refresh: 0');
		exit();
	}
	
	if(empty($mikor) || empty($elvittdb) || empty($mikortol) || empty($meddig)){
		echo "<script>alert(\"Üres mező!\");</script>";
		header('Refresh: 0');
		exit();
	}else if($elvitteE == 1){
		echo "<script>alert(\"Ez az eszköz már ki van adva, nem módosíthatod!\");</script>";
		header('Refresh: 0');
		exit();
	}else if($kie != $felh){
		echo "<script>alert(\"Csak a saját eszközeidet módosíthatod!\");</script>";
		header('Refresh: 0');
		exit();
	}else{
		$update4 = "UPDATE igenyles SET ettol= '$mikortol', eddig = '$meddig', mennyit='$elvittdb' , kicsoda='$felh', megjegyzes='$megjegyz' WHERE igId ='$igID' AND kicsoda='$felh'";
		mysqli_query($link, $update4);
		header('Refresh: 0');
		exit();
	}

	
	
	
	
	if(empty($kicsoda) || empty($mikor) || empty($eszkNev) || empty($tipus) || empty($elvittdb) || empty($mikortol) || empty($meddig)){
		echo "<script>alert(\"Üres mező!\");</script>";
	}else{
		
		if($elv == 0 && $elvitteE == 1){
			
			if(($darab-$elvittdb)<0){
				echo "<script>alert(\"Nincs ennyi eszköz!\");</script>";
				header('Refresh: 0');
			exit();
			}else{
				$darab2 = $darab-$elvittdb;
				$update = "UPDATE  eszkozok SET darab = '$darab2' WHERE id='$id' ";
				$update2 = "UPDATE igenyles SET ettol= '$mikortol', eddig = '$meddig', eszkozNev= '$eszkNev', tipus='$tipus', mennyit='$elvittdb' , kicsoda='$kicsoda',elvitte='$elvitteE', megjegyzes='$megjegyz' WHERE igId ='$igID'";
				mysqli_query($link, $update);
				mysqli_query($link, $update2);
				header('Refresh: 0');
				exit();
			}
		}else if($elv == 1 && $elvitteE == 0){
			$darab3 = $darab + $elvittdb;
			$update3 = "UPDATE  eszkozok SET darab = '$darab3' WHERE id='$id' ";
			$update4 = "UPDATE igenyles SET ettol= '$mikortol', eddig = '$meddig', eszkozNev= '$eszkNev', tipus='$tipus', mennyit='$elvittdb' , kicsoda='$kicsoda',elvitte='$elvitteE', megjegyzes='$megjegyz' WHERE igId ='$igID'";
			mysqli_query($link, $update3);
			mysqli_query($link, $update4);
			header('Refresh: 0');
			exit();
		}else {
			$update5 = "UPDATE igenyles SET ettol= '$mikortol', eddig = '$meddig', eszkozNev= '$eszkNev', tipus='$tipus', mennyit='$elvittdb' , kicsoda='$kicsoda',elvitte='$elvitteE', megjegyzes='$megjegyz' WHERE igId ='$igID'";
			mysqli_query($link, $update5);
			header('Refresh: 0');
			exit();
		}
	}	
}
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

<script>
    var rIndex,
    table = document.getElementById("table");
    function selectedRowToInput(){
        for(var i = 1; i < table.rows.length; i++){
            table.rows[i].onclick = function(){
                rIndex = this.rowIndex;
				document.getElementById("mikor").value = this.cells[1].innerHTML;
				document.getElementById("elvittdb").value = this.cells[4].innerHTML;
				document.getElementById("mikortol").value = this.cells[5].innerHTML;
				document.getElementById("meddig").value = this.cells[6].innerHTML;
				document.getElementById("megj").value = this.cells[7].innerHTML;
				document.getElementById("id").value = this.cells[9].innerHTML;
				document.getElementById("igID").value = this.cells[10].innerHTML;
            };
        }	
    }
selectedRowToInput();
</script>




</body>
</html>