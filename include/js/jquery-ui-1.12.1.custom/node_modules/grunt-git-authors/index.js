var fs = require( "fs" );
var path = require( "path" );
var spawn = require( "spawnback" );

var banners = {
	count: "Authors ordered by number of contributions",
	date: "Authors ordered by first contribution"
};

var orderBy = {
	count: function( authors ) {
		var count = {};
		return authors
			.filter( unique( count ) )
			.sort(function( a, b ) {
				return count[ b ] - count[ a ];
			});
	},

	date: function( authors ) {
		return authors
			.reverse()
			.filter( unique() );
	}
};

exports.getAuthors = getAuthors;
exports.updateAuthors = updateAuthors;

function getAuthors( options, callback ) {
	spawn( "git",
		[ "log", "--pretty=%aN <%aE>", options.dir || "." ],
	function( error, result ) {
		if ( error ) {
			return callback( error );
		}

		var tracked = {};
		options = getOptions( options );

		var authors = result.trimRight().split( "\n" )
			.concat( (options.priorAuthors || []).reverse() );

		authors = orderBy[ options.order ]( authors );

		callback( null, authors );
	});
}

function getOptions( options ) {
	if ( !orderBy.hasOwnProperty( options.order ) ) {
		options.order = "date";
	}
	return options;
}

function unique( count ) {
	count = count || {};
	return function( key ) {
		if ( !(key in count) ) {
			count[ key ] = 0;
		}
		count[ key ]++;
		return count[ key ] === 1;
	};
}

function updateAuthors( options, callback ) {
	getAuthors( options, function( error, authors ) {
		if ( error ) {
			return callback( error );
		}

		options = getOptions( options );

		var banner = options.banner || banners[ options.order ];
		var dir = options.dir || ".";
		var filename = path.join( dir, options.filename || "AUTHORS.txt" );

		fs.writeFile( filename,
			banner + "\n\n" + authors.join( "\n" ) + "\n",
		function( error ) {
			if ( error ) {
				return callback( error );
			}

			callback( null, filename );
		});
	});
}
