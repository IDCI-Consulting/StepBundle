import joint              from 'jointjs';
import _                  from 'underscore';
import $                  from 'jquery';
import DiagramPathFactory from 'StepBundle/diagram/factory/DiagramPathFactory.js';
import DiagramStepFactory from 'StepBundle/diagram/factory/DiagramStepFactory.js';
import DiagramColors      from 'StepBundle/diagram/DiagramColors.js';
import DiagramUtils       from  'StepBundle/diagram/DiagramUtils.js';

/**
 * Object representing the map diagram
 *
 * @param element
 * @param map
 * @constructor
 */
function Diagram (element, map) {
  this.map = map;

  this.graph = new joint.dia.Graph;

  this.movedStepPosition = null;

  this.changedPathVertices = null;

  // Variable used to avoid calling some callbacks after events
  this.ongoingAction = null;

  this.paper = new joint.dia.Paper({
    el: element,
    gridSize: 1,
    height: 900,
    width: 900,
    model: this.graph,
    interactive: function (cellView) {
      if (cellView.model instanceof joint.dia.Link) {

        // Disable the default vertex add functionality on pointerdown.
        return {
          vertexAdd: false
        };
      }

      return true;
    },
    linkView: joint.dia.LinkView.extend({
      pointerdblclick: function (evt, x, y) {
        if (joint.V(evt.target).hasClass('connection') || joint.V(evt.target).hasClass('connection-wrap')) {
          this.addVertex({
            x: x,
            y: y
          });
        }
      },
      options: _.defaults({
        linkToolsOffset: 100,

        // Always scale down to 2 the link tools
        shortLinkLength: 10000
      }, joint.dia.LinkView.prototype.options)
    })
  });

  this.generateSteps(this.map.steps, this.map.graphPositions.steps);
  this.generatePaths(this.map.paths, this.map.graphPositions.paths);
}

/**
 * Get the map
 *
 * @returns {*} map
 */
Diagram.prototype.getMap = function () {
  return this.map;
};

Diagram.prototype.generateSteps = function (steps, positions) {
  for (var stepName in steps) {
    if (steps.hasOwnProperty(stepName)) {
      var isActive = this.map.steps[stepName].active;
      var stepCell = DiagramStepFactory.create(
        stepName,
        positions[stepName],
        isActive
      );

      this.graph.addCell(stepCell);
    }
  }
};

Diagram.prototype.generatePaths = function (paths, positions) {
  for (var i = 0, len = paths.length; i < len; i++) {
    var cells = DiagramPathFactory.create(
      this.graph,
      paths[i],
      i,
      positions[i]
    );

    this.graph.addCell(cells);
  }
};

/**
 * Check if a step cell is active
 *
 * @param stepCell
 *
 * @returns {boolean}
 */
Diagram.prototype.stepIsActive = function (stepCell) {
  return stepCell.attr('rect/fill') === DiagramColors.activeBackground;
};

/**
 * Check if a path is active
 *
 * @param element
 *
 * @returns {boolean}
 */
Diagram.prototype.pathIsActive = function (element) {
  if (DiagramUtils.isAPathLink(element)) {
    return element.attr('.connection/stroke') === DiagramColors.activeBackground;
  } else if (DiagramUtils.isAIntersectionOfPathLinks(element)) {
    return element.attr('circle/fill') === DiagramColors.activeBackground;
  } else if (DiagramUtils.isAEndOfPathLink(element)) {
    return element.attr('circle/fill') === DiagramColors.activeBackground;
  }
};

/**
 * Call callback function on cell drop, to set the position of cells
 *
 * @param callback
 */
Diagram.prototype.onCellDrop = function (callback) {
  var self = this;

  this.paper.on('cell:pointerup', function (cellView, event, x, y) {

    // Set the new position of a step cell
    if (null !== self.movedStepPosition) {
      var computedX = x - self.movedStepPosition.diff.x;
      var computedY = y - self.movedStepPosition.diff.y;
      var cellPosition = {
        x: computedX,
        y: computedY
      };

      var payload = {
        identifier: self.movedStepPosition.identifier,
        position: cellPosition
      };

      var cell = cellView.model;

      // We consider no drop was made if the cell was not moved
      if (!self.cellWasMoved(arguments)) {
        return;
      }


      if (DiagramUtils.isAEndOfPathLink(cell)) {
        return callback('setEndPathPosition', payload);
      }

      if (DiagramUtils.isAIntersectionOfPathLinks(cell)) {
        return callback('setConditionalPathPosition', payload);
      }

      if (DiagramUtils.isAStep(cell)) {
        return callback('setStepPosition', payload);
      }

      self.movedStepPosition = null;
    }
  });

  // Get the diff because x and y are mouse position and not cell position
  this.paper.on('cell:pointerdown', function (cellView, event, x, y) {
    if (DiagramUtils.isAEndOfPathLink(cellView.model) ||
        DiagramUtils.isAStep(cellView.model) ||
        DiagramUtils.isAIntersectionOfPathLinks(cellView.model)
    ) {
      var xDiff = x - cellView.model.get('position').x;
      var yDiff = y - cellView.model.get('position').y;
      var identifier = null;

      if (DiagramUtils.isAStep(cellView.model)) {
        identifier = DiagramUtils.getStepCellName(cellView.model);
      } else {
        identifier = cellView.model.pathIndex;
      }

      self.movedStepPosition = {
        identifier: identifier,
        diff: {
          x: xDiff,
          y: yDiff
        }
      };
    }
  });
};

/**
 * Call callback function after vertices were changed
 *
 * @param callback
 */
Diagram.prototype.onChangedVertices = function (callback) {
  var self = this;

  this.graph.on('change:vertices', function (pathLink, vertices) {
    self.changedPathVertices = {
      pathIndex: pathLink.index,
      vertices: vertices
    };

    if (pathLink.identifier) {
      self.changedPathVertices.identifier = pathLink.identifier;
    }
  });

  this.graph.on('batch:stop', function () {
    if (null !== self.changedPathVertices) {
      return callback(self.changedPathVertices);
    }

    self.changedPathVertices = null;
  });
};

/**
 * Call callback function on click on a PathLink cell
 *
 * @param callback
 */
Diagram.prototype.onPathLinkClick = function (callback) {
  var self = this;

  this.paper.on('link:pointerup', function (cellView) {
    // Do not trigger callback if the path is being removed
    // I don't know why but the 'link:remove' event is always triggered before the 'cell:pointerup' event
    if ('link:remove' === self.ongoingAction) {
      return;
    }

    // Do not trigger callback if vertices were changed (it's not a click in that case)
    if (self.verticesWereChanged(arguments)) {
      return;
    }

    var element = cellView.model;

    callback(element, element.index);
  });
};

/**
 * Call callback function on click on the delete button of a path link
 *
 * @param callback
 */
Diagram.prototype.onDeletePathClick = function (callback) {
  var self = this;

  this.paper.on('link:remove', function (cellView) {
    self.ongoingAction = 'link:remove';
    var element = cellView.model;

    callback(element, element.index);
  });
};

/**
 * Call callback function on click on a cell for a given type
 *
 * Do not trigger callback if the step is being remove
 * It can happen if the click was made on the delete button
 * I don't know why but the 'remove' event is always triggered before the 'cell:pointerup' event
 *
 * @param cellType - the type of the cell
 * @param callback
 */
Diagram.prototype.onCellClick = function (cellType, callback) {
  var self = this;

  this.paper.on('cell:pointerup', function (cellView) {
    if ('step' === cellType && 'step:remove' === self.ongoingAction) {
      return;
    }

    var cell = cellView.model;

    // We consider no click was made if the cell was moved
    if (self.cellWasMoved(arguments)) {
      return;
    }

    if ('step' === cellType && DiagramUtils.isAStep(cell)) {
      var stepName = DiagramUtils.getStepCellName(cell);

      return callback(cell, stepName);
    }

    if ('intersection' === cellType && DiagramUtils.isAIntersectionOfPathLinks(cell)) {
      return callback(cell, cell.pathIndex);
    }

    if ('end' === cellType && DiagramUtils.isAEndOfPathLink(cell)) {
      return callback(cell, cell.pathIndex);
    }
  });
};

/**
 * Call callback function on click on the delete button of a step cell
 *
 * @param callback
 */
Diagram.prototype.onDeleteStepClick = function (callback) {
  var self = this;

  this.graph.on('remove', function (cell) {
    self.ongoingAction = 'step:remove';

    callback(cell);
  });
};

/**
 * Check if a cell was moved in comparison to the map positions
 *
 * @param cellPointerUpArguments - the arguments of the event cell:pointerup function
 *
 * @return {boolean}
 */
Diagram.prototype.cellWasMoved = function (cellPointerUpArguments) {
  if (null === this.movedStepPosition) {
    return false;
  }

  var cell = cellPointerUpArguments[0].model;
  var x = cellPointerUpArguments[2];
  var y = cellPointerUpArguments[3];
  var computedX = x - this.movedStepPosition.diff.x;
  var computedY = y - this.movedStepPosition.diff.y;
  var cellPosition = {
    x: computedX,
    y: computedY
  };

  if (DiagramUtils.isAEndOfPathLink(cell)) {
    return JSON.stringify(this.map.graphPositions.paths[cell.pathIndex].endOfPath) !== JSON.stringify(cellPosition);
  }

  if (DiagramUtils.isAIntersectionOfPathLinks(cell)) {
    return JSON.stringify(this.map.graphPositions.paths[cell.pathIndex].intersection) !== JSON.stringify(cellPosition);
  }

  if (DiagramUtils.isAStep(cell)) {
    var stepName = DiagramUtils.getStepCellName(cell);

    return JSON.stringify(this.map.graphPositions.steps[stepName]) !== JSON.stringify(cellPosition);
  }
};

/**
 * Check if vertices were changed
 *
 * @return {boolean}
 */
Diagram.prototype.verticesWereChanged = function () {
  return null !== this.changedPathVertices;
};

export default Diagram;
