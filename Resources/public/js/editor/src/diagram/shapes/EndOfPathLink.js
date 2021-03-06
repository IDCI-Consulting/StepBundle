import joint         from 'jointjs';
import _             from 'underscore';
import DiagramColors from 'StepBundle/diagram/DiagramColors.js';

export default joint.shapes.basic.Circle.extend({
  defaults: joint.util.deepSupplement({
    type: 'extraStep.EndOfPathLink',
    attrs: {
      circle: {
        'stroke-width': 3,
        'fill': DiagramColors.background
      },
      text: {
        'font-weight': '800'
      }
    }
  }, joint.shapes.basic.Circle.prototype.defaults)
});
