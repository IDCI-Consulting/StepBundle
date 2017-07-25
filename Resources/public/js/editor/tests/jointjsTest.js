describe("Test the jointjs diagram generation", function() {

  var mapWithSinglePath = {
    options: {},
    name: 'test map',
    steps: {
      step1: {
        type: 'form',
        options: {}
      },
      step2: {
        type: 'html',
        options: {}
      }
    },
    paths: [{
      type: 'single',
      options: {
        source: 'step1',
        destination: 'step2'
      }
    }],
    graphPositions: {
      steps: {
        step1: {
          x: 213,
          y: 128
        },
        step2: {
          x: 324,
          y: 354
        }
      },
      paths: [{
        vertices: []
      }]
    }
  };

  // inject the HTML fixture for the tests
  beforeEach(function() {
    var jointElement = '<div id="joint-element"></div>';
    document.body.insertAdjacentHTML('afterbegin', jointElement);
  });

  // remove the html fixture from the DOM
  afterEach(function() {
    document.body.removeChild(document.getElementById('joint-element'));
  });

  it("Should create a graph with 2 step cells and 1 link", function() {
    var diagram = new Diagram(document.getElementById('joint-element'), mapWithSinglePath);
    var elements = diagram.graph.getElements();
    var links = diagram.graph.getLinks();

    expect(elements.length).toEqual(2);
    expect(links.length).toEqual(1);
  });

  it("Should remove 1 link and 1 step cell", function() {
    var diagram = new Diagram(document.getElementById('joint-element'), mapWithSinglePath);
    var deleteButtons = document.querySelectorAll(".joint-paper button.delete");

    deleteButtons[0].click();
    var elements = diagram.graph.getElements();
    var links = diagram.graph.getLinks();

    expect(elements.length).toEqual(1);
    expect(links.length).toEqual(0);
  });

  var mapWithConditionalPath = {
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

  it("Should create a graph with 3 step cells, 1 conditional cell and 3 links", function() {
    var diagram = new Diagram(document.getElementById('joint-element'), mapWithConditionalPath);
    var elements = diagram.graph.getElements();
    var links = diagram.graph.getLinks();

    expect(elements.length).toEqual(4);
    expect(links.length).toEqual(3);
  });
  
  var mapWithConditionalPathWithoutDefault = {
    name: '',
    options: {},
    steps: {
      step1: {
        type: 'html',
        options: {}
      },
      step2: {
        type: 'html',
        options: {}
      }
    },
    paths: [{
        type: 'conditional_destination',
        options: {
        source: 'step1',
        destinations: {
          step2: 'test here'
        }
      }
    }],
    graphPositions: {
      steps: {
        step1: {
          x: 20,
          y: 20
        },
        step2: {
          x: 220,
          y: 20
        }
      },
      paths: [
        {
          intersection: {
            x: 170,
            y: 120
          },
          endOfPath: {
            x: 220,
            y: 120
          }
        }
      ]
    }
  };

  it("Should create a graph with 2 step cells, 1 conditional cell, 1 end path cell and 3 links", function() {
    var diagram = new Diagram(document.getElementById('joint-element'), mapWithConditionalPathWithoutDefault);
    var elements = diagram.graph.getElements();
    var links = diagram.graph.getLinks();

    expect(elements.length).toEqual(4);
    expect(links.length).toEqual(3);
  });

});
