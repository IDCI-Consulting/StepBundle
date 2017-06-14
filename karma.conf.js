// Karma configuration
// Generated on Fri May 05 2017 08:10:00 GMT+0000 (UTC)

module.exports = function(config) {

  var extraFormPath = '../../../vendor/idci/extra-form-bundle/IDCI/Bundle/ExtraFormBundle/Resources/public/js/';

  config.set({

    // base path that will be used to resolve all patterns (eg. files, exclude)
    basePath: 'Resources/public/js/',

    browserConsoleLogOptions: {
      level: 'log',
      format: '%b %T: %m',
      terminal: true
    },

    // frameworks to use
    // available frameworks: https://npmjs.org/browse/keyword/karma-adapter
    frameworks: ['jasmine'],

    // list of files / patterns to load in the browser
    files: [
      // extra form bundle dependencies
      extraFormPath + '/vendor/jquery-2.2.4.min.js',
      extraFormPath + 'vendor/codemirror.js',
      extraFormPath + 'vendor/codemirror-javascript-mode.js',
      extraFormPath + 'vendor/vue-prod.min.js',
      extraFormPath + 'vendor/vuex-2.1.2.js',
      extraFormPath + 'vendor/sortable.min.js',
      extraFormPath + 'vendor/vue-multiselect.min.js',
      extraFormPath + 'vendor/vue-draggable.min.js',
      extraFormPath + 'vendor/vue-resource-1.2.0.min.js',
      extraFormPath + 'editor/utils/utils.js',
      extraFormPath + 'editor/utils/JsonToTwigTransformer.js',
      extraFormPath + 'editor/mixins/waitForIt.vue.js',
      extraFormPath + 'editor/mixins/option.vue.js',
      extraFormPath + 'editor/mixins/jsonOption.vue.js',
      extraFormPath + 'editor/mixins/http.vue.js',
      extraFormPath + 'editor/mixins/rawModal.vue.js',
      extraFormPath + 'editor/mixins/raw.vue.js',
      extraFormPath + 'editor/mixins/icon.vue.js',
      extraFormPath + 'editor/store/getters.vue.js',
      extraFormPath + 'editor/store/actions.vue.js',
      extraFormPath + 'editor/store/mutations.vue.js',
      extraFormPath + 'editor/components/common/modal.vue.js',
      extraFormPath + 'editor/components/common/options/textarea.vue.js',
      extraFormPath + 'editor/components/common/options/choice.vue.js',
      extraFormPath + 'editor/components/common/options/text.vue.js',
      extraFormPath + 'editor/components/common/options/number.vue.js',
      extraFormPath + 'editor/components/common/options/integer.vue.js',
      extraFormPath + 'editor/components/common/options/checkbox.vue.js',
      extraFormPath + 'editor/components/editor-simple/types-selectbox.vue.js',
      extraFormPath + 'editor/components/editor-simple/new-field.vue.js',
      extraFormPath + 'editor/components/editor-simple/new-field-constraint.vue.js',
      extraFormPath + 'editor/components/editor-simple/field-options.vue.js',
      extraFormPath + 'editor/components/editor-simple/field-constraint-options.vue.js',
      extraFormPath + 'editor/components/editor-simple/field-constraints.vue.js',
      extraFormPath + 'editor/components/editor-simple/field.vue.js',
      extraFormPath + 'editor/components/editor-advanced/new-field-constraint.vue.js',
      extraFormPath + 'editor/components/editor-advanced/field-constraint-options.vue.js',
      extraFormPath + 'editor/components/editor-advanced/field-constraints.vue.js',
      extraFormPath + 'editor/components/editor-advanced/field-options.vue.js',
      extraFormPath + 'editor/components/editor-advanced/field.vue.js',
      extraFormPath + 'editor/components/editor-advanced/extra-form-fields.vue.js',
      extraFormPath + 'editor/components/editor-advanced/extra-form-fields-configuration.vue.js',
      extraFormPath + 'editor/components/editor-advanced/configured-extra-form-type.vue.js',
      extraFormPath + 'editor/components/editor-advanced/base-extra-form-type.vue.js',
      extraFormPath + 'editor/components/editor-advanced/extra-form-types.vue.js',
      extraFormPath + 'editor/components/editor-simple/editor.vue.js',
      extraFormPath + 'editor/components/editor-advanced/editor.vue.js',
      extraFormPath + 'editor/components/editor-raw.vue.js',
      extraFormPath + 'editor/app.js',
      'vendor/polyfills.js',
      'vendor/lodash-3.10.1.min.js',
      'vendor/backbone-1.3.3.js',
      'vendor/joint-1.1.0.js',
      'editor/diagram/DiagramColors.js',
      'editor/diagram/factory/DiagramStepFactory.js',
      'editor/diagram/factory/DiagramPathFactory.js',
      'editor/diagram/shapes/EndOfPathLink.js',
      'editor/diagram/shapes/IntersectionOfPathLinks.js',
      'editor/diagram/shapes/PathLink.js',
      'editor/diagram/shapes/Step.js',
      'editor/diagram/DiagramUtils.js',
      'editor/diagram/Diagram.js',
      'editor/utils/MapUtils.js',
      'editor/mixins/path-type-options.vue.js',
      'editor/components/step-editor/options/conditional-destinations-collection.vue.js',
      'editor/components/step-editor/options/form-builder.vue.js',
      'editor/store/getters.vue.js',
      'editor/store/mutations/mutations.vue.js',
      'editor/store/mutations/pathEventActionMutations.vue.js',
      'editor/store/mutations/stepEventActionMutations.vue.js',
      'editor/store/actions.vue.js',
      'editor/components/step-editor-raw.vue.js',
      'editor/components/step-editor/diagram.vue.js',
      'editor/components/step-editor/map/map-configuration-button.vue.js',
      'editor/components/step-editor/map/map-options.vue.js',
      'editor/components/step-editor/map/map-configuration.vue.js',
      'editor/components/step-editor/step-event-actions/new-step-event-action.vue.js',
      'editor/components/step-editor/step-event-actions/step-event-action-configuration.vue.js',
      'editor/components/step-editor/step-event-actions/step-event-actions.vue.js',
      'editor/components/step-editor/path-event-actions/new-path-event-action.vue.js',
      'editor/components/step-editor/path-event-actions/path-event-action-configuration.vue.js',
      'editor/components/step-editor/path-event-actions/path-event-actions.vue.js',
      'editor/components/step-editor/step-types/new-step-type.vue.js',
      'editor/components/step-editor/step-types/step-type-options.vue.js',
      'editor/components/step-editor/step-types/step-types-configuration.vue.js',
      'editor/components/step-editor/path-types/path-type-required-options.vue.js',
      'editor/components/step-editor/path-types/new-path-type.vue.js',
      'editor/components/step-editor/path-types/path-type-options.vue.js',
      'editor/components/step-editor/path-types/path-types-configuration.vue.js',
      'editor/components/step-editor/step-editor.vue.js',
      'editor/app.js',
      'editor/load-extra-step-editors.js',

      'tests/**/*.js'
    ],

    // list of files to exclude
    exclude: [
    ],

    // preprocess matching files before serving them to the browser
    // available preprocessors: https://npmjs.org/browse/keyword/karma-preprocessor
    preprocessors: {
    },

    // test results reporter to use
    // possible values: 'dots', 'progress'
    // available reporters: https://npmjs.org/browse/keyword/karma-reporter
    reporters: ['progress'],

    // web server port
    port: 9876,

    // enable / disable colors in the output (reporters and logs)
    colors: true,

    // level of logging
    // possible values: config.LOG_DISABLE || config.LOG_ERROR || config.LOG_WARN || config.LOG_INFO || config.LOG_DEBUG
    logLevel: config.LOG_INFO,

    // enable / disable watching file and executing tests whenever any file changes
    autoWatch: true,

    // start these browsers
    // available browser launchers: https://npmjs.org/browse/keyword/karma-launcher
    browsers: ['Chromium', 'Firefox'],

    // Continuous Integration mode
    // if true, Karma captures browsers, runs the tests and exits
    singleRun: false,

    // Concurrency level
    // how many browser should be started simultaneous
    concurrency: Infinity
  })
};
