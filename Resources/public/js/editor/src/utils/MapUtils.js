import JsonToTwigTransformer from 'ExtraFormBundle/utils/JsonToTwigTransformer.js';

var MapUtils = {

  /**
   * Transform the raw map in json
   *
   * @param {string} rawMap
   * @param {function} successCallback
   * @param {function} errorCallback
   */
  transformRawToJson: function (rawMap, successCallback, errorCallback) {
    var raw = JSON.parse(JSON.stringify(rawMap));
    var strippedRaw = MapUtils.stripAutoescapeFalse(MapUtils.stripAutoescape(raw));
    var autoescapeFalseOptionValue = raw !== strippedRaw;
    var transformedRaw = JsonToTwigTransformer.toJson(strippedRaw);

    try {
      var map = JSON.parse(transformedRaw);

      MapUtils.setAutoescapeFalseOption(map, autoescapeFalseOptionValue);
      successCallback(map);
    } catch (error) {
      errorCallback(error, transformedRaw);
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
      0 === raw.indexOf(startAutoescapeString) &&
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
      0 === raw.indexOf(startAutoescapeString) &&
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
  },

  /**
   * Check if the map has unpositioned cells.
   *
   * @param map
   *
   * @returns boolean
   */
  hasUnpositionedCells: function (map) {
    if (map.paths.length !== map.graphPositions.paths.length) {
      return true
    }

    if (Object.keys(map.steps).length !== Object.keys(map.graphPositions.steps).length) {
      return true;
    }

    return false;
  }
};

export default MapUtils;
