module.exports = function (grunt) {

    var files = [
        'src/rcm-html-editor-guid.js',
        'src/rcm-html-editor-event-manager.js',
        'src/rcm-html-editor-service.js',
        'src/angular-rcm-html-editor.js'
    ];

    var adapterFiles = [
        'src/adapter-tinymce/rcm-html-editor-config.js',
        'src/adapter-tinymce/rcm-html-editor-options.js',
        'src/adapter-tinymce/rcm-html-editor.js',
        'src/adapter-tinymce/rcm-html-editor-toolbar.js'
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
                        'dist/<%= pkg.name %>.min.js': files,
                        'dist/adapter-tinymce/<%= pkg.name %>.min.js': adapterFiles
                    }
                }
            },
            cssmin: {
                options: {
                    shorthandCompacting: false,
                    sourceMap: true,
                    roundingPrecision: -1
                },
                target: {
                    files: {
                        'dist/adapter-tinymce/<%= pkg.name %>.min.css': [
                            'src/adapter-tinymce/css/editor.css'
                        ]
                    }
                }
            },
            copy: {
                dist: {
                    expand: true,
                    cwd: 'src/adapter-tinymce/html',
                    src: '**',
                    dest: 'dist/adapter-tinymce/html'
                }
            },
            concat: {
                options: {
                },
                dist: {
                    files: {
                        'dist/<%= pkg.name %>.js': files,
                        'dist/adapter-tinymce/<%= pkg.name %>.js': adapterFiles
                    }
                }
            },
            watch: {
                src: {
                    files: ['src/*.js', 'src/**/*.js'],
                    tasks: ['uglify', 'copy', 'cssmin', 'concat']
                }
            }
        }
    );

    // Load the plugin that provides the "uglify" task.
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-contrib-copy');
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-watch');

    // Default task(s).
    grunt.registerTask('default', ['uglify', 'copy', 'cssmin', 'concat']);
};
