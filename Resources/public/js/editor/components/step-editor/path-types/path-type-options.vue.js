/* global hashCode */
/* exported stepEditorPathTypeOptions */

var stepEditorPathTypeOptions = {

  template:
    '<div>' +
      '<component :key="getKey(typeOption)" ' +
        ':is="typeOption.component_name" ' +
        'v-for="(typeOption, key) in typeOptions" ' +
        ':option="typeOption" ' +
        ':name="key"' +
        ':value="pathOptions[key]" ' +
        '@changed="updatePathOption"' +
      '>' +
      '</component>' +
    '</div>',

  props: ['path', 'index'],

  /* global pathTypeOptionMixin */
  mixins: [pathTypeOptionMixin],

  computed: {
    typeOptions: function () {
      return this.formatOptions(
        this.$store.getters.getPathTypeOptions(this.path.type),
        this.$store.getters.getStepsNames
      );
    },
    pathOptions: function () {
      return this.$store.getters.getPathOptions(this.index);
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
     * Update an option
     *
     * @param option
     */
    updatePathOption: function (option) {
      var payload = {
        option: option,
        pathIndex: this.index
      };

      this.$store.commit('updatePathOption', payload);
    },

    /**
     * Get a unique key for a component
     *
     * The typeOptions computed property doesn't seem to trigger the update of the component
     * If a path is active and i delete a step, the choices components aren't updated immediately
     * (The removed step is still in the choices)
     *
     * This function compute the hash of the typeOptions to get a unique key and force update if the key changed
     */
    getKey: function (option) {
      return hashCode(JSON.stringify(option));
    }
  }

};
