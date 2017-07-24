var pathTypeOptionMixin = {

  methods: {

    /**
     * Format options for the path type
     *
     * @param options
     * @param stepNames
     *
     * @returns {*}
     */
    formatOptions: function (options, stepNames) {
      var optionNames = ['source', 'destination', 'default_destination'];

      for (var option in options) {
        if (options.hasOwnProperty(option)) {
          var steps = this.createStepChoices(stepNames);

          if (-1 !== optionNames.indexOf(option)) {
            options[option].options.choices = steps;
            options[option].component_name = 'option-choice';
          }

          if ('destinations' === option) {
            options[option].options.choices = steps;
            options[option].component_name = 'option-conditional-destinations-collection';
          }
        }
      }

      return options;
    },

    /**
     * Create an object to be rendered in a option-choice components, from a simple array
     *
     * @param array
     */
    createStepChoices: function (array) {
      var choices = {};

      for (var i = 0, len = array.length; i < len; i++) {
        choices[array[i]] = array[i];
      }

      return choices;
    }
  }

};

export default pathTypeOptionMixin;
