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
<title>Admin Oldal</title>
<link rel = "icon" href ="https://img.icons8.com/android/24/000000/retro-tv.png" type = "image/x-icon">
<meta charset ="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="adminOldal.css">
<!-- 
	Hátterek forrása:https://wonderfulengineering.com/41-free-high-definition-blue-wallpapers-for-download/
	Ikon forrása: https://icons8.com/
-->
<style>
	body {
	margin-top:100px;
	width:100%;
	height:100%;
	margin:0;
	background-image:url("hatter/hatter2.jpg");
}
}
div#menu {
	position: relative;
	list-style-type: none;
	margin: 0;
	padding: 0;
	z-index: -1;
	width:100%;
	z-index: 1;
}
ul#menuSor {
	list-style-type: none;
	margin: 0;
	padding: 0;
	overflow: hidden;
background-color: #00CED1;
}
li {
  float: left;
}
li#kilepes {
	float: right;
}
li.akt {
	background-color: blue;
	font-weight:bold;
}
li a {
	display: block;
	color: black;
	text-align: center;
	padding: 14px 16px;
	text-decoration: none;
	font-size: 20px;
	font-family:"Courier New", Courier, monospace;
}
li a:hover {
	background-color: blue;
	font-size: 20px;
	font-family:"Courier New", Courier, monospace;
	font-weight:bold;
}
li#nev {
	text-align:center;
	margin-top:10px;
	margin-right:5%;
	font-size: 25px;
	float: right;
}
tr:hover {
	box-shadow: 3px 3px;
	background-color:#00CED1;
}
td {
	max-width: 140px;
	overflow: auto;
	text-align:center;
}
div.container {
	position:relative;
	width:100%;
	height:100%;
}
div#igenylesek {
	display: inline-block;
	position:relative;
	float: left;
	border: 3px solid blue;
	margin-left:2%;
	margin-top:2%;
	background-color:cyan;
}
div#igenylesek2 {
	display: inline-block;
	position:relative;
	float: left;
	border: 3px solid blue;
	margin-left:2%;
	margin-top:2%;
	background-color:cyan;
}

p#cim {
	margin:0px;
	margin-top:5px;
	margin-bottom:5px;
	text-align:center;
	font-size: 20px;
}
</style>
</head>
<body>

<?php
	$nulla = 0;
	$link = mysqli_connect("localhost", "root", "", "eszkoztar");
	$userObj = mysqli_query($link, "SELECT * FROM igenyles WHERE elvitte='$nulla' ORDER BY mikor ASC ");
?>
<div id="menu">
	<ul id="menuSor">
		<li><a href="diakok.php">Diákok</a></li>
		<li><a href="eszkozok.php">Eszközök</a></li>
		<li><a href="igenyles.php">Igénylések</a></li>
		<li id="kilepes"><a href="index.php">Kilépés</a></li>
		<li id="nev"><?php echo $_SESSION["felhnev"];?></li>
	</ul> 
</div>


<div class="container">
    <div id="igenylesek">
		<table id="table" border="1">
			<thead>
		<p id="cim">Igénylések:</p>
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
				<td><?php echo $row['elvitte'];?></td>
				<td style="display:none"><?php echo $row['id'];?></td>
				<td style="display:none"><?php echo $row['igId'];?></td>
				</tr>
			<?php } ?>
		</table>
    </div>
			
			
	<div id="igenylesek2">
		<table id="table2" border="1">
			<thead>
		<p id="cim">Kiadott és lejárt eszközök:</p>
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
		$egy = 1;
		$userObj2 = mysqli_query($link, "SELECT * FROM igenyles WHERE elvitte='$egy' ORDER BY mikor ASC ");
			while($row = mysqli_fetch_array($userObj2)){ 
				if(strtotime($row['eddig']) <= time()){
					$color = "red";
				
				
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
				<td><?php echo $row['elvitte'];?></td>
				<td style="display:none"><?php echo $row['id'];?></td>
				<td style="display:none"><?php echo $row['igId'];?></td>
				</tr>
			<?php }
}			?>
		</table>
    </div>
			
			
    
</div>




</body>
</html>