/* global Vue */

/* exported stepEditorStepEventActionMutations */
var stepEditorStepEventActionMutations = {

  /**
   * Add a step event action
   *
   * @param state
   * @param payload
   */
  addStepEventAction: function (state, payload) {
    var stepOptions = state.map.steps[payload.stepName].options;
    var actionName = payload.action.name;
    var formEventName = payload.formEvent.name;

    if ('undefined' === typeof stepOptions.events) {
      Vue.set(stepOptions, 'events', {});
    }

    if ('undefined' === typeof stepOptions.events[formEventName]) {
      Vue.set(stepOptions.events, formEventName, []);
    }

    state.map.steps[payload.stepName].options.events[formEventName].push({
      action: actionName
    });
  },

  /**
   * Remove a step event action
   *
   * @param state
   * @param payload
   */
  removeStepEventAction: function (state, payload) {
    var stepOptions = state.map.steps[payload.stepName].options;
    var stepEvents = stepOptions.events;
    var stepEventFormArray = stepEvents[payload.formEventName];

    stepEventFormArray.splice(payload.actionIndex, 1);

    // Remove empty 'form event name' array
    if (0 === stepEventFormArray.length) {
      Vue.delete(stepEvents, payload.formEventName);
    }

    // Remove empty 'events' object
    if (0 === Object.keys(stepEvents).length) {
      Vue.delete(stepOptions, 'events');
    }

  },

  /**
   * Update or delete a step event action name
   *
   * @param state
   * @param payload
   */
  updateStepEventActionName: function (state, payload) {
    var action = state
      .map
      .steps[payload.stepName]
      .options
      .events[payload.formEventName][payload.actionIndex];

    if (0 === payload.actionName.length) {
      Vue.delete(action, 'name');
    } else {
      Vue.set(action, 'name', payload.actionName);
    }

  },

  /**
   * Update or delete a step event action option
   *
   * @param state
   * @param payload
   */
  updateStepEventActionOption: function (state, payload) {
    var action = state
      .map
      .steps[payload.stepName]
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
