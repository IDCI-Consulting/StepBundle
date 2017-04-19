/* exported stepEditorPathTypesConfiguration */
var stepEditorPathTypesConfiguration = {

  template:
    '<div>' +
      '<div v-for="(path, index) in paths" v-if="path.active" :key="index">' +
        '<h3>Path configuration</h3>' +
        '<ul class="nav nav-pills" role="tablist">' +
          '<li role="presentation" class="active">' +
            '<a role="tab" data-toggle="tab" :href="anchor(\'#\', index, \'options\')">Options</a>' +
          '</li>' +
          '<li role="presentation">' +
            '<a role="tab" data-toggle="tab" :href="anchor(\'#\', index, \'events\')">Path events actions</a>' +
          '</li>' +
        '</ul>' +
        '<div class="tab-content">' +
          '<div role="tabpanel" class="tab-pane in active" :id="anchor(\'\', index, \'options\')">' +
            '<path-type-options :path="path" :index="index"></path-type-options>' +
          '</div>' +
          '<div role="tabpanel" class="tab-pane" :id="anchor(\'\', index, \'events\')">' +
            '<new-path-event-action class="new-path-event-action" :index="index"></new-path-event-action>' +
            '<path-event-actions :index="index"><path-event-actions/>' +
          '</div>' +
        '</div>' +
      '</div>' +
    '</div>',

  computed: {
    paths: function () {
      return this.$store.getters.getPaths;
    }
  },

  components: {

    /* global stepEditorPathTypeOptions */
    'path-type-options': stepEditorPathTypeOptions,

    /* global stepEditorNewPathEventAction */
    'new-path-event-action': stepEditorNewPathEventAction,

    /* global stepEditorPathEventActions */
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
