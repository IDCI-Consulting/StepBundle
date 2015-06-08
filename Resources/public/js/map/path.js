'use strict';

define(
    [
        'jquery-private',
        'infos'
    ],
    function($, Infos) {
        /*
         * PATH used to handler the path JSON object
         */
        function Path(path) {
            this.PADDING = 20;
            this.FONT_FAMILY = 'Arial';

            this.key = undefined != path.options.next_options ? path.options.next_options.label : 'next';
            this.path = path;
            this.infos = new Infos(this.path);

            this.initSize();
        }

        /*
         * INIT the size of the key text
         */
        Path.prototype.initSize = function() {
            var struct = $('<div></div>');
            var pKey = $('<p></p>').text(this.key);

            struct.css('margin', 0);
            struct.css('padding', this.PADDING + 'px');
            struct.css('position', 'absolute');
            struct.css('font-family', this.FONT_FAMILY);
            struct.css('text-align', 'center');

            pKey.css('margin', 0);
            pKey.css('font-size', '15px');

            struct.append(pKey);
            struct.appendTo('body');

            this.size = {
                'width': struct.width() + 2 * this.PADDING,
                'height': struct.height() + 2 * (this.PADDING * 0.25)
            };

            struct.remove();
        };

        /*
         * GET the key of the json object (label)
         */
        Path.prototype.getKey = function() {
            return this.key;
        };

        /*
         * GET the padding used to set the size
         */
        Path.prototype.getPadding = function() {
            return this.PADDING;
        };

        /*
         * GET size of key text displaying
         */
        Path.prototype.getSize = function() {
            return this.size;
        };

        /*
         * GET the DOM used to display path object
         */
        Path.prototype.getInfosDOM = function() {
            return this.infos.getDOM();
        };

        /*
         * GET the path JSON object
         */
        Path.prototype.getPath = function() {
            return this.path;
        };

        return Path;
    });