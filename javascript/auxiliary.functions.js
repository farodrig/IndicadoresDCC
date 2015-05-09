function selectYear(){
			
	var year = document.getElementById("year").value;
	var jArray= "<?php echo json_encode($measurements); ?>";
	var size = <?php echo sizeof($measurements); ?> ;
	if(year==""){
		loadValues("","","","");
	}
	else{
		alert(size);
		for (i=0; i<size; i++) {
			var aux_year = jArray[i]['year'];
			if(aux_year==year){
				alert(1);
				loadValues(jArray[i]['metorg'],jArray[i]['value'],jArray[i]['target'],jArray[i]['expected']);
			}               
	    }
	}
}

function loadValues(metorg_id, value, target, expected){
	document.getElementById("value".concat(metorg_id)).value=value;
	document.getElementById("target".concat(metorg_id)).value=target;
	document.getElementById("expected".concat(metorg_id)).value=expected;
			
}
