$(function() {
    $( document ).tooltip();
});

$(function() { 
    $( "#datepickerD" ).datepicker({
	minDate:0,
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
    var x = e.clientX, y = e.clientY;
};

function deleteFromArray(item, arr){
  for (var i=0;i<arrr.length;i++){
    if (arr[i]==item){
      arr.splice(i,1); //this delete from the "i" index in the array to the "1" length
      break;
    }
  }  
}

$(document).ready(function() {
    $('#airline').multiselect({
	buttonWidth: '180px',
	maxHeight: 200,
	onChange: function(option, checked, select) {
				if($(option).val() != 'none' && $("#none").is(':checked')){
					$('#airline').multiselect('deselect', ['none']);
                }
                else if($(option).val() == 'none' && $("#none").is(':checked')){
                	$('#airline').multiselect('deselect', ['AS', 'AA', 'DL', 'F9', 'B6', 'WN', 'NK', 'US', 'UA', 'VX']);
                }
            }
    });
});

var codeArray = ["ABR", "ABI", "ADK", "KKI", "AKI", "CAK", "KQA", "AUK", "ALM", "ALS", "ALB", "CVO", "QWY", "ABQ", "WKK", "AEX", "AET", "ABE", "AIA", "APN", "AOO", "AMA", "ABL", "AKP", "ANC", "AGN", "ANI", "ANV", "ATW", "ACV", "ARC", "AVL", "HTS", "ASE", "AHN", "AKB", "ATL", "AIY", "ATK", "AGS", "AUG", "AUS", "BFL", "BWI", "BGR", "BHB", "BRW", "BTI", "BTR", "MBS", "BPT", "ZBV", "WBQ", "BKW", "BED", "BLV", "BLI", "BJI", "BEH", "BET", "ABE", "BTT", "BIL", "GPT", "BGM", "KBC", "BHM", "BIS", "BID", "BMI", "BLF", "BOI", "BOS", "XHH", "WHH", "WBU", "BYA", "BWG", "BZN", "BFD", "BRD", "BWD", "QKB", "TRI", "BKX", "RBH", "BRO", "BQK", "BKC", "BUF", "IFP", "BUR", "BRL", "BTV", "BTM", "CAK", "CGI", "LUR", "EHM", "MDH", "CLD", "CNM", "MRY", "CPR", "CDC", "CID", "CEM", "CDR", "CIK", "CMI", "CHS", "CRW", "CLT", "CHO", "CHA", "CYF", "VAK", "CYS", "CGX", "CHI", "MDW", "ORD", "CKX", "CIC", "KCG", "KCQ", "KCL", "CZN", "HIB", "CHU", "CVG", "CHP", "IRC", "CLP", "CKB", "PIE", "CLE", "CVN", "COD", "CFA", "KCC", "CDB", "CLL", "COS", "COU", "CAE", "CSG", "GTR", "CMH", "CCR", "CNK", "QCE", "CDV", "CRP", "CEZ", "CGA", "CEC", "CKO", "CUW", "CBE", "DFW", "DAY", "DAB", "DEC", "DRG", "DJN", "DEN", "QWM", "DSM", "DTT", "DTW", "DVL", "DIK", "DLG", "DDC", "DHN", "DUJ", "DBQ", "DLH", "DRO", "RDU", "RDU", "DUT", "ABE", "EAU", "EDA", "EEK", "KKU", "KEK", "IPL", "ELD", "ELP", "ELV", "ELI", "EKO", "ELM", "LYU", "EMK", "BGM", "WDG", "ERI", "ESC", "EUG", "ACV", "EUE", "EVV", "FAI", "FAR", "FYV", "XNA", "FAY", "FLG", "FNT", "FLO", "MSL", "FNL", "QWF", "FOD", "FLL", "TBN", "RSW", "FSM", "VPS", "FWA", "DFW", "FKL", "FAT", "GNV", "GUP", "GCK", "GYY", "GCC", "GGG", "GGW", "GDV", "GLV", "GNU", "JGC", "GCN", "GFK", "GRI", "GJT", "GRR", "GPZ", "KGX", "GTF", "GRB", "GSO", "GLH", "PGV", "GSP", "GON", "GPT", "GUC", "GST", "HGR", "SUN", "HNS", "PHF", "HNM", "PAK", "CMX", "LEB", "HRL", "MDT", "HRO", "BDL", "HAE", "HVR", "HDN", "HYS", "HKB", "HLN", "AVL", "HIB", "HKY", "GSO", "ITO", "HHH", "HBB", "HYL", "HCR", "HOM", "HNL", "MKK", "HNH", "HPB", "HOT", "HOU", "HOU", "IAH", "HUS", "HTS", "HSV", "HON", "HSL", "HYA", "HYG", "IDA", "IGG", "ILI", "IPL", "IND", "INL", "IYK", "IMT", "IWD", "ISP", "ITH", "JAC", "JAN", "MKL", "JAX", "OAJ", "JMS", "JHW", "JVL", "BGM", "TRI", "JST", "JBR", "JLN", "JNU", "OGG", "KAE", "KNK", "AZO", "LUP", "KLG", "KAL", "MUE", "MCI", "JHM", "KXA", "KUK", "LIH", "EAR", "EEN", "ENA", "KTN", "EYW", "QKS", "IAN", "GGG", "ILE", "KVC", "AKN", "IGM", "TRI", "KPN", "IRK", "KVL", "LMT", "KLW", "TYS", "OBU", "ADQ", "KOA", "KKH", "KOT", "OTZ", "KYU", "KWT", "KWK", "LSE", "LAF", "LFT", "LCH", "HII", "LMA", "LNY", "LNS", "LAN", "LAR", "LRD", "LAS", "LBE", "PIB", "LAW", "LEB", "KLL", "LWB", "LWS", "LWT", "LEX", "LBL", "LIH", "LNK", "LIT", "LGB", "GGG", "LPS", "LAX", "SDF", "NL)", "QWF", "LBB", "MCN", "MSN", "MDJ", "MHT", "MHK", "MBL", "MKT", "MLY", "KMO", "PKB", "MWA", "MQT", "MLL", "MVY", "AOO", "MCW", "MSS", "OGG", "MFE", "MCK", "MCG", "MFR", "MYU", "MLB", "MEM", "MCE", "MEI", "MTM", "WMK", "MIA", "MPB", "MBS", "MAF", "MLS", "MKE", "MSP", "MOT", "MNT", "MFE", "MSO", "CNY", "MOB", "MOD", "MLI", "MLU", "MRY", "MGM", "MTJ", "MGW", "MWH", "WMH", "MOU", "MSL", "MKG", "MYR", "ACK", "WNA", "PKA", "APF", "BNA", "NKI", "NLG", "NCN", "HVN", "KGK", "GON", "MSY", "KNW", "NYC", "JFK", "LGA", "EWR", "SWF", "PHF", "WWT", "NME", "NIB", "IKO", "WTK", "OME", "NNL", "ORV", "OFK", "ORF", "OTH", "LBF", "ORT", "NUI", "NUL", "NUP", "OAK", "MAF", "OGS", "OKC", "OMA", "ONT", "SNA", "ORL", "MCO", "OSH", "OTM", "OWB", "OXR", "PAH", "PGA", "PSP", "PFN", "PKB", "PSC", "PDB", "PEC", "PLN", "PDT", "PNS", "PIA", "KPV", "PSG", "PHL", "TTN", "PHX", "PIR", "UGB", "PIP", "PQS", "PIT", "PTU", "PLB", "PIH", "KPB", "PHO", "PIZ", "PNC", "PSE", "PTA", "CLM", "BPT", "KPC", "PTH", "PML", "PPV", "PCA", "PWM", "PDX", "PSM", "POU", "PRC", "PQI", "BLF", "PVD", "PVC", "SCC", "PUB", "PUW", "UIN", "KWN", "RDU", "RMP", "RAP", "RDG", "RDV", "RDD", "RDM", "RNO", "RHI", "RIC", "RIW", "ROA", "RCE", "RST", "ROC", "RKS", "ZRF", "ZRK", "RKD", "RSJ", "ROW", "RBY", "RSH", "RUT", "SMF", "MBS", "STC", "STG", "SGU", "STL", "KSM", "SMK", "SNP", "SLE", "SLN", "SBY", "SLC", "SJT", "SAT", "SAN", "SFO", "SJC", "SJU", "SBP", "SDP", "SNA", "SBA", "SAF", "SMX", "STS", "SLK", "SRQ", "CIU", "SAV", "SVA", "SCM", "BFF", "SDL", "AVP", "LKE", "SEA", "WLK", "SWD", "SHX", "SKK", "MSL", "SXP", "SHR", "SHH", "SHV", "SHG", "SVC", "SUX", "FSD", "SIT", "SGY", "SLQ", "SBN", "WSN", "SOP", "GSP", "GEG", "SPI", "SGF", "PIE", "SCE", "SHD", "SBS", "WBB", "CWA", "SVS", "SWF", "SCK", "SRV", "SUN", "SYR", "TCT", "TKA", "TLH", "TPA", "TAL", "TSM", "TEK", "KTS", "TEX", "TKE", "HUF", "TEH", "TXK", "TVF", "KTB", "TNC", "TOG", "TKJ", "OOK", "TOL", "FOE", "TVC", "TTN", "TUS", "TUL", "TLT", "WTL", "TNK", "TUP", "TCL", "TWF", "TWA", "TYR", "UNK", "CMI", "UCA", "UTO", "EGE", "QBF", "VDZ", "VLD", "VPS", "VEE", "OXR", "VEL", "VCT", "VIS", "ACT", "AIN", "WAA", "ALW", "WAS", "IAD", "DCA", "KWF", "ALO", "ART", "ATY", "CWA", "EAT", "PBI", "WYS", "HPN", "WST", "WSX", "WWP", "WMO", "LEB", "SPS", "ICT", "AVP", "PHF", "IPT", "ISN", "ILM", "BDL", "ORH", "WRL", "WRG", "YKM", "YAK", "COD", "YNG", "YUM"];  	

function isInArray(value, array) {
		return array.indexOf(value) > -1;
	}
	
function isDate(str) {
    var matches = str.match(/(\d{2,2})[\/](\d{2,2})[\/](\d{4})/);
    if (!matches) return false;

    // parse each piece and see if it makes a valid date object
    var month = parseInt(matches[1], 10);
    var day = parseInt(matches[2], 10);
    var year = parseInt(matches[3], 10);
    var date = new Date(year, month - 1, day);
    if (!date || !date.getTime()) return false;

    // make sure we have no funny rollovers that the date object sometimes accepts
    // month > 12, day > what's allowed for the month
    if (date.getMonth() + 1 != month ||
        date.getFullYear() != year ||
        date.getDate() != day) {
            return false;
        }
        
    var today = new Date();
    var mm = ((today.getMonth() + 1) < 10 ? '0' : '') + (today.getMonth() + 1);
    var dd = (today.getDate() < 10 ? '0' : '') + today.getDate();
    var yyyy = today.getFullYear();
    
    today = mm+'/'+dd+'/'+yyyy;
    
    if(str < today){
    	return false;
    }
     
    return true;
}

function validate(){
	var originError = "Enter an Origin with a valid Airport Code";
	var destError = "Enter a Destination with a valid Airport Code";
	var sameError = "Change either Origin or Destination so they are not the same";
	var departError = "Enter a valid Departure Date";
	var returnError = "Enter a valid Return Date";
	var passError = "Enter a Number of Passengers between 1 and 9";
	var chilError = "An Adult or Senior must accompany a child, seat infant, or lap infant";
	
	var oneway = document.getElementById('oneway');
	
    var sourceLine = document.getElementById("source").value;
	var sCode = sourceLine.substr(-4, 3);
	if(sourceLine == ""){
		bootbox.dialog({
  			title: "Whoops! We need you to: ",
  			message: originError
		});
		return false;
	 }
	 else if(!isInArray(sCode, codeArray)){
		bootbox.dialog({
  			title: "Whoops! We need you to: ",
  			message: originError
		});
		return false;
	 } 
	 else{
	 	$("#source").val(sCode);
	 }
	 
    var destLine = document.getElementById("destination").value;
	var dCode = destLine.substr(-4, 3);
	if(destLine == ""){
		bootbox.dialog({
  			title: "Whoops! We need you to: ",
  			message: destError
		});
		return false;
	 }
	 else if(!isInArray(dCode, codeArray)){
		bootbox.dialog({
  			title: "Whoops! We need you to: ",
  			message: destError
		});
		return false;
	 } 
	 else{
	 	$("#destination").val(dCode);
	 }

    if(sCode == dCode){
	    bootbox.dialog({
  			title: "Whoops! We need you to: ",
  			message: sameError
		});
	    return false;
    }
    
    var departDate = document.getElementById("datepickerD").value;
    var returnDate = document.getElementById("datepickerR").value;
    if(!isDate(departDate)) {
  		bootbox.dialog({
  			title: "Whoops! We need you to: ",
  			message: departError
		});
  		return false;
	}
	if(!isDate(returnDate) && !oneway.checked){
		bootbox.dialog({
  			title: "Whoops! We need you to: ",
  			message: returnError
		});
		return false;
	}
	
	if(!oneway.checked){
	  if(returnDate < departDate){
		  bootbox.dialog({
			  title: "Whoops! We need you to: ",
			  message: returnError
		  });
		  return false;
	  }
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
		bootbox.dialog({
  			title: "Whoops! We need you to: ",
  			message: passError
		});
		return false;
    }else if(totalpass >=9){
		bootbox.dialog({
  			title: "Whoops! We need you to: ",
  			message: passError
		});
		return false;
    }
    
    if(chilvalue >= 1 || sivalue >= 1 || livalue >= 1){
    	if(advalue < 1 && senvalue < 1){
    		bootbox.dialog({
  				title: "Whoops!",
  				message: chilError
			});
			return false;
    	}	
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

$(function() {
  	
    var availableTags = [
		"Aberdeen, SD (ABR)",
		"Abilene, TX (ABI)",
		"Adak Island, AK (ADK)",
		"Akiachak, AK (KKI)",
		"Akiak, AK (AKI)",
		"Akron/Canton, OH (CAK)",
		"Akuton, AK (KQA)",
		"Alakanuk, AK (AUK)",
		"Alamogordo, NM (ALM)",
		"Alamosa, CO (ALS)",
		"Albany, NY (ALB)",
		"Albany, OR - Bus service (CVO)",
		"Albany, OR - Bus service (QWY)",
		"Albuquerque, NM (ABQ)",
		"Aleknagik, AK (WKK)",
		"Alexandria, LA (AEX)",
		"Allakaket, AK (AET)",
		"Allentown, PA (ABE)",
		"Alliance, NE (AIA)",
		"Alpena, MI (APN)",
		"Altoona, PA (AOO)",
		"Amarillo, TX (AMA)",
		"Ambler, AK (ABL)",
		"Anaktueuk, AK (AKP)",
		"Anchorage, AK (ANC)",
		"Angoon, AK (AGN)",
		"Aniak, AK (ANI)",
		"Anvik, AK (ANV)",
		"Appleton, WI (ATW)",
		"Arcata, CA (ACV)",
		"Arctic Village, AK (ARC)",
		"Asheville, NC (AVL)",
		"Ashland, KY/Huntington, WV (HTS)",
		"Aspen, CO (ASE)",
		"Athens, GA (AHN)",
		"Atka, AK (AKB)",
		"Atlanta, GA (ATL)",
		"Atlantic City, NJ (AIY)",
		"Atqasuk, AK (ATK)",
		"Augusta, GA (AGS)",
		"Augusta, ME (AUG)",
		"Austin, TX (AUS)",
		"Bakersfield, CA (BFL)",
		"Baltimore, MD (BWI)",
		"Bangor, ME (BGR)",
		"Bar Harbour, ME (BHB)",
		"Barrow, AK (BRW)",
		"Barter Island, AK (BTI)",
		"Baton Rouge, LA (BTR)",
		"Bay City, MI (MBS)",
		"Beaumont/Port Arthur, TX (BPT)",
		"Beaver Creek, CO - Van service (ZBV)",
		"Beaver, AK (WBQ)",
		"Beckley, WV (BKW)",
		"Bedford, MA (BED)",
		"Belleville, IL (BLV)",
		"Bellingham, WA (BLI)",
		"Bemidji, MN (BJI)",
		"Benton Harbor, MI (BEH)",
		"Bethel, AK (BET)",
		"Bethlehem, PA (ABE)",
		"Bettles, AK (BTT)",
		"Billings, MT (BIL)",
		"Biloxi/Gulfport, MS (GPT)",
		"Binghamton, NY (BGM)",
		"Birch Creek, AK (KBC)",
		"Birmingham, AL (BHM)",
		"Bismarck, ND (BIS)",
		"Block Island, RI (BID)",
		"Bloomington, IL (BMI)",
		"Bluefield, WV (BLF)",
		"Boise, ID (BOI)",
		"Boston, MA (BOS)",
		"Boulder, CO - Bus service (XHH)",
		"Boulder, CO - Hiltons Har H (WHH)",
		"Boulder, CO - Municipal Airport (WBU)",
		"Boundary, AK (BYA)",
		"Bowling Green, KY (BWG)",
		"Bozeman, MT (BZN)",
		"Bradford, PA (BFD)",
		"Brainerd, MN (BRD)",
		"Brawnwood, TX (BWD)",
		"Breckonridge, CO - Van service (QKB)",
		"Bristol, VA (TRI)",
		"Brookings, SD (BKX)",
		"Brooks Lodge, AK (RBH)",
		"Brownsville, TX (BRO)",
		"Brunswick, GA (BQK)",
		"Buckland, AK (BKC)",
		"Buffalo, NY (BUF)",
		"Bullhead City/Laughlin, AZ (IFP)",
		"Burbank, CA (BUR)",
		"Burlington, IA (BRL)",
		"Burlington, VT (BTV)",
		"Butte, MT (BTM)",
		"Canton/Akron, OH (CAK)",
		"Cape Girardeau, MO (CGI)",
		"Cape Lisburne, AK (LUR)",
		"Cape Newenham, AK (EHM)",
		"Carbondale, IL (MDH)",
		"Carlsbad, CA (CLD)",
		"Carlsbad, NM (CNM)",
		"Carmel, CA (MRY)",
		"Casper, WY (CPR)",
		"Cedar City, UT (CDC)",
		"Cedar Rapids, IA (CID)",
		"Central, AK (CEM)",
		"Chadron, NE (CDR)",
		"Chalkyitsik, AK (CIK)",
		"Champaign/Urbana, IL (CMI)",
		"Charleston, SC (CHS)",
		"Charleston, WV (CRW)",
		"Charlotte, NC (CLT)",
		"Charlottesville, VA (CHO)",
		"Chattanooga, TN (CHA)",
		"Chefornak, AK (CYF)",
		"Chevak, AK (VAK)",
		"Cheyenne, WY (CYS)",
		"Chicago, IL - Meigs (CGX)",
		"Chicago, IL - All airports (CHI)",
		"Chicago, IL - Midway (MDW)",
		"Chicago, IL - O\'Hare (ORD)",
		"Chicken, AK (CKX)",
		"Chico, CA (CIC)",
		"Chignik, AK - Fisheries (KCG)",
		"Chignik, AK - (KCQ)",
		"Chignik, AK - Lagoon (KCL)",
		"Chisana, AK (CZN)",
		"Chisholm/Hibbing, MN (HIB)",
		"Chuathbaluk, AK (CHU)",
		"Cincinnati, OH (CVG)",
		"Circle Hot Springs, AK (CHP)",
		"Circle, AK (IRC)",
		"Clarks Point, AK (CLP)",
		"Clarksburg, WV (CKB)",
		"Clearwater/St Petersburg, FL (PIE)",
		"Cleveland, OH (CLE)",
		"Clovis, NM (CVN)",
		"Cody/Yellowstone, WY (COD)",
		"Coffee Point, AK (CFA)",
		"Coffman Cove, AK (KCC)",
		"Cold Bay, AK (CDB)",
		"College Station, TX (CLL)",
		"Colorado Springs, CO (COS)",
		"Columbia, MO (COU)",
		"Columbia, SC (CAE)",
		"Columbus, GA (CSG)",
		"Columbus, MS (GTR)",
		"Columbus, OH (CMH)",
		"Concord, CA (CCR)",
		"Concordia, KS (CNK)",
		"Copper Mountain, CO - Van service (QCE)",
		"Cordova, AK (CDV)",
		"Corpus Christi, TX (CRP)",
		"Cortez, CO (CEZ)",
		"Craig, AK (CGA)",
		"Crescent City, CA (CEC)",
		"Crooked Creek, AK (CKO)",
		"Cube Cove, AK (CUW)",
		"Cumberland, MD (CBE)",
		"Dallas/Fort Worth, TX (DFW)",
		"Dayton, OH (DAY)",
		"Daytona Beach, FL (DAB)",
		"Decatur, IL (DEC)",
		"Deering, AK (DRG)",
		"Delta Junction, AK (DJN)",
		"Denver, CO - International (DEN)",
		"Denver, CO - Longmont Bus service (QWM)",
		"Des Moines, IA (DSM)",
		"Detroit, MI - All airports (DTT)",
		"Detroit, MI - Metro/Wayne County (DTW)",
		"Devil\'s Lake, ND (DVL)",
		"Dickinson, ND (DIK)",
		"Dillingham, AK (DLG)",
		"Dodge City, KS (DDC)",
		"Dothan, AL (DHN)",
		"Dubois, PA (DUJ)",
		"Dubuque, IA (DBQ)",
		"Duluth, MN (DLH)",
		"Durango, CO (DRO)",
		"Durham, NC (RDU)",
		"Durham/Raleigh, NC (RDU)",
		"Dutch Harbor, AK (DUT)",
		"Easton, PA (ABE)",
		"Eau Claire, WI (EAU)",
		"Edna Bay, AK (EDA)",
		"Eek, AK (EEK)",
		"Ekuk, AK (KKU)",
		"Ekwok, AK (KEK)",
		"El Centro, CA (IPL)",
		"El Dorado, AR (ELD)",
		"El Paso, TX (ELP)",
		"Elfin Cove, AK (ELV)",
		"Elim, AK (ELI)",
		"Elko, NV (EKO)",
		"Elmira, NY (ELM)",
		"Ely, MN (LYU)",
		"Emmonak, AK (EMK)",
		"Endicott, NY (BGM)",
		"Enid, OK (WDG)",
		"Erie, PA (ERI)",
		"Escanaba, MI (ESC)",
		"Eugene, OR (EUG)",
		"Eureka/Arcata, CA (ACV)",
		"Eureka, NV (EUE)",
		"Evansville, IN (EVV)",
		"Fairbanks, AK (FAI)",
		"Fargo, ND (FAR)",
		"Fayetteville, AR - Municipal/Drake (FYV)",
		"Fayetteville, AR - Northwest Arkansas Regional (XNA)",
		"Fayetteville, NC (FAY)",
		"Flagstaff, AZ (FLG)",
		"Flint, MI (FNT)",
		"Florence, SC (FLO)",
		"Florence/Muscle Shoals/Sheffield, AL (MSL)",
		"Fort Collins/Loveland, CO - Municipal Airport (FNL)",
		"Fort Collins/Loveland, CO - Bus service (QWF)",
		"Fort Dodge, IA (FOD)",
		"Fort Lauderdale, FL (FLL)",
		"Fort Leonard Wood, MO (TBN)",
		"Fort Myers, FL (RSW)",
		"Fort Smith, AR (FSM)",
		"Fort Walton Beach, FL (VPS)",
		"Fort Wayne, IN (FWA)",
		"Fort Worth/Dallas, TX (DFW)",
		"Franklin, PA (FKL)",
		"Fresno, CA (FAT)",
		"Gainesville, FL (GNV)",
		"Gallup, NM (GUP)",
		"Garden City, KS (GCK)",
		"Gary, IN (GYY)",
		"Gillette, WY (GCC)",
		"Gladewater/Kilgore, TX (GGG)",
		"Glasgow, MT (GGW)",
		"Glendive, MT (GDV)",
		"Golovin, AK (GLV)",
		"Goodnews Bay, AK (GNU)",
		"Grand Canyon, AZ - Heliport (JGC)",
		"Grand Canyon, AZ - National Park (GCN)",
		"Grand Forks, ND (GFK)",
		"Grand Island, NE (GRI)",
		"Grand Junction, CO (GJT)",
		"Grand Rapids, MI (GRR)",
		"Grand Rapids, MN (GPZ)",
		"Grayling, AK (KGX)",
		"Great Falls, MT (GTF)",
		"Green Bay, WI (GRB)",
		"Greensboro, NC (GSO)",
		"Greenville, MS (GLH)",
		"Greenville, NC (PGV)",
		"Greenville/Spartanburg, SC (GSP)",
		"Groton/New London, CT (GON)",
		"Gulfport, MS (GPT)",
		"Gunnison, CO (GUC)",
		"Gustavus, AK (GST)",
		"Hagerstown, MD (HGR)",
		"Hailey, ID (SUN)",
		"Haines, AK (HNS)",
		"Hampton, VA (PHF)",
		"Hana, HI - Island of Maui (HNM)",
		"Hanapepe, HI (PAK)",
		"Hancock, MI (CMX)",
		"Hanover, NH (LEB)",
		"Harlingen, TX (HRL)",
		"Harrisburg, PA (MDT)",
		"Harrison, AR (HRO)",
		"Hartford, CT (BDL)",
		"Havasupai, AZ (HAE)",
		"Havre, MT (HVR)",
		"Hayden, CO (HDN)",
		"Hays, KS (HYS)",
		"Healy Lake, AK (HKB)",
		"Helena, MT (HLN)",
		"Hendersonville, NC (AVL)",
		"Hibbing/Chisholm, MN (HIB)",
		"Hickory, NC (HKY)",
		"High Point, NC (GSO)",
		"Hilo, HI - Island of Hawaii (ITO)",
		"Hilton Head, SC (HHH)",
		"Hobbs, NM (HBB)",
		"Hollis, AK (HYL)",
		"Holy Cross, AK (HCR)",
		"Homer, AK (HOM)",
		"Honolulu, HI - Island of Oahu (HNL)",
		"Hoolehua, HI - Island of Molokai (MKK)",
		"Hoonah, AK (HNH)",
		"Hooper Bay, AK (HPB)",
		"Hot Springs, AR (HOT)",
		"Houston, TX - All airports (HOU)",
		"Houston, TX - Hobby (HOU)",
		"Houston, TX - Intercontinental (IAH)",
		"Hughes, AK (HUS)",
		"Huntington, WV/Ashland, KY (HTS)",
		"Huntsville, AL (HSV)",
		"Huron, SD (HON)",
		"Huslia, AK (HSL)",
		"Hyannis, MA (HYA)",
		"Hydaburg, AK (HYG)",
		"Idaho Falls, ID (IDA)",
		"Igiugig, AK (IGG)",
		"Iliamna, AK (ILI)",
		"Imperial, CA (IPL)",
		"Indianapolis, IN (IND)",
		"International Falls, MN (INL)",
		"Inyokern, CA (IYK)",
		"Iron Mountain, MI (IMT)",
		"Ironwood, MI (IWD)",
		"Islip, NY (ISP)",
		"Ithaca, NY (ITH)",
		"Jackson Hole, WY (JAC)",
		"Jackson, MS (JAN)",
		"Jackson, TN (MKL)",
		"Jacksonville, FL (JAX)",
		"Jacksonville, NC (OAJ)",
		"Jamestown, ND (JMS)",
		"Jamestown, NY (JHW)",
		"Janesville, WI (JVL)",
		"Johnson City, NY (BGM)",
		"Johnson City, TN (TRI)",
		"Johnstown, PA (JST)",
		"Jonesboro, AR (JBR)",
		"Joplin, MO (JLN)",
		"Juneau, AK (JNU)",
		"Kahului, HI - Island of Maui, (OGG)",
		"Kake, AK (KAE)",
		"Kakhonak, AK (KNK)",
		"Kalamazoo, MI (AZO)",
		"Kalaupapa, HI - Island of Molokai, (LUP)",
		"Kalskag, AK (KLG)",
		"Kaltag, AK (KAL)",
		"Kamuela, HI - Island of Hawaii, (MUE)",
		"Kansas City, MO (MCI)",
		"Kapalua, HI - Island of Maui, (JHM)",
		"Kasaan, AK (KXA)",
		"Kasigluk, AK (KUK)",
		"Kauai Island/Lihue, HI (LIH)",
		"Kearney, NE (EAR)",
		"Keene, NH (EEN)",
		"Kenai, AK (ENA)",
		"Ketchikan, AK (KTN)",
		"Key West, FL (EYW)",
		"Keystone, CO - Van service (QKS)",
		"Kiana, AK (IAN)",
		"Kilgore/Gladewater, TX (GGG)",
		"Killeen, TX (ILE)",
		"King Cove, AK (KVC)",
		"King Salmon, AK (AKN)",
		"Kingman, AZ (IGM)",
		"Kingsport, TN (TRI)",
		"Kipnuk, AK (KPN)",
		"Kirksville, MO (IRK)",
		"Kivalina, AK (KVL)",
		"Klamath Falls, OR (LMT)",
		"Klawock, AK (KLW)",
		"Knoxville, TN (TYS)",
		"Kobuk, AK (OBU)",
		"Kodiak, AK (ADQ)",
		"Kona, HI - Island of Hawaii (KOA)",
		"Kongiganak, AK (KKH)",
		"Kotlik, AK (KOT)",
		"Kotzebue, AK (OTZ)",
		"Koyukuk, AK (KYU)",
		"Kwethluk, AK (KWT)",
		"Kwigillingok, AK (KWK)",
		"La Crosse, WI (LSE)",
		"Lafayette, IN (LAF)",
		"Lafayette, LA (LFT)",
		"Lake Charles, LA (LCH)",
		"Lake Havasu City, AZ (HII)",
		"Lake Minchumina, AK (LMA)",
		"Lanai City, HI - Island of Lanai (LNY)",
		"Lancaster, PA (LNS)",
		"Lansing, MI (LAN)",
		"Laramie, WY (LAR)",
		"Laredo, TX (LRD)",
		"Las Vegas, NV (LAS)",
		"Latrobe, PA (LBE)",
		"Laurel, MS (PIB)",
		"Lawton, OK (LAW)",
		"Lebanon, NH (LEB)",
		"Levelock, AK (KLL)",
		"Lewisburg, WV (LWB)",
		"Lewiston, ID (LWS)",
		"Lewistown, MT (LWT)",
		"Lexington, KY (LEX)",
		"Liberal, KS (LBL)",
		"Lihue, HI - Island of Kaui (LIH)",
		"Lincoln, NE (LNK)",
		"Little Rock, AR (LIT)",
		"Long Beach, CA (LGB)",
		"Longview, TX (GGG)",
		"Lopez Island, WA (LPS)",
		"Los Angeles, CA (LAX)",
		"Louisville, KY (SDF)",
		"Loveland/Fort Collins, CO - Municipal Airport (FNL)", 
		"Loveland/Fort Collins, CO - Bus service (QWF)",
		"Lubbock, TX (LBB)",
		"Macon, GA (MCN)",
		"Madison, WI (MSN)",
		"Madras, OR (MDJ)",
		"Manchester, NH (MHT)",
		"Manhattan, KS (MHK)",
		"Manistee, MI (MBL)",
		"Mankato, MN (MKT)",
		"Manley Hot Springs, AK (MLY)",
		"Manokotak, AK (KMO)",
		"Marietta, OH/Parkersburg, WV (PKB)",
		"Marion, IL (MWA)",
		"Marquette, MI (MQT)",
		"Marshall, AK (MLL)",
		"Martha\'s Vineyard, MA (MVY)",
		"Martinsburg, PA (AOO)",
		"Mason City, IA (MCW)",
		"Massena, NY (MSS)",
		"Maui, HI (OGG)",
		"Mcallen, TX (MFE)",
		"Mccook, NE (MCK)",
		"Mcgrath, AK (MCG)",
		"Medford, OR (MFR)",
		"Mekoryuk, AK (MYU)",
		"Melbourne, FL (MLB)",
		"Memphis, TN (MEM)",
		"Merced, CA (MCE)",
		"Meridian, MS (MEI)",
		"Metlakatla, AK (MTM)",
		"Meyers Chuck, AK (WMK)",
		"Miami, FL - International (MIA)",
		"Miami, FL - Sea Plane Base (MPB)",
		"Midland, MI (MBS)",
		"Midland/Odessa, TX (MAF)",
		"Miles City, MT (MLS)",
		"Milwaukee, WI (MKE)",
		"Minneapolis, MN (MSP)",
		"Minot, ND (MOT)",
		"Minto, AK (MNT)",
		"Mission, TX (MFE)",
		"Missoula, MT (MSO)",
		"Moab, UT (CNY)",
		"Mobile, AL (MOB)",
		"Modesto, CA (MOD)",
		"Moline, IL (MLI)",
		"Monroe, LA (MLU)",
		"Monterey, CA (MRY)",
		"Montgomery, AL (MGM)",
		"Montrose, CO (MTJ)",
		"Morgantown, WV (MGW)",
		"Moses Lake, WA (MWH)",
		"Mountain Home, AR (WMH)",
		"Mountain Village, AK (MOU)",
		"Muscle Shoals, AL (MSL)",
		"Muskegon, MI (MKG)",
		"Myrtle Beach, SC (MYR)",
		"Nantucket, MA (ACK)",
		"Napakiak, AK (WNA)",
		"Napaskiak, AK (PKA)",
		"Naples, FL (APF)",
		"Nashville, TN (BNA)",
		"Naukiti, AK (NKI)",
		"Nelson Lagoon, AK (NLG)",
		"New Chenega, AK (NCN)",
		"New Haven, CT (HVN)",
		"New Koliganek, AK (KGK)",
		"New London/Groton (GON)",
		"New Orleans, LA (MSY)",
		"New Stuyahok, AK (KNW)",
		"New York, NY - All airports (NYC)",
		"New York, NY - Kennedy (JFK)",
		"New York, NY - La Guardia (LGA)",
		"Newark, NJ (EWR)",
		"Newburgh/Stewart Field, NY (SWF)",
		"Newport News, VA (PHF)",
		"Newtok, AK (WWT)",
		"Nightmute, AK (NME)",
		"Nikolai, AK (NIB)",
		"Nikolski, AK (IKO)",
		"Noatak, AK (WTK)",
		"Nome, AK (OME)",
		"Nondalton, AK (NNL)",
		"Noorvik, AK (ORV)",
		"Norfolk, NE (OFK)",
		"Norfolk, VA (ORF)",
		"North Bend, OR (OTH)",
		"North Platte, NE (LBF)",
		"Northway, AK (ORT)",
		"Nuiqsut, AK (NUI)",
		"Nulato, AK (NUL)",
		"Nunapitchuk, AK (NUP)",
		"Oakland, CA (OAK)",
		"Odessa/Midland, TX (MAF)",
		"Ogdensburg, NY (OGS)",
		"Oklahoma City, OK (OKC)",
		"Omaha, NE (OMA)",
		"Ontario, CA (ONT)",
		"Orange County, CA (SNA)",
		"Orlando, FL - Herndon (ORL)",
		"Orlando, FL - International (MCO)",
		"Oshkosh, WI (OSH)",
		"Ottumwa, IA (OTM)",
		"Owensboro, KY (OWB)",
		"Oxnard/Ventura, CA (OXR)",
		"Paducah, KY (PAH)",
		"Page, AZ (PGA)",
		"Palm Springs, CA (PSP)",
		"Panama City, FL (PFN)",
		"Parkersburg, WV/Marietta, OH (PKB)",
		"Pasco, WA (PSC)",
		"Pedro Bay, AK (PDB)",
		"Pelican, AK (PEC)",
		"Pellston, MI (PLN)",
		"Pendleton, OR (PDT)",
		"Pensacola, FL (PNS)",
		"Peoria, IL (PIA)",
		"Perryville, AK (KPV)",
		"Petersburg, AK (PSG)",
		"Philadelphia, PA - International (PHL)",
		"Philadelphia, PA - Trenton/Mercer NJ (TTN)",
		"Phoenix, AZ (PHX)",
		"Pierre, SD (PIR)",
		"Pilot Point, AK - Ugashnik Bay (UGB)",
		"Pilot Point, AK (PIP)",
		"Pilot Station, AK (PQS)",
		"Pittsburgh, PA (PIT)",
		"Platinum, AK (PTU)",
		"Plattsburgh, NY (PLB)",
		"Pocatello, ID (PIH)",
		"Point Baker, AK (KPB)",
		"Point Hope, AK (PHO)",
		"Point Lay, AK (PIZ)",
		"Ponca City, OK (PNC)",
		"Ponce, Puerto Rico (PSE)",
		"Port Alsworth, AK (PTA)",
		"Port Angeles, WA (CLM)",
		"Port Arthur/Beaumont, TX (BPT)",
		"Port Clarence, AK (KPC)",
		"Port Heiden, AK (PTH)",
		"Port Moller, AK (PML)",
		"Port Protection, AK (PPV)",
		"Portage Creek, AK (PCA)",
		"Portland, ME (PWM)",
		"Portland, OR (PDX)",
		"Portsmouth, NH (PSM)",
		"Poughkeepsie, NY (POU)",
		"Prescott, AZ (PRC)",
		"Presque Isle, ME (PQI)",
		"Princeton, WV (BLF)",
		"Providence, RI (PVD)",
		"Provincetown, MA (PVC)",
		"Prudhoe Bay/Deadhorse, AK (SCC)",
		"Pueblo, CO (PUB)",
		"Pullman, WA (PUW)",
		"Quincy, IL (UIN)",
		"Quinhagak, AK (KWN)",
		"Raleigh/Durham, NC (RDU)",
		"Rampart, AK (RMP)",
		"Rapid City, SD (RAP)",
		"Reading, PA (RDG)",
		"Red Devil, AK (RDV)",
		"Redding, CA (RDD)",
		"Redmond, OR (RDM)",
		"Reno, NV (RNO)",
		"Rhinelander, WI, (RHI)",
		"Richmond, VA (RIC)",
		"Riverton, WY (RIW)",
		"Roanoke, VA (ROA)",
		"Roche Harbor, WA (RCE)",
		"Rochester, MN (RST)",
		"Rochester, NY (ROC)",
		"Rock Springs, WY (RKS)",
		"Rockford, IL - Park&Ride Bus (ZRF)",
		"Rockford, IL - Van Galder Bus (ZRK)",
		"Rockland, ME (RKD)",
		"Rosario, WA (RSJ)",
		"Roswell, NM (ROW)",
		"Ruby, AK (RBY)",
		"Russian Mission, AK (RSH)",
		"Rutland, VT (RUT)",
		"Sacramento, CA (SMF)",
		"Saginaw, MI (MBS)",
		"Saint Cloud, MN (STC)",
		"Saint George Island, AK (STG)",
		"Saint George, UT (SGU)",
		"Saint Louis, MO (STL)",
		"Saint Mary\'s, AK (KSM)",
		"Saint Michael, AK (SMK)",
		"Saint Paul Island, AK (SNP)",
		"Salem, OR (SLE)",
		"Salina, KS (SLN)",
		"Salisbury-Ocean City, MD (SBY)",
		"Salt Lake City, UT (SLC)",
		"San Angelo, TX (SJT)",
		"San Antonio, TX (SAT)",
		"San Diego, CA (SAN)",
		"San Francisco, CA (SFO)",
		"San Jose, CA (SJC)",
		"San Juan, Puerto Rico (SJU)",
		"San Luis Obispo, CA (SBP)",
		"Sand Point, AK (SDP)",
		"Santa Ana, CA (SNA)",
		"Santa Barbara, CA (SBA)",
		"Santa Fe, NM (SAF)",
		"Santa Maria, CA (SMX)",
		"Santa Rosa, CA (STS)",
		"Saranac Lake, NY (SLK)",
		"Sarasota, FL (SRQ)",
		"Sault Ste Marie, MI, (CIU)",
		"Savannah, GA (SAV)",
		"Savoonga, AK (SVA)",
		"Scammon Bay, AK (SCM)",
		"Scottsbluff, NE (BFF)",
		"Scottsdale, AZ (SDL)",
		"Scranton, PA (AVP)",
		"Seattle, WA - Lake Union SPB (LKE)",
		"Seattle, WA - Seattle/Tacoma International (SEA)",
		"Selawik, AK (WLK)",
		"Seward, AK (SWD)",
		"Shageluk, AK (SHX)",
		"Shaktoolik, AK (SKK)",
		"Sheffield/Florence/Muscle Shoals, AL (MSL)",
		"Sheldon Point, AK (SXP)",
		"Sheridan, WY (SHR)",
		"Shishmaref, AK (SHH)",
		"Shreveport, LA (SHV)",
		"Shungnak, AK (SHG)",
		"Silver City, NM (SVC)",
		"Sioux City, IA (SUX)",
		"Sioux Falls, SD (FSD)",
		"Sitka, AK (SIT)",
		"Skagway, AK (SGY)",
		"Sleetmore, AK (SLQ)",
		"South Bend, IN (SBN)",
		"South Naknek, AK (WSN)",
		"Southern Pines, NC (SOP)",
		"Spartanburg/Greenville, SC (GSP)",
		"Spokane, WA (GEG)",
		"Springfield, IL (SPI)",
		"Springfield, MO (SGF)",
		"St Petersburg/Clearwater, FL (PIE)",
		"State College/University Park, PA (SCE)",
		"Staunton, VA (SHD)",
		"Steamboat Springs, CO (SBS)",
		"Stebbins, AK (WBB)",
		"Stevens Point/Wausau, WI (CWA)",
		"Stevens Village, AK (SVS)",
		"Stewart Field/Newburgh, NY (SWF)",
		"Stockton, CA (SCK)",
		"Stony River, AK (SRV)",
		"Sun Valley, ID (SUN)",
		"Syracuse, NY (SYR)",
		"Takotna, AK (TCT)",
		"Talkeetna, AK (TKA)",
		"Tallahassee, FL (TLH)",
		"Tampa, FL (TPA)",
		"Tanana, AK (TAL)",
		"Taos, NM (TSM)",
		"Tatitlek, AK (TEK)",
		"Teller Mission, AK (KTS)",
		"Telluride, CO (TEX)",
		"Tenakee Springs, AK (TKE)",
		"Terre Haute, IN (HUF)",
		"Tetlin, AK (TEH)",
		"Texarkana, AR (TXK)",
		"Thief River Falls, MN (TVF)",
		"Thorne Bay, AK (KTB)",
		"Tin City, AK (TNC)",
		"Togiak Village, AK (TOG)",
		"Tok, AK (TKJ)",
		"Toksook Bay, AK (OOK)",
		"Toledo, OH (TOL)",
		"Topeka, KS (FOE)",
		"Traverse City, MI (TVC)",
		"Trenton/Mercer, NJ (TTN)",
		"Tucson, AZ (TUS)",
		"Tulsa, OK (TUL)",
		"Tuluksak, AK (TLT)",
		"Tuntutuliak, AK (WTL)",
		"Tununak, AK (TNK)",
		"Tupelo, MS (TUP)",
		"Tuscaloosa, AL (TCL)",
		"Twin Falls, ID (TWF)",
		"Twin Hills, AK (TWA)",
		"Tyler, TX (TYR)",
		"Unalakleet, AK (UNK)",
		"Urbana/Champaign, IL (CMI)",
		"Utica, NY (UCA)",
		"Utopia Creek, AK (UTO)",
		"Vail, CO - Eagle County Airport (EGE)",
		"Vail, CO - Van service (QBF)",
		"Valdez, AK (VDZ)",
		"Valdosta, GA (VLD)",
		"Valparaiso, FL (VPS)",
		"Venetie, AK (VEE)",
		"Ventura/Oxnard, CA (OXR)",
		"Vernal, UT (VEL)",
		"Victoria, TX (VCT)",
		"Visalia, CA (VIS)",
		"Waco, TX (ACT)",
		"Wainwright, AK (AIN)",
		"Wales, AK (WAA)",
		"Walla Walla, WA (ALW)",
		"Washington DC - All airports (WAS)",
		"Washington DC - Dulles (IAD)",
		"Washington DC - National (DCA)",
		"Waterfall, AK (KWF)",
		"Waterloo, IA (ALO)",
		"Watertown, NY (ART)",
		"Watertown, SD (ATY)",
		"Wausau/Stevens Point, WI (CWA)",
		"Wenatchee, WA (EAT)",
		"West Palm Beach, FL (PBI)",
		"West Yellowstone, MT (WYS)",
		"Westchester County, NY (HPN)",
		"Westerly, RI (WST)",
		"Westsound, WA (WSX)",
		"Whale Pass, AK (WWP)",
		"White Mountain, AK (WMO)",
		"White River, VT (LEB)",
		"Wichita Falls, TX (SPS)",
		"Wichita, KS (ICT)",
		"Wilkes Barre, PA (AVP)",
		"Williamsburg, VA (PHF)",
		"Williamsport, PA (IPT)",
		"Williston, ND (ISN)",
		"Wilmington, NC (ILM)",
		"Windsor Locks, CT (BDL)",
		"Worcester, MA (ORH)",
		"Worland, WY (WRL)",
		"Wrangell, AK (WRG)",
		"Yakima, WA (YKM)",
		"Yakutat, AK (YAK)",
		"Yellowstone/Cody, WY (COD)",
		"Youngstown, OH (YNG)",
		"Yuma, AZ (YUM)"
    ];
    $( "#source" ).autocomplete({
      source: availableTags
    });
    $( "#destination" ).autocomplete({
      source: availableTags
    });
});
	
function CountdownClock(time)
{
    var numhours = (time / (60 * 60)) / 24;
    var remaining_hours = 3600 * 24 * numhours;
    var clock = $('.clock').FlipClock(remaining_hours, {
    clockFace: 'DailyCounter',
    countdown: true
    });
}
