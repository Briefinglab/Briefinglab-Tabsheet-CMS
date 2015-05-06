module.exports = function(grunt) {

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        uglify: {
            options: {
                banner: '/*! <%= pkg.name %> (<%= pkg.version %>) - <%= grunt.template.today("yyyy-mm-dd") %> */\n',
                compress: { drop_console: true }
            },
            admin_options: {
                    src: 'admin/js/briefinglab-tabsheet-cms-options.js',
                    dest: 'admin/js/prod/briefinglab-tabsheet-cms-options.<%= pkg.version %>.min.js'
            },
            admin: {
                src: 'admin/js/briefinglab-tabsheet-cms-admin.js',
                dest: 'admin/js/prod/briefinglab-tabsheet-cms-admin.<%= pkg.version %>.min.js'
            },
            public: {
                src: 'public/js/briefinglab-tabsheet-cms-public.js',
                dest: 'public/js/prod/briefinglab-tabsheet-cms-public.<%= pkg.version %>.min.js'
            },
        },
        watch: {
            scripts: {
                files: ['admin/js/*.js','public/js/*.js'],
                tasks: ['uglify'],
            },
                php: {
              files: ['*.php', '**/*.php']
            },
            options: {
                livereload: true,
                spawn: false
            }
        },
    });

    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-uglify');

    grunt.registerTask('default', ['uglify', 'watch']);
};