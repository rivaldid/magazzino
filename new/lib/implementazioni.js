/*! specifiche jquery/jqueryui/datatables
 * Vilardi Dario per il progetto Gestione Magazzino
 */
 
$(document).ready(function() {
	
	$("#fornitore").autocomplete({
		source: "lib/json/contatti.php",
		select: function (event, ui) {
			this.value = ui.item.label;
			$(this).next("input").value(ui.item.value);
			event.preventDefault();
		},
		appendTo: "#dialog_carico"
	});

	$("#tipi_doc").autocomplete({
		source: "lib/json/tipi_doc.php",
		select: function (event, ui) {
			this.value = ui.item.label;
			$(this).next("input").value(ui.item.value);
			event.preventDefault();
		},
		appendTo: "#dialog_carico"
	});

	$("#num_doc").autocomplete({
		source: "lib/json/num_doc.php",
		select: function (event, ui) {
			this.value = ui.item.label;
			$(this).next("input").value(ui.item.value);
			event.preventDefault();
		},
		appendTo: "#dialog_carico"
	});

	$( ".datepicker" ).datepicker();

	$("input:text, input:password, input[type=email]").button().addClass("my-textfield");

	$("#dialog_carico").dialog({
		autoOpen: false,
		show: { effect: "blind", duration: 500 },
		hide: {	effect: "clip", duration: 500 },
		dialogClass: "no-close",
		buttons: {
			"Submit": {
				text: "Invia",
				click: function() {
					$(this).dialog("close");
				}
			}
		}
	});

	var table = $("#magazzino").DataTable({
		"iDisplayLength": 25,
        fixedHeader: { header: true, footer: true },
		columnDefs: [ {
            orderable: true,
            className: "select-checkbox",
            targets: 0
        } ],
		select: { style: "multi", selector: "td:first-child" }
	});

    $("#magazzino tbody")
        .on( "mouseenter", "td", function () {
            var colIdx = table.cell(this).index().column;
            $( table.cells().nodes() ).removeClass("highlight");
            $( table.column( colIdx ).nodes() ).addClass("highlight");
        } );

	new $.fn.DataTable.Buttons( table, {
        buttons: [
            {
                text: "Carico",
                action: function () {
					$("#dialog_carico").dialog("open");
                }
            },
            {
                text: "Scarico",
                action: function () {
                    alert("Scarico");
                }
            },
			"pdf","excel",
            {
                text: "Info",
                action: function () {
                    alert("Info");
                }
            }
        ]
	} );

	table.buttons().container().insertBefore("#magazzino_filter");

});
