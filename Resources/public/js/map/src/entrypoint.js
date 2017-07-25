import $                   from 'jquery';
import MapUtils            from 'StepBundle/utils/MapUtils.js';
import Diagram             from 'StepBundle/diagram/Diagram.js';
import stepEditorMutations from 'StepBundle/store/mutations/mutations.js';

(function () {

  $('div.step-diagram').each(function (index) {

    var id = $(this).attr('id');
    var raw = document.querySelectorAll('#' + id + ' .json-map')[0].innerHTML;
    var diagramId = 'svg-' + id;

    // Leave if the field is empty
    if (raw == '') {
        return;
    }

    /**
     * Disable pointer events on svg and update the diagram height according to the svg height
     */
    function updateStyle () {
        var svg = document.querySelectorAll('#' + id + ' svg > g')[0];

        svg.setAttribute('style', 'pointer-events: none');

        var diagramId = 'svg-' + id;
        var diagram = document.getElementById(diagramId);
        var height = svg.getBoundingClientRect().height + 50;

        diagram.setAttribute('style', 'height: ' + height + 'px;');
    }

    /**
     * Hide the close button on html elements of the diagram
     */
    function hideCloseButtons () {
        var closeButtons = document.querySelectorAll('#' + id + ' .html-element > button.delete');

        // Hide the close buttons on html elements of diagrams
        for (var k = 0; k < closeButtons.length; k++) {
            closeButtons[k].setAttribute('style', 'display: none');
        }
    }

    /**
     * Display a modal containing a textarea with the json
     *
     * @param json
     */
    function displayJsonModal (json) {
        var $modal = $(
            '<div class="tms_message_modal modal fade" tabindex="-1">' +
                '<div class="modal-dialog">' +
                    '<div class="modal-content">' +
                        '<div class="modal-header">' +
                            '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>' +
                            '<h3>Json</h3>' +
                        '</div>' +
                        '<textarea readonly class="modal-json modal-body">' + json + '</textarea>' +
                        '<div class="modal-footer"></div>' +
                    '<div>' +
                '<div>' +
            '</div>'
        );

        $modal.modal('show');

        $modal.on('hidden.bs.modal', function () {
            $(this).remove();
        });
    }

    MapUtils.transformRawToJson(
        raw,
        function setDiagram (map) {
            if (typeof map.graphPositions === 'undefined') {
                map.graphPositions = stepEditorMutations.setInitialGraphPositions(map);
            }

            new Diagram(document.getElementById(diagramId), map);
            updateStyle();
            hideCloseButtons();
        },
        function setErrorMessage (e) {
          console.log(e);
            document.getElementById(diagramId).innerHTML =
                '<div class="error">' +
                'Ce parcours contient du json invalide, le diagramme ne peut donc pas être généré.' +
                '</div>' +
                '<br>'
            ;
        }
    );

    // Add the button to display the modal with the raw map
    document.getElementById(id).innerHTML += '<i class="fa fa-file-code-o json-raw-button" aria-hidden="true"></i>';

    $('document').ready(function () {
        $('body').on('click', '#' + id + ' .json-raw-button', function (event) {
            event.preventDefault();
            displayJsonModal($(this).siblings('.json-map').html());
        });
    });

  });

}());
