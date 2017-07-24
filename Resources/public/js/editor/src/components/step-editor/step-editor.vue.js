import Vue from 'vue';
import stepEditorNewStepType from './step-types/new-step-type.vue';
import stepEditorNewPathType from './path-types/new-path-type.vue';
import stepEditorStepTypesConfiguration from './step-types/step-types-configuration.vue';
import stepEditorPathTypesConfiguration from './path-types/path-types-configuration.vue';
import stepEditorMapConfiguration from './map/map-configuration.vue';
import stepEditorMapConfigurationButton from './map/map-configuration-button.vue';
import stepEditorDiagram from './diagram.vue';

export default {

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
    'new-step-type': stepEditorNewStepType,
    'new-path-type': stepEditorNewPathType,
    'step-types-configuration': stepEditorStepTypesConfiguration,
    'path-types-configuration': stepEditorPathTypesConfiguration,
    'map-configuration': stepEditorMapConfiguration,
    'map-configuration-button': stepEditorMapConfigurationButton,
    'diagram': stepEditorDiagram
  }

};
