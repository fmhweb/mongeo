<?php
	require('../mysqli/mysqli.connect.php');
	if(isset($_POST['save'])){
		if($_POST['save'] == 1){
			if($_POST['option'] == 1){
				if($stmt = $mysqli->prepare("INSERT INTO `location_plots` (`name`,`latitude`,`longlitude`,`created`,`created_by_id`) VALUES (?,?,?,NOW(),1)")){
					$stmt->bind_param("sdd", $_POST['name'],$_POST['lat'],$_POST['lon']);
					$stmt->execute();
					$stmt->close();
					echo "{ret:0,option:1}";
				}
				else{
					echo "{ret:1,option:1}";
				}
			}
			elseif($_POST['option'] == 2){
				if($stmt = $mysqli->prepare("INSERT INTO `location_areas` (`name`,`map_id`,`map_tile_id`,`created`,`created_by_id`) VALUES (?,?,?,NOW(),1)")){
					$stmt->bind_param("sis", $_POST['name'],$_POST['map'],$_POST['area']);
					$stmt->execute();
					$stmt->close();
					echo "{ret:0,option:2}";
				}
				else{
					echo "{ret:1,option:2}";
				}
			}
		}
		//TODO: Update
		$mysqli->close();
	}
	elseif(isset($_POST['load_locations'])){
		$ret = 0;
		$plots = array();
		$areas = array();
		if($stmt = $mysqli->prepare("SELECT * FROM location_plots;")){
			$stmt->execute();
			$result = $stmt->get_result();
			$i = 0;
			while($row = $result->fetch_assoc()){
				foreach($row as $key => $val){
					$plots[$i][$key] = $val;
				}
				$i++;
			}
			$stmt->close();
		}
		else{$ret = 1;}
		if($stmt = $mysqli->prepare("SELECT * FROM location_areas;")){
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $i = 0;
                        while($row = $result->fetch_assoc()){
                                foreach($row as $key => $val){
                                        $areas[$i][$key] = $val;
                                }
                                $i++;
                        }
                        $stmt->close();
                }
                else{$ret = 1;}
		$mysqli->close();
		echo json_encode(array("ret"=>$ret,"plots"=>$plots,"areas"=>$areas));
	}
	else{
?>
<div class="container-fluid clear-top">
	<div class="row">
		<div id="map-col" class="col-sm pt-3">
			<div class="map-container">
				<div class="map">
					<span>Loading map...</span>
				</div>
			</div>
		</div>
		<div class="col-sm pt-3">
			<form id="form-location">
				<div class="form-group">
					<label for="input-name">1) Choose a name for the new location or edit an existing entry:</label>
					<input type="text" class="form-control" id="input-name" name="name" placeholder="Location name" />
				</div>
				<div class="form-group">
					<label for="input-map">2) Select a map:</label>
					<select class="form-control" id="input-map" name="map">
		<?php
			if($stmt = $mysqli->prepare("SELECT id, title, name, ratio FROM `maps` WHERE `active` = 1")){
				$stmt->execute();
				$result = $stmt->get_result();
				while($row = $result->fetch_assoc()){
					echo "<option value=\"".$row['id']."\" data-title=\"".$row['title']."\" data-name=\"".$row['name']."\" data-ratio=\"".$row['ratio']."\">".$row['title']."</option>\n";
				}
				$stmt->close();
			}
		?>
					</select>
				</div>
				<div class="form-group">
					<label>3) Set a location:</label>
					<small class="form-text text-muted">(Locations are not map specific since they depend on latitude and longlitude, but map areas are)</small>
				</div>
				<div class="form-group">
					<label for="input-plot-radio">3.1) Option 1 - Set a location:</label>
					<input type="radio" name="option" class="form-control mb-2" id="input-plot-radio" value="1" checked />
					<input type="number" class="form-control" id="input-plot-lat" name="lat" placeholder="Latitude" />
					<input type="number" class="form-control" id="input-plot-lon" name="lon" aria-describedby="input-plot-help" placeholder="Longlitude" />
					<small id="input-plot-help" class="form-text text-muted">(You can coose a location on the world map and then switch to another map in order to narrow down on your location)</small>
					<br>
					<input type="text" class="form-control" id="input-plot-google" aria-describedby="input-plot-google-help" placeholder="Google maps coordinates" />
					<small id="input-plot-google-help" class="form-text text-muted"><a id="link-google-help" href="#">Google maps instructions</a></small>
				</div>
				<div class="form-group">
					<label for="input-area-radio">3.2) Option 2 - Double click on the map and use the selected area (tile):</label>
					<input type="radio" name="option" class="form-control mb-2" id="input-area-radio" value="2" />
					<input type="text" class="form-control" id="input-area" name="area" placeholder="Select an area on the map" readonly />
				</div>
				<div class="form-group">
					<button type="button" class="btn btn-primary" id="btn-save-location">Save</button>
					<button type="button" class="btn btn-primary" id="btn-clear-location">Reset</button>
				</div>
			</form>
		</div>
	</div>
</div>
<div class="container-fluid mt-4">
	<h5>Location plots:</h5>
	<table class="table table-hover">
		<thead>
			<tr>
				<th scope="col">Name</th>
				<th scope="col">Latitude</th>
				<th scope="col">Longlitude</th>
				<th scope="col">created</th>
				<th scope="col">created_by_id</th>
				<th scope="col">updated</th>
				<th scope="col">updated_by_id</th>
			</tr>
		</thead>
		<tbody id="table-plots">
		</tbody>
	</table>
</div>
<div class="container-fluid mt-4">
        <h5>Location areas:</h5>
        <table class="table table-hover">
                <thead>
                        <tr>
                                <th scope="col">Name</th>
                                <th scope="col">Map</th>
                                <th scope="col">Area</th>
                                <th scope="col">created</th>
                                <th scope="col">created_by_id</th>
                                <th scope="col">updated</th>
                                <th scope="col">updated_by_id</th>
                        </tr>
                </thead>
                <tbody id="table-areas">
                </tbody>
        </table>
</div>
<div class="overlay bg-dark">
	<img class="google-help" src="images/google-maps-instructions.png" />
</div>
<?php
	$mysqli->close();
?>
<script>
	function loadMapLocation(title,name,ratio,containerx,containery,map,offsetx = 0, offsety = 0, zoom = false){

		var newW = $(containery).height() * ratio - offsety;
		if(newW > $(containerx).width()){
			newW = $(containerx).width() - offsetx;
		}
		$(map).width(newW);

		$(map).mapael({
			map: {
				name: name,
				zoom:{
					enabled:zoom
				},
				defaultPlot: {
					attrs: {
						fill: "#34495E"
					}
					, attrsHover: {
						opacity: 1
					}
					, text: {
						attrs: {
							fill: "#505444"
						}
						, attrsHover: {
							fill: "#000"
						}
					}
				},
				defaultArea: {
					attrs: {
						fill: "#424949"
						, stroke: "#ced8d0"
					}
					, attrsHover: {
						fill: "#5D6D7E"
					}
					, text: {
						attrs: {
							fill: "#505444"
						}
						, attrsHover: {
							fill: "#000"
						}
					},
					eventHandlers:{
						dblclick: function (e, id, mapElem, textElem) {
							$("#input-area").val(id);
							$("#input-area-radio").prop("checked", true);;
						}	
					}	
				}
			}
		});
	}

	function loadLocations(){
		$.post("ajax/locations.php", {load_locations:"1"}, function(data){
			//console.log(data);
			if(data.ret == 0){
				$("#table-plots tr").remove();
				$("#table-areas tr").remove();
				for(var i = 0;i < data.plots.length;i++){
					var row = data.plots[i];
					$("#table-plots").append("<tr><td>"+row.name+"</td><td>"+row.latitude+"</td><td>"+row.longlitude+"</td><td>"+row.created+"</td><td>"+row.created_by_id+"</td><td>"+row.updated+"</td><td>"+row.updated_by_id+"</td></tr>");
				}
				for(var i = 0;i < data.areas.length;i++){
					var row = data.areas[i];
					$("#table-areas").append("<tr><td>"+row.name+"</td><td>"+row.map_id+"</td><td>"+row.map_tile_id+"</td><td>"+row.created+"</td><td>"+row.created_by_id+"</td><td>"+row.updated+"</td><td>"+row.updated_by_id+"</td></tr>");
				}
			}
			else{console.log("Error loading locations");}
		},"json");
	}

	$( document ).ready(function() {
		loadMapLocation($("#input-map").text(),$("#input-map").find(':selected').data('name'),$("#input-map").find(':selected').data('ratio'),'#map-col',window,'.map-container',40,80,true);
		var newPlots = {
			"position": {
				latitude: 0,
				longitude: 0,
				size:10,
				attrs:{
					fill:"#E74C3C"
				}
			}
		}
		$(".map-container").trigger('update', [{
			newPlots: newPlots
		}]);
		loadLocations();
	});

	//Select map
	$('#input-map').change(function(){
		loadMapLocation($(this).text(),$(this).find(':selected').data('name'),$(this).find(':selected').data('ratio'),'#map-col',window,'.map-container',40,80,true);
		updateLocMap();
	});

	//Lat Long
	var lat = 0;
	var lon = 0;

	function updateLocMap(){
		$("#input-plot-radio").prop("checked", true);;
		$(".map-container").trigger('update', [{
			newPlots: {
				"position": {
					latitude: lat,
					longitude: lon,
					size:10,
					attrs:{
						fill:"#E74C3C"
					}
				}
			},
			deletePlotKeys:["position"],
                }]);
	}

	$('#input-plot-lat').bind('input', function(){
		lat = $(this).val();
		updateLocMap();
	});

	$('#input-plot-lon').bind('input', function(){
		lon = $(this).val();
		updateLocMap();
	});

	//Goolgle help
	$("#link-google-help").click(function(){
		$(".overlay").fadeIn( "slow", function(){
			$(".google-help").fadeIn( "slow", function() {});
		});
	});

	$(".overlay").click(function(){
		$(".google-help").fadeOut( "slow", function(){
			$(".overlay").fadeOut( "slow", function() {});
		});
	});
	
	$("#input-plot-google").on('paste', function(e){
		var data = e.originalEvent.clipboardData.getData('text').split(",");
		$("#input-plot-lat").val(data[0].trim());
		$("#input-plot-lon").val(data[1].trim());
		lat = data[0].trim();
		lon = data[1].trim();
		setTimeout(function(){$("#input-plot-google").val('')},10);
		updateLocMap();
	});
	$("#btn-save-location").click(function(){
		console.log("save=1&"+$("#form-location").serialize());
		$.post( "ajax/locations.php", "save=1&"+$("#form-location").serialize())
		.done(function(data){
			console.log(data);
			loadLocations();
		},"json");
	});

</script>
<?php
	}
?>
