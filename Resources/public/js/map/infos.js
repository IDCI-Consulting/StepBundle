'use strict';

define(
    [
        'jquery-private'
    ],
    function($, Infos) {
        /*
         * INFOS osed to return the DOM construction to display a JSON object
         */
        function Infos(obj) {
            this.PADDING = 20;
            this.FONT_FAMILY = 'Arial';
            this.FONT_SIZE = '0.9em';
            this.INDENT_SIZE = 20;
            
            this.indentLevel = 0;
            this.obj = obj;
            this.DOM = $('<div></div>');
            
            this.initDOM();
        }
        
        /*
         * GET the DOM to display object
         */
        Infos.prototype.getDOM = function() {
            return this.DOM;
        };
        
        /*
         * PARSE object to create the DOM
         */
        Infos.prototype.parseObj = function(obj, struct) {
            for (var key in obj) {
                if (typeof (obj[key]) === 'object') {
                    var subStruct = $('<div></div>');
                    var pKey = $('<p></p>');
                    var sKey = $('<span></span>').text('"' + key + '"');
                    var sStartObj = $('<span></span>').text(': {');
                    var pCloseObj = $('<p></p>').text('},');

                    pKey.css('margin', 0);
                    pKey.css('font-size', this.FONT_SIZE);
                    pKey.css('padding-left', (this.indentLevel * this.INDENT_SIZE) + 'px');
                    
                    sKey.css('color', 'rgb(4, 97, 201)');
                    sKey.css('font-weight', 'bold');

                    pCloseObj.css('margin', 0);
                    pCloseObj.css('font-size', this.FONT_SIZE);
                    pCloseObj.css('padding-left', (this.indentLevel * this.INDENT_SIZE) + 'px');

                    pKey.append(sKey, sStartObj);
                    subStruct.append(pKey);
                    this.indentLevel++;
                    this.parseObj(obj[key], subStruct);
                    subStruct.append(pCloseObj);
                    this.indentLevel--;

                    struct.append(subStruct);
                } else {
                    var pKeyValue = $('<p></p>');
                    var sKey = $('<span></span>').text('"' + key + '"');
                    var sValue = $('<span></span>').text(': "' + obj[key] + '", ');

                    pKeyValue.css('margin', 0);
                    pKeyValue.css('font-size', this.FONT_SIZE);
                    pKeyValue.css('padding-left', (this.indentLevel * this.INDENT_SIZE) + 'px');

                    sKey.css('color', 'rgb(4, 97, 201)');
                    sKey.css('font-weight', 'bold');

                    sValue.css('margin', 0);
                    sValue.css('font-size', this.FONT_SIZE);

                    pKeyValue.append(sKey, sValue);

                    struct.append(pKeyValue);
                }
            }
        };

        /*
         * CREATE the DOM
         */
        Infos.prototype.initDOM = function() {
            this.DOM.css('margin', 0);
            this.DOM.css('padding', 0);
            this.DOM.css('font-family', this.FONT_FAMILY);

            this.parseObj(this.obj, this.DOM);
        };
        
        return Infos;
    });