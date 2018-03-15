<?php
	session_start();
	require('mysqli/mysqli.connect.php');
?>
<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<link rel="stylesheet" href="css/bootstrap.min.css">
		<link rel="stylesheet" href="css/mongeo.main.css">
		<link rel="stylesheet" href="css/mongeo.mapael.css">

		<title>Mongeo</title>
		<link rel="shortcut icon" type="image/png" href="images/favicon.png"/>
	</head>
	<body>
		<nav class="navbar fixed-top navbar-dark bg-dark">
			<div class="d-flex justify-content-start">
				<div class="dropdown">
					<button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<i id="bmenu" class="material-icons">reorder</i>
					</button>
					<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
						<a class="dropdown-item" href="#">
							<i class="material-icons">dashboard</i>
							Dashboard
						</a>
						<a class="dropdown-item" href="#">
							<i class="material-icons">unarchive</i>
							Open map(s)
						</a>
						<a class="dropdown-item" href="#">
							<i class="material-icons">sync</i>
							Rotate maps
						</a>
						<a id="btn-create" class="dropdown-item" href="#">
							<i class="material-icons">public</i>
							Create map
						</a>
						<div class="dropdown-divider"></div>
						<a class="dropdown-item" href="#">
							<i class="material-icons">settings</i>
							Settings
						</a>
						<a class="dropdown-item" href="#">
							<i class="material-icons">wallpaper</i>
							Themes
						</a>
						<div class="dropdown-divider"></div>
						<a class="dropdown-item" href="#">
							<i class="material-icons">settings_applications</i>
							Admin settings
						</a>
						<a class="dropdown-item" href="#">
							<i class="material-icons">group</i>
							Users / Groups
						</a>
						<a id="btn-locations" class="dropdown-item" href="#">
							<i class="material-icons">edit_location</i>
							Edit locations
						</a>
						<a id="btn-activate" class="dropdown-item" href="#">
							<i class="material-icons">language</i>
							Activate maps
						</a>
					</div>
				</div>
			</div>
			<div class="d-flex justify-content-start">
				<button type="button" class="btn btn-primary ml-2"><i id="breload" class="material-icons">brightness_3</i></button>
				<button type="button" class="btn btn-primary ml-2"><i id="breload" class="material-icons">autorenew</i></button>
			</div>
		</nav>

		<main id="main-container" role="main" class="container-fluid"></main>

		<script src="js/jquery-3.3.1.min.js"></script>
		<script src="js/bootstrap.bundle.js"></script>
		<script src="js/raphael.min.js"></script>
		<script src="js/jquery.mousewheel.min.js"></script>
		<script src="js/jquery.mapael.min.js"></script>
<?php
	if($stmt = $mysqli->prepare("SELECT js_file FROM maps WHERE active = 1 ORDER BY name")){
		$stmt->execute();
		$result = $stmt->get_result();
		while($row = $result->fetch_assoc()){
			echo "\t\t<script src=\"js/maps/".$row['js_file']."\"></script>\n";
		}
		$stmt->close();
	}
	$mysqli->close();
?>
		
		<script src="js/mongeo.ui.js"></script>
		<script src="js/mongeo.maps.js"></script>
	</body>
</html>
