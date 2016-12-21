/* jshint node:true */
module.exports = function( grunt ) {

	// Load tasks.
	require('matchdep').filterDev(['grunt-*']).forEach( grunt.loadNpmTasks );

	//All configuration goes here
	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),

		uglify: {
			slideshow_frontend:{
				src: 'interface/js/bu-slideshow.js',
				dest: 'interface/js/bu-slideshow.min.js'
			},
			slideshow_admin:{
				src: 'interface/js/bu-slideshow-admin.js',
				dest: 'interface/js/bu-slideshow-admin.min.js'
			},
			slideshow_selector:{
				src: 'interface/js/bu-slideshow-selector.js',
				dest: 'interface/js/bu-slideshow-selector.min.js'
			}
		},

		sass: {
			dev: {
				options: {
					style: 'expanded',
					loadPath: 'css-dev',
				},
				files: {
					'interface/css/bu-slideshow-admin.css': 'interface/css-dev/bu-slideshow-admin.scss',
					'interface/css/bu-slideshow-selector.css': 'interface/css-dev/bu-slideshow-selector.scss',
					'interface/css/bu-slideshow.css': 'interface/css-dev/bu-slideshow.scss',
				}
			},
			prod: {
				options: {
					style: 'compressed',
					loadPath: 'css-dev',
				},
				files: {
					'interface/css/bu-slideshow-admin.min.css': 'interface/css-dev/bu-slideshow-admin.scss',
					'interface/css/bu-slideshow-selector.min.css': 'interface/css-dev/bu-slideshow-selector.scss',
					'interface/css/bu-slideshow.min.css': 'interface/css-dev/bu-slideshow.scss',
				}
			},
		},

		phplint: {
			options : {
				phpArgs : {
					'-lf': null
				}
			},
			all : {
				src : '**/*.php'
			}
		},

		watch: {
			scripts: {
				files: [
					'interface/js/*.js',
					'!interface/js/*.min.js'
				],
				tasks: ['uglify'],
				options: {  spawn: false, },
			},
			sass: {
				files: [
					'interface/css-dev/*.scss',
				],
				tasks: ['styles'],
				options: {  spawn: false, },
			},
			phplint : {
				files : [ '**/*.php' ],
				tasks : [ 'phplint' ],
				options : {
					spawn : false
				}
			}
		}
	});

	// Build task.
	grunt.registerTask( 'scripts', [ 'uglify' ] );
	grunt.registerTask( 'styles', [ 'sass' ] );
	grunt.registerTask( 'build', [ 'styles', 'scripts', 'phplint' ] );

	// Default task.
	grunt.registerTask( 'default', [ 'watch' ] );

};
