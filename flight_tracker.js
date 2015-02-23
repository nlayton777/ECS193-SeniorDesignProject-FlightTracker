$(function() {
    $( document ).tooltip();
});

$(function() { 
    $( "#datepickerD" ).datepicker({minDate:0,
	onSelect: function (selectedDate) {
	    $("#datepickerR").datepicker("option", "minDate", selectedDate);
	}
    }); 
    $( "#datepickerR" ).datepicker({
	onSelect: function (selectedDate) {
	    $("#datepickerD").datepicker ("option", " maxDate", selectedDate);
	}
    });
});

function OneWay() {
    var oneway = document.getElementById('oneway');
    var onewayHidden = document.getElementById('onewayHidden');
    if(oneway.checked) {
	$("#datepickerR" ).datepicker('disable');	
	onewayHidden.disabled = true;
    } else {
	$("#datepickerR" ).datepicker('enable');
	onewayHidden.disabled = false;
    }
}

function showValue(newValue){
    document.getElementById("range").innerHTML=newValue;
}

$(document).ready(function() {
    $('#airline').multiselect({
	buttonWidth: '180px',
	maxHeight: 200
	//checkboxName: 'airlines[]'
    });
    /*
    $('#source').multiselect({
	buttonWidth: '300px',
	maxHeight: 200
    });
    $('#destination').multiselect({
	buttonWidth: '300px',
	maxHeight: 200
    });
    */
});

function validate(){
    var ddl = document.getElementById("source");
    var selectedOValue = ddl.options[ddl.selectedIndex].value;
    if (selectedOValue == "selectplease"){
	alert("Please select an Origin");
	return false;
    }
    var ddd = document.getElementById("destination");
    var selectedDValue = ddd.options[ddd.selectedIndex].value;
    if (selectedDValue == "selectdest"){
	    alert("Please select a Destination");
	    return false;
      }


    if(selectedOValue == selectedDValue){
	    alert("Origin and Destination can not be the same");
	    return false;
    }
    var ad= document.getElementById("adult");
    var chil= document.getElementById("child");
    var sen= document.getElementById("senior");
    var si= document.getElementById("seatinfant");
    var li= document.getElementById("lapinfant");
    var advalue = ad.value;
    var chilvalue = chil.value;
    var senvalue = sen.value;
    var sivalue = si.value;
    var livalue = li.value;
    var totalpass = advalue + chilvalue + senvalue + sivalue + livalue;


    if(totalpass <1){
	alert("Please select passenger quantity");
	return false;
    }else if(totalpass >=9){
	alert("Passenger quantity is too high. Can't be" + totalpass);
	return false;
    }
    return true;
}
