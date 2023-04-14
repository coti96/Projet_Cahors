
var chart_config = {
  chart: {
    container: "#basic-example",
    connectors: {
      type: 'step'
    },
    node: {
      HTMLclass: 'nodeExample1'
    }
  },
  nodeStructure: {
    text: {name : 'Organisation'
    },
    children: treeArray
  }
}

new Treant(chart_config)