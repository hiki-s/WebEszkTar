<?php
	session_start();
	
	if($_SESSION["felhnev"] != 'Admin'){
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
		<li class="akt"><a href="igenyles.php">Igénylések</a></li>
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
				<td><?php echo $row['elvitte'];?></td>
				<td style="display:none"><?php echo $row['id'];?></td>
				<td style="display:none"><?php echo $row['igId'];?></td>
				</tr>
			<?php } ?>
		</table>
    </div>
			
			
			
    <div id="felvesz">
		<form action="<?=$_SERVER['PHP_SELF']?>" method="post">
			<br>
			<label class="login">Igényelte :</label><br><input type="text" name="username" id="username"><br>
			<label class="login">Eszköz :</label><br><input type="text" name="eszkoz" id="eszkoz"><br>
			<label class="login">Típus :</label><br><input type="text" name="tipus" id="tipus"><br>
			<label class="login">Elvitt db :</label><br><input type="number" name="elvittdb" id="elvittdb"><br>
			<label class="login">Ettől :</label><br><input style="max-width: 170px;" name="mikortol" id="mikortol" type="text"><br>
			<label class="login">Eddig :</label><br><input style="max-width: 170px;" name="meddig" id ="meddig" type="text"><br>
			<label class="login">Megjegyzés :</label><br><textarea type="text" name="megj" id="megj" maxlength="50"></textarea><br>
			<input type="radio" id="kiadva" name="class" value="kiadva" >
			<label for="class">Kiadva</label><br>
			<input type="radio" id="raktaron" name="class" value="raktaron">
			<label for="class">Még raktáron</label><br>
			
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
		$kicsoda = $_POST['username'];
		$mikor = $_POST['mikor'];
		$eszkNev = $_POST['eszkoz'];
		$tipus = $_POST['tipus'];
		$elvittdb = $_POST['elvittdb'];
		$mikortol = $_POST['mikortol'];
		$meddig = $_POST['meddig'];
		$megjegyz = $_POST['megj'];
		$elvitteE = $_POST['class'];  
		if ($elvitteE == 'kiadva') {          
			$elvitteE = 1;      
		}else {
			$elvitteE = 0;
		}
		$id = $_POST['id'];
		$igID = $_POST['igID'];
		$darab;
			
		$sql = mysqli_query($link, "SELECT * FROM eszkozok WHERE id='$id'");
		while($row = mysqli_fetch_array($sql)){ 
			$darab = $row['darab'];
		}
		$darab = $darab + $elvittdb;
			
		if($elvitteE == 1){
			$update = "UPDATE  eszkozok SET darab = '$darab' WHERE id='$id' ";
			mysqli_query($link, $update);
			$delete = "DELETE FROM igenyles WHERE igId = '$igID' AND mikor='$mikor' ";
			mysqli_query($link, $delete);
			header('Refresh: 0');
			exit();
		}else if($elvitteE == 0){
			$delete = "DELETE FROM igenyles WHERE igId = '$igID' AND mikor='$mikor' ";
			mysqli_query($link, $delete);
			header('Refresh: 0');
			exit();
		}
		
	}


	
	
	
	
	
if(isset($_POST['update'])){
	$kicsoda = $_POST['username'];
	$mikor = $_POST['mikor'];
	$eszkNev = $_POST['eszkoz'];
	$tipus = $_POST['tipus'];
	$elvittdb = $_POST['elvittdb'];
	$mikortol = $_POST['mikortol'];
	$meddig = $_POST['meddig'];
	$megjegyz = $_POST['megj'];
	$elvitteE = $_POST['class'];  
	if ($elvitteE == 'kiadva') {          
		$elvitteE = 1;      
	}else {
		$elvitteE = 0;
	}
	$id = $_POST['id'];
	$igID = $_POST['igID'];
	
	$sql = mysqli_query($link, "SELECT * FROM eszkozok WHERE id='$id'");
	while($row = mysqli_fetch_array($sql)){ 
		$darab = $row['darab'];
	}
		
	$sql2 = mysqli_query($link, "SELECT * FROM igenyles WHERE id='$id'");
	while($row = mysqli_fetch_array($sql2)){ 
		$elv = $row['elvitte'];
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
			$update4 = "UPDATE igenyles SET ettol= '$mikortol', eddig = '$meddig', eszkozNev= '$eszkNev', tipus='$tipus', mennyit='$elvittdb' , kicsoda='$kicsoda', elvitte='$elvitteE', megjegyzes='$megjegyz' WHERE igId ='$igID'";
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
				document.getElementById("username").value = this.cells[0].innerHTML;
				document.getElementById("mikor").value = this.cells[1].innerHTML;
				document.getElementById("eszkoz").value = this.cells[2].innerHTML;
				document.getElementById("tipus").value = this.cells[3].innerHTML;
				document.getElementById("elvittdb").value = this.cells[4].innerHTML;
				document.getElementById("mikortol").value = this.cells[5].innerHTML;
				document.getElementById("meddig").value = this.cells[6].innerHTML;
				document.getElementById("megj").value = this.cells[7].innerHTML;
				
				if(this.cells[8].innerHTML == '1'){
					radiobtn = document.getElementById("kiadva");
					radiobtn.checked = true;
				}else if(this.cells[8].innerHTML == '0'){
					radiobtn = document.getElementById("raktaron");
					radiobtn.checked = true;
				}
				
				document.getElementById("id").value = this.cells[9].innerHTML;
				document.getElementById("igID").value = this.cells[10].innerHTML;
            };
        }	
    }
selectedRowToInput();
</script>




</body>
</html>