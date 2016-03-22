/*! specifiche jquery/jqueryui/datatables
 * Vilardi D. per il progetto Gestione Magazzino
 */

$(document).ready(function() {

	/* 1) PREPARO I DATI */

	$("#fornitore input").autocomplete({
		appendTo: "#dialog_carico",
		source: "json/contatti.php",
		search: function(event, ui) {
			$('#fornitore .spinner').show();
		},
		response: function(event, ui) {
			$('#fornitore .spinner').hide();
		}
	});

	$("#tipi_doc input").autocomplete({
		appendTo: "#dialog_carico",
		source: "json/tipi_doc.php",
		search: function(event, ui) {
			$('#tipi_doc .spinner').show();
		},
		response: function(event, ui) {
			$('#tipi_doc .spinner').hide();
		}
	});

	$("#num_doc input").autocomplete({
		appendTo: "#dialog_carico",
		source: "json/num_doc.php",
		search: function(event, ui) {
			$('#num_doc .spinner').show();
		},
		response: function(event, ui) {
			$('#num_doc .spinner').hide();
		}
	});

	$("#tags").tagit({
		allowDuplicates: false,
		autocomplete: {
			appendTo: "#dialog_carico",
			source: "json/merce.php",
			search: function(event, ui) {
				$('#merce .spinner').show();
			},
			response: function(event, ui) {
				$('#merce .spinner').hide();
			}
		}
	});

	$("#posizione input").autocomplete({
		appendTo: "#dialog_carico",
		source: "json/posizioni.php",
		search: function(event, ui) {
			$('#posizione .spinner').show();
		},
		response: function(event, ui) {
			$('#posizione .spinner').hide();
		}
	});

	$("#oda input").autocomplete({
		appendTo: "#dialog_carico",
		source: "json/oda.php",
		search: function(event, ui) {
			$('#oda .spinner').show();
		},
		response: function(event, ui) {
			$('#oda .spinner').hide();
		}
	});

	/* 2) CONFIGURO I FORM DI INTERAZIONE */

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

	/* 3) CONFIGURO DATATABLE */

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

	/* 3) CONFIGURO JQUERYUI */

	$( ".datepicker" ).datepicker();

	$("input:text, input:password, input[type=email]").button().addClass("my-textfield");

});
