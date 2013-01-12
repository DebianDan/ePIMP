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
if(!('pk_id' in get) || !('pg_id' in get) || !('token' in get)) {
	$('input').attr("disabled", "disabled");
	$('button').attr("disabled", "disabled");
}
$('#pkID').val(get['pk_id']);
$('#pgID').val(get['pg_id']);
$('#token').val(get['token']);

$(document).ready(function() { 

	$('#inputPhone').change(function(){
		var phone_number = $('#inputPhone').val();
		$('#inputPhone').val(stripAlphaChars(phone_number));
	});

	$('#userRegistration').ajaxForm({ 
		beforeSubmit:  showRequest,  // pre-submit callback 
		success:  showResponse 
	}); 
});

// pre-submit callback 
function showRequest(formData, jqForm, options) { 
    // formData is an array; here we use $.param to convert it to a string to display it 
    // but the form plugin does this for you automatically when it submits the data 
    var queryString = $.param(formData); 
    var success = true;
 	
 	$(formData).each(function(key,value) {
 		if(value.name == 'phone') {
 			if(value.value.match(/^\d{10}$/) == null) {
 				$('#inputPhone').tooltip('show');
 				success = false;
 			}
 		} else if (value.name == 'email') {
 			if(!(isEmail(value.value))) {
 				$('#inputEmail').tooltip('show');
 				success = false;
 			}
 		}
 	});
 	return success;
}

// post-submit callback 
function showResponse(responseText, statusText, xhr, $form)  { 
	window.location = '/registration/welcome.html';
} 

/*
 * Clean the phone number
 * http://www.27seconds.com/kb/article_view.aspx?id=31
 */
function stripAlphaChars(pstrSource){ 
	var m_strOut = new String(pstrSource); 
	m_strOut = m_strOut.replace(/[^0-9]/g, ''); 
	return m_strOut; 
}

/*
 * Check to see if the email is valid
 */
function isEmail(email) {
	var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	return regex.test(email);
}
