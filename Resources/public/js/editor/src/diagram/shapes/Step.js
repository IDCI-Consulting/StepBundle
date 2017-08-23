import joint from 'jointjs';
import _     from 'underscore';
import $     from 'jquery';

// Ensure the extraStep object is defined
if ('undefined' === typeof joint.shapes.extraStep) {
  joint.shapes.extraStep = {};
}

joint.shapes.extraStep.Step = joint.shapes.basic.Rect.extend({
  defaults: joint.util.deepSupplement({
    type: 'extraStep.Step',
    attrs: {
      rect: {
        'stroke': 'none',
        'fill-opacity': 0
      }
    }
  }, joint.shapes.basic.Rect.prototype.defaults)
});

joint.shapes.extraStep.StepView = joint.dia.ElementView.extend({

  template: [
    '<div class="html-element">',
    '<button class="delete">x</button>',
    '<label></label>',
    '</div>'
  ].join(''),

  initialize: function () {
    _.bindAll(this, 'updateBox');
    joint.dia.ElementView.prototype.initialize.apply(this, arguments);

    this.$box = $(_.template(this.template)());

    this.$box.find('.delete').on('click', _.bind(this.model.remove, this.model));

    // Update the box position whenever the underlying model changes.
    this.model.on('change', this.updateBox, this);

    // Remove the box when the model gets removed from the graph.
    this.model.on('remove', this.removeBox, this);

    this.updateBox();
  },

  render: function () {
    joint.dia.ElementView.prototype.render.apply(this, arguments);
    this.paper.$el.prepend(this.$box);
    this.updateBox();

    return this;
  },

  updateBox: function () {
    // Set the position and dimension of the box so that it covers the JointJS element.
    var bbox = this.model.getBBox();

    // Example of updating the HTML with a data stored in the cell model.
    this.$box
      .find('label')
      .css({
        color: this.model.attr('text/fill')
      })
      .text(this.model.get('label'));
    this.$box.css({
      'background-color': this.model.attr('rect/fill'),
      'width': bbox.width,
      'height': bbox.height,
      'left': bbox.x,
      'top': bbox.y,
      'transform': 'rotate(' + (this.model.get('angle') || 0) + 'deg)'
    });
  },

  removeBox: function () {
    this.$box.remove();
  }
});
