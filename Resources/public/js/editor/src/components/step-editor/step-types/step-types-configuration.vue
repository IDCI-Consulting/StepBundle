<template>

  <div>
    <div v-for="(step, stepName) in steps" v-if="step.active" :key="stepName">
      <h3 class="heading-configuration">Step configuration</h3>
      <ul class="nav nav-pills" role="tablist">
        <li role="presentation" class="active">
          <a role="tab" data-toggle="tab" :href="anchor('#', stepName, 'options')">Options</a>
        </li>
        <li role="presentation">
          <a role="tab" data-toggle="tab" :href="anchor('#', stepName, 'events')">Step events actions</a>
        </li>
      </ul>
      <div class="tab-content">
        <div role="tabpanel" class="tab-pane in active" :id="anchor('', stepName, 'options')">
        <step-type-options
          v-if="step.active"
          :key="stepName"
          :step="step"
          :name="stepName"
          v-for="(step, stepName) in steps"
        >
        </step-type-options>
        </div>
        <div role="tabpanel" class="tab-pane" :id="anchor('', stepName, 'events')">
          <new-step-event-action class="new-step-event-action" :name="stepName"></new-step-event-action>
          <step-event-actions :name="stepName"></step-event-actions>
        </div>
      </div>
    </div>
  </div>

</template>

<script>

import stepEditorStepTypeOptions from './step-type-options.vue';
import stepEditorNewStepEventAction from 'StepBundle/components/step-editor/step-event-actions/new-step-event-action.vue';
import stepEditorStepEventActions from 'StepBundle/components/step-editor/step-event-actions/step-event-actions.vue';

export default {

  computed: {
    steps: function () {
      return this.$store.getters.getSteps;
    }
  },

  components: {
    'step-type-options': stepEditorStepTypeOptions,
    'new-step-event-action': stepEditorNewStepEventAction,
    'step-event-actions': stepEditorStepEventActions
  },

  methods: {

    /**
     * Create an anchor to hook on bootstrap pills feature
     *
     * @param prefix
     * @param name
     * @param type
     * @returns {string}
     */
    anchor: function (prefix, name, type) {
      return prefix + name + '_' + type;
    }

  }

};

</script>
