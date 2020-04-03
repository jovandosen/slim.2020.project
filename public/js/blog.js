$(document).ready(function(){

	$("#comment-button").attr("disabled", true);

	$("#comment-button").on("click", function(){

		if( $("#comment").val() != '' ){
			$("#comment-form").submit();
		}

	});

});

function getPostData(id, userID)
{
	var postID = id;

	$("#comments-table").attr("style", "display: none;");

	$.ajax({
		url: "/get-post-data",
		method: "GET",
		data: {postID: postID, userID: userID},
		success: function(response){
			
			var postDetails = JSON.parse(response);

			var postTitle = postDetails.postTitle;
			var postContent = postDetails.postContent;
			var postImage = postDetails.postImage;
			var userFirstName = postDetails.userFirstName;
			var userLastName = postDetails.userLastName;
			var postId = postDetails.postID;
			var userId = postDetails.userID;
			var loggedUserID = postDetails.loggedUserID;

			$("#title").val(postTitle);
			$("textarea#content").val(postContent);
			$("#author").val(userFirstName + " " + userLastName);
			$("#post-image").attr("src", "images/posts/" + postImage);
			$("#post-id").val(postId);
			$("#user-id").val(loggedUserID);

			$("#comment-button").attr("disabled", false);

		},
		error: function(response){
			console.log('Error');
		}
	});
}

function getCommentsData(postID)
{
	$.ajax({
		url: "/get-comment-data",
		method: "GET",
		data: {postID: postID},
		success: function(response){

			var comments = JSON.parse(response);

			if(comments){

				$("#comments-table").attr("style", "display: block;");

				$("#comments-table tbody").empty();

				for(var i = 0; i < comments.length; i++){
					$("#comments-table tbody").append("<tr><td>"+comments[i].userFirstName+"</td><td>"+comments[i].userLastName+"</td><td>"+comments[i].userComment+"</td><td>"+comments[i].userCommentCreated+"</td></tr>");
				}

			} 

			if(comments == ''){
				$("#comments-table").attr("style", "display: none;");
			}

		},
		error: function(response){
			console.log('Error while getting comments.');
		}
	});
}