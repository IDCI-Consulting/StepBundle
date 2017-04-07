/* global generateUniqueId */
/* exported stepEditorStepEventActionConfiguration */

var stepEditorStepEventActionConfiguration = {

  template:
    '<div>' +
      '<button @click.prevent="remove" aria-label="Close" class="close">' +
        '<span aria-hidden="true">×</span>' +
      '</button>' +
      '<strong>{{ action }}</strong>' +
      '<div class="options extra-form-inputs-required">' +
        '<div class="form-group">' +
          '<label>name</label>' +
          '<input class="form-control" v-model="stepEventActionName" type="text"/>' +
        '</div>' +
        '<a role="button" data-toggle="collapse" :href="\'#\' + id">' +
          'Parameters<span class="toggle"></span>' +
        '</a>' +
        '<div :id="id" class="panel-collapse collapse" role="tabpanel" aria-expanded="false" :aria-controls="id">' +
          '<component ' +
            ':is="option.component_name" ' +
            'v-for="(option, key) in stepEventActionType.extra_form_options" ' +
            ':option="option" ' +
            ':name="key" ' +
            ':key="option"' +
            ':value="getParameterValue(key)" ' +
            '@changed="updateOption"' +
          '/>' +
        '</div>' +
      '</div>' +
    '</div>',

  props: ['action', 'name', 'parameters'],

  data: function () {
    return {
      stepEventActionName: this.name,
      stepEventActionParameters: this.parameters
    };
  },

  computed: {
    id: function () {
      return 'step_event_action_' + generateUniqueId();
    },
    stepEventActionType: function () {
      return this.$store.getters.getStepEventActionType(this.action);
    }
  },

  watch: {
    stepEventActionName: {
      handler: function (stepEventActionName) {
        this.$emit('updateName', stepEventActionName);
      }
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
    'option-number': numberOption
  },

  methods: {

    /**
     * Remove a step event action
     */
    remove: function () {
      this.$emit('remove');
    },

    /**
     * Update an option
     *
     * @param option
     */
    updateOption: function (option) {
      this.$emit('updateOption', option);
    },

    /**
     * Get the parameters for an action
     *
     * @param key
     */
    getParameterValue: function (key) {
      if (typeof this.parameters !== 'undefined') {
        return this.parameters[key];
      }

      return '';
    }

  }

};