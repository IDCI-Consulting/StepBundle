<template>

  <div>
    <component
      :is="pathTypeRequiredOption.component_name"
      v-for="(pathTypeRequiredOption, key) in pathTypeRequiredOptions"
      :option="pathTypeRequiredOption"
      :name="key"
      :key="key"
      :value="pathOptions[key]"
      @changed="setOption"
    >
    </component>
  </div>

</template>

<script>

import checkboxOption                          from 'ExtraFormBundle/components/common/options/checkbox.vue';
import textareaOption                          from 'ExtraFormBundle/components/common/options/textarea.vue';
import choiceOption                            from 'ExtraFormBundle/components/common/options/choice.vue';
import textOption                              from 'ExtraFormBundle/components/common/options/text.vue';
import numberOption                            from 'ExtraFormBundle/components/common/options/number.vue';
import integerOption                           from 'ExtraFormBundle/components/common/options/integer.vue';
import conditionalDestinationsCollectionOption from 'StepBundle/components/step-editor/options/conditional-destinations-collection.vue';
import pathTypeOptionMixin                     from 'StepBundle/mixins/path-type-options.vue';

export default {

  props: ['type'],

  data: function () {
    return {
      pathOptions: {}
    };
  },

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

</script>
