import Vue from 'vue';

var stepEditorMutations = {

  /**
   * Cache an api response
   *
   * @param state
   * @param payload
   */
  cache: function (state, payload) {
    state.apiCache[payload.api_url] = payload.api_response;
  },

  /**
   * Set the map type
   *
   * @param state
   * @param type
   */
  setMapType: function (state, type) {
    state.mapType = type;
  },

  /**
   * Set the step types
   *
   * @param state
   * @param types
   */
  setStepTypes: function (state, types) {
    state.stepTypes = types;
  },

  /**
   * Set the path types
   *
   * @param state
   * @param types
   */
  setPathTypes: function (state, types) {
    state.pathTypes = types;
  },

  /**
   * Set the path event action types
   *
   * @param state
   * @param actionTypes
   */
  setPathEventActionTypes: function (state, actionTypes) {
    state.pathEventActionTypes = actionTypes;
  },

  /**
   * Set the step event action types
   *
   * @param state
   * @param actionTypes
   */
  setStepEventActionTypes: function (state, actionTypes) {
    state.stepEventActionTypes = actionTypes;
  },

  /**
   * Set the map
   *
   * @param state
   * @param map
   */
  setMap: function (state, map) {

    // Add name if not set
    if ('undefined' === typeof map.options) {
      map.options = {};
    }

    // Add name if not set
    if ('undefined' === typeof map.name) {
      map.name = '';
    }

    // Add steps if not set
    if ('undefined' === typeof map.steps) {
      map.steps = {};
    }

    // Add paths if not set
    if ('undefined' === typeof map.paths) {
      map.paths = [];
    }

    // Add graphPositions if not set
    if ('undefined' === typeof map.graphPositions) {
      map.graphPositions = stepEditorMutations.setInitialGraphPositions(map);
    }

    Vue.set(state, 'map', map);
  },

  /**
   * Add a step to the map
   *
   * @param state
   * @param newStep
   */
  addStep: function (state, newStep) {
    if ('' === newStep.name) {
      throw new Error('The step name must not be blank');
    }
    var steps = state.map.steps;

    for (var step in steps) {
      if (steps.hasOwnProperty(step) && step === newStep.name) {
        throw new Error('A step called ' + step + ' already exists');
      }
    }

    Vue.set(state.map.steps, newStep.name, {
      type: newStep.type,
      options: {}
    });

    stepEditorMutations.setStepPosition(state, {
      identifier: newStep.name,
      position: {
        x: 30,
        y: 30
      }
    });

    stepEditorMutations.setActiveStep(state, newStep.name);
  },

  /**
   * Remove a step from the map
   *
   * @param state
   * @param stepName
   */
  removeStep: function (state, stepName) {

    // If the removed step is active, we need to activate another step after the removal
    var updateActiveStep = state.map.steps[stepName].active;

    Vue.delete(state.map.steps, stepName);
    Vue.delete(state.map.graphPositions.steps, stepName);

    stepEditorMutations.removeAssociatedPaths(state, stepName);

    if (updateActiveStep && 0 !== Object.keys(state.map.steps).length) {
      stepEditorMutations.setActiveStep(state, Object.keys(state.map.steps)[0]);
    }
  },

  /**
   * Loop on paths to find the paths associated with the step, and remove them
   * We go in reverse order to avoid messing up with the indexes
   *
   * @param state
   * @param stepName
   */
  removeAssociatedPaths: function (state, stepName) {
    var paths = state.map.paths;

    for (var i = paths.length - 1; i >= 0; i--) {
      var path = paths[i];

      // Remove the path when the removed step is the source of the path
      // For all types of path
      if (path.options.source === stepName) {
        stepEditorMutations.removePath(state, i);

      // Remove the path when the removed step is the destination of the path
      // For path of type single only
      } else if ('single' === path.type && stepName === path.options.destination) {
        stepEditorMutations.removePath(state, i);

      // Remove the path when the removed step is linked to one of the destinations of the path
      // For path of type conditional_destination only
      } else if ('conditional_destination' === path.type) {

        if (stepName === path.options.default_destination) {
          stepEditorMutations.removePath(state, i);
        } else {

          var destinations = path.options.destinations;

          for (var destinationStepName in destinations) {
            if (destinations.hasOwnProperty(destinationStepName)) {
              if (stepName === destinationStepName) {
                stepEditorMutations.removePath(state, i);
                break;
              }
            }
          }
        }
      }
    }
  },

  /**
   * Add a path to the map
   *
   * @param state
   * @param newPath
   */
  addPath: function (state, newPath) {
    if ('undefined' === typeof(newPath.options.source)) {
      throw new Error('The source must be defined');
    }

    if ('single' === newPath.type) {
      if ('undefined' === typeof(newPath.options.destination)) {
        throw new Error('The destination must be defined');
      }

      if (newPath.options.source === newPath.options.destination) {
        throw new Error('The source and the destination must be different');
      }
    }

    if ('conditional_destination' === newPath.type) {
      if (newPath.options.source === newPath.options.default_destination) {
        throw new Error('The source and the default destination must be different');
      }

      var destinations = newPath.options.destinations;

      if ('undefined' === typeof(destinations) || 0 === destinations.length) {
        throw new Error('You must add at least one conditional destination, else you should use a path of type single');
      }

      for (var stepName in destinations) {
        if (destinations.hasOwnProperty(stepName)) {
          if (stepName === newPath.options.source) {
            throw new Error('The source and the conditional destinations must be different');
          }

          if (stepName === newPath.options.default_destination) {
            throw new Error('The default destination and the conditional destinations must be different');
          }

          if (!destinations[stepName]) {
            throw new Error('The condition must be defined');
          }

          if (!stepName) {
            throw new Error('The step must be defined');
          }
        }
      }
    }

    state.map.graphPositions.paths.push({});

    state.map.paths.push({
      type: newPath.type,
      options: newPath.options
    });

    var pathIndex = state.map.paths.length - 1;

    stepEditorMutations.setActivePath(state, pathIndex);

    if ('end' === newPath.type) {
      stepEditorMutations.setEndPathPosition(state, {
        identifier: pathIndex,
        position: {
          x: 30,
          y: 30
        }
      });
    } else if ('conditional_destination' === newPath.type) {
      stepEditorMutations.setConditionalPathPosition(state, {
        identifier: pathIndex,
        position: {
          x: 100,
          y: 100
        }
      });
    }

    stepEditorMutations.setPathVertices(state, {
      pathIndex: pathIndex
    });
  },

  /**
   * Remove a path from the map
   *
   * @param state
   * @param index
   */
  removePath: function (state, index) {

    // If the removed path is active, we need to activate another step after the removal
    var updateActiveStep = state.map.paths[index].active;

    state.map.paths.splice(index, 1);
    state.map.graphPositions.paths.splice(index, 1);

    if (updateActiveStep && 0 !== Object.keys(state.map.steps).length) {
      stepEditorMutations.setActiveStep(state, Object.keys(state.map.steps)[0]);
    }
  },

  /**
   * Update the value of a map option
   *
   * @param state
   * @param option
   */
  updateMapOption: function (state, option) {
    Vue.set(
      state.map.options,
      option.name,
      option.value
    );
  },

  /**
   * Update the value of a step option
   *
   * @param state
   * @param payload
   */
  updateStepOption: function (state, payload) {
    Vue.set(
      state.map.steps[payload.stepName].options,
      payload.option.name,
      payload.option.value
    );
  },

  /**
   * Update the value of a path option
   *
   * @param state
   * @param payload
   */
  updatePathOption: function (state, payload) {
    Vue.set(
      state.map.paths[payload.pathIndex].options,
      payload.option.name,
      payload.option.value
    );
  },

  /**
   * Reset the active property on all steps, paths or on the map
   *
   * @param state
   */
  resetActive: function (state) {
    var steps = state.map.steps;
    var paths = state.map.paths;

    if (state.map.active) {
      Vue.set(state.map, 'active', false);
    }

    for (var step in steps) {
      if (steps.hasOwnProperty(step)) {
        if (state.map.steps[step].active) {
          Vue.set(state.map.steps[step], 'active', false);
        }
      }
    }

    for (var i = 0, len = paths.length; i < len; i++) {
      if (state.map.paths[i].active) {
        Vue.set(state.map.paths[i], 'active', false);
      }
    }
  },

  /**
   * Set the map as active
   *
   * @param state
   */
  setActiveMap: function (state) {
    stepEditorMutations.resetActive(state);
    Vue.set(state.map, 'active', true);
  },

  /**
   * Set the active step on the map
   *
   * @param state
   * @param stepName
   */
  setActiveStep: function (state, stepName) {
    stepEditorMutations.resetActive(state);
    Vue.set(state.map.steps[stepName], 'active', true);
  },

  /**
   * Set the active step on the map
   *
   * @param state
   * @param pathIndex
   */
  setActivePath: function (state, pathIndex) {
    stepEditorMutations.resetActive(state);
    Vue.set(state.map.paths[pathIndex], 'active', true);
  },

  /**
   * Set the initial graph positions
   *
   * @param map
   */
  setInitialGraphPositions: function (map) {
    var graphPositions = {
      steps: {},
      paths: []
    };

    var firstPosition = true;
    var elementNumber = 0;
    var position = {
      x: -30,
      y: -30
    };

    /**
     * Get the initial position of the cell on the diagram
     *
     * @returns {{x: number, y: number}}
     */
    function getPosition () {
      // Set the first element on the top left of th corner
      if (firstPosition) {
        position = {
          x: position.x + 50,
          y: position.y + 50
        };

      // Set the odd element on the bottom of the two last elements
      } else if (0 === elementNumber % 2) {
        position = {
          x: position.x - 50,
          y: position.y + 100
        };

      // Set the event element at the right of the last one
      } else {
        position = {
          x: position.x + 200,
          y: position.y
        };
      }

      firstPosition = false;
      elementNumber++;

      return position;
    }

    for (var step in map.steps) {
      if (map.steps.hasOwnProperty(step)) {
        graphPositions.steps[step] = getPosition();
      }
    }

    for (var i = 0, len = map.paths.length; i < len; i++) {
      if ('end' === map.paths[i].type) {
        graphPositions.paths[i] = {
          endOfPath: getPosition()
        };
      } else if ('conditional_destination' === map.paths[i].type) {
        graphPositions.paths[i] = {
          intersection: getPosition()
        };

        // If the default_destination is not defined, then we set a path of type end instead
        if ('undefined' === typeof map.paths[i].options.default_destination) {
          graphPositions.paths[i].endOfPath = getPosition();
        }
      } else {
        graphPositions.paths[i] = {};
      }
    }

    return graphPositions;
  },

  /**
   * Set the position of a step
   *
   * @param state
   * @param {*} payload
   */
  setStepPosition: function (state, payload) {
    Vue.set(state.map.graphPositions.steps, payload.identifier, payload.position);
  },

  /**
   * Set the position of an end (the end of a path)
   *
   * @param state
   * @param {*} payload
   */
  setEndPathPosition: function (state, payload) {
    Vue.set(state.map.graphPositions.paths[payload.identifier], 'endOfPath', payload.position);
  },

  /**
   * Set the position of a conditional path (the intersection node of the path)
   *
   * @param state
   * @param {*} payload
   */
  setConditionalPathPosition: function (state, payload) {
    Vue.set(state.map.graphPositions.paths[payload.identifier], 'intersection', payload.position);
  },

  /**
   * Set the vertices of a path
   *
   * @param state
   * @param {*} payload
   */
  setPathVertices: function (state, payload) {
    // Case of a conditional path type (there are multiple path links)
    if (payload.identifier) {
      Vue.set(state.map.graphPositions.paths[payload.pathIndex], payload.identifier, payload.vertices);
    } else {
      Vue.set(state.map.graphPositions.paths[payload.pathIndex], 'vertices', payload.vertices);
    }
  }
};

export default stepEditorMutations;
