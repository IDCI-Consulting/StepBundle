/******/ (function(modules) { // webpackBootstrap
/******/ 	// install a JSONP callback for chunk loading
/******/ 	var parentJsonpFunction = window["webpackJsonp"];
/******/ 	window["webpackJsonp"] = function webpackJsonpCallback(chunkIds, moreModules, executeModules) {
/******/ 		// add "moreModules" to the modules object,
/******/ 		// then flag all "chunkIds" as loaded and fire callback
/******/ 		var moduleId, chunkId, i = 0, resolves = [], result;
/******/ 		for(;i < chunkIds.length; i++) {
/******/ 			chunkId = chunkIds[i];
/******/ 			if(installedChunks[chunkId]) {
/******/ 				resolves.push(installedChunks[chunkId][0]);
/******/ 			}
/******/ 			installedChunks[chunkId] = 0;
/******/ 		}
/******/ 		for(moduleId in moreModules) {
/******/ 			if(Object.prototype.hasOwnProperty.call(moreModules, moduleId)) {
/******/ 				modules[moduleId] = moreModules[moduleId];
/******/ 			}
/******/ 		}
/******/ 		if(parentJsonpFunction) parentJsonpFunction(chunkIds, moreModules, executeModules);
/******/ 		while(resolves.length) {
/******/ 			resolves.shift()();
/******/ 		}
/******/
/******/ 	};
/******/
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// objects to store loaded and loading chunks
/******/ 	var installedChunks = {
/******/ 		1: 0
/******/ 	};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/ 	// This file contains only the entry chunk.
/******/ 	// The chunk loading function for additional chunks
/******/ 	__webpack_require__.e = function requireEnsure(chunkId) {
/******/ 		var installedChunkData = installedChunks[chunkId];
/******/ 		if(installedChunkData === 0) {
/******/ 			return new Promise(function(resolve) { resolve(); });
/******/ 		}
/******/
/******/ 		// a Promise means "currently loading".
/******/ 		if(installedChunkData) {
/******/ 			return installedChunkData[2];
/******/ 		}
/******/
/******/ 		// setup Promise in chunk cache
/******/ 		var promise = new Promise(function(resolve, reject) {
/******/ 			installedChunkData = installedChunks[chunkId] = [resolve, reject];
/******/ 		});
/******/ 		installedChunkData[2] = promise;
/******/
/******/ 		// start chunk loading
/******/ 		var head = document.getElementsByTagName('head')[0];
/******/ 		var script = document.createElement('script');
/******/ 		script.type = 'text/javascript';
/******/ 		script.charset = 'utf-8';
/******/ 		script.async = true;
/******/ 		script.timeout = 120000;
/******/
/******/ 		if (__webpack_require__.nc) {
/******/ 			script.setAttribute("nonce", __webpack_require__.nc);
/******/ 		}
/******/ 		script.src = __webpack_require__.p + "" + ({"0":"bootstrap-vue-step-editor"}[chunkId]||chunkId) + ".async.js";
/******/ 		var timeout = setTimeout(onScriptComplete, 120000);
/******/ 		script.onerror = script.onload = onScriptComplete;
/******/ 		function onScriptComplete() {
/******/ 			// avoid mem leaks in IE.
/******/ 			script.onerror = script.onload = null;
/******/ 			clearTimeout(timeout);
/******/ 			var chunk = installedChunks[chunkId];
/******/ 			if(chunk !== 0) {
/******/ 				if(chunk) {
/******/ 					chunk[1](new Error('Loading chunk ' + chunkId + ' failed.'));
/******/ 				}
/******/ 				installedChunks[chunkId] = undefined;
/******/ 			}
/******/ 		};
/******/ 		head.appendChild(script);
/******/
/******/ 		return promise;
/******/ 	};
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/bundles/idcistep/js/editor/dist/";
/******/
/******/ 	// on error function for async loading
/******/ 	__webpack_require__.oe = function(err) { console.error(err); throw err; };
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 2);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ (function(module, exports) {

module.exports = jQuery;

/***/ }),
/* 1 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "d", function() { return filterObject; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "g", function() { return sortObject; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "e", function() { return generateUniqueId; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "f", function() { return hashCode; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "c", function() { return createBootstrapModal; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return colorEmptyRequiredInputs; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "b", function() { return createAttributeMapObject; });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_jquery__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_jquery___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0_jquery__);



/**
 * Remove all lines breaks and "extra" spaces, when there are more than 1 spaces in a row
 *
 * @returns {string}
 */
String.prototype.removeLineBreaksAnsExtraSpaces = function () {
  return this
    // Replace line breaks by spaces
    .replace(/\r?\n|\r/g, ' ')
    // Replace 2 or more spaces by only one
    .replace(/ {2,}/g, ' ');
};

/**
 * Create and return an object which contains all elements for which the callback returns true
 *
 * @param {object} object
 * @param {function} callable
 *
 * @return {object}
 */
function filterObject (object, callable) {
  var filteredObject = {};

  for (var property in object) {
    if (object.hasOwnProperty(property)) {
      if (callable(object[property])) {
        filteredObject[property] = object[property];
      }
    }
  }

  return filteredObject;
}

/**
 * Sort an object by keys
 *
 *
 * @param {object} object
 * @param {[]} [firstKeys] : if the firstKeys param is set, set them at the beginning of the object
 * @param {boolean} [sortAll] : if false, only sort by first keys
 *
 * @returns {{}}
 */
function sortObject (object, firstKeys, sortAll) {
  var ordered = {};

  if ('undefined' === typeof sortAll) {
    sortAll = true;
  }

  if ('undefined' === typeof firstKeys) {
    firstKeys = [];
  }

  for (var i = 0, len = firstKeys.length; i < len; i++) {
    var k = firstKeys[i];

    ordered[k] = object[k];
  }

  if (sortAll) {
    Object
      .keys(object)
      .sort()
      .forEach(function (key) {
        if (-1 === firstKeys.indexOf(key)) {
          ordered[key] = object[key];
        }
      });
  } else {
    for (var key in object) {
      if (object.hasOwnProperty(key)) {
        if (-1 === firstKeys.indexOf(key)) {
          ordered[key] = object[key];
        }
      }
    }
  }

  return ordered;
}

/**
 * Generate a unique id for the fields default names
 *
 * @returns {string}
 */
function generateUniqueId () {
  return Math
    .random()
    .toString(36)
    .substr(2, 9)
    ;
}

/**
 * Hash a string to a 32 bit integer
 *
 * @param {string} string
 * @returns {string}
 */
function hashCode (string) {
  var hash = 0;

  if (0 === string.length) {
    return hash.toString();
  }

  for (var i = 0; i < string.length; i++) {
    var chr = string.charCodeAt(i);

    hash = (hash << 5) - hash + chr;
    // Convert to 32bit integer
    hash |= 0;
  }

  return hash.toString();
}

/**
 * Create a javascript object to get the map all the attribute of the given element, as well as the value
 *
 * @param element
 *
 * @return {object}
 */
function createAttributeMapObject (element) {
  var attributes = element.attributes;
  var object = {};

  for (var attribute, i = 0, length = attributes.length; i < length; i++) {
    attribute = attributes[i];
    object[attribute.nodeName] = attribute.nodeValue;
  }

  object.value = element.value;

  return object;
}

/**
 * Create the html for a bootstrap modal
 *
 * @param id
 * @param name
 * @param extraClasses
 * @param title
 * @param body
 * @param [modalFooter]
 *
 * @returns {string}
 */
function createBootstrapModal (id, name, extraClasses, title, body, modalFooter) {
  var footer = modalFooter ? modalFooter : '';

  return '' +
    '<div id="' + name + '-' + id + '" class="editor-modal modal fade ' + extraClasses + ' ' + name + '">' +
      '<div class="modal-dialog" role="document">' +
        '<div class="modal-content">' +
          '<div class="modal-header">' +
            '<button type="button" class="close" aria-label="Close">' +
              '<span aria-hidden="true">&times;</span>' +
            '</button>' +
            '<h4 class="modal-title">' + title + '</h4>' +
          '</div>' +
          '<div class="modal-body">' +
            body +
          '</div>' +
          '<div class="modal-footer">' +
            footer +
          '</div>' +
        '</div>' +
      '</div>' +
    '</div>'
    ;
}

/**
 * Add some colors on empty required inputs
 *
 * @param elementId - the if of the element wrapping the inputs
 * @param parentClass - the class of the parent of the required input, in case we don't want to select every inputs
 */
function colorEmptyRequiredInputs (elementId, parentClass) {
  var inputSelector = '.' + parentClass + ' input[required="required"]';

  /**
   * Color in red when empty, in white when filled
   *
   * @param $input
   */
  function color($input) {
    if ($input.val()) {
      $input.css({
        'border-color': '#cccccc',
        'background-color': '#ffffff'
      });
    } else {
      $input.css({
        'border-color': '#c9302c',
        'background-color': '#f3d9d9'
      });
    }
  }

  // Color on change when the input is empty
  __WEBPACK_IMPORTED_MODULE_0_jquery___default()(document).on('change', inputSelector, function () {
    color(__WEBPACK_IMPORTED_MODULE_0_jquery___default()(this));
  });

  // Color when new children are added to the dom
  // Sometimes they are added but already filled by vuejs, sometimes they are empty
  var target = document.getElementById(elementId);
  var config = {
    childList: true,
    characterData: true,
    subtree: true
  };

  var observer = new MutationObserver(function (mutations) {
    mutations.forEach(function (mutation) {
      var $inputs = [];

      if (__WEBPACK_IMPORTED_MODULE_0_jquery___default()(mutation.target).hasClass(parentClass)) {
        $inputs = __WEBPACK_IMPORTED_MODULE_0_jquery___default()(mutation.target).find('input[required="required"]');
      } else {
        $inputs = __WEBPACK_IMPORTED_MODULE_0_jquery___default()(mutation.target).find(inputSelector);
      }

      $inputs.each(function () {
        color(__WEBPACK_IMPORTED_MODULE_0_jquery___default()(this));
      });
    });
  });

  observer.observe(target, config);
}




/***/ }),
/* 2 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__load_step_editors_js__ = __webpack_require__(3);


Object(__WEBPACK_IMPORTED_MODULE_0__load_step_editors_js__["a" /* default */])();


/***/ }),
/* 3 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (immutable) */ __webpack_exports__["a"] = loadStepEditors;
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_ExtraFormBundle_utils_utils_js__ = __webpack_require__(1);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_jquery__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_jquery___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_1_jquery__);




function loadStepEditors () {

  /**
   * Create the editor for each textareas with the class step-editor
   */
  __WEBPACK_IMPORTED_MODULE_1_jquery___default()('textarea.step-editor').each(function (index) {

    var textarea = this;

    __webpack_require__.e/* import() */(0).then(__webpack_require__.bind(null, 4)).then(function (app) {
      var editorComponentId = 'extraStepEditorComponent' + index;

      // Do not load the editor if it was already loaded
      if (document.getElementById(editorComponentId)) {
        return;
      }

      // Retrieve the textarea attributes and value
      var formProperties = Object(__WEBPACK_IMPORTED_MODULE_0_ExtraFormBundle_utils_utils_js__["b" /* createAttributeMapObject */])(textarea);
      var configuration = window[formProperties['data-configuration-variable']];

      configuration.componentId = editorComponentId;
      var rawModal = createRawModal();
      var rawModalButton =
        '<button class="trigger-extra-step-raw-mode-modal-' + index + '">' +
          'Raw mode' +
        '</button>';
      var advancedModal = createAdvancedModal();
      var advancedModalButton =
        '<button class="trigger-extra-step-advanced-visual-mode-modal-' + index + '">' +
          'Visual mode' +
        '</button>';

      /**
       * Insert buttons in place of the textarea
       */
      __WEBPACK_IMPORTED_MODULE_1_jquery___default()(textarea).after(
        '<div class="modal-buttons">' +
           advancedModalButton + ' ' + rawModalButton +
        '</div>'
      );

      // Insert the modals editor at the end of the body
      var $body = __WEBPACK_IMPORTED_MODULE_1_jquery___default()('body');

      $body.append(
        '<div id="' + editorComponentId + '">' + rawModal + advancedModal + '</div>'
      );

      // Hide the initial textarea
      textarea.style.display = 'none';

      // Display / Hide the modals
      var modalTypes = [
        'extra-step-advanced-visual-mode-modal',
        'extra-step-raw-mode-modal'
      ];

      modalTypes.forEach(function (modalType) {
        showModalOnClick(modalType, index);
      });

      modalTypes.forEach(function (modalType) {
        hideModalOnClick(modalType);
      });

      app.triggerVueStepEditor('#' + editorComponentId, configuration, formProperties);

      Object(__WEBPACK_IMPORTED_MODULE_0_ExtraFormBundle_utils_utils_js__["a" /* colorEmptyRequiredInputs */])(editorComponentId, 'extra-form-inputs-required');

      /**
       * Show modal on click on trigger button
       *
       * @param modalType
       * @param modalIdentifier
       */
      function showModalOnClick (modalType, modalIdentifier) {
        __WEBPACK_IMPORTED_MODULE_1_jquery___default()(document).on('click', 'button.trigger-' + modalType + '-' + modalIdentifier, function (event) {
          event.preventDefault();
          var $modal = __WEBPACK_IMPORTED_MODULE_1_jquery___default()('#' + modalType + '-' + modalIdentifier);

          $modal.modal('show');
        });
      }

      /**
       * Hide modal on click on close button
       *
       * @param modalType
       */
      function hideModalOnClick (modalType) {
        var classes =
          // On the generate field button from the editor-raw
          '.' + modalType + ' .modal-body button.close-modal, ' +

            // On the upper right cross of the modal
          '.' + modalType + ' .modal-footer > button.close-modal, ' +

            // On the close button on the left bottom of the modal
          '.' + modalType + ' .modal-header > button.close';

        __WEBPACK_IMPORTED_MODULE_1_jquery___default()(document).on('click', classes, function (event) {
          event.preventDefault();
          __WEBPACK_IMPORTED_MODULE_1_jquery___default()(this)
            .closest('.modal')
            .modal('hide')
          ;
        });
      }

      /**
       * Create the raw modal
       *
       * @returns {string}
       */
      function createRawModal () {
        return Object(__WEBPACK_IMPORTED_MODULE_0_ExtraFormBundle_utils_utils_js__["c" /* createBootstrapModal */])(
          index,
          'extra-step-raw-mode-modal',
          'modal-fullscreen',
          'Editor in raw mode',
          '<div class="editor">' +
            '<step-editor-raw></step-editor-raw>' +
          '</div><br>'
        );
      }

      /**
       * Create the advanced modal
       *
       * @returns {string}
       */
      function createAdvancedModal () {
        return Object(__WEBPACK_IMPORTED_MODULE_0_ExtraFormBundle_utils_utils_js__["c" /* createBootstrapModal */])(
          index,
          'extra-step-advanced-visual-mode-modal',
          'modal-fullscreen',
          'Visual mode',
          '<div class="editor extra-step-editor">' +
            '<step-editor></step-editor>' +
          '</div>',
          '<em>All your changes are automatically saved</em>'
        );
      }

    });

  });

};


/***/ })
/******/ ]);