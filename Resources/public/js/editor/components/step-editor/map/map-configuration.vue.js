/* exported stepEditorMapConfiguration */

var stepEditorMapConfiguration = {

  template:
  '<div v-if="map.active">' +
    '<h3 class="heading-configuration">Map configuration</h3>' +
    '<div class="form-group">' +
      '<label>name</label>' +
      '<input class="form-control" v-model="map.name" type="text"/>' +
    '</div>' +
    '<h4 class="heading-configuration">Map options</h4>' +
    '<map-options :map="map"></map-options>' +
  '</div>',

  computed: {
    map: function () {
      return this.$store.getters.getMap;
    }
  },

  components: {
    /* global stepEditorMapOptions */
    'map-options': stepEditorMapOptions
  }

};
