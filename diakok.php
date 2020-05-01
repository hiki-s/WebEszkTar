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
<title>Diákok</title>
<link rel = "icon" href ="https://img.icons8.com/android/24/000000/retro-tv.png" type = "image/x-icon">
<meta charset ="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="diakok.css">
<!-- 
	Hátterek forrása:https://wonderfulengineering.com/41-free-high-definition-blue-wallpapers-for-download/
	Ikon forrása: https://icons8.com/
-->
</head>
<body>
	
<div id="menu">
	<ul id="menuSor">
		<li class="akt"><a href="diakok.php">Diákok</a></li>
		<li><a href="eszkozok.php">Eszközök</a></li>
		<li><a href="igenyles.php">Igénylések</a></li>
		<li class="kilepes"><a href="index.php">Kilépés</a></li>
		<li class="kilepes"><a href="adminOldal.php">Vissza</a></li>
		<li style="text-align:center;margin-top:10px; margin-right:5%; font-size: 25px;" class="kilepes"><?php echo $_SESSION["felhnev"];?></li>
	</ul> 
</div>	

	
<?php
	$link = mysqli_connect("localhost", "root", "", "eszkoztar");
	$userObj = mysqli_query($link, 'SELECT * FROM felhasznalok WHERE NOT neptunKod = \'\' ORDER BY username');
?>



<div class="container">
    <div id="diakok">
        <table id="table" border="1">
			<thead>
				<tr>
					<th>Username</th>
					<th>Név</th>
					<th>Neptun</th>
					<th>Email</th>
					<th>Csapat</th>
				</tr>
			</thead>
            <?php
				while($row = mysqli_fetch_array($userObj)){ ?>
				<tr>	
					<td><?php echo $row['username']?></td>
					<td><?php echo $row['nev']?></td>
					<td><?php echo $row['neptunKod']?></td>
					<td><?php echo $row['email']?></td>
					<td><?php echo $row['csapat']?></td>
					<td style="display:none;"><?php echo $row['jelszo']?></td>
				</tr>
			<?php } ?>
			
        </table>
    </div>
	

    <div id="felvesz">
		<form action="<?=$_SERVER['PHP_SELF']?>" method="post">
		<br>
			<label>Felhasználónév :</label><br><input type="text" name="username" id="username"><br>
			<label>Teljes név :</label><br><input type="text" name="nev" id="nev"><br>
			<label>Neptun Kód :</label><br><input type="text" name="neptun" id="neptun"><br>
			<label>Email :</label><br><input type="text" name="email" id="email"><br />
			<label>Csapat :</label><br><input type="text" name="csapat" id="csapat" maxlength="15"><br>
			<input id="jelsz" type="button" name="jelsz" value="Jelszó" onclick="jelszoMutat()">
			<div id="jelszoMutat" style="display:none;">
				<input class="jelszo" type="text" name="jelszo" id="jelszo" ><br>
			</div><br>
			<input type="submit" name="edit" value="Módosít">
			<input type="button" name="delete" value="Töröl" onclick="biztosTorli()">
			<br><br>
			<div id="tolriE" style="display:none; border:0.5px solid black; ">
			
			Biztosan törli?<br>
			<br>
				<input type="submit" name="igen" value="Igen">
				<input type="button" name="nem" value="Nem" onclick="biztosTorli()">
				<br><br>
			</div>
			
		</form> 
    </div>

	<div id="ujNepK">
		<form action="<?=$_SERVER['PHP_SELF']?>" method="post">
		<br>
			<label class="login">Új neptunKód:</label><input type="text" name="nepK"><br><br>
			<input class="gomb" type="submit" name="add" value="Hozzáad"><br><br>
		</form>
	</div>

</div>
        
<?php
	if(isset($_POST['edit'])){
		$username = $_POST['username'];
		$nev = $_POST['nev'];
		$neptun = $_POST['neptun'];
		$email = $_POST['email'];
		$csapat = $_POST['csapat'];
		$jelszo = $_POST['jelszo'];
		
		$sql = "SELECT username FROM felhasznalok WHERE username = '$username'";
		$sql1 = "SELECT email FROM felhasznalok WHERE email = '$email'";
		$sql2 = "SELECT * FROM felhasznalok WHERE neptunKod = '$neptun'";

		$result = mysqli_query($link, $sql);
		$result1 = mysqli_query($link, $sql1);
		$result2 = mysqli_query($link, $sql2);
		
		$update = "UPDATE felhasznalok SET username ='$username', nev = '$nev', email = '$email', csapat = '$csapat', regE = 2, jelszo = '$jelszo' WHERE neptunKod = '$neptun'";
		mysqli_query($link, $update);
		header('Refresh: 0');
		
	}
	
	
	if(isset($_POST['igen'])){
		$username = $_POST['username'];
		$nev = $_POST['nev'];
		$neptun = $_POST['neptun'];
		$email = $_POST['email'];
		$csapat = $_POST['csapat'];
		
		if($neptun == ""){
			echo "<script>alert(\"Üres mező\")</script>";
		}else{
			$delete = "DELETE FROM felhasznalok WHERE neptunKod = '$neptun'";
			mysqli_query($link, $delete);
			header('Refresh: 0');
		}
	}
	
	
	
	if(isset($_POST['add'])){
		$nepK = strtoupper($_POST['nepK']);
		$addNep = "SELECT * FROM felhasznalok WHERE neptunKod = '$nepK'";
		$result = mysqli_query($link, $addNep);
		
		if(empty($nepK)){
			echo "<script>alert(\"Üres mező\")</script>";
		}else if(mysqli_num_rows($result) > 0){
			echo "<script>alert(\"Már van ilyen neptunkód!\")</script>";
		}else if(strlen($nepK) != 6){
			echo "<script>alert(\"Helytelen neptunkód! (6 karakter)\")</script>";
		}
		else {
			$ujNepKod = "INSERT INTO felhasznalok (neptunKod) VALUES ('$nepK')";
			
			if (mysqli_query($link, $ujNepKod)) {
				header('Refresh: 0');
			} else {
				echo "Error: Nem sikerült a felvétel: " . mysqli_error($link);
			}
		}
	}
	
ob_end_flush();
?>
<script>
	function jelszoMutat(){
		var x = document.getElementById("jelszoMutat");
		if (x.style.display === "none") {
			x.style.display = "block";
		} else {
			x.style.display = "none";
		}
	}
	
</script>

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
			document.getElementById("nev").value = this.cells[1].innerHTML;
			document.getElementById("neptun").value = this.cells[2].innerHTML;
			document.getElementById("email").value = this.cells[3].innerHTML;
			document.getElementById("csapat").value = this.cells[4].innerHTML;
			document.getElementById("jelszo").value = this.cells[5].innerHTML;
		};
	}
}
selectedRowToInput();
</script>
		
</body>
</html>