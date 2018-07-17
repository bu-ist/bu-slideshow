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

		less: {
			slideshow:{
				src: 'interface/css/bu-slideshow.less',
				dest: 'interface/css/bu-slideshow.css',
				// options:{   compress: true  }
			}
		},

		cssmin: {
			core: {
				expand: true,
				cwd: 'interface/css',
				src: [ '*.css', '!*.min.css' ],
				dest: 'interface/css',
				ext: '.min.css'
			}
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
			less: {
				files: [
					'interface/css/bu-slideshow.less',
				],
				tasks: ['styles'],
				options: {  spawn: false, },
			},
			styles: {
				files: [
					'interface/css/*.css',
					'!interface/css/*.min.css',
					'!interface/css/bu-slideshow.css'
				],
				tasks: ['cssmin'],
				options: {  spawn: false, },
			},
			phplint : {
				files : [ '**/*.php' ],
				tasks : [ 'phplint' ],
				options : {
					spawn : false
				}
			}
		},

		wp_readme_to_markdown: {
			options: {
        		screenshot_url: 'http://ps.w.org/{plugin}/assets/{screenshot}.png'
        	},
			your_target: {
				files: {
					'README.md': 'readme.txt'
				}
			},
		},
	});

	// Build task.
	grunt.registerTask( 'scripts', [ 'uglify' ] );
	grunt.registerTask( 'styles', [ 'less', 'cssmin' ] );
	grunt.registerTask( 'build', [ 'styles', 'scripts', 'phplint' ] );

	// readme
	grunt.loadNpmTasks( 'grunt-wp-readme-to-markdown' );
	grunt.registerTask( 'readme', ['wp_readme_to_markdown'] );

	// Default task.
	grunt.registerTask( 'default', [ 'build' ] );

};
