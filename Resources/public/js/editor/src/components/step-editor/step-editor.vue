<template>

  <div class="row advanced-mode">
    <div class="extra-step-actions col-xs-12">
      <map-configuration-button></map-configuration-button>
      <new-step-type></new-step-type>
      <new-path-type></new-path-type>
    </div>
    <div class="extra-step-configuration col-md-4">
      <step-types-configuration></step-types-configuration>
      <path-types-configuration></path-types-configuration>
      <map-configuration></map-configuration>
    </div>
    <div class="extra-step-map col-md-8">
      <diagram :map="map"></diagram>
    </div>
  </div>

</template>

<script>

import Vue from 'vue';
import stepEditorNewStepType from './step-types/new-step-type.vue';
import stepEditorNewPathType from './path-types/new-path-type.vue';
import stepEditorStepTypesConfiguration from './step-types/step-types-configuration.vue';
import stepEditorPathTypesConfiguration from './path-types/path-types-configuration.vue';
import stepEditorMapConfiguration from './map/map-configuration.vue';
import stepEditorMapConfigurationButton from './map/map-configuration-button.vue';
import stepEditorDiagram from './diagram.vue';

export default {

  computed: {
    map: function () {
      return this.$store.getters.getMap;
    }
  },

  components: {
    'new-step-type': stepEditorNewStepType,
    'new-path-type': stepEditorNewPathType,
    'step-types-configuration': stepEditorStepTypesConfiguration,
    'path-types-configuration': stepEditorPathTypesConfiguration,
    'map-configuration': stepEditorMapConfiguration,
    'map-configuration-button': stepEditorMapConfigurationButton,
    'diagram': stepEditorDiagram
  }

};

</script>

<style>

.extra-step-editor .joint-paper > svg {
    border: 1px solid #ccc;
    border-radius: 4px;
    box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
}

.extra-step-editor .collapsed-block.form-type-not-configured {
    background-color: #d43f3a;
    border: 1px solid #d43f3a;
}

.extra-step-editor .collapsed-block.form-type-not-configured .fa-icon {
    float: right;
    font-size: 20px;
}

.extra-step-editor .extra-step-configuration,
.extra-step-editor .extra-step-map  {
    max-height: 80vh;
    min-height: 60vh;
    overflow: auto;
}

.extra-step-editor .extra-step-configuration h3.heading-configuration {
    border-bottom: 2px solid #514D61;
    color: #514D61;
    margin-top: 0;
    margin-bottom: 20px;
}

.extra-step-editor .extra-step-configuration h4.heading-configuration {
    background-color: #514D61;
    color: #FFF;
    padding: 10px 15px;
    border-radius: 0;
}

.extra-step-editor .extra-step-actions,
.extra-step-editor .extra-step-actions .vue-modal-container .vue-modal-footer {
    text-align: center;
}

.extra-step-editor .extra-step-actions .vue-modal-container {
    text-align: initial;
}

.extra-step-editor .extra-step-actions button.extra-btn {
    display: inline-block;
    margin: 0 15px 15px 0;
}

.extra-step-editor .conditional-destination.form-group > select,
.extra-step-editor .conditional-destination.form-group > div {
    width: 65%;
    margin-left: 35%;
}

.extra-step-editor .conditional-destination.form-group > div {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    justify-content: space-between;
    margin-top: 1px;
}

.extra-step-editor .conditional-destination.form-group  button {
    margin-left: 1px;
}

.extra-step-editor .error {
    padding-bottom: 20px;
    color: #d43f3a;
}

.extra-step-editor .error i {
    float: right;
}

.extra-step-editor .nav-pills {
    margin: 0 0 10px 0;
}

.extra-step-editor .new-path-event-action {
    margin-bottom: 10px;
}

.extra-step-editor .new-path-event-action > .multiselect,
.extra-step-editor .new-step-event-action > .multiselect {
    display: inline-block;
    margin-right: 10px;
    width: 40%;
}

.extra-step-editor .new-path-event-action label,
.extra-step-editor .new-step-event-action label {
    font-weight: bold;
    margin-top: 10px;
}

.extra-step-editor .new-path-event-action button,
.extra-step-editor .new-step-event-action button {
    display: inline-block;
    min-width: inherit;
    padding: 8px;
}

.extra-step-editor .collapsed-block > div > .options {
    margin-top: 15px;
}

.extra-step-editor .extra-form-builder-container {
    width: 65%;
    float: right;
}

.extra-step-editor .extra-form-builder-container > textarea {
    height: 150px;
    margin-top: 10px;
}

.extra-step-editor .extra-form-builder-container > textarea,
.extra-step-editor .extra-form-builder-container > button {
    width: 100%;
}

</style>
