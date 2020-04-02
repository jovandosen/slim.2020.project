$(document).ready(function(){

	//

});

function getPostData(id)
{
	var postID = id;

	$.ajax({
		url: "/get-post-data",
		method: "GET",
		data: {postID: postID},
		success: function(response){
			
			var postDetails = JSON.parse(response);

			var postTitle = postDetails.postTitle;
			var postContent = postDetails.postContent;
			var postImage = postDetails.postImage;
			var userFirstName = postDetails.userFirstName;
			var userLastName = postDetails.userLastName;

			$("#title").val(postTitle);
			$("textarea#content").val(postContent);
			$("#author").val(userFirstName + " " + userLastName);
			$("#post-image").attr("src", "images/posts/" + postImage);

		},
		error: function(response){
			console.log('Error');
		}
	});
}