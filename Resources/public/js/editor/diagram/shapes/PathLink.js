/* global joint, _ */

// Ensure the extraStep object is defined
if ('undefined' === typeof joint.shapes.extraStep) {
  joint.shapes.extraStep = {};
}

joint.shapes.extraStep.PathLink = joint.dia.Link.extend({

  // Initial markup
  markup: [
    '<path class="connection" stroke="black" d="M 0 0 0 0"/>',
    '<path class="marker-source" fill="black" stroke="black" d="M 0 0 0 0"/>',
    '<path class="marker-target" fill="black" stroke="black" d="M 0 0 0 0"/>',
    '<path class="connection-wrap" d="M 0 0 0 0"/>',
    '<g class="labels"/>',
    '<g class="marker-vertices"/>',
    '<g class="marker-arrowheads"/>',
    '<g class="link-tools"/>'
  ].join(''),

  toolMarkup: [
    '<g class="link-tool">',
    '<g class="tool-remove" event="link:remove">',
    '<circle r="11" />',
    '<path transform="scale(.8) translate(-16, -16)" d="M24.778,21.419 19.276,15.917 24.777,10.415 21.949,7.585 16.447,13.087 10.945,7.585 8.117,10.415 13.618,15.917 8.116,21.419 10.946,24.248 16.447,18.746 21.948,24.248z" />',
    '<title>Remove link.</title>',
    '</g>',
    '</g>'
  ].join(''),

  defaults: _.defaultsDeep({
    type: 'extraStep.PathLink',
    attrs: {
      '.marker-target': {
        d: 'M 10 0 L 0 5 L 10 10 z'
      },
      '.connection': {
        'stroke-width': 3
      },
      '.link-tools .link-tool circle': {
        r: 20
      },
      '.marker-arrowheads': {
        display: 'none'
      }
    },
    smooth: false
  }, joint.dia.Link.prototype.defaults)
});
