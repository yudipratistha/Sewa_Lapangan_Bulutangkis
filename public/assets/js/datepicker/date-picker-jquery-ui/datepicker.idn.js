( function( factory ) {
	"use strict";

	if ( typeof define === "function" && define.amd ) {

		// AMD. Register as an anonymous module.
		define( [ "../widgets/datepicker" ], factory );
	} else {

		// Browser globals
		factory( jQuery.datepicker );
	}
} )( function( datepicker ) {
"use strict";

datepicker.regional.id = {
	closeText: "Selesai",
	prevText: "Previous",
	nextText: "Next",
	currentText: "Hari ini",
	monthNames: [ "Januari", "Februari", "Maret", "April", "Mei", "Juni",
	"Juli", "Agustus", "September", "Oktober", "Nopember", "Desember" ],
	monthNamesShort: [ "Jan", "Feb", "Mar", "Apr", "Mei", "Jun",
	"Jul", "Agus", "Sep", "Okt", "Nop", "Des" ],
	dayNames: [ "Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu" ],
	dayNamesShort: [ "Min", "Sen", "Sel", "Rab", "Kam", "Jum", "Sab" ],
	dayNamesMin: [ "Mg", "Sn", "Sl", "Rb", "Km", "Jm", "Sb" ],
	weekHeader: "Mg",
	dateFormat: "dd/mm/yy",
	firstDay: 0,
	isRTL: false,
	showMonthAfterYear: false,
	yearSuffix: "" };
datepicker.setDefaults( datepicker.regional.id );

return datepicker.regional.id;

} );