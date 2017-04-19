/* exported triggerExtraStepVueEditor */

/**
 * The function that will trigger the editor
 *
 * @param element {string|Object} The dom element to trigger the editor
 * @param formProperties : Object containing the properties of the default form
 * @param configuration : Object containing the editor configuration (api url, etc)
 */
function triggerExtraStepVueEditor (element, formProperties, configuration) {

  /* global Vue VueResource VueMultiselect Vuex */
  Vue.use(VueResource);
  Vue.component('Multiselect', VueMultiselect.default);

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

    /* global stepEditorGetters, extraFormEditorGetters */
    getters: Object.assign(
      stepEditorGetters,
      extraFormEditorGetters
    ),

    /* global
        stepEditorMutations,
        stepEditorPathEventActionMutations,
        stepEditorStepEventActionMutations,
        extraFormEditorMutations
    */
    mutations: Object.assign(
      stepEditorMutations,
      stepEditorPathEventActionMutations,
      stepEditorStepEventActionMutations,
      extraFormEditorMutations
    ),

    /* global stepEditorActions, extraFormEditorActions */
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
