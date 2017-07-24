import stepEditorPathEventActionConfiguration from './path-event-action-configuration.vue';

var stepEditorPathEventActions = {

  template:
    '<div>' +
      '<div v-for="formEvent in formEvents" v-if="formEventHasActions(formEvent)" :key="formEvent">' +
        '<strong>{{ formEvent.name }}</strong>' +
        '<path-event-action-configuration ' +
          ':key="action" ' +
          'v-for="(action, index) in getFormEventPathEventActions(formEvent)" ' +
          ':action="action.action" ' +
          ':name="action.name" ' +
          ':parameters="action.parameters" ' +
          '@remove="removePathEventAction(formEvent, index)" ' +
          '@updateName="updatePathEventActionName($event, formEvent, index)" ' +
          '@updateOption="updatePathEventActionOption($event, formEvent, index)" ' +
        '></path-event-action-configuration>' +
      '</div>' +
    '</div>',

  props: ['index'],

  computed: {
    pathEventActions: function () {
      return this.$store.getters.getPathEventActions(this.index);
    },
    formEvents: function () {
      return this.$store.getters.getFormEvents;
    }
  },

  components: {

    'path-event-action-configuration': stepEditorPathEventActionConfiguration
  },

  methods: {

    /**
     * Get the path events action triggered by a form event
     *
     * @param formEvent
     *
     * @return []
     */
    getFormEventPathEventActions: function (formEvent) {
      for (var pathEventAction in this.pathEventActions) {
        if (this.pathEventActions.hasOwnProperty(pathEventAction) && pathEventAction === formEvent.name) {
          return this.pathEventActions[pathEventAction];
        }
      }

      return null;
    },

    /**
     * Check if a form event contains path event actions
     *
     * @param formEvent
     */
    formEventHasActions: function (formEvent) {
      return this.pathEventActions && this.pathEventActions[formEvent.name];
    },

    /**
     * Remove a path event action
     *
     * @param formEvent
     * @param actionIndex
     */
    removePathEventAction: function (formEvent, actionIndex) {
      this.$store.commit('removePathEventAction', {
        pathIndex: this.index,
        formEventName: formEvent.name,
        actionIndex: actionIndex
      });
    },

    /**
     * Update the name of a path event action
     *
     * @param name
     * @param formEvent
     * @param actionIndex
     */
    updatePathEventActionName: function (name, formEvent, actionIndex) {
      this.$store.commit('updatePathEventActionName', {
        pathIndex: this.index,
        formEventName: formEvent.name,
        actionIndex: actionIndex,
        actionName: name
      });
    },

    /**
     * Update an option of a path event action
     *
     * @param option
     * @param formEvent
     * @param actionIndex
     */
    updatePathEventActionOption: function (option, formEvent, actionIndex) {
      this.$store.commit('updatePathEventActionOption', {
        pathIndex: this.index,
        formEventName: formEvent.name,
        actionIndex: actionIndex,
        option: option
      });
    }

  }
};

export default stepEditorPathEventActions;
