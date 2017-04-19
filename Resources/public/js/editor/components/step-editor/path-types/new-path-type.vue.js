/* exported stepEditorNewPathType */

var stepEditorNewPathType = {

  template:
    '<span>' +
      '<button class="extra-btn" @click.prevent="openModal">' +
        '<i class="fa fa-arrow-right fa-icon" aria-hidden="true"></i>' +
        'New path' +
      '</button>' +
      '<modal v-if="modal.show">' +
        '<h3 slot="header">' +
          'Add a path' +
          '<button @click="closeModal" type="button" class="close" aria-label="Close">&times;</button>' +
        '</h3>' +
        '<div slot="body">' +
          '<div class="error" v-if="errorMessage !== \'\'">' +
            '{{ errorMessage }}' +
            '<i class="fa fa-exclamation-circle"></i>' +
          '</div>' +
          '<div class="form-group">' +
            '<label>path type</label>' +
            '<select class="form-control" v-model="newPath.type">' +
              '<option v-for="type in pathTypes" :value="type.name">' +
                '{{ type.name }}' +
              '</option>' +
            '</select>' +
          '</div>' +
          '<path-type-required-options ' +
            '@updateOptions="updateRequiredOptions" ' +
            ':type="newPath.type"' +
            ':key="newPath.type" ' +
          '>' +
          '</path-type-required-options>' +
        '</div>' +
        '<div slot="footer">' +
          '<button @click.prevent="createPath" type="button" class="extra-btn" aria-label="Create">Create</button>' +
        '</div>' +
      '</modal>' +
    '</span>',

  data: function () {
    return {
      modal: {
        show: false
      },
      newPath: {},
      errorMessage: ''
    };
  },

  components: {

    /* global stepEditorPathTypeRequiredOptions */
    'path-type-required-options': stepEditorPathTypeRequiredOptions
  },

  computed: {
    pathTypes: function () {
      return this.$store.getters.getPathTypes;
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
     * Update a required option
     *
     * @param options
     */
    updateRequiredOptions: function (options) {
      this.newPath.options = options;
    },

    /**
     * Create a path in the map
     */
    createPath: function () {
      try {
        this.$store.commit('addPath', this.newPath);
        this.closeModal();
      } catch (error) {
        this.errorMessage = error.message;
      }
    },

    /**
     * Reset the modal values
     */
    resetModal: function () {
      this.newPath = {
        type: this.pathTypes[0].name,
        options: {}
      };
      this.errorMessage = '';
    }

  }

};
