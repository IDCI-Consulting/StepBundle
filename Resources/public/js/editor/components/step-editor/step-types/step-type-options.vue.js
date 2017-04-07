/* exported stepEditorStepTypeOptions */
var stepEditorStepTypeOptions = {

  template:
    '<div>' +
      '<component ' +
        ':is="typeOption.component_name" ' +
        'v-for="(typeOption, key) in typeOptions" ' +
        ':option="typeOption" ' +
        ':name="key"' +
        ':value="stepOptions[key]" ' +
        '@changed="updateStepOption"' +
      '>' +
      '</component>' +
    '</div>',

  props: ['step', 'name'],

  computed: {
    typeOptions: function () {
      return this.formatOptions(
        this.$store.getters.getStepTypeOptions(this.step.type)
      );
    },
    stepOptions: function () {
      return this.$store.getters.getStepOptions(this.name);
    }
  },

  components: {

    /* global checkboxOption */
    'option-checkbox': checkboxOption,

    /* global textareaOption */
    'option-textarea': textareaOption,

    /* global choiceOption */
    'option-choice': choiceOption,

    /* global textOption */
    'option-text': textOption,

    /* global numberOption */
    'option-number': numberOption,

    /* global integerOption */
    'option-integer': integerOption,

    /* global formBuilderOption */
    'option-form-builder': formBuilderOption

  },

  methods: {

    /**
     * Update an option
     *
     * @param option
     */
    updateStepOption: function (option) {
      var payload = {
        option: option,
        stepName: this.name
      };

      this.$store.commit('updateStepOption', payload);
    },

    /**
     * Format the options
     *
     * @param options
     */
    formatOptions: function (options) {
      for (var option in options) {
        if (options.hasOwnProperty(option)) {
          if ('@builder' === option) {
            options[option].component_name = 'option-form-builder';
          }
        }
      }

      return options;
    }
  }

};
