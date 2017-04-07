/* exported stepEditorNewPathEventAction */

var stepEditorNewPathEventAction = {

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
        'v-model="selectedPathEventActionType" ' +
        ':options="pathEventActionTypes" ' +
        'label="name" ' +
        'key="name" ' +
        'selectLabel="" ' +
        'placeholder="Select a path event">' +
      '</multiselect>' +
      '<button @click.prevent="createPathEventAction" type="button" class="extra-btn" aria-label="Create">' +
        'Add' +
      '</button>' +
    '</div>',

  props: ['index'],

  data: function () {
    return {
      selectedPathEventActionType: null,
      selectedFormEvent: null,
      errorMessage: '',
      modal: {
        show: false
      }
    };
  },

  computed: {
    pathEventActionTypes: function () {
      var actionTypes = this.$store.getters.getPathEventActionTypes;

      this.selectedPathEventActionType = actionTypes[0];

      return actionTypes;
    },
    formEvents: function () {
      var formEvents = this.$store.getters.getFormEvents;

      this.selectedFormEvent = formEvents[0];

      return formEvents;
    }
  },

  methods: {
    createPathEventAction: function () {
      try {
        var payload = {
          pathIndex: this.index,
          action: this.selectedPathEventActionType,
          formEvent: this.selectedFormEvent
        };

        this.$store.commit('addPathEventAction', payload);
        this.closeModal();
      } catch (error) {
        this.errorMessage = error.message;
      }
    }
  }

};
