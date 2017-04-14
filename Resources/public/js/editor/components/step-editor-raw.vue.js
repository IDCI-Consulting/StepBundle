/* global Vue */
Vue.component('step-editor-raw', {

  template:
    '<div class="extra-step-editor raw-mode">' +
      '<textarea ' +
        'v-model="raw" ' +
        'style="width: 100%; height: 300px;"' +
      '>' +
      '</textarea>' +
      '<div class="json-errors"></div>' +
      '<button @click.prevent="generateMap">' +
        'Generate the diagram of the map from the json' +
      '</button>' +
    '</div>',

  data: function () {
    return {
      raw: '',
      textarea: this.$store.state.formProperties
    };
  },

  created: function () {
    // If the textarea is empty, do not attempt to generate fields
    if (this.textarea.value !== '') {
      this.raw = this.textarea.value;
      this.generateMap();
    }
  },

  /* global rawMixin, rawModalMixin */
  mixins: [rawMixin, rawModalMixin],

  computed: {
    map: function () {
      return this.$store.getters.getMap;
    }
  },

  watch: {
    map: {
      handler: function (map) {
        this.raw = this.generateRaw(map);
      },
      deep: true
    }
  },

  methods: {

    /**
     * Generate the map from the json
     */
    generateMap: function (event) {
      try {
        // Avoid mutating the raw from the state
        var clonedRaw = JSON.parse(JSON.stringify(this.raw));

        /* global jsonifyTwigStrings */
        var newMap = JSON.parse(jsonifyTwigStrings(clonedRaw));

        // Set the first step as active
        var firstStep = Object.keys(newMap.steps)[0];

        newMap.steps[firstStep].active = true;
        this.$store.commit('setMap', newMap);
        this.closeModal(event);

      // Json parsing error
      } catch (error) {
        this.displayJsonParseErrors(event, error);
      }
    },

    /**
     * Generate the raw json
     *
     * @param map
     *
     * @returns {*}
     */
    generateRaw: function (map) {
      // Avoid mutating the map from the state
      var clonedMap = JSON.parse(JSON.stringify(map));

      for (var step in clonedMap.steps) {
        if (clonedMap.steps.hasOwnProperty(step)) {
          this.formatOptions(clonedMap.steps[step].options);
          this.formatEventsParameters(clonedMap.steps[step].options.events);
          delete clonedMap.steps[step].active;
        }
      }

      for (var i = 0, len = clonedMap.paths.length; i < len; i++) {
        this.formatOptions(clonedMap.paths[i].options);
        this.formatEventsParameters(clonedMap.paths[i].options.events);
        delete clonedMap.paths[i].active;
      }

      /* global twigifyJsonString */
      return twigifyJsonString(JSON.stringify(clonedMap, null, 4));
    },

    /**
     * Format the event parameters
     *
     * @param events
     */
    formatEventsParameters: function (events) {
      if (typeof events !== 'undefined') {
        for (var formEvent in events) {
          if (events.hasOwnProperty(formEvent)) {
            for (var j = 0; j < events[formEvent].length; j++) {
              if (typeof events[formEvent][j].parameters !== 'undefined') {
                events[formEvent][j].parameters = this.formatOptions(events[formEvent][j].parameters);
              }
            }
          }
        }
      }
    }

  }

});
