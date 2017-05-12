describe("Test the step editor mutations", function() {

  it("Should remove all associated paths", function() {
    var state = {};
    var map = {
      options: {},
      name: '',
      steps: {
        step1: {
          type: 'form',
          options: {}
        },
        step2: {
          type: 'html',
          options: {}
        },
        step3: {
          type: 'form',
          options: {}
        }
      },
      paths: [{
        type: 'conditional_destination',
        options: {
          source: 'step1',
          destinations: {
            step3: 'test'
          },
          default_destination: 'step2'
        }
      }],
      graphPositions: {
        steps: {
          step1: {
            x: 104,
            y: 116
          },
          step2: {
            x: 96,
            y: 384
          },
          step3: {
            x: 315,
            y: 385
          }
        },
        paths: [{
          intersection: {
            x: 262,
            y: 229
          },
          vertices: []
        }]
      }
    };

    stepEditorMutations.setMap(state, map);
    stepEditorMutations.removeStep(state, 'step1');

    var steps = stepEditorGetters.getSteps(state);
    var paths = stepEditorGetters.getPaths(state);

    expect(Object.keys(steps).length).toEqual(2);
    expect(paths.length).toEqual(0);
  });

});
