$(document).ready(function(){

	getEmails();

	$("#profile-button").on("click", function(element){

		element.preventDefault();

		var error = false;

		var firstNameErrorMessage = '';
		var lastNameErrorMessage = '';
		var emailErrorMessage = '';

		var firstName = $("#first-name").val();
		var lastName = $("#last-name").val();
		var email = $("#email").val();

		if( firstName == '' ){
			error = true;
			firstNameErrorMessage = 'First Name field can not be empty.';
			$("#first-name-help").text(firstNameErrorMessage);
			$("#first-name-help").attr("style", "color: red !important");
			$("#first-name").css({"border":"1px solid red"});
		} else {
			var firstNameLength = firstName.length;
			if( firstNameLength < 3 || firstNameLength > 20 ){
				error = true;
				firstNameErrorMessage = 'First Name must have atleast 3 characters, but not more than 20.';
				$("#first-name-help").text(firstNameErrorMessage);
				$("#first-name-help").attr("style", "color: red !important");
				$("#first-name").css({"border":"1px solid red"});
			}
		}

		if( firstNameErrorMessage == '' ){
			$("#first-name-help").text('Enter your first name.');
			$("#first-name-help").attr("style", "");
			$("#first-name").css({"border":""});
		}

		if( lastName == '' ){
			error = true;
			lastNameErrorMessage = 'Last Name field can not be empty.';
			$("#last-name-help").text(lastNameErrorMessage);
			$("#last-name-help").attr("style", "color: red !important");
			$("#last-name").css({"border":"1px solid red"});
		} else {
			var lastNameLength = lastName.length;
			if( lastNameLength < 3 || lastNameLength > 20 ){
				error = true;
				lastNameErrorMessage = 'Last Name must have atleast 3 characters, but not more than 20.';
				$("#last-name-help").text(lastNameErrorMessage);
				$("#last-name-help").attr("style", "color: red !important");
				$("#last-name").css({"border":"1px solid red"});
			}
		}

		if( lastNameErrorMessage == '' ){
			$("#last-name-help").text('Enter your last name.');
			$("#last-name-help").attr("style", "");
			$("#last-name").css({"border":""});
		}

		if( email == '' ){
			error = true;
			emailErrorMessage = 'Email field can not be empty.';
			$("#email-help").text(emailErrorMessage);
			$("#email-help").attr("style", "color: red !important");
			$("#email").css({"border":"1px solid red"});
		} else if( validateEmailAddress(email) === false ){ 
			error = true;
			emailErrorMessage = 'Email address is not valid.';
			$("#email-help").text(emailErrorMessage);
			$("#email-help").attr("style", "color: red !important");
			$("#email").css({"border":"1px solid red"});	 
		} else {

			var emailsData = $("#user-emails").val();
			emailsData = emailsData.split(",");

			var oldEmail = $("#old-user-email").val();

			emailsData = emailsData.filter(function(item, value){
				if( item != oldEmail ){
					return item;
				}
			});

			if( emailsData != '' ){
				for(var i = 0; i < emailsData.length; i++){
					if( email == emailsData[i] ){
						error = true;
						emailErrorMessage = 'Email address already exists.';
						$("#email-help").text(emailErrorMessage);
						$("#email-help").attr("style", "color: red !important");
						$("#email").css({"border":"1px solid red"});
					}
				}
			}
		}

		if( emailErrorMessage == '' ){
			$("#email-help").text('Enter your email address.');
			$("#email-help").attr("style", "");
			$("#email").css({"border":""});
		}

		if( error === false ){
			$("#profile-form").submit();
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