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
			<form id="form-filter">
				<div class="form-group">
					<label for="map-name">1) Choose a name:</label>
					<input class="form-control" id="map-name" type="text" name="name" />
				</div>
				<div class="form-group">
					<label for="map-select">2) Select a map:</label>
					<select class="form-control" id="map-select" name="map">
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
			</form>
			<label>3) Add items to map:</label>
			<div id="accordion-main">
				<div class="card">
					<div class="card-header p-1" id="heading-opt1">
						<h5 class="mb-0">
							<button type="button" class="btn btn-link" data-toggle="collapse" data-target="#collapse-opt1" aria-expanded="true" aria-controls="collapse-opt1">
								Option 3.1 - Hosts and services
							</button>
						</h5>
					</div>
					<div id="collapse-opt1" class="collapse" aria-labelledby="heading-opt1" data-parent="#accordion-main">
						<div class="card-body">
							<form id="form-location">
								<div class="form-group">
									<div class="btn-group" role="group" aria-label="Choose location type">
										<button type="button" class="btn btn-primary location-type" id="location-type-1" data-type="1">Auto</button>
										<button type="button" class="btn btn-secondary location-type" id="location-type-2" data-type="2">Plot</button>
										<button type="button" class="btn btn-secondary location-type" id="location-type-3" data-type="3">Area</button>
									</div>
									<input type="text" class="form-control collapse hostgroups mt-3" id="location-hostgroup" name="hostgroup" placeholder="Location hostgroup" />
									<select class="form-control collapse mt-2" id="location-plot" name="plot">
										<option selected disabled>Choose location:</option>
<?php
	$locationNames = array();
	if($stmt = $mysqli->prepare("SELECT id, name, latitude, longlitude FROM `location_plots`")){
		$stmt->execute();
		$result = $stmt->get_result();
		while($row = $result->fetch_assoc()){
			echo "<option value=\"".$row['id']."\" data-name=\"".$row['name']."\" data-latitude=\"".$row['latitude']."\" data-longlitude=\"".$row['longlitude']."\">".$row['name']."</option>\n";
			$locationNames[$row['id']] = $row['name'];
		}
		$stmt->close();
	}
?>
									</select>
									<input type="text" class="form-control collapse mt-2" id="location-area" name="hostgroup" placeholder="Choose area on map" readonly />
									<label id="location-error" class="text-danger collapse pl-2"></label>
								</div>
							</form>
							<div id="accordion">
								<div class="card">
									<div class="card-header p-1" id="heading-hostgroups">
										<h5 class="mb-0">
											<button type="button" class="btn btn-link" data-toggle="collapse" data-target="#collapse-hostgroups" aria-expanded="true" aria-controls="collapse-hostgroups">
												Add hostgroup
											</button>
										</h5>
									</div>
									<div id="collapse-hostgroups" class="collapse" aria-labelledby="heading-hostgroups" data-parent="#accordion">
										<div class="card-body">
											<form id="form-add-hostgroups">
												<div class="form-group">
													<div class="mt-1" id="add-hostgroups">
														<input class="form-control hostgroups" name="add-hostgroups[]" placeholder="Hostgroup" />
														<button type="button" data-target="add-hostgroups" data-autocomplete="hostgroups" data-placeholder="Hostgroup" class="btn btn-outline-primary p-0 btn-add-item"><i class="material-icons">add</i></button>
													</div>
													<div class="mt-3" id="include-hostgroups-service">
														<input class="form-control services" name="include-hostgroups-service[]" placeholder="Include service (* for all)" />
														<button type="button" data-target="include-hostgroups-service" data-autocomplete="services" data-placeholder="Include service (* for all)" class="btn btn-outline-primary p-0 btn-add-item"><i class="material-icons">add</i></button>
													</div>
													<div class="mt-3" id="include-hostgroups-servicegroups">
														<input class="form-control servicegroups" name="include-hostgroups-servicegroups[]" placeholder="Include servicegroups" />
														<button type="button" data-target="include-hostgroups-servicegroups" data-autocomplete="services" data-placeholder="Include service (* for all)" class="btn btn-outline-primary p-0 btn-add-item"><i class="material-icons">add</i></button>
													</div>
													<div class="mt-3" id="exclude-hostgroups-service">
														<input class="form-control services" name="exclude-hostgroups-service[]" placeholder="Exclude service" />
														<button type="button" data-target="exclude-hostgroups-service" data-autocomplete="services" data-placeholder="Exclude service" class="btn btn-outline-primary p-0 btn-add-item"><i class="material-icons">add</i></button>
													</div>
													<div class="mt-3" id="exclude-hostgroups-servicegroups">
														<input class="form-control servicegroups" name="exclude-hostgroups-servicegroups[]" placeholder="Exclude servicegroup" />
														<button type="button" data-target="exclude-hostgroups-servicegroups" data-autocomplete="servicegroups" data-placeholder="Exclude servicegroup" class="btn btn-outline-primary p-0 btn-add-item"><i class="material-icons">add</i></button>
													</div>
												</div>
											</form>
											<button type="button" id="btn-add-hostgroups" class="btn btn-primary">Add hostgroups</button>
										</div>
									</div>
								</div>
								<div class="card">
									<div class="card-header p-1" id="heading-servicegroups">
										<h5 class="mb-0">
											<button type="button" class="btn btn-link" data-toggle="collapse" data-target="#collapse-servicegroups" aria-expanded="true" aria-controls="collapse-servicegroups">
												Add servicegroup
											</button>
										</h5>
									</div>
									<div id="collapse-servicegroups" class="collapse" aria-labelledby="heading-servicegroups" data-parent="#accordion">
										<div class="card-body">
											<form id="form-add-servicegroups">
												<div class="form-group">
													<div class="mt-1" id="add-servicegroups">
														<input class="form-control servicegroups" name="add-servicegroups[]" placeholder="Servicegroup" />
														<button type="button" data-target="add-servicegroups" data-autocomplete="servicegroups" data-placeholder="Servicegroup" class="btn btn-outline-primary p-0 btn-add-item"><i class="material-icons">add</i></button>
													</div>
													<div class="mt-3" id="exclude-servicegroups-service">
														<input class="form-control services" name="exclude-servicegroups-service[]" placeholder="Exclude Service" />
														<button type="button" data-target="exclude-servicegroups-service" data-autocomplete="servics" data-placeholder="Exclude service" class="btn btn-outline-primary p-0 btn-add-item"><i class="material-icons">add</i></button>
													</div>
													<div class="mt-3" id="exclude-servicegroups-host">
														<input class="form-control hosts" name="exclude-servicegroups-host[]" placeholder="Exclude host" />
														<button type="button" data-target="exclude-servicegroups-host" data-autocomplete="hosts" data-placeholder="Exclude host" class="btn btn-outline-primary p-0 btn-add-item"><i class="material-icons">add</i></button>
													</div>
													<div class="mt-3" id="exclude-servicegroups-hostgroups">
														<input class="form-control hostgroups" name="exclude-servicegroups-hostgroups[]" placeholder="Exclude hostgroup" />
														<button type="button" data-target="exclude-servicegroups-hostgroups" data-autocomplete="hostgroups" data-placeholder="Exclude hostgroup" class="btn btn-outline-primary p-0 btn-add-item"><i class="material-icons">add</i></button>
													</div>
												</div>
											</form>
											<button type="button" id="btn-add-servicegroups" class="btn btn-primary">Add servicegroups</button>
										</div>
									</div>
								</div>
								<div class="card">
									<div class="card-header p-1" id="heading-service">
										<h5 class="mb-0">
											<button type="button" class="btn btn-link" data-toggle="collapse" data-target="#collapse-service" aria-expanded="true" aria-controls="collapse-service">
												Add service
											</button>
										</h5>
									</div>
									<div id="collapse-service" class="collapse" aria-labelledby="heading-service" data-parent="#accordion">
										<div class="card-body">
											<form id="add-service">
												<div class="form-group">
													<div class="mt-1" id="add-service">
														<input class="form-control services" name="add-service[]" placeholder="Service" />
															<button type="button" data-target="add-service" data-autocomplete="services" data-placeholder="Service" class="btn btn-outline-primary p-0 btn-add-item"><i class="material-icons">add</i></button>
													</div>
													<div class="mt-3" id="exclude-service-host">
														<input class="form-control hosts" name="exclude-service-host[]" placeholder="Exclude host" />
														<button type="button" data-target="exclude-service-host" data-autocomplete="hosts" data-placeholder="Exclude host" class="btn btn-outline-primary p-0 btn-add-item"><i class="material-icons">add</i></button>
													</div>
													<div class="mt-3" id="exclude-service-hostgroup">
														<input class="form-control hostgroups" name="exclude-service-hostgroups[]" placeholder="Exclude hostgroup" />
														<button type="button" data-target="exclude-service-hostgroup" data-autocomplete="hostgroups" data-placeholder="Exclude hostgroup" class="btn btn-outline-primary p-0 btn-add-item"><i class="material-icons">add</i></button>
													</div>
												</div>
											</form>
											<button type="button" id="btn-add-service" class="btn btn-primary">Add service</button>
										</div>
									</div>
								</div>
								<div class="card">
									<div class="card-header p-1" id="heading-host">
										<h5 class="mb-0">
											<button type="button" class="btn btn-link" data-toggle="collapse" data-target="#collapse-host" aria-expanded="true" aria-controls="collapse-host">
												Add host
											</button>
										</h5>
									</div>
									<div id="collapse-host" class="collapse" aria-labelledby="heading-host" data-parent="#accordion">
										<div class="card-body">
											<form id="form-add-host">
												<div class="form-group">
													<div class="mt-1" id="add-host">
														<input class="form-control hosts" name="add-host[]" placeholder="Hostname" />
														<button type="button" data-target="add-host" data-autocomplete="hosts" data-placeholder="Hostname" class="btn btn-outline-primary p-0 btn-add-item"><i class="material-icons">add</i></button>
													</div>
													<div class="mt-3" id="include-host-service">
														<input class="form-control services" name="include-host-service[]" placeholder="Include service (* for all)" />
														<button type="button" data-target="include-host-service" data-autocomplete="services" data-placeholder="Include service (* for all)" class="btn btn-outline-primary p-0 btn-add-item"><i class="material-icons">add</i></button>
													</div>
													<div class="mt-3" id="include-host-servicegroups">
														<input class="form-control servicegroups" name="include-host-servicegroups[]" placeholder="Include servicegroup" />
														<button type="button" data-target="include-host-servicegroups" data-autocomplete="servicegroups" data-placeholder="Include servicegroup" class="btn btn-outline-primary p-0 btn-add-item"><i class="material-icons">add</i></button>
													</div>
													<div class="mt-3" id="exclude-host-service">
														<input class="form-control services" name="exclude-host-service[]" placeholder="Exclude service" />
														<button type="button" data-target="exclude-host-service" data-autocomplete="services" data-placeholder="Exclude service" class="btn btn-outline-primary p-0 btn-add-item"><i class="material-icons">add</i></button>
													</div>
													<div class="mt-3" id="exclude-host-servicegroups">
														<input class="form-control servicegroups" name="exclude-host-servicegroups[]" placeholder="Exclude servicegroup" />
														<button type="button" data-target="exclude-host-servicegroups" data-autocomplete="servicegroups" data-placeholder="Exclude servicegroup" class="btn btn-outline-primary p-0 btn-add-item"><i class="material-icons">add</i></button>
													</div>
												</div>
											</form>
											<button type="button" id="btn-add-host" class="btn btn-primary">Add host</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="card">
					<div class="card-header p-1" id="heading-network">
						<h5 class="mb-0">
							<button type="button" class="btn btn-link" data-toggle="collapse" data-target="#collapse-network" aria-expanded="true" aria-controls="collapse-network">
								Option 3.2 - Parents / Network
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
				<div class="card">
                                        <div class="card-header p-1" id="heading-link-areas">
                                                <h5 class="mb-0">
                                                        <button type="button" class="btn btn-link" data-toggle="collapse" data-target="#collapse-link-areas" aria-expanded="true" aria-controls="collapse-link-areas">
                                                                Option 3.3 - Legend options
                                                        </button>
                                                </h5>
                                        </div>
                                        <div id="collapse-link-areas" class="collapse" aria-labelledby="heading-link-areas" data-parent="#accordion-main">
                                                <div class="card-body">
                                                        <div class="form-group" id="form-group-link-areas">
                                                        </div>
                                                </div>
                                        </div>
                                </div>
				<div class="card">
					<div class="card-header p-1" id="heading-link-areas">
						<h5 class="mb-0">
							<button type="button" class="btn btn-link" data-toggle="collapse" data-target="#collapse-link-areas" aria-expanded="true" aria-controls="collapse-link-areas">
								Option 3.4 - Link areas to other maps
							</button>
						</h5>
					</div>
					<div id="collapse-link-areas" class="collapse" aria-labelledby="heading-link-areas" data-parent="#accordion-main">
						<div class="card-body">
							<div class="form-group" id="form-group-link-areas">
							</div>
						</div>
					</div>
				</div>
				<div class="card">
					<div class="card-header p-1" id="heading-map-theme">
						<h5 class="mb-0">
							<button type="button" class="btn btn-link" data-toggle="collapse" data-target="#collapse-map-theme" aria-expanded="true" aria-controls="collapse-map-theme">
								Option 3.5 - Map theme
							</button>
						</h5>
					</div>
					<div id="collapse-map-theme" class="collapse" aria-labelledby="heading-map-theme" data-parent="#accordion-main">
						<div class="card-body">
                                                                <div class="form-group" id="form-group-map-theme">
                                                                </div>
                                                </div>
                                        </div>
				</div>
			</div>
			<div>
				<form id="form-user">
					<div class="form-group mt-3">
						<label>4) User settings:</label>
					</div>
				</form>
			</div>
			<div>
				<form id="form-user">
					<div class="form-group mt-3">
						<label>4) Global settings:</label>
						<h6 class="text-secondary">Host states:</h6>
						<input class="ml-3" type="checkbox" id="up-servicegroups" name="up" checked />
						<label class="mr-2" for="up-servicegroups">Up</label>
						<input type="checkbox" id="down-servicegroups" name="down" checked />
						<label for="down-servicegroups">Down</label>
						<br>
						<h6 class="text-secondary">Service states:</h6>
						<input class="ml-3" type="checkbox" id="ok-servicegroups" name="ok" checked />
						<label class="mr-2" for="ok-servicegroups">OK</label>
						<input type="checkbox" id="warning-servicegroups" name="warning" checked />
						<label class="mr-2" for="warning-servicegroups">Warning</label>
						<input type="checkbox" id="critical-servicegroups" name="critical" checked />
						<label class="mr-2" for="critical-servicegroups">Critical</label>
						<input type="checkbox" id="unknown-servicegroups" name="unknown" checked />
						<label for="unknown-servicegroups">Unknown</label>
						<br>
						<h6 class="text-secondary">Acknowledgments:</h6>
						<input class="ml-3" type="checkbox" id="acknowledge-hosts-servicegroups" name="acknowledge-hosts" />
						<label for="acknowledge-hosts-servicegroups">Show acknowleged hosts as OK</label>
						<br>
						<input class="ml-3" type="checkbox" id="acknowledge-services-servicegroups" name="acknowledge-services" />
						<label for="acknowledge-services-servicegroups">Show acknowleged services as OK</label>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<div id="filter-buttons" class="container-fluid mb-3 collapse">
	<button type="button" id="btn-preview-filter" class="btn btn-outline-primary">Preview Filter</button>
	<button type="button" id="btn-save-filter" class="btn btn-outline-primary ml-1">Save Filter</button>
</div>
<div class="container-fluid clearfix mt-3" id="filters">
</div>
<div class="jumbotron p-3 p-md-5 mt-4 text-white rounded bg-dark">
	<div class="col-md-6 px-0">
		<p class="lead mb-3">Option 2.2 - Enable parent detection or create an andvanced network view</p>
		<p class="lead mb-3"></p>
	</div>
</div>
<script>
	var locationType = 1;
	var locationNames = <?= json_encode($locationNames) ?>;

	console.log(locationNames);

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
					eventHandlers:{
						click: function (e, id, mapElem, textElem) {
							$("#location-area").val(id);
						}	
					}	
                                }
                        }
                });
        }

	function loadItems(){
		$.post( "./cgi-bin/livestatus.items.cgi", function( data ) {
			$(".hosts").autocomplete({source:data.hosts,minLength:0})
			.focus(function(){$(this).autocomplete("search");});
			$(".services").autocomplete({source:data.services,minLength:0})
			.focus(function(){$(this).autocomplete("search");});
			$(".hostgroups").autocomplete({source:data.hostgroups,minLength:0})
			.focus(function(){$(this).autocomplete("search");});
			$(".servicegroups").autocomplete({source:data.servicegroups,minLength:0})
			.focus(function(){$(this).autocomplete("search");});
			items = data;
			loading(0);
		},"json");
	}

	$( document ).ready(function(){
                loadMapCreate($("#map-select").text(),$("#map-select").find(':selected').data('name'),$("#map-select").find(':selected').data('ratio'),'#map-col',window,'.map-container',40,80,true);
		loadItems();
        });

	$.fn.serializeObject = function(){
		var o = {};
		var a = this.serializeArray();
		$.each(a, function(){
			if(this.value){
				if (o[this.name] !== undefined){
					if (!o[this.name].push){
						o[this.name] = [o[this.name]];
					}
					o[this.name].push(this.value || '');
				}
				else{
					o[this.name] = this.value || '';
				}
			}
		});
		return o;
	};

	$('#map-select').change(function(){
                loadMapCreate($(this).text(),$(this).find(':selected').data('name'),$(this).find(':selected').data('ratio'),'#map-col',window,'.map-container',40,80,true);
        });

	$('.btn-add-item').click(function(){
		$("#"+$(this).data("target")+" .btn:last").before("<input class=\"form-control "+$(this).data("autocomplete")+"\" name=\""+$(this).data("target")+"[]\" placeholder=\""+$(this).data("placeholder")+"\" />");
		$("."+$(this).data("autocomplete")).autocomplete({source:items[$(this).data("autocomplete")],minLength:0})
		.focus(function(){$(this).autocomplete("search");});
	});
	
	var filterData = {map:"",filter:{},network:"",cityconnect:"",theme:""};

	function createFilterObject(key){
		var o = {"location":{}};
		var valid = true;

		if(locationType == 1){
			o["location"]["type"] = 1;
		}
		else if(locationType == 2){
			o["location"]["type"] = 2;
			o["location"]["hostgroup"] = $("#location-hostgroup").val();
			o["location"]["plot"] = $("#location-plot").val();
			alert($("#location-plot").val());
			if(!o["location"]["hostgroup"]){
				$("#location-hostgroup").addClass("is-invalid");
				valid = false;
			}
			if(!o["location"]["plot"]){
				$("#location-plot").addClass("is-invalid");
				valid = false;
			}
			if(!valid){$("#location-error").text("Please connect a hostgroup to a location").fadeIn("slow");}
		}
		else if(locationType == 3){
			o["location"]["type"] = 3;
			o["location"]["hostgroup"] = $("#location-hostgroup").val();
			o["location"]["area"] = $("#location-area").val();
			if(!o["location"]["hostgroup"]){
				$("#location-hostgroup").addClass("is-invalid");
				valid = false;
			}
			if(!o["location"]["area"]){
				$("#location-area").addClass("is-invalid");
				valid = false;
			}
			if(!valid){$("#location-error").text("Please connect a hostgroup to an area").fadeIn("slow");}
		}

		if(valid){
			$("#location-hostgroup").removeClass("is-invalid");
			$("#location-plot").removeClass("is-invalid");
			$("#location-area").removeClass("is-invalid");
			$("#location-error").hide();
			var a = $("#form-add-"+key).serializeArray();
			$.each(a, function(){
	                        if(this.value){
					if (!o[this.name]){o[this.name] = [];}
					o[this.name].push(this.value);
				}
			});
			if(!filterData["filter"][key]){filterData["filter"][key] = [];}
			filterData["filter"][key].push(o);
			console.log(filterData);
			createFilterCards();
		}
	}

	function createFilterCards(){
		$("#filters").html("");
		$.each(filterData["filter"], function(i, o){
			console.log("1: "+i+" - "+o);
			if(i == "hostgroups"){
				$.each(o, function(i2, o2){
					var hostgroups = "";
					var services = "";
					var servicegroups = "";
					var excservices = "";
					var excservicegroups = "";
					var card = "<div class=\"card float-left m-3\" style=\"width: 18rem;\"><div class=\"card-header text-white bg-dark\">Hostgroup</div><div class=\"card-body\">";
					console.log("2: "+i2+" - "+o2);
					console.log(o2);
					console.log(o2["add-hostgroups[]"]);
					$.each(o2["add-hostgroups[]"], function(i3, v){
						if(hostgroups){hostgroups += ", ";}
						hostgroups += v;
					});
					$.each(o2["include-hostgroups-service[]"], function(i3, v){
						if(services){services += ", ";}
						services += v;
					});
					$.each(o2["include-hostgroups-servicegroups[]"], function(i3, v){
						if(servicegroups){servicegroups += ", ";}
						servicegroups += v;
					});
					$.each(o2["exclude-hostgroups-service[]"], function(i3, v){
						if(excservices){excservices += ", ";}
						excservices += v;
					});
					$.each(o2["exclude-hostgroups-servicegroups[]"], function(i3, v){
						if(excservicegroups){excservicegroups += ", ";}
						excservicegroups += v;
					});
					if(hostgroups){card += "<p class=\"card-text\">"+hostgroups+"</p>";}
					if(services){card += "<h6 class=\"card-subtitle mb-2 text-muted\">Services</h6><p class=\"card-text\">"+services+"</p>";}
					if(servicegroups){card += "<h6 class=\"card-subtitle mb-2 text-muted\">Servicegroups</h6><p class=\"card-text\">"+servicegroups+"</p>";}
					if(excservices){card += "<h6 class=\"card-subtitle mb-2 text-muted\">Exclude services</h6><p class=\"card-text\">"+excservices+"</p>";}
					if(excservicegroups){card += "<h6 class=\"card-subtitle mb-2 text-muted\">Exclude servicegroups</h6><p class=\"card-text\">"+excservicegroups+"</p>";}
					if(o2["location"]["type"] == 1){card += "<h6 class=\"card-subtitle mb-2 text-muted\">Location</h6><p class=\"card-text\">Auto</p>";}
					else if(o2["location"]["type"] == 2){card += "<h6 class=\"card-subtitle mb-2 text-muted\">Location</h6><p class=\"card-text\">"+o2["location"]["hostgroup"]+"<br>"+locationNames[o2["location"]["plot"]]+"</p>";}
					card += "<button type=\"button\" id=\"btn-edit-filter\" class=\"btn btn-primary ml-1\" data-index=\""+i+"\" data-type=\"hostgroups\">Edit</button><button type=\"button\" id=\"btn-delete-filter\" class=\"btn btn-danger ml-1\" data-index=\""+i+"\" data-type=\"hostgroups\">Delete</button></div></div>";
					$("#filters").append(card);
				});
			}
		});
		$("#filter-buttons").fadeIn("slow");
	}

	$('#btn-add-hostgroups').click(function(){
		createFilterObject('hostgroups');
	});
	
	$('#btn-add-servicegroups').click(function(){
		createFilterObject('servicegroups');
	});

	$('#btn-add-service').click(function(){
		createFilterObject('service');
	});

	$('#btn-add-host').click(function(){
		createFilterObject('host');
	});

	$('.location-type').click(function(){
		locationType = $(this).data("type");
		if(locationType == 1){
			$("#location-hostgroup").fadeOut("slow");
			$("#location-plot").fadeOut("slow");
			$("#location-area").fadeOut("slow");
			$("#location-type-1").addClass("btn-primary").removeClass("btn-secondary");
			$("#location-type-2").addClass("btn-secondary").removeClass("btn-primary");
			$("#location-type-3").addClass("btn-secondary").removeClass("btn-primary");
		}
		else if(locationType == 2){
			$("#location-area").fadeOut("slow", function(){
				$("#location-hostgroup").fadeIn("slow");
				$("#location-plot").fadeIn("slow");
			});
			$("#location-type-1").addClass("btn-secondary").removeClass("btn-primary");
			$("#location-type-2").addClass("btn-primary").removeClass("btn-secondary");
			$("#location-type-3").addClass("btn-secondary").removeClass("btn-primary");
		}
		else if(locationType == 3){
			$("#location-plot").fadeOut("slow", function(){
				$("#location-hostgroup").fadeIn("slow");
				$("#location-area").fadeIn("slow");
			});
			$("#location-type-1").addClass("btn-secondary").removeClass("btn-primary");
			$("#location-type-2").addClass("btn-secondary").removeClass("btn-primary");
			$("#location-type-3").addClass("btn-primary").removeClass("btn-secondary");
		}
	});
</script>
<?php
	$mysqli->close();
?>
