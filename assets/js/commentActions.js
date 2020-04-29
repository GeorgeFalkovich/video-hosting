function postComment(button, postedBy, videoId, replyTo, containerClass) {
    let textarea = $(button).siblings("textarea");
    let commentText = textarea.val();
    textarea.val("");

    if(commentText) {
        $.post('ajax/postComment.php',{commentText: commentText, postedBy: postedBy,
                                            videoId: videoId, responseTo: replyTo}).done((comment) => {
                    $("." + containerClass).prepend(comment);
        })
    }

    else {
        alert("You cant post an empty comment!");
    }
}

function toggleReply(button) {
    let parent = $(button).closest(".itemContainer");
    let commentForm = parent.find(".commentForm").first();

    commentForm.toggleClass("hidden");
}

function likeComment(commentId, button, videoId) {

    $.post("ajax/likeComment.php", {commentId: commentId, videoId: videoId}).done(numToChange => {

        let likeButton = $(button);
        let dislikeButton = $(button).siblings(".dislikeButton");

        likeButton.addClass("active");
        dislikeButton.removeClass("active");

        let likesCount = $(button).siblings(".likesCount");
        updateLikesValue(likesCount, numToChange);

        if(numToChange < 0) {
            likeButton.removeClass("active");
            likeButton.find("img:first").attr("src", "assets/img/icons/thumb-up.png");
        }

        else {
            likeButton.find("img:first").attr("src", "assets/img/icons/thumb-up-active.png");
        }

        dislikeButton.find("img:first").attr("src", "assets/img/icons/thumb-down.png");

    });

}

function dislikeComment(commentId, button, videoId) {

    $.post("ajax/dislikeComment.php", {commentId: commentId, videoId: videoId}).done(numToChange => {


        let dislikeButton = $(button);
        let likeButton = $(button).siblings(".likeButton");

        dislikeButton.addClass("active");
        likeButton.removeClass("active");

        let likesCount = $(button).siblings(".likesCount");
        updateLikesValue(likesCount, numToChange);

        if(numToChange > 0) {
            dislikeButton.removeClass("active");
            dislikeButton.find("img:first").attr("src", "assets/img/icons/thumb-down.png");
        }

        else {
            dislikeButton.find("img:first").attr("src", "assets/img/icons/thumb-down-active.png");
        }

        likeButton.find("img:first").attr("src", "assets/img/icons/thumb-up.png");

    });


}

function updateLikesValue(element, num) {

    let likesCountVal = element.text() || 0;
    element.text(parseInt(likesCountVal) + parseInt(num));

}

function getReplies() {

    console.log('REPLIES');

}