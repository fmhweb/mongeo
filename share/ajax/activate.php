<?php
	//TODO: Change table to div

	if(isset($_POST['active']) && isset($_POST['name'])){
		require('../mysqli/mysqli.connect.php');
		if($stmt = $mysqli->prepare("UPDATE `maps` SET `active` = ? WHERE `name` = ?")){
			$stmt->bind_param("is", $_POST['active'],$_POST['name']);
                        $stmt->execute();
                        $stmt->close();
                }
                $mysqli->close();
	}
	elseif(isset($_POST['title']) && isset($_POST['name']) && isset($_POST['ratio'])){
		require('../mysqli/mysqli.connect.php');
		if($stmt = $mysqli->prepare("INSERT INTO `maps` (`title`,`name`,`js_file`,`ratio`) VALUES (?,?,?,?)")){
			$stmt->bind_param("sssd", $_POST['title'],$_POST['name'],$_POST['js_file'],$_POST['ratio']);
                        $stmt->execute();
                        $stmt->close();
                }
                $mysqli->close();
	}
	else{
		require('../mysqli/mysqli.connect.php');
		$scan_result = "";
		if(isset($_POST['reload_maps'])){
			if($stmt = $mysqli->prepare("SELECT id,js_file FROM `maps`")){
				$stmt->execute();
				$result = $stmt->get_result();
				while($row = $result->fetch_assoc()){
					if(!is_file("../js/maps/".$row['js_file'])){
						if($stmt2 = $mysqli->prepare("DELETE FROM `maps_active` WHERE id = ?")){
							$stmt->bind_param("i", $row['id']);
							$stmt2->execute();
							$result = $stmt2->get_result();
							$stmt2->close();
						}
					}
				}
				$stmt->close();
			}
			$new_maps = array();
			foreach(scandir("../js/maps/") as $file){
				if(preg_match('/(.*)\.min\.js$/',$file,$name)){
					if($stmt = $mysqli->prepare("SELECT id FROM `maps` WHERE js_file = ?")){
						$stmt->bind_param("s", $file);
						$stmt->execute();
						$result = $stmt->get_result();
						$stmt->close();
						if(!$result->num_rows){
							$new_maps[] = array('title'=>ucwords(str_replace("_"," ",$name[1])), 'name'=>$name[1], 'js_file'=>$file);
						}
					}
				}
			}
?>
<div class="globlb"></div>
<div class="mapamc">
	<div class="map">
		<span>Loading map...</span>
	</div>
</div>
<div id="imaparc">
	<div id="imapap"></div>
	<div id="imapapb"></div>
	<br>
	<div align="center"><input id="bmapard" type="button" value="Abort" /></div>
	<br>
	<br>
	<div id="imapapi">Scanning map: 0/0</div>
</div>
<script>
	var newMaps = <?= json_encode($new_maps) ?>;
	var newMapsLength = newMaps.length;
	var newMapsInterval;
	var newMapsIntervalTries = 10;

	$( function() {
		$( "#imapapb" ).progressbar({
			max: newMapsLength,
			value: 0
		});
	});

	$( document ).ready(function() {
		$( ".globlb" ).fadeIn( "slow", function() {
			$( "#imaparc" ).fadeIn( "slow", function() {});
		});
		checkMaps(0);
	});

	function checkMaps(i){
		$( "#imapapb" ).progressbar( "value", i );
		$.getScript('js/maps/'+newMaps[i]['js_file'])
		.done(function( script, textStatus ) {
			$(function () {
				$(".mapamc").mapael({
					map: {
						name: newMaps[i]['name']
					}
				});
			});
		});
		var t = 0;
		var ratio = 0;
		newMapsInterval = setInterval(function() {
			t++;
			$( "#imapap" ).text("Scanning map: "+i+"/"+newMapsLength+" - "+newMaps[i]['title']+" ("+t+"/"+newMapsIntervalTries+")");
			checkMapsWait(i,t);
		}, 500);
	}
	
	function checkMapsWait(i,t){
		if($(".mapamc").height() > 50){
			clearInterval(newMapsInterval);
			ratio = $(".mapamc").width() / $(".mapamc").height();
			//$("#imapapi").append("<div>Map loaded: "+newMaps[i]['title']</div>");
			$.post( "ajax/activate.php", {title:newMaps[i]['title'], name:newMaps[i]['name'], js_file:newMaps[i]['js_file'], ratio:ratio.toFixed(3)})
			.done(function(){
				checkMapsDone(i);
			});
		}
		else if(t >= newMapsIntervalTries){
			clearInterval(newMapsInterval);
			$("#imapapi").append("<div class=\"error\">Failed loading map: "+newMaps[i]['title']+"</div>");
			checkMapsDone(i);
		}
	}

	function checkMapsDone(i){
		i++;
		if(i < newMapsLength){
			$(".mapamc").html("<div class=\"map\"><span>Loading map...</span></div>");
			checkMaps(i);
		}
		else{
			$("#imapap").text("Complete");
			$("#bmapard").val("Close");
			$("#imapapb").progressbar("value", newMapsLength );
			$(".mapamc").fadeOut("slow", function() {});
		}
	}

	$("#bmapard").click(function(){
		$( "#imaparc" ).fadeOut( "slow", function() {
			$( ".globlb" ).fadeOut( "slow", function() {
				$.post( "ajax/activate.php", function( data ) {
					$( "#menu-opt" ).html( data );
				});
			});
		});
	});
</script>
<?php
		}
?>
<div class="jumbotron p-3 p-md-5 mt-4 text-white rounded bg-dark">
	<div class="col-md-6 px-0">
		<p class="lead mb-3">Maps that are not needed can be disabled here. They will not be preloaded anymore and take up memory and loading time on the client.</p>
		<p class="lead mb-3">It is possible to create your own map. Please follow the instructions here: <a href="https://www.vincentbroute.fr/mapael/create-map.php" target="_blank">https://www.vincentbroute.fr/mapael/create-map.php</a></p>
		<p class="lead mb-3">A list of available maps can be found in this repository: <a href="https://github.com/neveldo/mapael-maps" target="_blank">https://github.com/neveldo/mapael-maps</a></p>
		<p class="lead mb-3">Read the documentaion on how to add a new map to naGeo: <a href="www.it-hunger.de" target="_blank">www.it-hunger.de</a></p>
		<p class="lead mb-3">If you have added a new map or removed a map from the maps directory click here: <button id="btn-load-maps" type="button" class="btn btn-primary ml-2">Update maps</button></p>
	</div>
</div>
<div class="container">
	<table class="table">
		<thead>
			<tr>
				<th scope="col">Name</th>
				<th scope="col">Ratio</th>
				<th scope="col">Active</th>
			</tr>
		</thead>
		<tbody>
<?php
		if($stmt = $mysqli->prepare("SELECT title,name,active,ratio FROM maps ORDER BY name")){
			$stmt->execute();
			$result = $stmt->get_result();
			while($row = $result->fetch_assoc()){
				echo "<tr><td>".$row['title']."</td><td>".$row['ratio']."</td><td>".$row['active']."</td></tr>\n";
			}
			$stmt->close();
		}
		$mysqli->close();
?>
		</tbody>
	</table>
</div>
<script>
	$( "#btn-load-maps" ).click(function() {
		$.post( "ajax/activate.php", {reload_maps:1})
		.done(function( data ) {
			$( "#menu-opt" ).html( data );
		});
	});
</script>
<?php } ?>
