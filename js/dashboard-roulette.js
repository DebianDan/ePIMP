
$(document).ready(function() {
	var get = retrieve_get();
	$('#pkid').val(get['pkid']);
	$('#pgid').val(get['pgid']);
	$('#token').val(get['token']);
});