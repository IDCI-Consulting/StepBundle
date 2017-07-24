var stepEditorNewStepEventAction = {

  template:
    '<div>' +
      '<multiselect ' +
        'v-model="selectedFormEvent" ' +
        ':options="formEvents" ' +
        'label="name" ' +
        'key="name" ' +
        'selectLabel="" ' +
        'placeholder="Select a form event">' +
      '</multiselect>' +
      '<multiselect ' +
        'v-model="selectedStepEventActionType" ' +
        ':options="stepEventActionTypes" ' +
        'label="name" ' +
        'key="name" ' +
        'selectLabel="" ' +
        'placeholder="Select a step event">' +
      '</multiselect>' +
      '<button @click.prevent="createStepEventAction" type="button" class="extra-btn" aria-label="Create">' +
        'Add' +
      '</button>' +
    '</div>',

  props: ['name'],

  data: function () {
    return {
      selectedStepEventActionType: null,
      selectedFormEvent: null,
      errorMessage: '',
      modal: {
        show: false
      }
    };
  },

  computed: {
    stepEventActionTypes: function () {
      var actionTypes = this.$store.getters.getStepEventActionTypes;

      this.selectedStepEventActionType = actionTypes[0];

      return actionTypes;
    },
    formEvents: function () {
      var formEvents = this.$store.getters.getFormEvents;

      this.selectedFormEvent = formEvents[0];

      return formEvents;
    }
  },

  methods: {
    createStepEventAction: function () {
      try {
        var payload = {
          stepName: this.name,
          action: this.selectedStepEventActionType,
          formEvent: this.selectedFormEvent
        };

        this.$store.commit('addStepEventAction', payload);
        this.closeModal();
      } catch (error) {
        this.errorMessage = error.message;
      }
    }
  }

};

export default stepEditorNewStepEventAction;
