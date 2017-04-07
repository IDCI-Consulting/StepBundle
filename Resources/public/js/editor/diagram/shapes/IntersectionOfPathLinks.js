/* global joint, _, DiagramColors */

// Ensure the extraStep object is defined
if ('undefined' === typeof joint.shapes.extraStep) {
  joint.shapes.extraStep = {};
}

joint.shapes.extraStep.IntersectionOfPathLinks = joint.shapes.basic.Circle.extend({
  defaults: _.defaultsDeep({
    type: 'extraStep.IntersectionOfPathLinks',
    attrs: {
      circle: {
        'stroke-width': 3,
        'fill': DiagramColors.background
      }
    }
  }, joint.shapes.basic.Circle.prototype.defaults)
});
