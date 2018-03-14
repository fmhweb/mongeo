$("#btn-activate-maps").click(function(){
	$.post( "ajax/activate.php", function( data ) {
		$( "#main-container" ).html( data );
	});
});

$("#btn-locations").click(function(){
	$.post( "ajax/locations.php", function( data ) {
		$( "#main-container" ).html( data );
	});
});
