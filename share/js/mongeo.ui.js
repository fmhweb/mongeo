var loadingTimeout;
var items;

function loading(o){
	if(o){
		$("#loading").fadeToggle("normal");
		loadingTimeout = setTimeout(function(){loading(1);},1000);
	}
	else{
		$("#loading").fadeOut("normal");
		clearTimeout(loadingTimeout);
	}
}

$("#btn-create").click(function(){
	loading(1);
	$.post( "ajax/create.php", function( data ) {
		$( "#main-container" ).html( data );
	});
});

$("#btn-activate").click(function(){
	$.post( "ajax/activate.php", function( data ) {
		$( "#main-container" ).html( data );
	});
});

$("#btn-locations").click(function(){
	$.post( "ajax/locations.php", function( data ) {
		$( "#main-container" ).html( data );
	});
});
