<!doctype html>
<html lang="en">
	<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/mongeo.main.css">

	<title>Mongeo</title>
	</head>
	<body>
		<nav class="navbar navbar-dark bg-dark">
			<div class="d-flex justify-content-start">
				<div class="dropdown">
					<button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<i id="bmenu" class="material-icons">reorder</i>
					</button>
					<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
						<a class="dropdown-item" href="#">
							<i class="material-icons">unarchive</i>
							Open map(s)
						</a>
						<a class="dropdown-item" href="#">
							<i class="material-icons">sync</i>
							Rotate maps
						</a>
						<a class="dropdown-item" href="#">
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
						<a class="dropdown-item" href="#">
							<i class="material-icons">edit_location</i>
							Edit locations
						</a>
						<a id="btn-activate-maps" class="dropdown-item" href="#">
							<i class="material-icons">language</i>
							Activate maps
						</a>
					</div>
				</div>
			</div>
			<div class="d-flex justify-content-start">
				<button type="button" class="btn btn-secondary ml-2"><i id="breload" class="material-icons">brightness_3</i></button>
				<button type="button" class="btn btn-secondary ml-2"><i id="breload" class="material-icons">autorenew</i></button>
			</div>
		</nav>

		<main id="main-container" role="main" class="container-fluid"></main>

		<script src="js/jquery-3.3.1.min.js"></script>
		<script src="js/bootstrap.bundle.js"></script>
		<script src="js/raphael.min.js"></script>
		<script src="js/jquery.mousewheel.min.js"></script>
		<script src="js/jquery.mapael.min.js"></script>
		
		<script src="js/mongeo.ui.js"></script>
	</body>
</html>
