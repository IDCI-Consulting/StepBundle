import checkboxOption       from 'ExtraFormBundle/components/common/options/checkbox.vue';
import textareaOption       from 'ExtraFormBundle/components/common/options/textarea.vue';
import choiceOption         from 'ExtraFormBundle/components/common/options/choice.vue';
import textOption           from 'ExtraFormBundle/components/common/options/text.vue';
import numberOption         from 'ExtraFormBundle/components/common/options/number.vue';
import { generateUniqueId } from 'ExtraFormBundle/utils/utils.js';

var stepEditorStepEventActionConfiguration = {

  template:
    '<div class="collapsed-block" :class="{ \'form-type-not-configured\': !hasConfiguration() }">' +
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
          '<a v-if="hasConfiguration()" role="button" data-toggle="collapse" :href="\'#\' + id">' +
            'Parameters<span class="toggle"></span>' +
          '</a>' +
          '<div v-else>' +
            'This path event action was not configured <i class="fa-icon fa fa-exclamation-triangle" aria-hidden="true"></i>' +
          '</div>' +
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
    'option-checkbox': checkboxOption,
    'option-textarea': textareaOption,
    'option-choice': choiceOption,
    'option-text': textOption,
    'option-number': numberOption
  },

  methods: {

    /**
     * Check if the step event action type has a configuration
     *
     * @returns {boolean}
     */
    hasConfiguration: function () {
      return Object.keys(this.stepEventActionType).length > 0;
    },

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
    }

  }

};

export default stepEditorStepEventActionConfiguration;
