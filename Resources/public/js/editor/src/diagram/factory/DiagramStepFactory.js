import joint         from 'jointjs';
import DiagramColors from 'StepBundle/diagram/DiagramColors.js';

import 'StepBundle/diagram/shapes/Step.js';

var DiagramStepFactory = {

  /**
   * Create a cell representing a step
   *
   * @param {string} stepName
   * @param {*} position
   * @param {boolean} isActive
   *
   * @returns {joint.shapes.extraStep.Step}
   */
  create: function (stepName, position, isActive) {

    var stepWidth = (stepName.length * 10) < 65 ? 65 : stepName.length * 10;

    var stepCell = new joint.shapes.extraStep.Step({
      label: stepName,
      position: {
        x: position.x,
        y: position.y
      },
      size: {
        width: stepWidth,
        height: 50
      },
      attrs: {
        rect: {
          fill: DiagramColors.background
        },
        text: {
          text: stepName,
          fill: DiagramColors.text
        }
      }
    });

    if (isActive) {
      stepCell.attr({
        rect: {
          fill: DiagramColors.activeBackground
        },
        text: {
          fill: DiagramColors.activeText
        }
      });
    }

    return stepCell;
  }

};

export default DiagramStepFactory;
