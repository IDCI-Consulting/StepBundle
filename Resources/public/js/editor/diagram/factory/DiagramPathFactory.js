/* global DiagramUtils, DiagramColors, joint */

var DiagramPathFactory = {

  /**
   * Create a diagram path. It can be composed of multiple cells
   *
   * @param {joint.dia.Graph} graph
   * @param {*} path - the object representing the path from the json map
   * @param {number} index - the index of the path used as an identifier
   * @param {*} position - an object to set the location of the path on the diagram
   *
   * @returns {*} [] - an array of joint js cells
   */
  create: function (graph, path, index, position) {
    if (!path.options.source) {
      throw new Error('A path must always have a source');
    }

    var cells = [];
    var isActive = path.active;
    var sourceCell = DiagramUtils.getStepCell(graph, path.options.source);

    if ('single' === path.type) {
      var destinationCell = DiagramUtils.getStepCell(graph, path.options.destination);
      var singlePathLinkCell = DiagramPathFactory.createPathLink(
        sourceCell,
        destinationCell,
        index,
        isActive,
        position.vertices
      );

      cells.push(singlePathLinkCell);

      return cells;
    }

    if ('end' === path.type) {
      var endCell = DiagramPathFactory.createEndOfPathLinkCell(index, isActive, position);
      var endPathLinkCell = DiagramPathFactory.createPathLink(sourceCell, endCell, index, isActive, position.vertices);

      cells.push(endCell);
      cells.push(endPathLinkCell);

      return cells;
    }

    if ('conditional_destination' === path.type) {
      var intersectionOfPathLinksCell = DiagramPathFactory.createIntersectionOfPathLinksCell(index, isActive, position);

      cells.push(intersectionOfPathLinksCell);

      var defaultDestinationCell = DiagramUtils.getStepCell(graph, path.options.default_destination);
      var sourceToIntersectionLinkCell = DiagramPathFactory.createPathLink(
        sourceCell,
        intersectionOfPathLinksCell,
        index,
        isActive,
        position.sourceToIntersectionLinkCell,
        null,
        'sourceToIntersectionLinkCell'
      );

      cells.push(sourceToIntersectionLinkCell);

      var intersectionToDefaultDestinationLinkCell = DiagramPathFactory.createPathLink(
        intersectionOfPathLinksCell,
        defaultDestinationCell,
        index,
        isActive,
        position.intersectionToDefaultDestination,
        'default',
        'intersectionToDefaultDestination'
      );

      cells.push(intersectionToDefaultDestinationLinkCell);

      var destinations = path.options.destinations;
      var linkIdentifier = 0;

      for (var stepName in destinations) {
        if (destinations.hasOwnProperty(stepName)) {
          var destinationStepCell = DiagramUtils.getStepCell(graph, stepName);
          var pathLinkIdentifier = 'intersectionToDestination' + linkIdentifier;
          var conditionalPathLinkCell = DiagramPathFactory.createPathLink(
            intersectionOfPathLinksCell,
            destinationStepCell,
            index,
            isActive,
            position[pathLinkIdentifier],
            destinations[stepName],
            pathLinkIdentifier
          );

          cells.push(conditionalPathLinkCell);
          linkIdentifier++;
        }
      }

      return cells;
    }

    throw new Error('Unrecognized path type \'' + path.type + '\'');
  },

  /**
   * Create a link between 2 cells
   *
   * @param source
   * @param target
   * @param index
   * @param isActive
   * @param [vertices]
   * @param [label]
   * @param [identifier]
   *
   * @returns {joint.shapes.extraStep.PathLink}
   */
  createPathLink: function (source, target, index, isActive, vertices, label, identifier) {

    /**
     * Reduce the size of the label if too large
     *
     * @param length
     */
    function getReducedLabel (length) {
      if (label && label.length > length) {
        return label.substring(0, length) + '...';
      }

      return label;
    }

    var pathLinkCell = new joint.shapes.extraStep.PathLink({
      source: {
        id: source.id
      },
      target: {
        id: target.id
      },
      vertices: vertices || [],
      labels: [{
        position: 0.5,
        attrs: {
          text: {
            'text': getReducedLabel(30) || '',
            'font-weight': 'bold'
          }
        }
      }]
    });

    pathLinkCell.index = index;

    // Used to specify the vertices positions of a conditional path
    if (identifier) {
      pathLinkCell.identifier = identifier;
    }

    if (isActive) {
      pathLinkCell.attr({
        '.connection': {
          stroke: DiagramColors.activeBackground
        },
        '.marker-target': {
          fill: DiagramColors.activeBackground,
          stroke: DiagramColors.activeBackground
        }
      });
    }

    return pathLinkCell;
  },

  /**
   * Create a cell representing a end
   *
   * @param index
   * @param isActive
   * @param position
   *
   * @returns {joint.shapes.extraStep.EndOfPathLink}
   */
  createEndOfPathLinkCell: function (index, isActive, position) {
    var endOfPathLinkCell = new joint.shapes.extraStep.EndOfPathLink({
      position: {
        x: position.endOfPath.x,
        y: position.endOfPath.y
      },
      size: {
        width: 50,
        height: 50
      },
      attrs: {
        text: {
          text: 'End'
        }
      }
    });

    endOfPathLinkCell.pathIndex = index;

    if (isActive) {
      endOfPathLinkCell.attr({
        circle: {
          fill: DiagramColors.activeBackground
        },
        text: {
          fill: DiagramColors.activeText
        }
      });
    }

    return endOfPathLinkCell;
  },

  /**
   * Create a cell which is the intersection of multiple path links
   *
   * @param index
   * @param isActive
   * @param position
   *
   * @returns {joint.shapes.extraStep.IntersectionOfPathLinks}
   */
  createIntersectionOfPathLinksCell: function (index, isActive, position) {
    var intersectionOfPathLinksCell = new joint.shapes.extraStep.IntersectionOfPathLinks({
      position: {
        x: position.intersection.x,
        y: position.intersection.y
      },
      size: {
        width: 25,
        height: 25
      }
    });

    intersectionOfPathLinksCell.pathIndex = index;

    if (isActive) {
      intersectionOfPathLinksCell.attr({
        circle: {
          fill: DiagramColors.activeBackground
        }
      });
    }

    return intersectionOfPathLinksCell;
  }

};
