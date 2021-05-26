[![Build Status](https://secure.travis-ci.org/jzaefferer/node-testswarm.png)](http://travis-ci.org/jzaefferer/node-testswarm) [![NPM version](https://badge.fury.io/js/testswarm.png)](http://badge.fury.io/js/testswarm)

# node-testswarm

Nodejs module for interacting with TestSwarm

## Getting Started
Install the module with: `npm install testswarm`.

See [addjob documentation](lib/testswarm.js#L179) what options are available.

```javascript
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
		browserSets: ["example"],
	}, function( err, passed ) {
		if ( err ) {
			throw err;
		}
		process.exit( passed ? 0 : 1 );
	}
);
```

For local testing, copy `sample-test.js` to `test.js` and modify to match your local TestSwarm setup.

## License
Copyright (c) 2012 Jörn Zaefferer
Licensed under the MIT license.
