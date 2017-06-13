/* global Vue */
Vue.component('step-editor-raw', {

  template:
    '<div class="extra-step-editor raw-mode">' +
      '<textarea v-model="raw" :id="id"></textarea>' +
      '<div class="json-errors">{{ error.message }}</div>' +
      '<button style="margin-right: 20px" @click.prevent="generateMap">' +
        'Generate the diagram of the map from the json' +
      '</button>' +
      '<button @click.prevent="saveRaw">' +
        'Save the content of the textarea (even if the json is not valid)' +
      '</button>' +
    '</div>',

  created: function () {
    // If the textarea is empty, do not attempt to generate fields
    if ('' === this.textarea.value) {
      this.$store.commit('setMap', { active: true });
    } else {
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
        this.updateInitialTextareaValue();
      },
      deep: true
    }
  },

  methods: {

    /**
     * Generate the map from the json
     */
    generateMap: function () {
      var self = this;

      /* global MapUtils */
      MapUtils.transformRawToJson(
        this.raw,
        function (map) {
          map.active = true;
          self.$store.commit('setMap', map);
          self.closeModal();
        },
        function (error, wrongJson) {
          self.handleJsonError(error, wrongJson);
        }
      );
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

      this.formatMapOptions(clonedMap);
      this.formatStepsOptions(clonedMap.steps);
      this.formatPathsOptions(clonedMap.paths);

      /* global JsonToTwigTransformer */
      var raw = JsonToTwigTransformer.toRaw(JSON.stringify(clonedMap, null, 4));

      if (map.options.autoescape_false) {
        return '{% autoescape false %}' + raw + '{% endautoescape %}';
      }

      return raw;
    },

    /**
     * Format the option of the map
     *
     * @param {*} map
     */
    formatMapOptions: function (map) {
      delete map.active;
      delete map.options.autoescape_false;
      this.formatOptions(map.options);
    },

    /**
     * Format the steps of the map
     *
     * @param {*} steps
     */
    formatStepsOptions: function (steps) {
      for (var step in steps) {
        if (steps.hasOwnProperty(step)) {
          this.formatOptions(steps[step].options);
          this.formatEventsParameters(steps[step].options.events);
          delete steps[step].active;
        }
      }
    },

    /**
     * Format the paths of the map
     *
     * @param {[]} paths
     */
    formatPathsOptions: function (paths) {
      for (var i = 0, len = paths.length; i < len; i++) {
        this.formatOptions(paths[i].options);
        this.formatEventsParameters(paths[i].options.events);
        delete paths[i].active;
      }
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
    },

    /**
     * Get the css selector of the modal containing the raw
     *
     * @returns {string}
     */
    getModalSelector: function () {
      return '#' + this.$store.state.configuration.componentId + ' .extra-step-raw-mode-modal';
    }

  }

});
