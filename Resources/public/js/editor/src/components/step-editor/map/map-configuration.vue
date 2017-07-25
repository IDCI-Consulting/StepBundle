<template>

  <div v-if="map.active">
    <h3 class="heading-configuration">Map configuration</h3>
    <div class="form-group">
      <label>name</label>
      <input class="form-control" v-model="map.name" type="text"/>
    </div>
    <h4 class="heading-configuration">Map options</h4>
    <map-options :map="map"></map-options>
  </div>

</template>

<script>

import stepEditorMapOptions from 'StepBundle/components/step-editor/map/map-options.vue';

export default {

  computed: {
    map: function () {
      return this.$store.getters.getMap;
    }
  },

  components: {

    'map-options': stepEditorMapOptions
  }

};

</script>
