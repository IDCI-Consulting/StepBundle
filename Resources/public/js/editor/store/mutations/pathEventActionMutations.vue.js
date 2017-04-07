/* global Vue */

/* exported stepEditorPathEventActionMutations */
var stepEditorPathEventActionMutations = {

  /**
   * Add a path event action
   *
   * @param state
   * @param payload
   */
  addPathEventAction: function (state, payload) {
    var pathOptions = state.map.paths[payload.pathIndex].options;
    var actionName = payload.action.name;
    var formEventName = payload.formEvent.name;

    if ('undefined' === typeof pathOptions.events) {
      Vue.set(pathOptions, 'events', {});
    }

    if ('undefined' === typeof pathOptions.events[formEventName]) {
      Vue.set(pathOptions.events, formEventName, []);
    }

    state.map.paths[payload.pathIndex].options.events[formEventName].push({
      action: actionName
    });
  },

  /**
   * Remove a path event action
   *
   * @param state
   * @param payload
   */
  removePathEventAction: function (state, payload) {
    var pathOptions = state.map.paths[payload.pathIndex].options;
    var pathEvents = pathOptions.events;
    var pathEventFormArray = pathEvents[payload.formEventName];

    pathEventFormArray.splice(payload.actionIndex, 1);

    // Remove empty 'form event name' array
    if (0 === pathEventFormArray.length) {
      Vue.delete(pathEvents, payload.formEventName);
    }

    // Remove empty 'events' object
    if (0 === Object.keys(pathEvents).length) {
      Vue.delete(pathOptions, 'events');
    }

  },

  /**
   * Update or delete a path event action name
   *
   * @param state
   * @param payload
   */
  updatePathEventActionName: function (state, payload) {
    var action = state
      .map
      .paths[payload.pathIndex]
      .options
      .events[payload.formEventName][payload.actionIndex];

    if (0 === payload.actionName.length) {
      Vue.delete(action, 'name');
    } else {
      Vue.set(action, 'name', payload.actionName);
    }

  },

  /**
   * Update or delete a path event action option
   *
   * @param state
   * @param payload
   */
  updatePathEventActionOption: function (state, payload) {
    var action = state
      .map
      .paths[payload.pathIndex]
      .options
      .events[payload.formEventName][payload.actionIndex];

    if (0 === payload.option.value.length) {
      Vue.delete(action.parameters, payload.option.name);

      // Remove empty 'parameters' object
      if (0 === Object.keys(action.parameters).length) {
        Vue.delete(action, 'parameters');
      }
    } else {
      if ('undefined' === typeof action.parameters) {
        Vue.set(action, 'parameters', {});
      }
      Vue.set(action.parameters, payload.option.name, payload.option.value);
    }
  }

};
