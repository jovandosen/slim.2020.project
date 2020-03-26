$(document).ready(function(){

	$("#hide-show-el").on("click", function(){

		if( $("#password").attr("type") == "text" ){
			$("#password").attr("type", "password");
			$("#hide-show-el").removeClass("fa-eye");
			$("#hide-show-el").addClass("fa-eye-slash");
		} else {
			$("#password").attr("type", "text");
			$("#hide-show-el").removeClass("fa-eye-slash");
			$("#hide-show-el").addClass("fa-eye");
		}

	});

});