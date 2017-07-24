import checkboxOption from 'ExtraFormBundle/components/common/options/checkbox.vue';
import textareaOption from 'ExtraFormBundle/components/common/options/textarea.vue';
import choiceOption   from 'ExtraFormBundle/components/common/options/choice.vue';
import textOption     from 'ExtraFormBundle/components/common/options/text.vue';
import numberOption   from 'ExtraFormBundle/components/common/options/number.vue';
import integerOption  from 'ExtraFormBundle/components/common/options/integer.vue';
import httpMixin      from 'ExtraFormBundle/mixins/http.vue';

var stepEditorMapOptions = {

  template:
  '<div>' +
    '<component ' +
      ':is="mapTypeOption.component_name" ' +
      'v-for="(mapTypeOption, key) in mapTypeOptions" ' +
      ':key=key ' +
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

    'option-checkbox': checkboxOption,
    'option-textarea': textareaOption,
    'option-choice': choiceOption,
    'option-text': textOption,
    'option-number': numberOption,
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

export default stepEditorMapOptions;
