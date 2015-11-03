module.exports = function (grunt) {
    var pkg = grunt.file.readJSON('package.json');
    pkg.webRoot = 'web/';
    pkg.bowerRoot = 'web/vendor/';
    pkg.banner = '/*! ahrameev <%= grunt.template.today("yyyy-mm-dd") %> */\n';

    var source = {
        js: [
            '<%= pkg.webRoot %>js/_bower.js',
            '<%= pkg.webRoot %>js/src/modules/*.js',
            '<%= pkg.webRoot %>js/src/main.js'
        ],
        css: [
            '<%= pkg.webRoot %>css/_bower.css',
            '<%= pkg.webRoot %>css/styles.css'
        ]
    };

    var config = {
        pkg: pkg,

        composer: {
            options: {
                composerLocation: 'SYMFONY_ENV=build composer'
            },
            i: {}
        }
    };
    grunt.util._.merge(config, require('./grunt/bump')(grunt));
    grunt.util._.merge(config, require('./grunt/bower')(grunt));
    grunt.util._.merge(config, require('./grunt/concat')(grunt, source));
    grunt.util._.merge(config, require('./grunt/clean')(grunt));
    grunt.util._.merge(config, require('./grunt/uglify')(grunt));
    grunt.util._.merge(config, require('./grunt/watch')(grunt, source));
    grunt.util._.merge(config, require('./grunt/compass')(grunt));
    grunt.util._.merge(config, require('./grunt/concurrent')(grunt));
    grunt.util._.merge(config, require('./grunt/autoprefixer')(grunt));
    grunt.util._.merge(config, require('./grunt/csso')(grunt));
    grunt.util._.merge(config, require('./grunt/svg2png')(grunt));
    grunt.util._.merge(config, require('./grunt/compress')(grunt));
    grunt.util._.merge(config, require('./grunt/deploy')(grunt));
    grunt.util._.merge(config, require('./grunt/lint')(grunt));
    grunt.util._.merge(config, require('./grunt/test')(grunt));

    if (grunt.file.exists('./grunt/local.js')) {
        grunt.util._.merge(config, require('./grunt/local')(grunt));
    }

    grunt.initConfig(config);

    require('matchdep')
        .filterDev('grunt-*')
        .forEach(grunt.loadNpmTasks);

    grunt.registerTask('dev', ['concurrent:dev']);

    // Styles: concat css -> autoprefixer -> csso -> remove compass output
    grunt.registerTask('css', ['compass:compile', 'concat:css', 'autoprefixer:all', 'csso:all']);

    // Scripts: concat js -> uglify
    grunt.registerTask('js', ['bower:install', 'bower_concat', 'concat:js', 'uglify:js']);

    grunt.registerTask('compile', ['clean:css', 'clean:js', 'clean:fonts', 'bower:install', 'bower_concat', 'svg', 'css', 'js']);
    grunt.registerTask('assemble', ['composer:i:install:no-interaction:prefer-dist:optimize-autoloader:no-dev', 'compile', 'compress']);
    grunt.registerTask('verify', ['composer:i:install:no-interaction', 'lint:jenkins', 'test']);
    grunt.registerTask('build', ['clean:build', 'verify', 'assemble']);
    grunt.registerTask('svg', [/*'svgmin', */'svg2png']);

    grunt.registerTask('default', ['compile']);
};
