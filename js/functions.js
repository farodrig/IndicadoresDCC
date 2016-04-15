
  function validate_year(id,opt){
    return changeOnValidation(id, ((!isNaN(parseFloat(opt)) && isFinite(opt)) && opt.toString().length==4 && opt>=1980));
  }

  function changeOnValidation(id, validator){
    if(validator){
      document.getElementById(id).style.borderColor="green";
      return true;
    }
    else{
      document.getElementById(id).style.borderColor="red";
      document.getElementById(id).focus();
      return false;
    }
  }


function serieTypeToChartType(type) {
    if(type==="Barra"){
      return "column";
    }
    if(type==="Linea"){
      return "line";
    }
    return "";
}

function createGraphicData(series, yUnit){
    var data = [];
    for(var i in series){
        var serie = series[i];
        var val = [];
        for(var j in series[i].values){
            val.push(parseInt(series[i].values[j].value));
        }
        var prename = (serie.aggregation=="" ? "" : serie.aggregation + " de ");
        var dato = {
            'id': serie.id,
            'name': prename + serie.name + " de " + serie.org,
            'type': serieTypeToChartType(serie.type),
            'data': val,
            'tooltip': {
                'valueSuffix': ' ' + yUnit
            },
            'cursor': 'pointer',
            'point': {
                'events': {
                    'click': function () {
                        alert('Category: ' + this.category + ', value: ' + this.y);
                    }
                }
            }
        };
        if(!(serie.color===null))
            dato.color = serie.color;
        
        data.push(dato);
    }
    return data;
}

function getGraphicOptions(title, xName, xValues, yName, yUnit, data, goBack) {
    var options = {
        credits: {
            enabled: false
        },
        chart: {
            zoomType: 'xy'
        },
        title: {
            text: title
        },
        xAxis: [{
            title: {
                text: xName,
                style: {
                    color: Highcharts.getOptions().colors[1]
                }
            },
            categories: xValues,
            crosshair: true
        }],
        yAxis: [{ // Primary yAxis
            labels: {
                format: '{value}',
                style: {
                    color: Highcharts.getOptions().colors[1]
                }
            },
            title: {
                text: yName + " [" + yUnit + "]",
                style: {
                    color: Highcharts.getOptions().colors[1]
                }
            }
        }],
        tooltip: {
            shared: true
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'top',
            y: -15,
            floating: true,
            backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'
        },
        series: data,
        exporting: {
            buttons: {
                contextButton: {
                    enabled: false
                }
            }
        }
    };
    if(goBack!=null){
        options.exporting = {
            buttons: {
                contextButton: {
                    enabled: false

                },
                customButton: {
                    text: 'Volver',
                    x: 60,
                    align: 'left',
                    verticalAlign: 'top',
                    onclick: function () {
                        alert('Clicked');
                    }
                }
            }
        };
        options.navigation = {
            buttonOptions: {
                y: -10,
                theme: {
                    style: {
                        color: '#039'
                    }
                }
            }
        };
    }
    return options;
}