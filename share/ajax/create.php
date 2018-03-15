<?php
	require('../mysqli/mysqli.connect.php');
?>

<div class="container-fluid clear-top">
	<div class="row">
		<div class="col-sm pt-3">
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
				<div class="form-group">
					<label for="input-location">2) Select a location:</label>
					<select class="form-control" id="input-location" name="map">
					</select>
				</div>
				<label>3) Add hosts and services:</label>
				<br>
				<div class="form-group" id="form-group-serviceg">
                                        <div class="mt-3">
                                                <input class="form-control" name="include_serviceg" placeholder="Servicegroup" />
                                        </div>
                                        <div class="mt-3" id="form-group-service-exc-ser">
                                                <input class="form-control" name="exclude_service_host" placeholder="Exclude host" />
                                        </div>
                                        <div class="mt-3" id="form-group-service-exc-serg">
                                                <input class="form-control" name="exclude_service_hostg" placeholder="Exclude hostgroup" />
                                        </div>
                                </div>
				<div id="accordion">
					<div class="card">
						<div class="card-header" id="heading-serivce">
							<h5 class="mb-0">
								<button class="btn btn-link" data-toggle="collapse" data-target="#collapse-serivce" aria-expanded="true" aria-controls="collapse-serivce">
									Add service
								</button>
							</h5>
						</div>
						<div id="collapse-serivce" class="collapse" aria-labelledby="heading-serivce" data-parent="#accordion">
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
					<div class="card">
						<div class="card-header" id="heading-host">
							<h5 class="mb-0">
								<button class="btn btn-link" data-toggle="collapse" data-target="#collapse-host" aria-expanded="true" aria-controls="collapse-host">
									Add host
								</button>
							</h5>
						</div>
						<div id="collapse-host" class="collapse" aria-labelledby="heading-host" data-parent="#accordion">
							<div class="card-body">
								<div class="form-group" id="form-group-host">
									<div class="mt-3">
										<input class="form-control" name="include_host" placeholder="Hostname" />
										<button type="button" class="btn btn-outline-primary btn-sm btn-block"><i class="material-icons">add</i></button>
									</div>
									<div class="mt-3">
										<input class="form-control" name="include_service" placeholder="Service" />
										<button type="button" class="btn btn-outline-primary btn-sm btn-block"><i class="material-icons">add</i></button>
									</div>
									<div class="mt-3" id="form-group-host-exc-ser">
										<input class="form-control" name="exclude_host_service" placeholder="Exclude service" />
										<button type="button" class="btn btn-outline-primary btn-sm btn-block"><i class="material-icons">add</i></button>
									</div>
									<div class="mt-3" id="form-group-host-exc-serg">
										<input class="form-control" name="exclude_host_serviceg" placeholder="Exclude servicegroup" />
										<button type="button" class="btn btn-outline-primary btn-sm btn-block"><i class="material-icons">add</i></button>
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
<?php
	$mysqli->close();
?>
