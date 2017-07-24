import checkboxOption    from 'ExtraFormBundle/components/common/options/checkbox.vue';
import textareaOption    from 'ExtraFormBundle/components/common/options/textarea.vue';
import choiceOption      from 'ExtraFormBundle/components/common/options/choice.vue';
import textOption        from 'ExtraFormBundle/components/common/options/text.vue';
import numberOption      from 'ExtraFormBundle/components/common/options/number.vue';
import integerOption     from 'ExtraFormBundle/components/common/options/integer.vue';
import formBuilderOption from 'StepBundle/components/step-editor/options/form-builder.vue';

var stepEditorStepTypeOptions = {

  template:
    '<div>' +
      '<component ' +
        ':is="typeOption.component_name" ' +
        'v-for="(typeOption, key) in typeOptions" ' +
        ':option="typeOption" ' +
        ':key="key" ' +
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
    'option-checkbox': checkboxOption,
    'option-textarea': textareaOption,
    'option-choice': choiceOption,
    'option-text': textOption,
    'option-number': numberOption,
    'option-integer': integerOption,
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

export default stepEditorStepTypeOptions;
