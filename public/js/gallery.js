$(document).ready(function(){

	$("#gallery-button").on("click", function(event){
		event.preventDefault();
		validateGalleryData();
	});

});

function validateGalleryData()
{
	var error = false;
	var nameError = '';
	var descriptionError = '';

	var name = $("#name").val();
	var description = $("#description").val();

	if( name == '' ){
		error = true;
		nameError = 'Gallery name can not be empty.';
		$("#name-help").text(nameError);
		$("#name-help").attr("style", "color: red !important");
		$("#name").css({"border":"1px solid red"});
	} else {
		var nameLength = name.length;
		if( nameLength < 3 || nameLength > 30 ){
			error = true;
			nameError = 'Gallery name must have atleast 3 characters, but not more than 30.';
			$("#name-help").text(nameError);
			$("#name-help").attr("style", "color: red !important");
			$("#name").css({"border":"1px solid red"});
		}
	}

	if( nameError == '' ){
		$("#name-help").text('Enter your gallery name.');
		$("#name-help").attr("style", "");
		$("#name").css({"border":""});
	}

	if( description == '' ){
		error = true;
		descriptionError = 'Gallery description can not be empty.';
		$("#description-help").text(descriptionError);
		$("#description-help").attr("style", "color: red !important");
		$("#description").css({"border":"1px solid red"});
	}

	if( descriptionError == '' ){
		$("#description-help").text('Enter your gallery description.');
		$("#description-help").attr("style", "");
		$("#description").css({"border":""});
	}

	if( error === false ){
		$("#gallery-form").submit();
	}
}

function getGalleryData(id)
{
	$("#gallery-form-wrapper").attr("style", "display: block");
}