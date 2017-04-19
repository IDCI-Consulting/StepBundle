/* exported stepEditorMapConfigurationButton */

var stepEditorMapConfigurationButton = {

  template:
    '<span>' +
      '<button type="button" class="extra-btn" @click.prevent="showConfiguration">' +
        '<i class="fa fa-cogs fa-icon" aria-hidden="true"></i>' +
        'Map configuration' +
      '</button>' +
    '<span>',

  methods: {

    /**
     * Set the map as active to show the map configuration panel
     */
    showConfiguration: function () {
      this.$store.commit('setActiveMap');
    }

  }

};
