module.exports = function (grunt) {

    var files = [
        'src/rcm.js',
        'src/rcm-api.js',
        'src/rcm-bootstrap-alert-confirm.js',
        'src/rcm-form-double-submit-protect.js',
        'src/rcm-popout-window.js'
    ];

    // Project configuration.
    grunt.initConfig(
        {
            pkg: grunt.file.readJSON('package.json'),
            uglify: {
                dist : {
                    options: {
                        banner: '/*! <%= pkg.name %> <%= grunt.template.today("yyyy-mm-dd") %> */\n',
                        mangle: false,
                        sourceMap: true
                    },
                    files: {
                        'dist/<%= pkg.name %>.min.js': files
                    }
                }
            },
            concat: {
                options: {
                },
                dist: {
                    files: {
                        'dist/<%= pkg.name %>.js': files
                    }
                }
            },
            watch: {
                src: {
                    files: ['src/*.js', 'src/**/*.js'],
                    tasks: ['uglify', 'concat']
                }
            }
        }
    );

    // Load the plugin that provides the "uglify" task.
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-copy');
    grunt.loadNpmTasks('grunt-contrib-watch');

    // Default task(s).
    grunt.registerTask('default', ['uglify', 'concat']);
};
