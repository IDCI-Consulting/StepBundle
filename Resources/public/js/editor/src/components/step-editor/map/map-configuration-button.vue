<template>

  <span>
    <button type="button" class="extra-btn" @click.prevent="showConfiguration">
      <i class="fa fa-cogs fa-icon" aria-hidden="true"></i>
      Map configuration
    </button>
  </span>

</template>

<script>

export default {

  methods: {
    /**
     * Set the map as active to show the map configuration panel
     */
    showConfiguration: function () {
      this.$store.commit('setActiveMap');
    }
  }

};

</script>
