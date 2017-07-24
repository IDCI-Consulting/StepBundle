import $            from 'jquery';
import Diagram      from 'StepBundle/diagram/Diagram.js';
import DiagramUtils from 'StepBundle/diagram/DiagramUtils.js';
import { generateUniqueId } from 'ExtraFormBundle/utils/utils.js';

var stepEditorDiagram = {

  template:
    '<div>' +
      '<div class="joint-paper" :id="id"></div>' +
    '</div>',

  props: ['map'],

  data: function () {
    return {
      diagram: null,
      id: 'paper' + generateUniqueId()
    };
  },

  mounted: function () {
    var self = this;

    $(this.getModalSelector()).on('shown.bs.modal', function () {
      self.generateDiagram(self.map);
    });
  },

  watch: {
    map: {
      handler: function (map) {
        this.generateDiagram(map);
      },
      deep: true
    }
  },

  methods: {

    /**
     * Get the css selector of the modal containing the diagram
     *
     * @returns {string}
     */
    getModalSelector: function () {
      return '#' + this.$store.state.configuration.componentId + ' .extra-step-advanced-visual-mode-modal';
    },

    /**
     * Generate the jointjs diagram from the map
     *
     * @param map
     */
    generateDiagram: function (map) {

      var self = this;
      var clonedMap = JSON.parse(JSON.stringify(map));

      // Do not generate the map if no data changed
      if (null !== this.diagram && JSON.stringify(this.diagram.getMap()) === JSON.stringify(clonedMap)) {
        return;
      }

      // Do not generate the map if the modal is closed
      var $modal = $(this.getModalSelector());
      var modalIsOpen = ($modal.data('bs.modal') || {}).isShown;

      if ($modal.length > 0 && !modalIsOpen) {
        $(self.getModalSelector()).on('shown.bs.modal', function () {
          self.generateDiagram(map);
        });

        return;
      }

      var diagramElement = document.getElementById(this.id);

      this.diagram = new Diagram(diagramElement, clonedMap);

      /**
       * Update cell positions on cell drop
       */
      this.diagram.onCellDrop(function (mutation, payload) {
        self.$store.commit(mutation, payload);
      });

      /**
       * Set a path active on click on a path link if it is not already active and if the vertices are not being changed
       */
      this.diagram.onPathLinkClick(function (pathLink, index) {
        if (!self.diagram.pathIsActive(pathLink)) {
          self.$store.commit('setActivePath', index);
        }
      });

      /**
       * Set a step active on click if it is not already active
       */
      this.diagram.onCellClick('step', function (stepCell, stepName) {
        if (!self.diagram.stepIsActive(stepCell)) {
          self.$store.commit('setActiveStep', stepName);
        }
      });

      /**
       * Set a path active on click on its intersection node
       */
      this.diagram.onCellClick('intersection', function (intersectionCell, pathIndex) {
        if (!self.diagram.pathIsActive(intersectionCell)) {
          self.$store.commit('setActivePath', pathIndex);
        }
      });

      /**
       * Set a path active on click on its end node
       */
      this.diagram.onCellClick('end', function (endCell, pathIndex) {
        if (!self.diagram.pathIsActive(endCell)) {
          self.$store.commit('setActivePath', pathIndex);
        }
      });

      /**
       * Update vertices position on changed
       */
      this.diagram.onChangedVertices(function (payload) {
        self.$store.commit('setPathVertices', payload);
      });

      /**
       * Remove a step
       *
       * Associated paths are removed thanks to the mutation
       */
      this.diagram.onDeleteStepClick(function (cell) {
        if (DiagramUtils.isAStep(cell)) {
          self.$store.commit('removeStep', DiagramUtils.getStepCellName(cell));
        }
      });

      /**
       * Remove a path
       */
      this.diagram.onDeletePathClick(function (cell) {
        self.$store.commit('removePath', cell.index);
      });

    }
  }

};

export default stepEditorDiagram;
