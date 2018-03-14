<?php
	require('../mysqli/mysqli.connect.php');
?>
<div class="locmap">
	<div class="map">
		<span>Loading map...</span>
	</div>
</div>
<div class="locm">
	<form>
		1) Choose a name for the new location.
		<span class="locmi"><br>(Existing location will drop down below, so you can see which ones exist with a similiar name. Hover over them to temporarily show them on the map)</span>
		<br>
		<br>
		<input class="locmfe" type="text" placeholder="Location name" />
		<br>
		<br>
		2) Select a map:
		<br>
		<br>
		<select class="locmfe locmfes">
			<option value="" disabled selected>Choose...</option>
<?php
	if($stmt = $mysqli->prepare("SELECT id, title, name, ratio FROM `maps_activate` WHERE `active` = 1")){
		$stmt->execute();
		$result = $stmt->get_result();
		while($row = $result->fetch_assoc()){
			echo "<option value=\"".$row['id']."\" data-title=\"".$row['title']."\" data-name=\"".$row['name']."\" data-ratio=\"".$row['ratio']."\">".$row['title']."</option>\n";
		}
		$stmt->close();
	}
?>
		</select>
		<br>
		<br>
		3) Set a location:
		<span class="locmi"><br>(Locations are not map specific since they depend on latitude and longlitude, but map areas are)</span>
		<br>
		<br>
		<table><tr>
			<td><i class="locmo">Option 1 - Provide the latitude and longlitude</i></td>
			<td><input type="radio" name="option" value="2" /></td>
		</tr></table>
		<span class="locmi">(You can coose a location on the world map and then switch to another map in order to narrow down on your location)<br></span>
		<br>
		<input class="locmfe locmfela" type="number" placeholder="Latitude" />
		<br>
		<input class="locmfe locmfelo" type="number" placeholder="Longlitude" />
		<br>
		<br>
		<input class="locmfe" type="text" placeholder="Google maps coordinates" />
		<br>
		<span id="iblocgi" class="li locmi">Use google maps instructions<br></span>
		<br>
		<table><tr>
			<td><i class="locmo">Option 2 - Click on the map and use the selected area (tile):</i></td>
			<td><input type="radio" name="option" value="3" /></td>
		</tr></table>
		<br>
		<input class="locmfe" type="text" placeholder="Selected area" disabled />
		<br>
		<br>
		3) Save the location or area:
		<br>
		<br>
		<input class="locmfe" type="button" value="Save" disabled />
		<input class="locmfe" type="button" value="Discard" disabled />
		<br>
		<br>
		<i id="iblocmis" class="material-icons bt">help_outline</i>
		<br>
		<br>
	</form>
</div>
<div class="globlb"></div>
<div class="locgiic"><img class="locgii" src="images/google-maps-instructions.png" /></div>
<?php
	$mysqli->close();
?>
<script>
	$( document ).ready(function() {
		//$(".menr").append($(".locm"));
		$(".menr").empty();
		$(".locm>").appendTo(".menr");
		$(".menr").fadeIn( "slow", function(){}).css("display","table-cell");
		loadMap('World Countries','world_countries','2.534','meno','locmap',40,30,true);
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
		$(".locmap").trigger('update', [{
			newPlots: newPlots
		}]);
	});

	//Select map
	$('.locmfes').change(function(){
		loadMap($(this).text(),$(this).find(':selected').data('name'),$(this).find(':selected').data('ratio'),'meno','locmap',40,30,true);
	});

	//Lat Long
	var lat = 0;
	var lon = 0;

	function updateLocMap(){
		console.log(lat+" - "+lon)
		var newPlots = {
                        "position": {
                                latitude: lat,
                                longitude: lon,
                                size:10,
                                attrs:{
                                        fill:"#E74C3C"
                                }
                        }
                }
		$(".locmap").trigger('update', [{
			newPlots: newPlots,
			deletePlotKeys:["position"],
                }]);
	}

	$('.locmfela').bind('input', function(){
		lat = $(this).val();
		updateLocMap();
	});

	$('.locmfelo').bind('input', function(){
		lon = $(this).val();
		updateLocMap();
	});

	//Goolgle help
	$("#iblocgi").click(function(){
		$(".globlb").fadeIn( "slow", function(){
			$(".locgiic").fadeIn( "slow", function() {});
		});
	});
	$(".globlb").click(function(){
		$(".locgiic").fadeOut( "slow", function(){
			$(".globlb").fadeOut( "slow", function() {});
		});
	});

	//Help button
	$("#iblocmis").click(function(){
		$(".locmi").fadeToggle( "slow", function(){});
	});
</script>
