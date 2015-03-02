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
	document.getElementById("datepickerR").style.display = "none";
	onewayHidden.disabled = true;
	document.getElementById("datepickerD").style.width = "348px";
    } else {
	$("#datepickerR" ).datepicker('enable');
	document.getElementById("datepickerR").style.display = "initial";
	onewayHidden.disabled = false;
	document.getElementById("datepickerD").style.width = "initial";
    }
}

function showValue(newValue){
    document.getElementById("range").innerHTML=newValue;
}

//tooltip changing position
var tooltipSpan = document.getElementById('tooltip-span');

window.onmousemove = function (e) {
    var x = e.clientX,
            y = e.clientY;
	        tooltipSpan.style.top = (y + 20) + 'px';
		    tooltipSpan.style.left = (x + 20) + 'px';
};



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
    var advalue = parseInt(ad.value);
    var chilvalue = parseInt(chil.value);
    var senvalue = parseInt(sen.value);
    var sivalue = parseInt(si.value);
    var livalue = parseInt(li.value);
    var totalpass = advalue + chilvalue + senvalue + sivalue + livalue;


    if(totalpass <1){
	alert("Please select passenger quantity");
	return false;
    }else if(totalpass >=9){
	alert("Passenger quantity is too high. Can't be" + totalpass);
	return false;
    }

}

jQuery(document).ready(function(){
    // This button will increment the value
    $('.qtyplus').click(function(e){
        // Stop acting like a button
        e.preventDefault();
        // Get the field name
        fieldName = $(this).attr('field');
        // Get its current value
        var currentVal = parseInt($('input[name='+fieldName+']').val());
        // If is not undefined
        if (!isNaN(currentVal)) {
            // Increment
            $('input[name='+fieldName+']').val(currentVal + 1);
        } else {
            // Otherwise put a 0 there
            $('input[name='+fieldName+']').val(0);
        }
    });
    // This button will decrement the value till 0
    $(".qtyminus").click(function(e) {
        // Stop acting like a button
        e.preventDefault();
        // Get the field name
        fieldName = $(this).attr('field');
        // Get its current value
        var currentVal = parseInt($('input[name='+fieldName+']').val());
        // If it isn't undefined or its greater than 0
        if (!isNaN(currentVal) && currentVal > 0) {
            // Decrement one
            $('input[name='+fieldName+']').val(currentVal - 1);
        } else {
            // Otherwise put a 0 there
            $('input[name='+fieldName+']').val(0);
        }
    });
});
