<?php
	//TODO: Change table to div

	if(isset($_POST['active']) && isset($_POST['id'])){
		require('../mysqli/mysqli.connect.php');
		if($stmt = $mysqli->prepare("UPDATE `maps` SET `active` = ? WHERE `id` = ?")){
			$stmt->bind_param("ii", $_POST['active'],$_POST['id']);
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
						if($stmt2 = $mysqli->prepare("DELETE FROM `maps` WHERE id = ?")){
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
<div class="map-container">
	<div class="map">
		<span>Loading map...</span>
	</div>
</div>
<div class="overlay bg-dark">
	<div class="status-container">
		<div id="status"></div>
		<div class="progress">
			<div class="progress-bar  progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="<?= count($new_maps)  ?>"></div>
		</div>
		<br>
		<div align="center"><button id="btn-close" class="btn btn-primary ml-2" type="button">Abort</button></div>
		<br>
		<div id="statusi"></div>
	</div>
</div>
<script>
	var newMaps = <?= json_encode($new_maps) ?>;
	var newMapsLength = newMaps.length;
	var newMapsInterval;
	var newMapsIntervalTries = 10;
	var newMapsError = false;

	$( document ).ready(function() {
		$( ".overlay" ).fadeIn( "slow", function() {
			$( ".status-container" ).fadeIn( "slow", function() {});
		});
		checkMaps(0);
	});

	function checkMaps(i){
		$( ".progress-bar" ).css('width', (100 / newMapsLength * i)+'%').attr('aria-valuenow', i);
		$.getScript('js/maps/'+newMaps[i]['js_file'])
		.done(function( script, textStatus ) {
			$(function () {
				$(".map-container").mapael({
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
			$( "#status" ).text("Scanning map: "+(i + 1)+"/"+newMapsLength+" - "+newMaps[i]['title']+" ("+t+"/"+newMapsIntervalTries+")");
			checkMapsWait(i,t);
		}, 500);
	}
	
	function checkMapsWait(i,t){
		if($(".map-container").height() > 50){
			clearInterval(newMapsInterval);
			ratio = $(".map-container").width() / $(".map-container").height();
			$.post( "ajax/activate.php", {title:newMaps[i]['title'], name:newMaps[i]['name'], js_file:newMaps[i]['js_file'], ratio:ratio.toFixed(3)})
			.done(function(){
				checkMapsDone(i);
			});
		}
		else if(t >= newMapsIntervalTries){
			clearInterval(newMapsInterval);
			$("#statusi").append("<div class=\"alert alert-danger\">Failed loading map: "+newMaps[i]['title']+"</div>");
			newMapsError = true;
			checkMapsDone(i);
		}
	}

	function checkMapsDone(i){
		i++;
		if(i < newMapsLength){
			$(".map-container").html("<div class=\"map\"><span>Loading map...</span></div>");
			checkMaps(i);
		}
		else{
			$("#status").text("Complete");
			$("#btn-close").text("Close");
			$( ".progress-bar" ).css('width', '100%').attr('aria-valuenow', newMapsLength);
			if(newMapsError){$(".progress-bar").addClass('bg-danger');}
			else{$(".progress-bar").addClass('bg-success');}
			$(".map-container").fadeOut("slow", function() {});
		}
	}

	$("#btn-close").click(function(){
		$( ".status-container" ).fadeOut( "slow", function() {
			$( ".overlay" ).fadeOut( "slow", function() {
				$.post( "ajax/activate.php", function( data ) {
					$( "#main-container" ).html( data );
				});
			});
		});
	});
</script>
<?php
		}
?>
<div class="container-fluid clear-top">
	<table class="table table-hover">
		<thead>
			<tr>
				<th scope="col">Name</th>
				<th scope="col">Ratio</th>
				<th scope="col">Active</th>
			</tr>
		</thead>
		<tbody>
<?php
		$check = array('location_off','location_on');
		$check_clr = array('danger','success');
		if($stmt = $mysqli->prepare("SELECT id,title,active,ratio FROM maps ORDER BY name")){
			$stmt->execute();
			$result = $stmt->get_result();
			while($row = $result->fetch_assoc()){
				echo "<tr class=\"map-entry\" data-id=\"".$row['id']."\" data-active=\"".$row['active']."\"><td>".$row['title']."</td><td>".$row['ratio']."</td><td class=\"text-center active-icon\"><i class=\"material-icons text-".$check_clr[$row['active']]."\">".$check[$row['active']]."</i></td></tr>\n";
			}
			$stmt->close();
		}
		$mysqli->close();
?>
		</tbody>
	</table>
</div>
<div class="jumbotron p-3 p-md-5 mt-4 text-white rounded bg-dark">
	<div class="col-md-6 px-0">
		<p class="lead mb-3">If you have added a new map or removed a map from the maps directory click here: <button id="btn-load-maps" type="button" class="btn btn-primary ml-2">Update maps</button></p>
		<p class="lead mb-3">Maps that are not needed can be disabled here. They will not be preloaded anymore and take up memory and loading time on the client.</p>
		<p class="lead mb-3">It is possible to create your own map. Please follow the instructions here: <a href="https://www.vincentbroute.fr/mapael/create-map.php" target="_blank">https://www.vincentbroute.fr/mapael/create-map.php</a></p>
		<p class="lead mb-3">Read the documentaion on how to add a new map to Mongeo (EXTERNAL LINK): <a href="www.it-hunger.de" target="_blank">www.it-hunger.de/eyegeo/</a></p>
		<p class="lead mb-3 text-secondary">HINT: Check this out: This is the true size of countries (EXTERNAL LINK): <a href="https://thetruesize.com/" target="_blank">https://thetruesize.com/</a></p>F3AFFAF3A
	</div>
</div>
<script>
	$("#btn-load-maps").click(function() {
		$.post( "ajax/activate.php", {reload_maps:1})
		.done(function( data ) {
			$( "#main-container" ).html(data);
		});
	});
	
	$(".map-entry").click(function() {
		if($(this).data("active") == 1){
			$(this).data("active",0);
			$(this).find(".active-icon").html("<i class=\"material-icons text-danger\">location_off</i>");
			$.post( "ajax/activate.php", {id:$(this).data("id"),active:0})
		}
		else{
			$(this).data("active",1);
			$(this).find(".active-icon").html("<i class=\"material-icons text-success\">location_on</i>");
			$.post( "ajax/activate.php", {id:$(this).data("id"),active:1})
		}
	});
</script>
<?php } ?>
