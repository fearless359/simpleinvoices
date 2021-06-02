module.exports = function(grunt) {

	grunt.loadNpmTasks("grunt-contrib-jshint");

	grunt.initConfig({
		pkg: "<json:package.json>",
		jshint: {
			options: grunt.file.readJSON( ".jshintrc" ),
			files: ["Gruntfile.js", "lib/**/*.js", "sample-test.js"]
		}
	});

	grunt.registerTask( "test", ["jshint"] );
	grunt.registerTask( "default", "test" );
};
