<?php
	require('../mysqli/mysqli.connect.php');
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
			<form>
				<div class="form-group">
					<label for="input-map">1) Select a map:</label>
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
				<label>2) Add elements to map:</label>
				<div id="accordion-main">
                                        <div class="card">
                                                <div class="card-header" id="heading-opt1">
                                                        <h5 class="mb-0">
                                                                <button type="button" class="btn btn-link" data-toggle="collapse" data-target="#collapse-opt1" aria-expanded="true" aria-controls="collapse-opt1">
                                                                        Option 2.1 - Hosts and services
                                                                </button>
                                                        </h5>
                                                </div>
                                                <div id="collapse-opt1" class="collapse" aria-labelledby="heading-opt1" data-parent="#accordion-main">
                                                        <div class="card-body">
								<div class="form-group">
									<label for="input-location">Select a location:</label>
									<select class="form-control" id="input-location" name="map">
									</select>
								</div>
								<div id="accordion">
									<div class="card">
										<div class="card-header" id="heading-hostg">
											<h5 class="mb-0">
												<button type="button" class="btn btn-link" data-toggle="collapse" data-target="#collapse-hostg" aria-expanded="true" aria-controls="collapse-hostg">
													Add hostgroup
												</button>
											</h5>
										</div>
										<div id="collapse-hostg" class="collapse" aria-labelledby="heading-hostg" data-parent="#accordion">
											<div class="card-body">
												<div class="form-group" id="form-group-hostg">
													<div class="mt-3">
														<input class="form-control" name="include_hostg" placeholder="Servicegroup" />
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="card">
										<div class="card-header" id="heading-serviceg">
											<h5 class="mb-0">
												<button type="button" class="btn btn-link" data-toggle="collapse" data-target="#collapse-serviceg" aria-expanded="true" aria-controls="collapse-serviceg">
													Add servicegroup
												</button>
											</h5>
										</div>
										<div id="collapse-serviceg" class="collapse" aria-labelledby="heading-serviceg" data-parent="#accordion">
											<div class="card-body">
												<div class="form-group" id="form-group-serviceg">
													<div class="mt-3">
														<input class="form-control" name="include_serviceg" placeholder="Servicegroup" />
													</div>
													<div class="mt-3" id="form-group-serviceg-exc-ser">
														<input class="form-control" name="exclude_serviceg_host" placeholder="Exclude host" />
													</div>
													<div class="mt-3" id="form-group-serviceg-exc-serg">
														<input class="form-control" name="exclude_serviceg_hostg" placeholder="Exclude hostgroup" />
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="card">
										<div class="card-header" id="heading-service">
											<h5 class="mb-0">
												<button type="button" class="btn btn-link" data-toggle="collapse" data-target="#collapse-service" aria-expanded="true" aria-controls="collapse-service">
													Add service
												</button>
											</h5>
											</div>
											<div id="collapse-service" class="collapse" aria-labelledby="heading-service" data-parent="#accordion">
												<div class="card-body">
													<div class="form-group" id="form-group-service">
														<div class="mt-3">
															<input class="form-control" name="include_service" placeholder="Service" />
														</div>
														<div class="mt-3" id="form-group-service-exc-ser">
															<input class="form-control" name="exclude_service_host" placeholder="Exclude host" />
														</div>
														<div class="mt-3" id="form-group-service-exc-serg">
															<input class="form-control" name="exclude_service_hostg" placeholder="Exclude hostgroup" />
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="card">
										<div class="card-header" id="heading-host">
											<h5 class="mb-0">
												<button type="button" class="btn btn-link" data-toggle="collapse" data-target="#collapse-host" aria-expanded="true" aria-controls="collapse-host">
													Add host
												</button>
											</h5>
										</div>
										<div id="collapse-host" class="collapse" aria-labelledby="heading-host" data-parent="#accordion">
											<div class="card-body">
												<div class="form-group" id="form-group-host">
													<div class="mt-3">
														<input class="form-control" name="include_host" placeholder="Hostname" />
														<button type="button" class="btn btn-outline-primary btn-sm"><i class="material-icons">add</i></button>
													</div>
													<div class="mt-3">
														<input class="form-control" name="include_service" placeholder="Service" />
														<button type="button" class="btn btn-outline-primary btn-sm"><i class="material-icons">add</i></button>
													</div>
													<div class="mt-3" id="form-group-host-exc-ser">
														<input class="form-control" name="exclude_host_service" placeholder="Exclude service" />
														<button type="button" class="btn btn-outline-primary btn-sm"><i class="material-icons">add</i></button>
													</div>
												<div class="mt-3" id="form-group-host-exc-serg">
													<input class="form-control" name="exclude_host_serviceg" placeholder="Exclude servicegroup" />
													<button type="button" class="btn btn-outline-primary btn-sm"><i class="material-icons">add</i></button>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="card">
						<div class="card-header" id="heading-network">
							<h5 class="mb-0">
								<button type="button" class="btn btn-link" data-toggle="collapse" data-target="#collapse-network" aria-expanded="true" aria-controls="collapse-network">
									Option 2.2 - Network
								</button>
							</h5>
						</div>
						<div id="collapse-network" class="collapse" aria-labelledby="heading-network" data-parent="#accordion-main">
							<div class="card-body">
								<div class="form-group" id="form-group-network">
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
		</div>
	</div>
</div>
<div class="jumbotron p-3 p-md-5 mt-4 text-white rounded bg-dark">
	<div class="col-md-6 px-0">
		<p class="lead mb-3">Option 2.2 - Enable parent detection or create an andvanced network view</p>
		<p class="lead mb-3"></p>
	</div>
</div>
<script>
function loadMapCreate(title,name,ratio,containerx,containery,map,offsetx = 0, offsety = 0, zoom = false){

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
                                                fill: "#007bff",
                                                stroke: "#ced8d0"
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
                                }
                        }
                });
        }

	$( document ).ready(function() {
                loadMapCreate($("#input-map").text(),$("#input-map").find(':selected').data('name'),$("#input-map").find(':selected').data('ratio'),'#map-col',window,'.map-container',40,80,true);
        });

	$('#input-map').change(function(){
                loadMapCreate($(this).text(),$(this).find(':selected').data('name'),$(this).find(':selected').data('ratio'),'#map-col',window,'.map-container',40,80,true);
        });
</script>
<?php
	$mysqli->close();
?>
