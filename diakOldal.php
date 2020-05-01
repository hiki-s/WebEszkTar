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
<title>Diák Oldal</title>
<link rel = "icon" href ="https://img.icons8.com/android/24/000000/retro-tv.png" type = "image/x-icon">
<meta charset ="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="diakOldal.css">
<!-- 
	Hátterek forrása:https://wonderfulengineering.com/41-free-high-definition-blue-wallpapers-for-download/
	Ikon forrása: https://icons8.com/
-->
</head>
<body>

<?php
	$felh = $_SESSION["felhnev"];
	$link = mysqli_connect("localhost", "root", "", "eszkoztar");
	$userObj = mysqli_query($link, "SELECT * FROM igenyles WHERE kicsoda='$felh' ORDER BY mikor ASC ");
?>
<div id="menu">
	<ul id="menuSor">
		<li><a href="eszkozok.php">Eszközök</a></li>
		<li><a href="diakokIgenyles.php">Igénylések</a></li>
		<li id="kilepes"><a href="index.php">Kilépés</a></li>
		<li id="nev"><?php echo $_SESSION["felhnev"];?></li>
	</ul> 
</div>


<div class="container">
    <div id="igenylesek">
		<table id="table" border="1">
			<thead>
		<p id="cim">Igényléseim:</p>
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
				if(strtotime($row['ettol']) <= time()){
					$color2 = "red";
				}else{
					$color2="";
				}
				?>
				<tr>	
				<td><?php echo $row['kicsoda'];?></td>
				<td><?php echo $row['mikor'];?></td>
				<td><?php echo $row['eszkozNev'];?></td>
				<td><?php echo $row['tipus'];?></td>
				<td><?php echo $row['mennyit'];?></td>
				<td style='background-color: <?php echo $color2 ?>;'><?php echo $row['ettol'];?></td>
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
</div>




</body>
</html>