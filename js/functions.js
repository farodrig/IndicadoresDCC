
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
            'cursor': 'pointer'
            /*'point': {
                'events': {
                    'click': function () {
                        alert(this.category);
                    }
                }
            }*/
        };
        if(!(serie.color===null))
            dato.color = serie.color;
        
        data.push(dato);
    }
    return data;
}

function getGraphicOptions(title, xName, xValues, yName, yUnit, data) {
    var options = {
        credits: {
            enabled: false
        },
        chart: {
            /*cursor: 'pointer',
            events: {
                click: function (e) {
                    alert(e.xAxis[0].axis.categories[Math.round(e.xAxis[0].value)]);
                }
            },*/
            zoomType: 'xy',
            marginBottom: 13 * data.length + 75
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
                text: yName + "<br> [" + yUnit + "]",
                offset: 40,
                x:-15,
                style: {
                    color: Highcharts.getOptions().colors[1]
                }
            }
        }],
        tooltip: {
            shared: true,
            useHTML: true,
            borderRadius: 0,
            borderWidth: 0,
            shadow: false,
            enabled: true,
            backgroundColor: 'none',
            formatter: function() {
                var s = "<div style='border-style: solid; border-width: 1px; box-shadow: 1px 1px 1px 1px #7cb5ec; border-color: #7cb5ec; background-color: #f5f6f6; opacity: 0.92; padding: 15px 15px 15px 15px;'>";
                s += '<b>'+ this.x +'</b><br/>';
                $.each(this.points, function(i, point) {
                    console.log(point);
                    s += '<span><i style="color:'+ point.series.color +'">\u25CF</i> '+ point.series.name +' : <b>'+ point.y +" "+point.series.tooltipOptions.valueSuffix +'</b></span><br>';
                });

                return s + "</div>";
            }
        },
        legend: {
            enabled: true,
            floating: true,
            verticalAlign: 'bottom',
            align:'left',
            y: 0,
            x: 35,
            layout: 'vertical',
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
    return options;
}