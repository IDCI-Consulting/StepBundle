/* global Vue */
Vue.component('step-editor-raw', {

  template:
    '<div class="extra-step-editor raw-mode">' +
      '<textarea ' +
        'v-model="raw" ' +
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
        this.updateInitialTextareaValue();
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
        // Avoid mutating the raw from the state (create a clone)
        var raw = JSON.parse(JSON.stringify(this.raw));
        var strippedRaw = this.stripAutoescape(raw);

        // If the raw was stripped, then the {% autoescape false %} is present
        var autoescapeFalseOptionValue = raw !== strippedRaw;

        /* global transformRawToJson */
        var newMap = JSON.parse(transformRawToJson(strippedRaw));
        this.setAutoescapeFalseOption(newMap, autoescapeFalseOptionValue);

        newMap.active = true;
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

      delete clonedMap.active;
      this.formatOptions(clonedMap.options);

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

      /* global transformJsonToRaw */
      var raw = transformJsonToRaw(JSON.stringify(clonedMap, null, 4));

      if (clonedMap.options.autoescape_false) {
        return '{% autoescape false %}' + raw + '{% endautoescape %}';
      }

      return raw;
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
     * Strip the twig autoescape block if needed
     *
     * @param raw
     */
    stripAutoescape: function (raw) {
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
