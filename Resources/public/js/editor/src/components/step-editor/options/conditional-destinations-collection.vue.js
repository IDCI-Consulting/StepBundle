var conditionalDestinationsCollectionOption = {

  template:
    '<div class="form-group">' +
      '<label :for="name">{{ name }}</label>' +
      // Only allow the add button when creating a new path, else we can only update
      '<button v-if="pathCreation" @click.prevent="addDestination">Add</button>' +
      '<div class="conditional-destination form-group" v-for="(destination, index) in destinations">' +
        '<select class="form-control" ' +
          'v-model="destination.step" ' +
          ':required="option.options.required" ' +
          ':name="name" ' +
          '@change="updateDestinationStep(index, $event.target.value)" ' +
        '>' +
          '<option :value="key" v-for="(choice, key) in option.options.choices">{{ choice }}</option>' +
        '</select>' +
        '<div>' +
          '<input class="form-control" ' +
            'type="text" ' +
            'v-model="destination.condition" ' +
            '@input="updateDestinationCondition(index, $event.target.value)" ' +
          '>' +
          '<button v-if="pathCreation" @click.prevent="removeDestination(index)">x</button>' +
        '</div>' +
      '</div>' +
    '</div>',

  props: ['option', 'name', 'value'],

  data: function () {
    return {
      destinations: [],
      pathCreation: false
    };
  },

  /**
   * Update the value on component creation
   */
  created: function () {
    if ('undefined' === typeof this.value) {
      this.pathCreation = true;
    } else {
      this.destinations = this.convertDestinationObjectToArray(this.value);
    }
  },

  watch: {
    destinations: {
      handler: function (destinations) {
        this.$emit('changed', {
          name: this.name,
          value: this.convertDestinationArrayToObject(destinations)
        });
      },
      deep: true
    }
  },

  methods: {

    /**
     * Add a destination
     */
    addDestination: function () {
      this.destinations.push({
        step: '',
        condition: ''
      });
    },

    /**
     * Remove a destination
     */
    removeDestination: function (index) {
      this.destinations.splice(index, 1);
    },

    /**
     * Update the condition of the destination
     *
     * @param index
     * @param value
     */
    updateDestinationCondition: function (index, value) {
      this.destinations[index].condition = value;
    },

    /**
     * Update the destination step
     *
     * @param index
     * @param value
     */
    updateDestinationStep: function (index, value) {
      this.destinations[index].step = value;
    },

    convertDestinationObjectToArray: function (object) {
      var array = [];

      for (var key in object) {
        if (object.hasOwnProperty(key)) {
          array.push({
            step: key,
            condition: object[key]
          });
        }
      }

      return array;
    },

    convertDestinationArrayToObject: function (array) {
      var object = {};

      for (var i = 0, len = array.length; i < len; i++) {
        object[array[i].step] = array[i].condition;
      }

      return object;
    }
  }

};

export default conditionalDestinationsCollectionOption;
