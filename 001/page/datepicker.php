<!DOCTYPE html>
<html>
<head>
	<link href="../demoengine/demoengine.css" rel="stylesheet">
	<script src="../demoengine/demoengine.js" defer></script>
	<title>jQuery UI Datepicker: Parse and Format Dates</title>
	<link href="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/themes/ui-darkness/jquery-ui.css" rel="stylesheet">
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
	

</head>
<body>
	<input id="datepicker" type="text">
	<p id="dateoutput"></p>
	<script>
		/*
		 * jQuery UI Datepicker: Parse and Format Dates
		 * http://salman-w.blogspot.com/2013/01/jquery-ui-datepicker-examples.html
		 */
		$(function() {
			$("#datepicker").datepicker({
				dateFormat: "dd-mm-yy",
				onSelect: function(dateText, inst) {
					var date = $.datepicker.parseDate(inst.settings.dateFormat || $.datepicker._defaults.dateFormat, dateText, inst.settings);
					var dateText1 = $.datepicker.formatDate("D, d M yy", date, inst.settings);
					date.setDate(date.getDate() + 7);
					var dateText2 = $.datepicker.formatDate("D, d M yy", date, inst.settings);
					$("#dateoutput").html("Chosen date is <b>" + dateText1 + "</b>; chosen date + 7 days yields <b>" + dateText2 + "</b>");
				}
			});
		});
		
/************************************************************************************/
/*******		help buitton in pagina etichette							********/
/************************************************************************************/
	$(function() {
	$( "#dialog" ).dialog({
	autoOpen: false,
	show: {
	effect: "blind",
	duration: 10
	},
	hide: {
	effect: "explode",
	duration: 10
	}
	});
	$( "#opener" ).click(function() {
	$( "#dialog" ).dialog( "open" );
	});
	});
		
		

</script>
	
	
	
<div id="dialog" title="Basic dialog">
<p>This is an animated dialog which is useful for displaying information. The dialog window can be moved, resized and closed with the 'x' icon.</p>
</div>
<img src="img/help_icon.png" id="opener">









                        <td align="center" valign="middle" style="padding:4px 0 8px 0;"><a onclick="javascript:void(0);" class="helpPop uppercase blue f12 bold" ref="The main differences between digital printing (short run printing) and traditional methods such as offset printing with ink (long run printing), is that no printing plates are used."><span id="shortlong">SHORT RUN DIGITAL</span> <img src="https://orderingplatform.com/files/Skins/e5c30380-2711-492d-baf0-b1d548cca6fc/images/icon_help_small.png" style="margin-top:2px;"></a></td>


</body>
</html>









<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>jQuery UI Tooltip - Forms</title>
<link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<link rel="stylesheet" href="/resources/demos/style.css">
<style>
label {
display: inline-block; width: 5em;
}
fieldset div {
margin-bottom: 2em;
}
fieldset .help {
display: inline-block;
}
.ui-tooltip {
width: 210px;
}
</style>
<script>
$(function() {
var tooltips = $( "[class]" ).tooltip();
$( "<button>" )
.text( "Show help" )
.button()
.click(function() {
tooltips.tooltip( "open" );
})
.insertAfter( "form" );
});
</script>
</head>
<body>
<form>
<fieldset>
<div>
<label for="firstname">Firstname</label>
<input id="firstname" name="firstname" class="Please provide your firstname." title="Please provide your firstname.">
</div>
<div>
<label for="lastname">Lastname</label>
<input id="lastname" name="lastname" title="Please provide also your lastname.">
</div>
<div>
<label for="address">Address</label>
<input id="address" name="address" title="Your home or work address.">
</div>
</fieldset>
</form>
</body>
</html>













