function loadMap(title,name,ratio,containerx,containery,map,offsetx = 0, offsety = 0, zoom = false){

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
			}
			, defaultPlot: {
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
			}
			, defaultArea: {
				attrs: {
					fill: "#85929E"
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
				}
			}
		}
	});
}
