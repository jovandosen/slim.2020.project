$(document).ready(function(){

	$("#post-button").on("click", function(event){

		event.preventDefault();

		validatePostData();
	});

});

function validatePostData()
{
	var error = false;
	var postTitleError = '';
	var postContentError = '';
	var postImageError = '';

	var postTitle = $("#title").val();
	var postContent = $("#content").val();
	var postImage = $("#image").val();

	if( postTitle == '' ){
		error = true;
		postTitleError = 'Post Title can not be empty.';
		$("#title-help").text(postTitleError);
		$("#title-help").attr("style", "color: red !important");
		$("#title").css({"border":"1px solid red"});
	} else {
		var postTitleLength = postTitle.length;
		if( postTitleLength < 3 || postTitleLength > 20 ){
			error = true;
			postTitleError = 'Post Title must have atleast 3 characters, but not more than 20.';
			$("#title-help").text(postTitleError);
			$("#title-help").attr("style", "color: red !important");
			$("#title").css({"border":"1px solid red"});
		}
	}

	if( postTitleError == '' ){
		$("#title-help").text('Enter your post title.');
		$("#title-help").attr("style", "");
		$("#title").css({"border":""});
	}

	if( postContent == '' ){
		error = true;
		postContentError = 'Post Content can not be empty.';
		$("#content-help").text(postContentError);
		$("#content-help").attr("style", "color: red !important");
		$("#content").css({"border":"1px solid red"});
	} else {
		var postContentLength = postContent.length;
		if( postContentLength < 3 ){
			error = true;
			postContentError = 'Post Content must have atleast 3 characters.';
			$("#content-help").text(postContentError);
			$("#content-help").attr("style", "color: red !important");
			$("#content").css({"border":"1px solid red"});
		}
	}

	if( postContentError == '' ){
		$("#content-help").text('Enter your post content.');
		$("#content-help").attr("style", "");
		$("#content").css({"border":""});
	}

	if( postImage == '' ){
		error = true;
		postImageError = 'Please add post image.';
		$("#post-image-help").text(postImageError);
		$("#post-image-help").attr("style", "color: red !important");
		$("#image").css({"border":"1px solid red"});
	}

	if( postImageError == '' ){
		$("#post-image-help").text('Upload post image.');
		$("#post-image-help").attr("style", "");
		$("#image").css({"border":""});
	}

	if( error === false ){
		$("#post-form").submit();
	}
}