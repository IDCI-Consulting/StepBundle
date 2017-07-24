/**
 * DiagramUtils
 *
 * @constructor
 */
var DiagramUtils = {};

/**
 * Get the step cell from its name in a graph
 *
 * @param graph
 * @param stepName
 *
 * @return {joint.shapes.extraStep.Step}
 */
DiagramUtils.getStepCell = function (graph, stepName) {
  var elements = graph.getElements();

  // We loop over all elements of the graph
  for (var i = 0, len = elements.length; i < len; i++) {
    if (DiagramUtils.isAStep(elements[i])) {
      if (stepName === DiagramUtils.getStepCellName(elements[i])) {
        return elements[i];
      }
    }
  }

  throw new Error('Could not find step cell in the graph for the step ' + stepName);
};

/**
 * Get the name of the step from the cell
 *
 * @param cell
 *
 * @return string
 */
DiagramUtils.getStepCellName = function (cell) {
  return cell.attributes.attrs.text.text;
};

/**
 * Check if a cell is a step
 *
 * @param cell
 *
 * @returns {boolean}
 */
DiagramUtils.isAStep = function (cell) {
  return 'extraStep.Step' === cell.attributes.type;
};

/**
 * Check if a cell is an intersection of path links
 *
 * @param cell
 *
 * @returns {boolean}
 */
DiagramUtils.isAIntersectionOfPathLinks = function (cell) {
  return 'extraStep.IntersectionOfPathLinks' === cell.attributes.type;
};

/**
 * Check if a cell is an end of a path link
 *
 * @param cell
 *
 * @returns {boolean}
 */
DiagramUtils.isAEndOfPathLink = function (cell) {
  return 'extraStep.EndOfPathLink' === cell.attributes.type;
};

/**
 * Check if the cell is a path link
 *
 * @param cell
 *
 * @returns {boolean}
 */
DiagramUtils.isAPathLink = function (cell) {
  return 'extraStep.PathLink' === cell.attributes.type;
};

export default DiagramUtils;
