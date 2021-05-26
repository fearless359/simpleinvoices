var testswarm = require( "./lib/testswarm" ),
	testUrl = "http://localhost/jquery-core/test/",
	runs = {};

["attributes", "callbacks"].forEach(function (suite) {
	runs[suite] = testUrl + "?module=" + suite;
});

testswarm.createClient( {
	url: "http://localhost/testswarm/"
} )
.addReporter( testswarm.reporters.cli )
.auth( {
	id: "example",
	token: "yourauthtoken"
} )
.addjob(
	{
		name: "node-testswarm test job",
		runs: runs,
		browserSets: ["example"]
	}, function( err, passed ) {
		if ( err ) {
			throw err;
		}
		process.exit( passed ? 0 : 1 );
	}
);
