module.exports = function(grunt) {
	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),
		sass: {
			options: {
				sourceMap: true,
				outputStyle: 'compressed'
			},
			dist: {
				files: [{
					expand: true,
					cwd: 'app/webroot/scss/',
					src: ['*.scss', '**/*.scss'],
					dest: 'app/webroot/css/',
					ext: '.css'
				}, {
					expand: true,
					cwd: 'app/Plugin/Layout/webroot/scss/',
					src: ['*.scss', '**/*.scss'],
					dest: 'app/Plugin/Layout/webroot/css/',
					ext: '.css'					
				}]
			}
		},
		watch: {
			css: {
				files: [
					'app/webroot/scss/**/*.scss',
					'app/Plugin/**/webroot/scss/**/*.scss'
				],
				tasks: ['sass']
			},
			js: {
				files: [
					'app/Plugin/**/webroot/js/src/**/*.js',
					'app/webroot/js/src/**/*.js'
				],
				tasks: ['uglify']
			}
		}
	});
	//grunt.loadNpmTasks('grunt-contrib-sass');
	grunt.loadNpmTasks('grunt-sass');
	grunt.loadNpmTasks('grunt-contrib-watch');
	grunt.loadNpmTasks('grunt-contrib-uglify');
	grunt.registerTask('default',['watch']);
}