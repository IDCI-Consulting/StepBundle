import joint         from 'jointjs';
import _             from 'underscore';
import DiagramColors from 'StepBundle/diagram/DiagramColors.js';

export default joint.shapes.basic.Circle.extend({
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
