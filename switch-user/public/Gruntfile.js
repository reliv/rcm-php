module.exports = function (grunt) {

    var files = [
        'src/switch-user-module.js',
        'src/config.js',
        'src/switch-to-user-directive.js',
        'src/switch-to-user-directive-simple.js',
        'src/switch-to-user-directive-horizontal.js',
        'src/switch-user-service.js',
        'src/switch-user-admin-service.js',
        'src/switch-user-message-inject.js',
        'src/switch-user-message.js',
        'src/switch-user-admin.js',
        'src/switch-user-admin-simple.js',
        'src/switch-user-admin-horizontal.js',
        'src/tooltip-directive.js'
    ];

    grunt.initConfig(
        {
            pkg: grunt.file.readJSON('package.json'),
            concat: {
                options: {},
                switchUser: {
                    files: {
                        'dist/switch-user.js': files
                    }
                },
            },
            inlineTemplate: {
                options: {},
                dist: {
                    src: ['dist/switch-user.js'],
                    dest: 'dist/switch-user.js'
                }
            },
            uglify: {
                switchUser: {
                    options: {
                        banner: '/*! <%= pkg.name %> <%= grunt.template.today("yyyy-mm-dd") %> */\n',
                        mangle: false,
                        sourceMap: true
                    },
                    files: {
                        'dist/switch-user.min.js': ['dist/switch-user.js']
                    }
                },
            },

            watch: {
                src: {
                    files: [
                        'Gruntfile.js',
                        'src/**/*.js',
                        'src/**/*.css',
                        'src/**/*.html'
                    ],
                    tasks: ['concat', 'inlineTemplate', 'uglify']
                }
            }
        }
    );

    grunt.loadNpmTasks('grunt-forever');//@todo remove doesn't work
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-inline-template');
    grunt.registerTask('default', ['concat', 'inlineTemplate', 'uglify']);

};
