$(document).ready(function(){

	checkFlashMessage();

});

function checkFlashMessage()
{
	var flashMessage = $("#register-user-flash-message").text();
	
	if( flashMessage != '' ){
		setTimeout(function(){
			$("#register-user-flash-message").fadeOut('slow');
		}, 5000);
	}
}