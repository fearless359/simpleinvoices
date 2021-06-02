var request = require( "request" ),
	querystring = require( "querystring" ).stringify,
	url = require( "url" ),
	util = require( "util" ),
	events = require( "events" );

function extend( a, b ) {
	for ( var key in b ) {
		a[key] = b[key];
	}
	return a;
}

/**
 * Utility for polling the API.
 * @param {Object} params
 * - {number} interval
 * - {number} timeout
 * - {Function} makeRequest
 * - {Function} doContinue
 * - {Function} onSuccess [optional] Called when continue returns false.
 * - {Function} onTimeout [optional] Called if continue returns true after timeout is reached.
 */
function pollJSON( params ) {
	var tsStart = Date.now();

	params = extend({
		onSuccess: function() {},
		onTimeout: function() {}
	}, params );

	function handleRequest( err, resp, body ) {
		var data;
		if ( err ) {
			throw err;
		}
		try {
			data = JSON.parse( body );
		} catch ( e ) {
			throw e;
		}
		if ( params.doContinue( data ) ) {
			if ( tsStart + params.timeout < Date.now() ) {
				params.onTimeout( data );
			} else {
				setTimeout( function() {
					params.makeRequest( handleRequest );
				}, params.interval );
			}
		} else {
			params.onSuccess( data );
		}
	}

	params.makeRequest( handleRequest );
}

function isJobDone( job ) {
	return !job.runs.filter(function( run ) {
		return Object.keys(run.uaRuns).filter(function( uaRun ) {
			return (/new|progress/).test( run.uaRuns[ uaRun ].runStatus );
		}).length;
	}).length;
}

/**
 * @param {TestSwarm} testswarm
 * @param {Object} options From TestSwarm..addjob.
 * @param {Object} jobInfo
 * @param {Function( {mixed} err, {Object} job )} callback
 */
function pollJobStatus( testswarm, options, jobInfo, callback ) {
	var config = testswarm.config,
		i = 1;
	pollJSON({
		interval: options.pollInterval,
		timeout: options.timeout,
		makeRequest: function( handleRequest ) {
			request.get( config.url + "/api.php?" + querystring({
				action: "job",
				item: jobInfo.id
			}), handleRequest);
		},
		doContinue: function( data ) {
			if ( !data.job ) {
				throw new Error( "Invalid API response, can't continue. Response was: " + data );
			}
			testswarm.emit( "addjob-poll", ++i );
			return !isJobDone( data.job );
		},
		onTimeout: function( data ) {
			callback( "Timed out after " + options.timeout + "ms", data.job );
		},
		onSuccess: function( data ) {
			callback( null, data.job );
		}
	});
}

/**
 * Get url to root of testswarm server (without the path to the installation, if any).
 *
 * @param {TestSwarm} testswarm
 * @return {string} Domain root of url.
 */
function getServerRoot( testswarm ) {
	var obj = url.parse( testswarm.config.url );
	obj.href = obj.pathname = obj.search = obj.path = obj.query = obj.hash = undefined;
	// Normalise url to no trailing slash
	return url.format( obj ).replace( /\/$/, "" );
}

/**
 * Add authentication parameters to request data.
 *
 * @param {TestSwarm} testswarm
 * @param {Object} data
 * @return {Object} data
 */
function authenticateRequest( testswarm, data ) {
	if ( !testswarm.auth ) {
		throw new Error( "Authentication required, call .auth() first." );
	}
	data.authID = testswarm.auth.id;
	data.authToken = testswarm.auth.token;
	return data;
}

function keysMissingFrom( obj, keys ) {
	return keys.filter(function( val ) {
		if ( obj[ val ] === undefined ) {
			return true;
		}
		return false;
	});
}

/**
 * @constructor
 * @extends EventEmitter
 * @param {Object} config
 * - {string} url Url to root of TestSwarm install.
 * @emits {string} log Regular output regarding progress of performed actions.
 * @emits {string} verbose Verbose output for debugging.
 */
function TestSwarm( config ) {
	var missing = keysMissingFrom( config, [ "url" ] );
	if ( missing.length ) {
		throw new Error( "Invalid configuration, missing key '" + missing[0] + "'." );
	}

	// Normalise url to no trailing slash
	config.url = url.format( url.parse( config.url ) ).replace( /\/$/, "" );
	this.config = config;
}

util.inherits( TestSwarm, events.EventEmitter );

TestSwarm.prototype.addReporter = function( reporter ) {
	reporter( this );
	return this;
};

/**
 * @param {Object} auth
 * - {string} id Username of TestSwarm account.
 * - {string} token Authentication token of account.
 */
TestSwarm.prototype.auth = function( auth ) {
	var missing = keysMissingFrom( auth, [ "id", "token" ] );
	if ( missing.length ) {
		throw new Error( "Invalid authentication, missing key '" + missing[0] + "'." );
	}

	this.auth = auth;
	return this;
};

/**
 * @method
 * @param {Object} options
 * - {string} name
 * - {number} runMax [optional]
 * - {Object} runs Run urls by run name.
 * - {Array|string} browserSets
 * - {number} pollInterval [optional] In milliseconds.
 * - {number} timeout [optional] In milliseconds.
 * @param {Function( {mixed} err, {boolean} passed, {Object} results )} callback
 * @emits addjob-created ( {Object} jobInfo )
 * @emits addjob-poll ( {number}  iteration ) Starts with 1.
 * @emits addjob-ready ( {boolean} passsed, {Object} results )
 */
TestSwarm.prototype.addjob = function( options, callback ) {
	var testswarm, missing, runNames, runUrls;

	testswarm = this;
	missing = keysMissingFrom( options, [ "name", "runs", "browserSets" ] );
	if ( missing.length ) {
		callback( "Invalid options, missing key '" + missing[0] + "'." );
		return;
	}

	options = extend({
		runMax: 2,
		pollInterval: 5000, // 5 seconds
		timeout: 15 * 60 * 1000 // 15 minutes
	}, options);

	runNames = Object.keys( options.runs );
	runUrls = runNames.slice().map(function( name ) {
		return options.runs[name];
	});

	request.post({
		url: testswarm.config.url + "/api.php",
		form: authenticateRequest( testswarm, {
			action: "addjob",
			jobName: options.name,
			runMax: options.runMax,
			"runNames[]": runNames,
			"runUrls[]": runUrls,
			"browserSets[]": options.browserSets
		})
	}, function( err, resp, body ) {
		var data, jobInfo;
		if ( err ) {
			callback( err, false );
			return;
		}
		try {
			data = JSON.parse( body );
		} catch ( e ) {
			callback( "Failed parsing body as json, was: " + body, false );
		}
		if ( !data.addjob ) {
			callback( "API returned error, can't continue. Response was: " + body, false );
			return;
		}
		jobInfo = data.addjob;
		testswarm.emit( "log", "Submitted job " + jobInfo.id + " " + testswarm.config.url + "/job/" + jobInfo.id );
		testswarm.emit( "log", "Polling for results" );
		testswarm.emit( "verbose", JSON.stringify( jobInfo ) );
		testswarm.emit( "addjob-created", jobInfo );
		pollJobStatus( testswarm, options, jobInfo, function( err, job ) {
			var passed = true, results = {};

			function runStats( uaID, uaRun ) {
				var val = uaRun.runStatus;
				if ( uaRun.runResultsUrl ) {
					val +=  " (" + uaRun.runResultsLabel + "). " + getServerRoot( testswarm ) + uaRun.runResultsUrl;
				}
				return val;
			}

			job.runs.filter(function( run ) {
				results[run.info.name] = {};
				for ( var uaID in run.uaRuns ) {
					results[run.info.name][uaID] = runStats( uaID, run.uaRuns[uaID] );
					if ( passed && run.uaRuns[uaID].runStatus !== "passed" ) {
						// "new", "failed", "error", "timedout", ..
						passed = false;
					}
				}
			});

			testswarm.emit( "addjob-ready", err, passed, results );
			callback( err, passed, results );
		} );
	});
};

module.exports = {
	createClient: function( config ) {
		return new TestSwarm( config );
	},
	reporters: require( "./reporters" )
};
