var bar = new ProgressBar.Line(container, {
    strokeWidth: 1,
    easing: 'easeInOut',
    duration: 800,
    color: '#0080FF',
    trailColor: '#eee',
    trailWidth: 1,
    svgStyle: {width: '100%', height: '25%'}
  });
  
  bar.animate(1.0);