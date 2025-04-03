module.exports = function(grunt) {
  grunt.initConfig({
    less: {
      development: {
        options: {
          compress: false,
          yuicompress: false,
          optimization: 2,
          sourceMap: true,
          sourceMapFilename: 'lib/css/main.css.map',
          sourceMapBasepath: 'lib/less',
          sourceMapURL: 'main.css.map'
          //sourceMapRootpath: '../../lib/less'
        },
        files: {
          // target.css file: source.less file
          'lib/css/main.css': 'lib/less/main.less'
        }
      },
      develementor: {
        options: {
          compress: false,
          yuicompress: false,
          optimization: 2,
          sourceMap: true,
          sourceMapFilename: 'lib/css/elementor.css.map',
          sourceMapBasepath: 'lib/less',
          sourceMapURL: 'elementor.css.map'
          //sourceMapRootpath: '../../lib/less'
        },
        files: {
          // target.css file: source.less file
          'lib/css/elementor.css': 'lib/less/elementor.less'
        }
      },
      production: {
        options: {
          compress: true,
          yuicompress: true,
          optimization: 2
        },
        files: {
          // target.css file: source.less file
          'lib/css/main.css': 'lib/less/main.less',
          'lib/css/elementor.css': 'lib/less/elementor.less'
        }
      },
    },
    watch: {
      options: {
        livereload: true,
      },
      styles: {
        files: ['lib/less/**/*.less','js/**/*.js'], // which files to watch
        tasks: ['less:development','less:develementor'],
        options: {
          nospawn: true
        }
      }
    },
    wp_readme_to_markdown: {
      your_target: {
        files: {
          'README.md': 'README.txt'
        },
      },
    },
  });

  grunt.loadNpmTasks('grunt-contrib-less');
  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-wp-readme-to-markdown');
  grunt.registerTask('builddev', ['less:development','less:develementor']);
  grunt.registerTask('build', ['less:production']);
  grunt.registerTask('default', ['watch']);
  grunt.registerTask('readme', ['wp_readme_to_markdown']);
};