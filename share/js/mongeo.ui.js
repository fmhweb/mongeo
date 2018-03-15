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
