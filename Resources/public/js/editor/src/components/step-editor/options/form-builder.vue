<template>

  <div class="form-group">
    <label :for="name">worker</label>
    <multiselect
      class="multiselect-form-control"
      v-model="selectedWorker"
      :options="workers"
      label="name"
      key="name"
      selectLabel=""
      placeholder="Select a worker"
    >
    </multiselect>
    <div v-if="selectedWorker.name === 'extra_form_builder'" class="extra-form-builder-container">
      <button :id="id" class="extra-btn" @click.prevent="triggerModal">
        Extra form builder
      </button>
      <div class="modal fade modal-fullscreen" :id="'modal_' + id">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
              <h4 class="modal-title">Advanced visual mode</h4>
            </div>
            <div class="modal-body">
              <form-editor-advanced :fields="fields"></form-editor-advanced>
            </div>
            <div class="modal-footer">
              <button class="btn btn-default close-modal">
                Go back to the step editor <i class="fa fa-times"></i>
              </button>
              <em>All your changes are automatically saved</em>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div v-if="selectedWorker.name === 'form_builder'" class="extra-form-builder-container">
      <textarea v-model="raw"></textarea>
      <button class="extra-btn" @click.prevent="setExtraFormRaw">
        Validate modifications
      </button>
    </div>
  </div>

</template>

<script>

import $                    from 'jquery';
import httpMixin            from 'ExtraFormBundle/mixins/http.vue';
import rawMixin             from 'ExtraFormBundle/mixins/raw.vue';
import { generateUniqueId } from 'ExtraFormBundle/utils/utils.js';

export default {

  props: ['option', 'value', 'name'],

  data: function () {
    return {
      workers: [
        { name: 'extra_form_builder' },
        { name: 'form_builder' }
      ],
      selectedWorker: {
        name: 'extra_form_builder'
      },
      fields: [],
      raw: {}
    };
  },

  computed: {
    id: function () {
      return generateUniqueId();
    }
  },

  mixins: [httpMixin, rawMixin],

  created: function () {
    this.getExtraFormTypeOptions('extra_form_builder');

    if ('undefined' !== typeof this.value) {
      this.selectedWorker = { name: this.value.worker };
      if ('extra_form_builder' === this.value.worker) {
        this.fields = this.createFieldsRecursively(this.value.parameters.configuration);
      } else {
        this.raw = this.value.parameters.configuration;
      }
    }
  },

  watch: {
    fields: {
      handler: function (newFields) {
        this.$emit('changed', {
          name: this.name,
          value: {
            worker: this.selectedWorker.name,
            parameters: {
              configuration: this.createExtraFormRawRecursively(newFields)
            }
          }
        });
      },
      deep: true
    }
  },

  methods: {

    /**
     * Trigger a modal
     *
     * @param event
     */
    triggerModal: function (event) {
      var $button = $(event.target);
      var $modal = $button
        .siblings('#modal_' + event.target.id)
        .first();

      $modal.modal('show');
      $modal.find('.modal-header .close').on('click', function (e) {
        e.preventDefault();
        $(this)
          .closest('.modal')
          .modal('hide')
        ;
      });
    },

    /**
     * Get the form type options
     *
     * @param type
     */
    getExtraFormTypeOptions: function (type) {
      var url = this.$store.getters.getExtraFormTypeOptionsApiUrl(type);
      var self = this;

      this.handleGetRequest(url, function (options) {
        self.options = options;
      });
    },

    /**
     * Generate fields
     */
    setExtraFormRaw: function () {
      var raw = JSON.parse(this.raw);

      this.raw = JSON.stringify(raw, null, 2);
      this.$emit('changed', {
        name: this.name,
        value: {
          worker: this.selectedWorker.name,
          parameters: {
            configuration: raw
          }
        }
      });
    }
  }

};

</script>
