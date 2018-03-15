var loadingTimeout;

function loading(o){
	if(o){
		$("#loading").fadeToggle("slow");
		loadingTimeout = setTimeout(function(){loading(1);},1000);
	}
	else{
		$("#loading").fadeOut("slow");
		clearTimeout(loadingTimeout);
	}
}

$("#btn-create").click(function(){
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
