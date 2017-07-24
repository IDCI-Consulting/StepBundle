import Vue from 'vue';
import Vuex from 'vuex';
import Multiselect from 'vue-multiselect';
import VueResource from 'vue-resource';
import stepEditorRawComponent from './components/step-editor-raw.vue';
import stepEditorComponent from './components/step-editor/step-editor.vue';
import stepEditorGetters from './store/getters.js';
import extraFormEditorGetters from 'ExtraFormBundle/store/getters.js';
import stepEditorActions from './store/actions.js';
import extraFormEditorActions from 'ExtraFormBundle/store//actions.js';
import stepEditorMutations from './store/mutations/mutations.js';
import stepEditorPathEventActionMutations from './store/mutations/pathEventActionMutations.js';
import stepEditorStepEventActionMutations from './store/mutations/stepEventActionMutations.js';
import extraFormEditorMutations from 'ExtraFormBundle/store//mutations.js';
import modalComponent from 'ExtraFormBundle/components/common/modal.vue';
import formEditorAdvancedComponent from 'ExtraFormBundle/components/editor-advanced/editor.vue';

Vue.component('modal', modalComponent);
Vue.component('step-editor-raw', stepEditorRawComponent);
Vue.component('step-editor', stepEditorComponent);
Vue.component('multiselect', Multiselect);
Vue.component('form-editor-advanced', formEditorAdvancedComponent);

/**
 * The function that will trigger the editor
 *
 * @param element {string|Object} The dom element to trigger the editor
 * @param configuration : Object containing the editor configuration (api url, etc)
 * @param [formProperties] : Object containing the properties of the default form
 */
function triggerVueStepEditor (element, configuration, formProperties) {

  Vue.use(Vuex);
  Vue.use(VueResource);
  Vue.use(Multiselect);

  /**
   * The common state
   */
  var stepEditorStore = new Vuex.Store({

    state: {
      configuration: configuration,
      formProperties: formProperties,
      mapType: {},
      stepTypes: [],
      pathTypes: [],
      pathEventActionTypes: [],
      stepEventActionTypes: [],
      configuredTypes: [],
      baseTypes: [],
      apiCache: {},
      map: {
        steps: {},
        paths: [],
        graphPositions: {
          steps: {},
          paths: []
        }
      }
    },

    getters: Object.assign(
      stepEditorGetters,
      extraFormEditorGetters
    ),

    mutations: Object.assign(
      stepEditorMutations,
      stepEditorPathEventActionMutations,
      stepEditorStepEventActionMutations,
      extraFormEditorMutations
    ),

    actions: Object.assign(
      stepEditorActions,
      extraFormEditorActions
    )

  });

  /**
   * The step editor app
   */
  new Vue({

    el: element,

    store: stepEditorStore,

    /**
     * Call the APIs before creating the app
     */
    beforeCreate: function () {
      stepEditorStore.dispatch('setStepTypes', this.$http);
      stepEditorStore.dispatch('setPathTypes', this.$http);
      stepEditorStore.dispatch('setPathEventActionTypes', this.$http);
      stepEditorStore.dispatch('setStepEventActionTypes', this.$http);
      stepEditorStore.dispatch('setBaseExtraFormTypes', this.$http);
      stepEditorStore.dispatch('setMapType', this.$http);
      if (this.$store.getters.showConfiguredTypes) {
        stepEditorStore.dispatch('setConfiguredExtraFormTypes', this.$http);
      }
    }

  });

}

export { triggerVueStepEditor }
