var app_path = "interface/";

module.exports = function(grunt) {

	//All configuration goes here 
	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),

		concat: {   
			options: {
				// the banner is inserted at the top of the output
				banner: '/*! COMPILED BY GRUNT. DO NOT MODIFY. <%= pkg.name %> */\n'
			},
			slideshow_frontend: {
				src: [
					app_path + 'js/bu-slideshow-frontend.js'
				],
				dest: app_path + 'js/bu-slideshow.dev.js',
			},
		},

		uglify: {
			options: {
				// the banner is inserted at the top of the output
				banner: '/*! Compiled by Grunt <%= pkg.name %> */\n'
			},
			slideshow_frontend:{
				src: app_path + 'js/bu-slideshow.dev.js',
				dest: app_path + 'js/bu-slideshow.js'
			},
			slideshow_admin:{
				src: app_path + 'js/bu-slideshow-admin.dev.js',
				dest: app_path + 'js/bu-slideshow-admin.js'
			},
			slideshow_selector:{
				src: app_path + 'js/bu-slideshow-selector.dev.js',
				dest: app_path + 'js/bu-slideshow-selector.js'
			}
		},

		less: {
			slideshow:{
				src: app_path + 'css/bu-slideshow.less',
				dest: app_path + 'css/bu-slideshow.css',
				// options:{   compress: true  }
			}
		},

		watch: {
			scripts: {
				files: [
					app_path + 'js/*.dev.js'
				],
				tasks: ['concat', 'uglify'],
				options: {  spawn: false, },
			} ,
			less: {
				files: [
					app_path + 'css/bu-slideshow.less'
				],
				tasks: ['less'],
				options: {  spawn: false, },
			} 
		}
	});

	//the packages we plan on using.
	grunt.loadNpmTasks('grunt-contrib-concat');
	grunt.loadNpmTasks('grunt-contrib-uglify');
	grunt.loadNpmTasks('grunt-contrib-less');
	grunt.loadNpmTasks('grunt-contrib-watch');

	//sequence of events to run when grunt is run.
	grunt.registerTask('default', ['concat','uglify',"less"]);

};