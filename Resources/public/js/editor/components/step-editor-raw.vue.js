/* global Vue */
Vue.component('step-editor-raw', {

  template:
    '<div class="extra-step-editor raw-mode">' +
      '<textarea ' +
        'v-model="raw" ' +
      '>' +
      '</textarea>' +
      '<div class="json-errors"></div>' +
      '<button style="margin-right: 20px" @click.prevent="generateMap">' +
        'Generate the diagram of the map from the json' +
      '</button>' +
      '<button @click.prevent="saveRaw">' +
        'Save the content of the textarea (even if the json is not valid)' +
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
    } else {
      this.$store.commit('setMap', { active: true });
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
     * Save the content of the raw textarea in the initial textarea
     */
    saveRaw: function (event) {
      this.updateInitialTextareaValue();
      this.closeModal(event);
    },

    /**
     * Generate the map from the json
     */
    generateMap: function (event) {
      try {
        // Avoid mutating the raw from the state (create a clone)
        var raw = JSON.parse(JSON.stringify(this.raw));
        raw = this.stripAutoescape(raw);
        var strippedRaw = this.stripAutoescapeFalse(raw);

        // If the raw was stripped, then the {% autoescape false %} is present
        var autoescapeFalseOptionValue = raw !== strippedRaw;

        /* global transformRawToJson */
        var newMap = JSON.parse(transformRawToJson(strippedRaw));
        this.setAutoescapeFalseOption(newMap, autoescapeFalseOptionValue);

        newMap.active = true;
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

      this.formatMapOptions(clonedMap);
      this.formatStepsOptions(clonedMap.steps);
      this.formatPathsOptions(clonedMap.paths);

      /* global transformJsonToRaw */
      var raw = transformJsonToRaw(JSON.stringify(clonedMap, null, 4));

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
     * Strip the twig autoescape false block if needed
     *
     * @param raw
     */
    stripAutoescapeFalse: function (raw) {
      var endAutoescapeString = '{% endautoescape %}';
      var startAutoescapeString = '{% autoescape false %}';
      var endAutoescapeStringPosition = raw.length - endAutoescapeString.length;
      if (
        raw.indexOf(startAutoescapeString) === 0 &&
        raw.indexOf(endAutoescapeString) === endAutoescapeStringPosition
      ) {
        return raw.substring(startAutoescapeString.length, endAutoescapeStringPosition);
      }

      return raw;
    },

    /**
     * Strip the twig autoescape block
     * This block is useless because autoescape true is the default value
     *
     * @param raw
     */
    stripAutoescape: function (raw) {
      var endAutoescapeString = '{% endautoescape %}';
      var startAutoescapeString = '{% autoescape %}';
      var endAutoescapeStringPosition = raw.length - endAutoescapeString.length;
      if (
        raw.indexOf(startAutoescapeString) === 0 &&
        raw.indexOf(endAutoescapeString) === endAutoescapeStringPosition
      ) {
        return raw.substring(startAutoescapeString.length, endAutoescapeStringPosition);
      }

      return raw;
    },

    /**
     * Set the value of the autoescape_false option
     *
     * @param map
     * @param autoescapeFalseValue
     */
    setAutoescapeFalseOption: function (map, autoescapeFalseValue) {
      if ('undefined' === typeof map.options) {
        map.options = {};
      }

      map.options.autoescape_false = autoescapeFalseValue;
    }

  }

});
