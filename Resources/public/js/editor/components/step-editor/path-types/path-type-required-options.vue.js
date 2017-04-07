/* exported stepEditorPathTypeRequiredOptions */
var stepEditorPathTypeRequiredOptions = {

  template:
    '<div>' +
      '<component ' +
        ':is="pathTypeRequiredOption.component_name" ' +
        'v-for="(pathTypeRequiredOption, key) in pathTypeRequiredOptions" ' +
        ':option="pathTypeRequiredOption" ' +
        ':name="key" ' +
        ':value="pathOptions[key]" ' +
        '@changed=setOption' +
      '>' +
      '</component>' +
    '</div>',

  props: ['type'],

  data: function () {
    return {
      pathOptions: {}
    };
  },

  /* global pathTypeOptionMixin */
  mixins: [pathTypeOptionMixin],

  computed: {
    pathTypeRequiredOptions: function () {
      return this.formatOptions(
        this.$store.getters.getPathTypeRequiredOptions(this.type),
        this.$store.getters.getStepsNames
      );
    }
  },

  // Delete the option that are not on the type after the type changed
  watch: {
    type: {
      handler: function (newType) {
        var requiredOptions = this.$store.getters.getPathTypeRequiredOptions(newType);

        for (var option in this.pathOptions) {
          if (!requiredOptions.hasOwnProperty(option)) {
            this.$delete(this.pathOptions, option);
          }
        }
      },
      deep: true
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

    /* global conditionalDestinationsCollectionOption */
    'option-conditional-destinations-collection': conditionalDestinationsCollectionOption
  },

  methods: {

    /**
     * Set an option
     *
     * @param option
     */
    setOption: function (option) {
      this.$set(this.pathOptions, option.name, option.value);
      this.$emit('updateOptions', this.pathOptions);
    }
  }

};
