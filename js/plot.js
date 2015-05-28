
var size = names.length;

for(i=0; i<size; i++){
  names[i] = names[i].replace(/\s+/g, '');
}

for(i = 0; i<index; i++){

  if(graph_info[i]['graph_type']==2){ //Es de linea
    $(document).ready((function( $ ) {

      'use strict';

      (function() {
        var plot = $.plot('#'.concat(names[i]), data[i], {
          series: {
            lines: {
              show: true,
              fill: false,
              lineWidth: 1,
              fillColor: {
                colors: [{
                  opacity: 0.45
                  }, {
                  opacity: 0.45
                }]
              }
            },
            points: {
              show: true
            },
            shadowSize: 0
          },
          grid: {
            hoverable: true,
            clickable: true,
            borderColor: 'rgba(0,0,0,0.1)',
            borderWidth: 1,
            labelMargin: 15,
            backgroundColor: 'transparent'
          },
          axisLabels: {
            show: true
          },
          yaxis: {
            min: graph_info[i]['min'],
            max: graph_info[i]['max'],
            color: 'rgba(0,0,0,0.1)',
            position: "left",
            axisLabel: graph_info[i]['unit'],
            axisLabelUseCanvas: true,
            axisLabelFontSizePixels: 12,
            axisLabelFontFamily: 'Verdana, Arial, Helvetica, Tahoma, sans-serif',
            axisLabelPadding: 5
          },
          xaxis: {
            axisLabel: "Año",
            axisLabelUseCanvas: true,
            axisLabelFontSizePixels: 12,
            axisLabelFontFamily: 'Verdana, Arial, Helvetica, Tahoma, sans-serif',
            axisLabelPadding: 5,
            ticks: graph_info[i]['measure_number']==0 ? 1 :  graph_info[i]['measure_number'],
            color: 'rgba(0,0,0,0.1)',
            tickDecimals: 0
          },
          tooltip: true,
          tooltipOpts: {
            content: '%s: Valor para %x es %y',
            shifts: {
              x: -60,
              y: 25
            },
            defaultTheme: false
          }
        });
      })()}).apply( this, [ jQuery ]));
  }
  else{
    $(document).ready((function( $ ) {

      'use strict';

      (function() {
        var plot = $.plot('#'.concat(names[i]), [data[i][0]['data']], {
          colors: ['#8CC9E8'],
          series: {
            bars: {
              show: true,
              barWidth: 0.8,
              align: 'center'
            }
          },
          axisLabels: {
            show: true
          },
          xaxis: {
            axisLabel: "Año",
            axisLabelUseCanvas: true,
            axisLabelFontSizePixels: 12,
            axisLabelFontFamily: 'Verdana, Arial, Helvetica, Tahoma, sans-serif',
            axisLabelPadding: 5,
            mode: 'categories',
            tickLength: 0,
            tickDecimals: 0
          },
          yaxis :{
            axisLabel: graph_info[i]['unit'],
            axisLabelUseCanvas: true,
            axisLabelFontSizePixels: 12,
            axisLabelFontFamily: 'Verdana, Arial, Helvetica, Tahoma, sans-serif',
            axisLabelPadding: 5
          },
          grid: {
            hoverable: true,
            clickable: true,
            borderColor: 'rgba(0,0,0,0.1)',
            borderWidth: 1,
            labelMargin: 15,
            backgroundColor: 'transparent'
          },
          tooltip: true,
          tooltipOpts: {
            content: '%y',
            shifts: {
              x: -10,
              y: 20
            },
          defaultTheme: false
          }
        });
      })()}).apply( this, [ jQuery ]));

  }

}
