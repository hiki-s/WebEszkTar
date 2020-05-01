<?php
	session_start();
	session_unset();
?>
<!DOCTYPE html>
<html>
<head>
<title>Login</title>
<link rel = "icon" href ="https://img.icons8.com/android/24/000000/retro-tv.png" type = "image/x-icon">
<meta charset ="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="index.css">
<!-- 
	Hátterek forrása:https://wonderfulengineering.com/41-free-high-definition-blue-wallpapers-for-download/
	Ikon forrása: https://icons8.com/
-->
</head>
<body>

<div id="login">
	<form action="<?=$_SERVER['PHP_SELF']?>" method="post">
		<label>Felhasználónév:</label><br><input type="text" name="felhNev"><br><br>
		<label>Jelszó:</label><br><input type="password" name="jelszo"><br><br>
		<input id="belepGomb" type="submit" name="belep" value="Login"><br><br>
		<input type="submit" name="reg" value="Regisztráció">
	</form>
</div>


<?php
$link = mysqli_connect("localhost", "root", "", "eszkoztar");

if($link === false){
    die("ERROR: Nem sikerült csatlakozni a szerverhez. " . mysqli_connect_error());
}

if(isset($_POST['belep'])){
	$felh = $_POST['felhNev'];
    $jel = $_POST['jelszo'];
	
	if(empty($felh) || empty($jel)){
		echo "<script>alert(\"Üres mező!\")</script>";
	}else{
		$sql = "SELECT * FROM felhasznalok WHERE username ='$felh' AND jelszo = '$jel' and adminE = 2";
		$sql1 = "SELECT * FROM felhasznalok WHERE username ='$felh' AND jelszo = '$jel' and adminE = 1";
		
		$result1 = mysqli_query($link, $sql1);
		if($result = mysqli_query($link, $sql)){
			if(mysqli_num_rows($result) == 1){
				$_SESSION["felhnev"] = $felh;
				header("Location: adminOldal.php");
				exit();
			}if(mysqli_num_rows($result1) > 0){
				$_SESSION["felhnev"] = $felh;
				header("Location: diakOldal.php");
				exit();
			}else{
				echo "<script>alert(\"Hibás felhasználó név vagy jelszó\")</script>";
			}
			
			
		}else{
			echo "ERROR: Nem sikerült lefuttatni a kódot: " . mysqli_error($link);
		}
		
		
	}
}
if(isset($_POST['reg'])){
	header("Location: regisztracio.php");
	exit();
}

mysqli_close($link);
?>

</body>
</html>