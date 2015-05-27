var from, to; 
var initial_from, initial_to;
var pressed;
function saveValFrom(e){
  from = e.value;
}

function saveValTo(e){
  to = e.value;
}

function checkInput(){
  if(validate_year('from', from) && validate_year('to', to)){
    if(from<=to)
      return true;
    else{
      $('#from').attr('value',initial_from);
      $('#to').attr('value',initial_to);
      alert("Año de inicio debe ser menor al año final");
      return false;
    }
  }
  else{
    alert("Años inválidos");
    return false;
  }
}

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


  $('section.body').click(function(e){
    if(!(e['target']['attributes']['class'].value=="btn-group-vertical col-md-12") &&
      !(e['target']['attributes']['class'].value=="btn btn-default" && e['target']['attributes']['href'].value=="#popover")){
      $('#popover').popover('hide');
    }
  });


  function updateYears(id){
    var min_year = years[id]['min'];
    var max_year = years[id]['max'];
    var check = years[id]['checked'];
    var type = years[id]['type'];
    var id_graph = years[id]['id'];
    pressed = id;

    from = new Number(JSON.parse(min_year));
    to = new Number(JSON.parse(max_year));
    initial_from = from;
    initial_to = to;
    
    $('#from').attr('value',new Number(JSON.parse(min_year)));
    $('#to').attr('value',new Number(JSON.parse(max_year)));
    $('#id_met').attr('value',new Number(id));
    $('#id_graph').attr('value',new Number(id_graph));
    $('#mostrar').attr('checked', check==0 ? null : 1);

    var select_grafico = document.getElementById('type');
    select_grafico.options.length = 0;

    if(type=="2"){
      select_grafico.options[select_grafico.options.length]= new Option('Líneas', 2);
      select_grafico.options[select_grafico.options.length]= new Option('Barra', 1);
    }
    else{
      select_grafico.options[select_grafico.options.length]= new Option('Barra', 1);
      select_grafico.options[select_grafico.options.length]= new Option('Líneas', 2);
    }

  }
