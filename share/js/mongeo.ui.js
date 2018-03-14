$("#btn-activate-maps").click(function(){
	$.post( "ajax/activate.php", function( data ) {
		console.log(data);
		$( "#main-container" ).html( data );
	});
});

$("#btn-activate-maps").click(function(){
	$.post( "ajax/activate.php", function( data ) {
		console.log(data);
		$( "#main-container" ).html( data );
	});
});
