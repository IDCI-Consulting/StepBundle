'use strict';

define(
    [
        'jquery-private',
        'infos'
    ],
    function($, Infos) {
        /*
         * STEP used to handler the step JSON object
         */
        function Step(key, step) {
            this.PADDING = 20;
            this.FONT_FAMILY = 'Arial';
            
            this.key = key;
            this.step = step;
            this.infos = new Infos(this.step);
            
            this.initSize();
        }
        
        /*
         * GET the DOM used to display step JSON object
         */
        Step.prototype.getInfosDOM = function() {
            return this.infos.getDOM();
        };
        
        /*
         * INIT the size of the text used to display a step in SVG
         */
        Step.prototype.initSize = function() {
            var struct = $('<div></div>');
            var pKey = $('<p></p>').text(this.key);
            var pType = $('<p></p>').text(this.step.type);
            
            struct.css('margin', 0);
            struct.css('padding', this.PADDING + 'px');
            struct.css('position', 'absolute');
            struct.css('font-family', this.FONT_FAMILY);
            struct.css('text-align', 'center');
            
            pKey.css('margin', 0);
            pKey.css('margin-bottom', this.PADDING + 'px');
            pKey.css('font-size', '20px');
            pKey.css('font-weight', 'bold');
            
            pType.css('font-size', '17px');
            pType.css('margin', 0)
            
            struct.append(pKey,pType);
            struct.appendTo('body');
            
            this.size = {
                'width': struct.width() + 2*this.PADDING,
                'height': struct.height() + 2*(this.PADDING*0.25)
            };
            
            struct.remove();
        };
        
        /*
         * GET the step key
         */
        Step.prototype.getKey = function() {
            return this.key;
        };
        
        /*
         * GET the step type
         */
        Step.prototype.getType = function() {
            return this.step.type;
        };
        
        /*
         * GET the padding used to calculate the size
         */
        Step.prototype.getPadding = function() {
            return this.PADDING;
        };
        
        /*
         * GET the size's text used to display step in SVG
         */
        Step.prototype.getSize = function() {
            return this.size;
        };
        
        /*
         * SET size (use to draw end step)
         */
        Step.prototype.setSize = function(width, height) {
            this.size = {
                'width': width,
                'height': height
            };
        };
        
        return Step;
    });