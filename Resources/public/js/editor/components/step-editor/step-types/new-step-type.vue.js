/* exported stepEditorNewStepType */

var stepEditorNewStepType = {

  template:
    '<span>' +
      '<button class="extra-btn" @click.prevent="openModal">New step</button>' +
      '<modal v-if="modal.show">' +
        '<h3 slot="header">' +
          'Add a step' +
          '<button @click="closeModal" type="button" class="close" aria-label="Close">&times;</button>' +
        '</h3>' +
        '<div slot="body">' +
          '<div class="error" v-if="errorMessage !== \'\'">' +
            '{{ errorMessage }}' +
            '<i class="fa fa-exclamation-circle"></i>' +
          '</div>' +
          '<div class="form-group">' +
            '<label>step type</label>' +
            '<select class="form-control" v-model="newStep.type">' +
              '<option v-for="type in stepTypes" :value="type.name">' +
                '{{ type.name }}' +
              '</option>' +
            '</select>' +
          '</div>' +
          '<div class="form-group">' +
            '<label>step name</label>' +
            '<input class="form-control" v-model="newStep.name" type="text">' +
          '</div>' +
        '</div>' +
        '<div slot="footer">' +
          '<button @click.prevent="createStep" type="button" class="extra-btn" aria-label="Create">Create</button>' +
        '</div>' +
      '</modal>' +
    '</span>',

  data: function () {
    return {
      modal: {
        show: false
      },
      newStep: {},
      errorMessage: ''
    };
  },

  computed: {
    stepTypes: function () {
      return this.$store.getters.getStepTypes;
    }
  },

  methods: {

    /**
     * Open the modal to add a step
     */
    openModal: function () {
      this.resetModal();
      this.modal.show = true;
    },

    /**
     * Close the modal add a step
     */
    closeModal: function () {
      this.modal.show = false;
    },

    /**
     * Create a step in the map
     */
    createStep: function () {
      try {
        this.$store.commit('addStep', this.newStep);
        this.closeModal();
      } catch (error) {
        this.errorMessage = error.message;
      }
    },

    /**
     * Reset the modal values
     */
    resetModal: function () {
      this.newStep = {
        type: this.stepTypes[0].name,
        name: ''
      };
      this.errorMessage = '';
    }

  }

};
