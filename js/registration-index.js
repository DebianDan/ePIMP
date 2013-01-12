function isInt(n) {
	return typeof n === 'number' && n % 1 == 0;
}

var get = [], hash;
var q = document.URL.split('?')[1];
if(q != undefined){
	q = q.split('&');
	for(var i = 0; i < q.length; i++){
		hash = q[i].split('=');
		get.push(hash[1]);
		get[hash[0]] = hash[1];
	}
}
// .attr("disabled", "disabled")
if(!('pk_id' in get)) {
	$('input').attr("disabled", "disabled");
	$('button').attr("disabled", "disabled");
}
$('#pkID').val(get['pk_id']);

// $(document).ready(function() { 
// 	// bind 'myForm' and provide a simple callback function 
// 	$('#userRegistration').ajaxForm(function() { 
// 		alert("Thank you for your comment!"); 
// 	}); 
// });