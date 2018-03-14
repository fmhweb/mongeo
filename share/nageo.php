<?php
	session_start();
	require('mysqli/mysqli.connect.php');
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<title>naGeo</title>
		<link rel="shortcut icon" type="image/png" href="images/favicon.png"/>

		<link rel="stylesheet" type="text/css" href="css/nageo.main.css">
		<link rel="stylesheet" type="text/css" href="css/nageo.maps.css">
		<link rel="stylesheet" type="text/css" href="css/nageo.mapael.css">
		
		<link rel="stylesheet" type="text/css" href="css/jquery-ui.min.css">
		<link rel="stylesheet" type="text/css" href="css/tabulator.min.css">
		<link rel="stylesheet" type="text/css" href="css/jquery.mCustomScrollbar.min.css">

		<script src="js/jquery-3.3.1.min.js" charset="utf-8"></script>
		<script src="js/jquery-ui.min.js" charset="utf-8"></script>
		<script src="js/jquery.mousewheel.min.js" charset="utf-8"></script>
		<script src="js/tabulator.min.js" charset="utf-8"></script>
		<script src="js/raphael.min.js" charset="utf-8"></script>
		<script src="js/jquery.mapael.min.js" charset="utf-8"></script>
		<script src="js/jquery.mCustomScrollbar.concat.min.js" charset="utf-8"></script>
<?php
	if($stmt = $mysqli->prepare("SELECT js_file FROM maps_activate WHERE active = 1 ORDER BY name")){
		$stmt->execute();
		$result = $stmt->get_result();
		while($row = $result->fetch_assoc()){
			echo "\t\t<script src=\"js/maps/".$row['js_file']."\" charset=\"utf-8\"></script>\n";
		}
		$stmt->close();
	}
?>
	</head>
	<body>
		<div class="tb">
			<i id="bmenu" class="material-icons mit fll bt">reorder</i>
			<span id="title-logo" class="tbl fll"><img height="30" src="logos/naGeo.png" /></span>
			<span id="title-custom" class="tbtc fll">naGeo</span>
			<span id="title-main" class="tbt fll">Network overview of <bdo dir="rtl">naGeo</bdo></span>
			<span id="title-category" class="tbc fll">Germany / Network</span>
			<i id="breload" class="material-icons mit flr bt">autorenew</i>
			<i id="bnight" class="material-icons mit flr bt info" data-info="Nightvision mode on. Overwrite themes for all maps.">brightness_3</i>
		</div>
		
		<div class="menc">
			<div class="menro">
				<div class="men menl fll">
					<table>
						<tr id="bmaps" class="bt"><td><i class="material-icons">unarchive</i></td><td>Open map(s)</td></tr>
						<tr id="bmapsrot" class="bt"><td><i class="material-icons">sync</i></td><td>Rotate maps</td></tr>
						<tr id="bmapscre" class="bt"><td><i class="material-icons">public</i></td><td>Create map</td></tr>
						<tr><td colspan="2"><hr></td></tr>
						<tr id="bset" class="bt"><td><i class="material-icons">settings</i></td><td>Settings</td></tr>
						<tr id="bthe" class="bt"><td><i class="material-icons">wallpaper</i></td><td>Themes</td></tr>
						<tr><td colspan="2"><hr></td></tr>
						<tr id="bsetadm" class="bt"><td><i class="material-icons">settings_applications</i></td><td>Admin settings</td></tr>
						<tr id="bsetadm" class="bt"><td><i class="material-icons">person</i></td><td>Edit users</td></tr>
						<tr id="bsetadm" class="bt"><td><i class="material-icons">group</i></td><td>Edit groups</td></tr>
						<tr id="bediloc" class="bt"><td><i class="material-icons">edit_location</i></td><td>Add locations</td></tr>
						<tr id="bedimap" class="bt"><td><i class="material-icons">language</i></td><td>Activate maps</td></tr>
					</table>
				</div>
				
				<div class="meno"></div>

				<div class="men menr flr"></div>
			</div>

		<div>

		<div class="mapc"></div>


		<script src="js/nageo.ui.js"></script>
		<script src="js/nageo.maps.js"></script>
	</body>
</html>
<?php
	$mysqli->close();
?>
