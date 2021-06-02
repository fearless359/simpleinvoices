var gitAuthors = require( "../" );

module.exports = function( grunt ) {

grunt.registerTask( "authors",
	"Generate a list of authors in order of first contribution",
function( dir ) {
	var done = this.async();

	gitAuthors.getAuthors({
		dir: dir || ".",
		order: grunt.config( "authors.order" ),
		priorAuthors: grunt.config( "authors.prior" )
	}, function( error, authors ) {
		if ( error ) {
			grunt.log.error( error );
			return done( false );
		}

		grunt.log.writeln( authors.join( "\n" ) );
		done();
	});
});

grunt.registerTask( "update-authors",
	"Updates an authors file with the current list of authors",
function( dir ) {
	var done = this.async();

	gitAuthors.updateAuthors({
		dir: dir || ".",
		order: grunt.config( "authors.order" ),
		priorAuthors: grunt.config( "authors.prior" )
	}, function( error, filename ) {
		if ( error ) {
			grunt.log.error( error );
			return done( false );
		}

		grunt.log.writeln( "Updated " + filename + "." );
		done();
	});
});

};
