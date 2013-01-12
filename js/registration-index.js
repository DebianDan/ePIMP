function isInt(n) {
	return typeof n === 'number' && n % 1 == 0;
}

var get = retrieve_get();

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

	$('#userRegistration').click(function() {
		$('#userRegistration').submit(function() {
			if($('#inputPhone').val().match(/^\d{10}$/) == null) {
				$('#inputPhone').tooltip('show');
				return false;
			}
			if(!(isEmail($('#inputEmail').val()))) {
				$('#inputEmail').tooltip('show');
				return false;
			}
			return true;
		}); 
	});
});


// post-submit callback 
function showResponse(responseText, statusText, xhr, $form)  { 
	// window.location = '/registration/index.html';
	$('<form id="dummyForm" method="GET" action="/registration/index.html"> <input type="hidden" name="application" value="success" /></form>').appendTo('body'); 
	document.getElementById('dummyForm').submit();
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
