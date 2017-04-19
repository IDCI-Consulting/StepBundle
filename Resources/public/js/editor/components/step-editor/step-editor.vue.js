/* global Vue */
Vue.component('step-editor', {

  template:
    '<div class="row advanced-mode">' +
      '<div class="extra-step-actions col-xs-12">' +
        '<map-configuration-button></map-configuration-button>' +
        '<new-step-type></new-step-type>' +
        '<new-path-type></new-path-type>' +
      '</div>' +
      '<div class="extra-step-configuration col-md-4">' +
        '<step-types-configuration></step-types-configuration>' +
        '<path-types-configuration></path-types-configuration>' +
        '<map-configuration></map-configuration>' +
      '</div>' +
      '<div class="extra-step-map col-md-8">' +
        '<diagram :map="map"></diagram>' +
      '</div>' +
    '</div>',

  computed: {
    map: function () {
      return this.$store.getters.getMap;
    }
  },

  components: {

    /* global stepEditorNewStepType */
    'new-step-type': stepEditorNewStepType,

    /* global stepEditorNewPathType */
    'new-path-type': stepEditorNewPathType,

    /* global stepEditorStepTypesConfiguration */
    'step-types-configuration': stepEditorStepTypesConfiguration,

    /* global stepEditorPathTypesConfiguration */
    'path-types-configuration': stepEditorPathTypesConfiguration,

    /* global stepEditorMapConfiguration */
    'map-configuration': stepEditorMapConfiguration,

    /* global stepEditorMapConfigurationButton */
    'map-configuration-button': stepEditorMapConfigurationButton,

    /* global stepEditorDiagram */
    'diagram': stepEditorDiagram
  }

});
