$(document).ready(function(){

	getEmails();

	$("#login-button").on("click", function(element){

		element.preventDefault();

		var error = false;

		var emailErrorMessage = '';
		var passwordErrorMessage = '';

		var email = $("#email").val();
		var password = $("#password").val();

		if( email == '' ){
			error = true;
			emailErrorMessage = 'Email field can not be empty.';
			$("#email-help").text(emailErrorMessage);
			$("#email-help").attr("style", "color: red !important");
			$("#email").css({"border":"1px solid red"});
		} else if( validateEmailAddress(email) === false  ) {
			error = true;
			emailErrorMessage = 'Email address is not valid.';
			$("#email-help").text(emailErrorMessage);
			$("#email-help").attr("style", "color: red !important");
			$("#email").css({"border":"1px solid red"});	
		} else {
			var emailsData = $("#user-emails").val();
			emailsData = emailsData.split(",");
			var emailCount = 0;

			if( emailsData != '' ){
				for(var i = 0; i < emailsData.length; i++){
					if( email == emailsData[i] ){
						emailCount++;
					}
				}
			}

			if( emailCount === 0 ){
				error = true;
				emailErrorMessage = 'Email address does not exist.';
				$("#email-help").text(emailErrorMessage);
				$("#email-help").attr("style", "color: red !important");
				$("#email").css({"border":"1px solid red"});
			}
		}

		if( emailErrorMessage == '' ){
			$("#email-help").text('Enter your email address.');
			$("#email-help").attr("style", "");
			$("#email").css({"border":""});
		}

		if( password == '' ){
			error = true;
			passwordErrorMessage = 'Password field can not be empty.';
			$("#password-help").text(passwordErrorMessage);
			$("#password-help").attr("style", "color: red !important");
			$("#password").css({"border":"1px solid red"});
		} else {
			var passwordLength = password.length;
			if( passwordLength < 5 || passwordLength > 20 ){
				error = true;
				passwordErrorMessage = 'Password must have atleast 5 characters, but not more than 20.';
				$("#password-help").text(passwordErrorMessage);
				$("#password-help").attr("style", "color: red !important");
				$("#password").css({"border":"1px solid red"});
			}
		}

		if( passwordErrorMessage == '' ){
			$("#password-help").text('Enter your password.');
			$("#password-help").attr("style", "");
			$("#password").css({"border":""});
		}

		if( error === false ){
			$("#login-form").submit();
		}

	});

});

function validateEmailAddress(email)
{
	var regularEx = /\S+@\S+\.\S+/;
    return regularEx.test(email);
}

function getEmails()
{
	$.ajax({
		url: "/emails",
		method: "GET",
		success: function(response){
			if(response){
				var emails = JSON.parse(response);
				$("#user-emails").val(emails);
			} else {
				$("#user-emails").val('');
			}
		},
		error: function(){
			console.log('not good');
		}
	});
}