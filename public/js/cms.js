$(document).ready(function() {
console.log("in ready");
var ajax_status = 0;
	  function showLoader() {
		if(ajax_status==0) { $("#blockUI").show(); }
	  }
		$(document).ajaxStart(function() {
			ajax_status = 0;
			console.log("Hey ");
			setTimeout(showLoader,150);
		});
		$(document).ajaxComplete(function(event, request, settings) {
		    ajax_status = 1;
		    jQuery('#blockUI').fadeOut(200);
		});
});