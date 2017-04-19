/* exported stepEditorMapOptions */

var stepEditorMapOptions = {

  template:
  '<div>' +
    '<component ' +
      ':is="mapTypeOption.component_name" ' +
      'v-for="(mapTypeOption, key) in mapTypeOptions" ' +
      ':option="mapTypeOption" ' +
      ':name="key" ' +
      ':value="mapOptions[key]" ' +
      '@changed="updateMapOption"' +
    '>' +
    '</component>' +
  '</div>',

  computed: {
    mapTypeOptions: function () {
      return this.addAutoescapeOption(this.$store.getters.getMapTypeOptions);

    },
    mapOptions: function () {
      return this.$store.getters.getMapOptions;
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
    'option-integer': integerOption
  },

  methods: {

    /**
     * Update an option
     *
     * @param option
     */
    updateMapOption: function (option) {
      this.$store.commit('updateMapOption', option);
    },

    /**
     * Add the autoescape option
     *
     * @param options
     */
    addAutoescapeOption: function (options) {
      if ('undefined' !== typeof options) {
        options.autoescape_false = {
          extra_form_type: 'checkbox',
          options: {
            required: false,
            data: false
          },
          component_name: 'option-checkbox'
        };

        return options;
      }
    }
  }

};
