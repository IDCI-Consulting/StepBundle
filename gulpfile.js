'use strict';

var src = {};

src.scripts = [
  'Resources/public/js/vendor/polyfills.js',
  'Resources/public/js/vendor/lodash-3.10.1.min.js',
  'Resources/public/js/vendor/backbone-1.3.3.js',
  'Resources/public/js/vendor/joint-1.1.0.js',
  'Resources/public/js/editor/utils/MapUtils.js',
  'Resources/public/js/editor/diagram/DiagramColors.js',
  'Resources/public/js/editor/diagram/factory/DiagramStepFactory.js',
  'Resources/public/js/editor/diagram/factory/DiagramPathFactory.js',
  'Resources/public/js/editor/diagram/shapes/EndOfPathLink.js',
  'Resources/public/js/editor/diagram/shapes/IntersectionOfPathLinks.js',
  'Resources/public/js/editor/diagram/shapes/PathLink.js',
  'Resources/public/js/editor/diagram/shapes/Step.js',
  'Resources/public/js/editor/diagram/DiagramUtils.js',
  'Resources/public/js/editor/diagram/Diagram.js',
  'Resources/public/js/editor/mixins/path-type-options.vue.js',
  'Resources/public/js/editor/components/step-editor/options/conditional-destinations-collection.vue.js',
  'Resources/public/js/editor/components/step-editor/options/form-builder.vue.js',
  'Resources/public/js/editor/store/getters.vue.js',
  'Resources/public/js/editor/store/mutations/mutations.vue.js',
  'Resources/public/js/editor/store/mutations/pathEventActionMutations.vue.js',
  'Resources/public/js/editor/store/mutations/stepEventActionMutations.vue.js',
  'Resources/public/js/editor/store/actions.vue.js',
  'Resources/public/js/editor/components/step-editor-raw.vue.js',
  'Resources/public/js/editor/components/step-editor/diagram.vue.js',
  'Resources/public/js/editor/components/step-editor/map/map-configuration-button.vue.js',
  'Resources/public/js/editor/components/step-editor/map/map-options.vue.js',
  'Resources/public/js/editor/components/step-editor/map/map-configuration.vue.js',
  'Resources/public/js/editor/components/step-editor/step-event-actions/new-step-event-action.vue.js',
  'Resources/public/js/editor/components/step-editor/step-event-actions/step-event-action-configuration.vue.js',
  'Resources/public/js/editor/components/step-editor/step-event-actions/step-event-actions.vue.js',
  'Resources/public/js/editor/components/step-editor/path-event-actions/new-path-event-action.vue.js',
  'Resources/public/js/editor/components/step-editor/path-event-actions/path-event-action-configuration.vue.js',
  'Resources/public/js/editor/components/step-editor/path-event-actions/path-event-actions.vue.js',
  'Resources/public/js/editor/components/step-editor/step-types/new-step-type.vue.js',
  'Resources/public/js/editor/components/step-editor/step-types/step-type-options.vue.js',
  'Resources/public/js/editor/components/step-editor/step-types/step-types-configuration.vue.js',
  'Resources/public/js/editor/components/step-editor/path-types/path-type-required-options.vue.js',
  'Resources/public/js/editor/components/step-editor/path-types/new-path-type.vue.js',
  'Resources/public/js/editor/components/step-editor/path-types/path-type-options.vue.js',
  'Resources/public/js/editor/components/step-editor/path-types/path-types-configuration.vue.js',
  'Resources/public/js/editor/components/step-editor/step-editor.vue.js',
  'Resources/public/js/editor/app.js',
  'Resources/public/js/editor/load-extra-step-editors.js'
];

var dist = {};

dist.styles = 'Resources/public/css';
dist.scripts = 'Resources/public/js';

var chmod          = require('gulp-chmod'),
  concat           = require('gulp-concat'),
  gulp             = require('gulp'),
  uglify           = require('gulp-uglify')
;

// Task to compile Sass files
gulp.task('scripts', function() {
  gulp.src(src.scripts)
    .pipe(uglify())
    .pipe(concat({ path: 'editor.min.js'}))
    .pipe(chmod(775))
    .pipe(gulp.dest(dist.scripts))
  ;
});
