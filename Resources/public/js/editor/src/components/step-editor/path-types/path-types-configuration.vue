<template>

  <div>
    <div v-for="(path, index) in paths" v-if="path.active" :key="index">
      <h3 class="heading-configuration">Path configuration</h3>
      <tabs>
        <tab name="Options">
            <path-type-options :path="path" :index="index"></path-type-options>
        </tab>
        <tab name="Path events actions">
            <new-path-event-action class="new-path-event-action" :index="index"></new-path-event-action>
            <path-event-actions :index="index"></path-event-actions>
        </tab>
      </tabs>
    </div>
  </div>

</template>

<script>

import stepEditorPathTypeOptions from './path-type-options.vue';
import stepEditorNewPathEventAction from 'StepBundle/components/step-editor/path-event-actions/new-path-event-action.vue';
import stepEditorPathEventActions from 'StepBundle/components/step-editor/path-event-actions/path-event-actions.vue';

export default {

  computed: {
    paths: function () {
      return this.$store.getters.getPaths;
    }
  },

  components: {

    'path-type-options': stepEditorPathTypeOptions,
    'new-path-event-action': stepEditorNewPathEventAction,
    'path-event-actions': stepEditorPathEventActions
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
