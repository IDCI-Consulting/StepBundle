<template>

  <div>
    <component
      :key="getKey(typeOption)"
      :is="typeOption.component_name"
      v-for="(typeOption, key) in typeOptions"
      :option="typeOption"
      :name="key"
      :value="pathOptions[key]"
      @changed="updatePathOption"
    >
    </component>
  </div>

</template>

<script>

import { hashCode }                            from 'ExtraFormBundle/utils/utils.js';
import checkboxOption                          from 'ExtraFormBundle/components/common/options/checkbox.vue';
import textareaOption                          from 'ExtraFormBundle/components/common/options/textarea.vue';
import choiceOption                            from 'ExtraFormBundle/components/common/options/choice.vue';
import textOption                              from 'ExtraFormBundle/components/common/options/text.vue';
import numberOption                            from 'ExtraFormBundle/components/common/options/number.vue';
import integerOption                           from 'ExtraFormBundle/components/common/options/integer.vue';
import pathTypeOptionMixin                     from 'StepBundle/mixins/path-type-options.vue';
import conditionalDestinationsCollectionOption from 'StepBundle/components/step-editor/options/conditional-destinations-collection.vue';

export default {

  props: ['path', 'index'],

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
    'option-checkbox': checkboxOption,
    'option-textarea': textareaOption,
    'option-choice': choiceOption,
    'option-text': textOption,
    'option-number': numberOption,
    'option-integer': integerOption,
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

</script>
