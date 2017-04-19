/* exported stepEditorActions */
var stepEditorActions = {

  /**
   * Set an attribute on all extra_form_options of the given types to indicate which component is able to render it
   *
   * @param types []
   * @param [excludedOptions] [] : do not set component names for excluded options.
   */
  setComponentNameOnOptionsTypes: function (types, excludedOptions) {
    for (var i = 0, len = types.length; i < len; i++) {
      var options = types[i].extra_form_options;

      for (var option in options) {
        if (options.hasOwnProperty(option)) {
          if ('undefined' === typeof excludedOptions || -1 === excludedOptions.indexOf(option)) {
            options[option].component_name = 'option-' + options[option].extra_form_type;
          }
        }
      }
    }
  },

  /**
   * Set the map type in the store
   *
   * @param $store
   * @param $http
   */
  setMapType: function ($store, $http) {
    var url = $store.getters.getMapTypeConfigurationApiUrl;

    stepEditorActions.handleGetRequest(url, $store, $http, function (mapType) {
      stepEditorActions.setComponentNameOnOptionsTypes([mapType]);
      $store.commit('setMapType', mapType);
    });
  },


  /**
   * Set the step event action types in the store
   *
   * @param $store
   * @param $http
   */
  setStepEventActionTypes: function ($store, $http) {
    var url = $store.getters.getExtraStepEventActionTypesApiUrl;

    stepEditorActions.handleGetRequest(url, $store, $http, function (actionTypes) {
      stepEditorActions.setComponentNameOnOptionsTypes(actionTypes);
      $store.commit('setStepEventActionTypes', actionTypes);
    });
  },

  /**
   * Set the step types in the store
   *
   * @param $store
   * @param $http
   */
  setStepTypes: function ($store, $http) {
    var url = $store.getters.getExtraStepTypesApiUrl;

    stepEditorActions.handleGetRequest(url, $store, $http, function (stepTypes) {
      stepEditorActions.setComponentNameOnOptionsTypes(stepTypes);

      // Replace builder option by @builder
      for (var i = 0, len = stepTypes.length; i < len; i++) {
        if ('form' === stepTypes[i].name) {
          stepTypes[i].extra_form_options['@builder'] = stepTypes[i].extra_form_options.builder;
          delete stepTypes[i].extra_form_options.builder;
        }
      }

      $store.commit('setStepTypes', stepTypes);
    });
  },

  /**
   * Set the path event action types in the store
   *
   * @param $store
   * @param $http
   */
  setPathEventActionTypes: function ($store, $http) {
    var url = $store.getters.getExtraPathEventActionTypesApiUrl;

    stepEditorActions.handleGetRequest(url, $store, $http, function (actionTypes) {
      stepEditorActions.setComponentNameOnOptionsTypes(actionTypes);
      $store.commit('setPathEventActionTypes', actionTypes);
    });
  },

  /**
   * Set the path types in the store
   *
   * @param $store
   * @param $http
   */
  setPathTypes: function ($store, $http) {
    var url = $store.getters.getExtraPathTypesApiUrl;

    stepEditorActions.handleGetRequest(url, $store, $http, function (pathTypes) {
      var excludedOptions = ['events', 'source', 'destination', 'default_destination'];

      stepEditorActions.setComponentNameOnOptionsTypes(pathTypes, excludedOptions);
      $store.commit('setPathTypes', pathTypes);
    });
  }

};
