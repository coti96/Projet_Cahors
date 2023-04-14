
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
            text: {
                name: "Mark Hill",
               
            },
           
            children: [
                {
                    text:{
                        name: "Joe Linux",
                        
                    },
                    stackChildren: true,
                    
                    children: [
                        {
                            text:{
                                name: "Ron Blomquist",
                               
                            },
                            
                        },
                        {
                            text:{
                                name: "Michael Rubin",
                               
                            },
                            
                        }
                    ]
                },
                {
                    stackChildren: true,
                    text:{
                        name: "Linda May",
                      
                    },
                   
                    children: [
                        {
                            text:{
                                name: "Alice Lopez",
                                
                            },
                          
                        },
                        {
                            text:{
                                name: "Mary Johnson",
                               
                            },
                         
                        },
                        {
                            text:{
                                name: "Kirk Douglas",
                              
                            },
                          
                        }
                    ]
                },
                {
                    text:{
                        name: "John Green",
                       
                    },
                   
                    children: [
                        {
                            text:{
                                name: "Erica Reel",
                                
                            }
                          
                        }
                    ]
                }
            ]
        }
    };

