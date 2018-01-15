$(function () {
	// for version 3 method - size should be defined
	if($.fn.jquery.substr(0,1) == '3') {
		$.fn.size = function () { return this.length; }
	} 
});
