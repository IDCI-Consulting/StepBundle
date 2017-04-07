/* exported stepEditorGetters */
var stepEditorGetters = {

  /**
   * Get the identifier of the editor
   *
   * @param state
   * @returns string
   */
  editorId: function (state) {
    return state.configuration.editorId;
  },

  /**
   * Get the url of the api to retrieve extra path event actions
   *
   * @param state
   * @returns string
   */
  getExtraPathEventActionTypesApiUrl: function (state) {
    return state.configuration.api_url.get_extra_path_event_actions;
  },

  /**
   * Get the url of the api to retrieve extra path types
   *
   * @param state
   * @returns string
   */
  getExtraPathTypesApiUrl: function (state) {
    return state.configuration.api_url.get_extra_path_types;
  },

  /**
   * Get the url of the api to retrieve extra step event action types
   *
   * @param state
   * @returns string
   */
  getExtraStepEventActionTypesApiUrl: function (state) {
    return state.configuration.api_url.get_extra_step_event_actions;
  },

  /**
   * Get the url of the api to retrieve extra step types
   *
   * @param state
   * @returns string
   */
  getExtraStepTypesApiUrl: function (state) {
    return state.configuration.api_url.get_extra_step_types;
  },

  /**
   * Get a resource from the cache
   *
   * @param state
   * @returns string
   */
  getCachedResource: function (state) {
    return function (url) {
      return state.apiCache[url];
    };
  },

  /**
   * Get the list of symfony form events
   *
   * @returns []
   */
  getFormEvents: function () {
    return [
      { name: 'form.pre_set_data' },
      { name: 'form.post_set_data' },
      { name: 'form.pre_bind' },
      { name: 'form.bind' },
      { name: 'form.post_bind' }
    ];
  },

  /**
   * Get the step types
   *
   * @param state
   * @returns []
   */
  getStepTypes: function (state) {
    return state.stepTypes;
  },

  /**
   * Get the step event action types
   *
   * @param state
   * @returns []
   */
  getStepEventActionTypes: function (state) {
    return state.stepEventActionTypes;
  },

  /**
   * Get a step event action type
   *
   * @param state
   * @returns []
   */
  getStepEventActionType: function (state) {
    return function (type) {
      for (var stepEventActionType in state.stepEventActionTypes) {
        if (
          state.stepEventActionTypes.hasOwnProperty(stepEventActionType) &&
          state.stepEventActionTypes[stepEventActionType].name === type
        ) {
          return state.stepEventActionTypes[stepEventActionType];
        }
      }

      return {};
    };
  },


  /**
   * Get the path types
   *
   * @param state
   * @returns []
   */
  getPathTypes: function (state) {
    return state.pathTypes;
  },

  /**
   * Get the required options for a given path type
   *
   * @param state
   * @returns Object
   */
  getPathTypeRequiredOptions: function (state) {
    return function (type) {
      for (var pathType in state.pathTypes) {
        if (state.pathTypes.hasOwnProperty(pathType) && state.pathTypes[pathType].name === type) {
          var options = state.pathTypes[pathType].extra_form_options;

          /* global filterObject */
          return filterObject(options, function (option) {
            return true === option.options.required;
          });
        }
      }

      throw new Error('No required options found for given path type: ' + type);
    };
  },

  /**
   * Get the path event action types
   *
   * @param state
   * @returns []
   */
  getPathEventActionTypes: function (state) {
    return state.pathEventActionTypes;
  },

  /**
   * Get a path event action type
   *
   * @param state
   * @returns []
   */
  getPathEventActionType: function (state) {
    return function (type) {
      for (var pathEventActionType in state.pathEventActionTypes) {
        if (
          state.pathEventActionTypes.hasOwnProperty(pathEventActionType) &&
          state.pathEventActionTypes[pathEventActionType].name === type
        ) {
          return state.pathEventActionTypes[pathEventActionType];
        }
      }

      return {};
    };
  },

  /**
   * Get the map
   *
   * @param state
   * @returns Object
   */
  getMap: function (state) {
    return state.map;
  },

  /**
   * Get the steps of the map
   *
   * @param state
   * @returns Object
   */
  getSteps: function (state) {
    return state.map.steps;
  },

  /**
   * Get the paths of the map
   *
   * @param state
   * @returns []
   */
  getPaths: function (state) {
    return state.map.paths;
  },

  /**
   * Get the options of a given step type
   *
   * @param state
   *
   * @returns {*}
   */
  getStepTypeOptions: function (state) {
    return function (type) {

      for (var stepType in state.stepTypes) {
        if (state.stepTypes.hasOwnProperty(stepType) && state.stepTypes[stepType].name === type) {
          if ('form' === type) {
            /* global sortObject */
            return sortObject(state.stepTypes[stepType].extra_form_options, ['title', '@builder'], false);
          }

          return state.stepTypes[stepType].extra_form_options;
        }
      }

      return {};
    };
  },

  /**
   * Get the options of a given path type
   *
   * @param state
   *
   * @returns {*}
   */
  getPathTypeOptions: function (state) {
    return function (type) {
      for (var pathType in state.pathTypes) {
        if (state.pathTypes.hasOwnProperty(pathType) && state.pathTypes[pathType].name === type) {
          return state.pathTypes[pathType].extra_form_options;
        }
      }

      return {};
    };
  },

  /**
   * Get the options of a step in the map
   *
   * @param state
   * @returns []
   */
  getStepOptions: function (state) {
    return function (name) {
      for (var step in state.map.steps) {
        if (state.map.steps.hasOwnProperty(step) && name === step) {
          return state.map.steps[name].options;
        }
      }

      return null;
    };
  },

  /**
   * Get the options of a path in the map
   *
   * @param state
   * @returns []
   */
  getPathOptions: function (state) {
    return function (index) {
      return state.map.paths[index].options;
    };
  },

  /**
   * Get the event actions of a path in the map
   *
   * @param state
   * @returns []
   */
  getPathEventActions: function (state) {
    return function (index) {
      return state.map.paths[index].options.events;
    };
  },

  /**
   * Get the event actions of a step in the map
   *
   * @param state
   * @returns []
   */
  getStepEventActions: function (state) {
    return function (stepName) {
      return state.map.steps[stepName].options.events;
    };
  },

  /**
   * Get the names of the steps in the map
   *
   * @param state
   * @returns []
   */
  getStepsNames: function (state) {
    var names = [];

    for (var step in state.map.steps) {
      if (state.map.steps.hasOwnProperty(step)) {
        names.push(step);
      }
    }

    return names;
  }

};
