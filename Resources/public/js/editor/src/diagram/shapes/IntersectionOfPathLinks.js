import joint         from 'jointjs';
import _             from 'underscore';
import DiagramColors from 'StepBundle/diagram/DiagramColors.js';

// Ensure the extraStep object is defined
if ('undefined' === typeof joint.shapes.extraStep) {
  joint.shapes.extraStep = {};
}

joint.shapes.extraStep.IntersectionOfPathLinks = joint.shapes.basic.Circle.extend({
  defaults: joint.util.deepSupplement({
    type: 'extraStep.IntersectionOfPathLinks',
    attrs: {
      circle: {
        'stroke-width': 3,
        'fill': DiagramColors.background
      }
    }
  }, joint.shapes.basic.Circle.prototype.defaults)
});
