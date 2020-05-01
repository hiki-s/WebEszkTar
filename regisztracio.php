<?php 
	session_unset();
?>
<!DOCTYPE html>
<html>
<head>
<title>Regisztráció</title>
<link rel = "icon" href ="https://img.icons8.com/android/24/000000/retro-tv.png" type = "image/x-icon">
<meta charset ="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="regisztracio.css">
<!-- 
	Hátterek forrása:https://wonderfulengineering.com/41-free-high-definition-blue-wallpapers-for-download/
	Ikon forrása: https://icons8.com/
-->
</head>

<body>

<div id="login">
	<form action="<?=$_SERVER['PHP_SELF']?>" method="post">
		<label>Felhasználónév:</label><br><input type="text" name="user"><br>
		<label>Teljes név:</label><br><input type="text" name="nev"><br>
		<label>Jelszó:</label><br><input type="password" name="pass"><br>
		<label>Neptunkód:</label><br><input type="text" name="neptunk"><br>
		<label>Email:</label><br><input type="text" name="email"><br>
		<label>Csapat:</label><br><input type="text" name="csapat"><br>
		<br>
		<input id="regGomb" type="submit" name="regisztracio" value="Regisztráció"><br>
		<input id="BelGomb" type="submit" name="belepes" value="Belépás">
	</form>
</div>


<?php
$link = mysqli_connect("localhost", "root", "", "eszkoztar");
if($link === false){
	die("ERROR: Nem sikerült csatlakozni a szerverhez. " . mysqli_connect_error());
}
	
if (isset($_POST['regisztracio'])){
	
	$username = $_POST['user'];
	$nev = $_POST['nev'];
	$neptunK = $_POST['neptunk'];
	$neptunK = strtoupper("$neptunK");
	$jelszo = $_POST['pass'];
	$email = $_POST['email'];
	$csapat = $_POST['csapat'];
	
	
	$sql = "SELECT username FROM felhasznalok WHERE username = '$username'";
	$sql1 = "SELECT email FROM felhasznalok WHERE email = '$email'";
	$sql2 = "SELECT * FROM felhasznalok WHERE neptunKod = '$neptunK' AND regE = 1";


	$result = mysqli_query($link, $sql);
	$result1 = mysqli_query($link, $sql1);
	$result2 = mysqli_query($link, $sql2);



	if (empty($username) || empty($nev) || empty($neptunK) || empty($jelszo) || empty($email) || empty($csapat)) {
		echo "<script>alert(\"Üres mező!\")</script>";
	}else if(mysqli_num_rows($result) > 0){
		echo "<script>alert(\"Ez a név már foglalt!\")</script>";
	}else if(mysqli_num_rows($result1) > 0) {
		echo "<script>alert(\"Ez az email cím már foglalt!\")</script>";
	}else if(strlen($neptunK) != 6){
		echo "<script>alert(\"Helytelen neptunkód!\")</script>";
	}else if(strlen($csapat) >= 15){
		echo "<script>alert(\"Csak 15 karakter lehet a csapat!\")</script>";
	}else{
		if($result = mysqli_query($link, $sql2)){
			if(mysqli_num_rows($result2) > 0){
					$update = "UPDATE felhasznalok SET username ='$username', nev = '$nev', email = '$email',  jelszo = '$jelszo', csapat = '$csapat', regE = 2 WHERE neptunKod = '$neptunK' AND regE = 1";
					if (mysqli_query($link, $update)) {
						header("Location: index.php");
						exit();
					} else {
						echo "Error: Nem sikerült a frissítés: " . mysqli_error($link);
					}
			} else{
				echo "<script>alert(\"Hibás neptunkód vagy már foglalt!\")</script>";
			}
		} else{
			echo "ERROR: Nem sikerült lefuttatni a kódot. " . mysqli_error($link);
		}
	}
}
if (isset($_POST['belepes'])){
	header("Location: index.php");
	exit();
}

mysqli_close($link);
?>


</body>
</html>