module.exports = {
	cli: function( testswarm ) {
		testswarm.on( "log", console.log.bind( console ) );

		//testswarm.on( "verbose", console.log.bind( console ) );

		testswarm.on( "addjob-poll", function() {
			process.stdout.write( "." );
		} );

		testswarm.on( "addjob-ready", function( err, passed, results ) {
			var name, result, uaID;
			// Line break to return from the dot-writes
			process.stdout.write( "\n" );

			console.log( "State: " + ( err || "Finished" ));
			if ( results ) {
				console.log( "Results: " );
				for ( name in results ) {
					console.log( "\t* " + name );
					result = results[name];
					for ( uaID in result ) {
						console.log( "\t  " + uaID + ": " + result[uaID] );
					}
				}
			}
		} );
	}
};
