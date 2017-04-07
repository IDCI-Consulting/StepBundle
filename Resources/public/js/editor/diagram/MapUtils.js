/**
 * MapUtils
 *
 * @param map
 *
 * @constructor
 */
function MapUtils (map) {
  this.map = map;
}

/**
 * Get the paths that have the given stepName as destination
 *
 * @param stepName
 * @returns {Array}
 */
MapUtils.prototype.getPathsWithDestination = function (stepName) {
  var paths = this.map.paths;
  var results = [];

  for (var i = 0, len = paths.length; i < len; i++) {
    if (paths[i].options.destination === stepName) {
      results.push(paths[i]);
    }
  }

  return results;
};

/**
 * Get the steps count for a given depth
 *
 * @param depth
 *
 * @returns {number}
 */
MapUtils.prototype.getStepsCountForDepth = function (depth) {
  var stepsCount = 0;
  var steps = this.map.steps;

  for (var step in steps) {
    if (steps.hasOwnProperty(step)) {
      var stepDepth = this.getStepDepth(step);

      if (stepDepth === depth) {
        stepsCount++;
      }
    }
  }

  return stepsCount;
};

/**
 * Get the maximum step depth for the map
 *
 * @returns {number}
 */
MapUtils.prototype.getMaxStepDepth = function () {
  var max = 0;
  var steps = this.map.steps;

  for (var step in steps) {
    if (steps.hasOwnProperty(step)) {
      var stepDepth = this.getStepDepth(step);

      if (stepDepth > max) {
        max = stepDepth;
      }
    }
  }

  return max;
};

/**
 * Get the depth of the step in the map
 *
 * @param {string} stepName
 * @param {int} [level]
 */
MapUtils.prototype.getStepDepth = function (stepName, level) {

  if ('undefined' === typeof level) {
    level = 0;
  }
  var pathsWithStepAsDestination = this.getPathsWithDestination(stepName);

  if (0 === pathsWithStepAsDestination.length) {
    return level;
  }
  level++;

  var pathDepth = 0;

  for (var i = 0, len = pathsWithStepAsDestination.length; i < len; i++) {
    var tempPathDepth = this.getStepDepth(pathsWithStepAsDestination[i].options.source, level);

    if (tempPathDepth > pathDepth) {
      pathDepth = tempPathDepth;
    }
  }

  return pathDepth;
};
