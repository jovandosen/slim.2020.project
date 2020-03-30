$(document).ready(function(){

	$("#hide-show-el-two").on("click", function(){

		if( $("#passwordNew").attr("type") == "text" ){
			$("#passwordNew").attr("type", "password");
			$("#hide-show-el-two").removeClass("fa-eye");
			$("#hide-show-el-two").addClass("fa-eye-slash");
		} else {
			$("#passwordNew").attr("type", "text");
			$("#hide-show-el-two").removeClass("fa-eye-slash");
			$("#hide-show-el-two").addClass("fa-eye");
		}

	});

	$("#hide-show-el").on("click", function(){

		if( $("#passwordOld").attr("type") == "text" ){
			$("#passwordOld").attr("type", "password");
			$("#hide-show-el").removeClass("fa-eye");
			$("#hide-show-el").addClass("fa-eye-slash");
		} else {
			$("#passwordOld").attr("type", "text");
			$("#hide-show-el").removeClass("fa-eye-slash");
			$("#hide-show-el").addClass("fa-eye");
		}

	});

	$("#passwordOld, #passwordNew").keydown(function(event){
		preventEnterAction(event);
	});

	$("#password-button").on("click", function(event){
		validatePasswordChange(event);
	});

});

function preventEnterAction(event)
{	
	var eventKeyCode = event.keyCode; // character number

	var values = [13];

	if( $.inArray(eventKeyCode, values) !== -1 ){
		event.preventDefault();
		return false;
	}
}

function validatePasswordChange(event)
{
	event.preventDefault();
	
	var error = false;

	var passwordOldError = '';
	var passwordNewError = '';

	var passwordOld = $("#passwordOld").val();
	var passwordNew = $("#passwordNew").val();

	if( passwordOld == '' ){
		error = true;
		passwordOldError = 'Old Password can not be empty.';
		$("#password-help-old").text(passwordOldError);
		$("#password-help-old").attr("style", "color: red !important");
		$("#passwordOld").css({"border":"1px solid red"});
	} else {
		var passwordOldLength = passwordOld.length;
		if( passwordOldLength < 5 || passwordOldLength > 20 ){
			error = true;
			passwordOldError = 'Password must have atleast 5 characters, but not more than 20.';
			$("#password-help-old").text(passwordOldError);
			$("#password-help-old").attr("style", "color: red !important");
			$("#passwordOld").css({"border":"1px solid red"});
		}
	}

	if( passwordOldError == '' ){
		$("#password-help-old").text('Enter your old password.');
		$("#password-help-old").attr("style", "");
		$("#passwordOld").css({"border":""});
	}

	if( passwordNew == '' ){
		error = true;
		passwordNewError = 'New Password can not be empty.';
		$("#password-help-new").text(passwordNewError);
		$("#password-help-new").attr("style", "color: red !important");
		$("#passwordNew").css({"border":"1px solid red"});
	} else {
		var passwordNewLength = passwordNew.length;
		if( passwordNewLength < 5 || passwordNewLength > 20 ){
			error = true;
			passwordNewError = 'Password must have atleast 5 characters, but not more than 20.';
			$("#password-help-new").text(passwordNewError);
			$("#password-help-new").attr("style", "color: red !important");
			$("#passwordNew").css({"border":"1px solid red"});
		}
	}

	if( passwordNewError == '' ){
		$("#password-help-new").text('Enter your new password.');
		$("#password-help-new").attr("style", "");
		$("#passwordNew").css({"border":""});
	}

	if( error === false ){
		$("#password-form").submit();
	}

}