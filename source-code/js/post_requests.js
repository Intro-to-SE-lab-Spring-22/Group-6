function post_getOnePost(postID) {
    $.post(
        "php/controller.php",
        {
            function: "getOnePost",
            postID: postID,
        },
        function(result) {

            var json = JSON.parse(result);

            if (json.success == "true") {
                document.querySelector("main").prepend(
                    generatePostElement(
                        json.data.postID,
                        json.data.user_id,
                        json.data.content,
                        json.data.num_likes,
                        json.data.is_liked,
                        -1,
                        json.data.is_editable,
                        (json.data.created_at != json.data.last_edited_at),
                        json.data.last_edited_at,
                        json.data.has_image,
                        json.data.image_filename)
                );
            }
            else {
                console.log(json.message);
            }
        }
    );
}

function post_updatePost(postElement) {
    postID = postElement.id.substring(2);
    // console.log(postID);
    $.post(
        "php/controller.php",
        {
            function: "getOnePost",
            postID: postID,
        },
        function(result) {

            var json = JSON.parse(result);

            if (json.success == "true") {
                postElement.querySelector('.post-content').querySelector('p').innerHTML = json.data.content;
                if (json.data.has_image) {
                    if (!postElement.querySelector('.post-images-holder')) {
                        postImagesHolder = document.createElement("div");
                        postImagesHolder.classList.add('post-images-holder');

                        var imageContainer = document.createElement("div");
                        imageContainer.classList.add("post-image-container");
                        var image = document.createElement("img");

                        image.src = "../images/post/" + json.data.postID + "/" + json.data.image_filename;

                        imageContainer.appendChild(image);
                        postImagesHolder.appendChild(imageContainer);
                        postElement.querySelector('.post-content').appendChild(postImagesHolder);
                    }
                    else {
                        postElement.querySelector('.post-image-container').querySelector('img').src = "../images/post/" + json.data.postID + "/" + json.data.image_filename;
                    }
                }
                postElement.querySelector('.post-icon-like').querySelector('p').innerHTML = json.data.num_likes
                postElement.querySelector('.post-icon-comment').querySelector('p').innerHTML = json.data.num_comments
                if (json.data.is_liked == true) {
                    if (!postElement.querySelector('.post-icon-like').classList.contains('is-liked')) {
                        postElement.querySelector('.post-icon-like').classList.add('is-liked');
                    }
                }
                else {
                    if (postElement.querySelector('.post-icon-like').classList.contains('is-liked')) {
                        postElement.querySelector('.post-icon-like').classList.remove('is-liked');
                    }
                }

                if (json.data.created_at != json.data.last_edited_at) {
                    var date_string = "Edited: "
                }
                else {
                    var date_string = "Created: "
                }
            
                var date = new Date(json.data.last_edited_at);
            
                var date_options = {year: 'numeric', month: 'short', day: 'numeric'};
                var time_options = {hour: 'numeric', minute: '2-digit', second: '2-digit', hourCycle: 'h24'}
            
                date_string += date.toLocaleDateString("en-us", date_options) + " at ";
                date_string += date.toLocaleTimeString("en-us", time_options);

                postElement.querySelector('.post-date').innerHTML = date_string;
            }
            else {
                console.log(json.message);
            }
        }
    );
}

function post_getAllCommentsByPost(postID) {
    $.post(
        "php/controller.php",
        {
            function: "getAllPostComments",
            postID: postID,
        },
        function(result) {

            var json = JSON.parse(result);

            if (json.success == "true") {
                for (var i = 0; i < json.data.length; i++) {

                    generateCommentElement(
                            json.data[i].commentID,
                            json.data[i].username,
                            json.data[i].content,
                            json.data[i].is_editable,
                            (json.data[i].created_at != json.data[i].last_edited_at),
                            json.data[i].last_edited_at
                    )  
                }
            }
            else {
                console.log(json.message);
            }
        }
    );
}

function post_likePost(element) {
    const parent_element = element.closest(".post");
    const postID = parent_element.id.substring(2);
    var likeIconHolderElement = parent_element.querySelector(".post-icon-like");
    var likeIconTextElement = likeIconHolderElement.querySelector("p");

    $.post(
        "php/controller.php",
        {
            function: "likePost",
            postID: postID
        },
        function(result) {

            var json = JSON.parse(result);

            if (json.success == "true") {
                if (json.action == "liked") {
                    likeIconHolderElement.classList.add("is-liked");
                }
                else {
                    likeIconHolderElement.classList.remove("is-liked");
                }
                likeIconTextElement.innerHTML = json.num_likes;
            }
            else {
                console.log(json.message);
            }
        }
    );
}

function post_addComment() {
    //add comments
    var postID = document.getElementsByClassName('post')[0].id.substring(2);
    var content = document.getElementById('c.new').querySelector('textarea').value;

    $.post(
        "php/controller.php",
        {
            function: "addComment",
            postID: postID,
            content: content
        },
        function(result) {

            var json = JSON.parse(result);

            if (json.success == "true") {
                updateCommentBox(json.user, json.content, json.commentID, json.created_at, 'add');
                addNewCommentBox(json.user);
            }
            else {
                alert(json);
            }
        }
    );
}

function post_addNewCommentBox() {

    $.post(
        "php/controller.php",
        {
            function: "getUser",
        },
        function(result) {

            var json = JSON.parse(result);

            if (json.success == "true") {
                addNewCommentBox(json.user);
            }
            else {
                console.log(json.message);
            }
        }
    );
    // return user;
}

function post_addComment() {
    //add comments
    var postID = document.getElementsByClassName('post')[0].id.substring(2);
    var content = document.getElementById('c.new').querySelector('textarea').value;

    $.post(
        "php/controller.php",
        {
            function: "addComment",
            postID: postID,
            content: content
        },
        function(result) {

            var json = JSON.parse(result);

            if (json.success == "true") {
                updateCommentBox(json.user, json.content, json.commentID, json.created_at, 'add');
                addNewCommentBox(json.user);
            }
            else {
                alert(json);
            }
        }
    );
}

function post_editComment(eventElement) {
    //store input and relevant data
    var comment = eventElement.parentElement.parentElement;
    var commentID = comment.id.substring(2);
    var content = comment.querySelector('textarea').value;

    $.post(
        "edit_comment.php",
        {
            commentID: commentID,
            content: content
        },
        function(result) {

            var json = JSON.parse(result);

            if (json.success == "true") {
                updateCommentBox(json.user, json.content, json.commentID, json.last_edited_at, 'edit');
            }
        }
    );
}

//edit button sends post to the controller
function post_editPost(eventElement) {
    var main = document.querySelector('main');
    var postElement = eventElement.closest('.post');
    var postID = postElement.id.substring(2);
    var content = postElement.querySelector('.post-content').querySelector('textarea').value;

    var formData = new FormData();

    formData.append('function', 'editPost');
    formData.append('postID', postID);
    formData.append('content', content);

    var files = document.getElementsByClassName('post')[0].querySelector('input').files;

    if (files.length > 0) {
        formData.append('image', files[0]);
    }

    $.ajax({
        url: "php/controller.php",
        type: "POST",
        data: formData,
        contentType: false,
        cache: false,
        processData:false,
        
        success: function(result) {
            var json = JSON.parse(result);

            if (json.success == "true") {
                console.log(json.data);
                console.log(json.data.created_at == json.data.last_edited_at);
                postElement.id = "p.old";
                main.insertBefore(
                    generatePostElement(
                        json.data.postID,
                        json.data.user_id,
                        json.data.content,
                        json.data.num_likes,
                        json.data.is_liked,
                        -1,
                        json.data.is_editable,
                        (json.data.created_at != json.data.last_edited_at),
                        json.data.last_edited_at,
                        json.data.has_image,
                        json.data.image_filename),
                    postElement
                );
                postElement.remove()

                // document.location = json.location;
            }
            else {
                console.log(result);
            }
        }         
    });

    // $.post(
    //     "php/controller.php",
    //     {
    //         function: "editPost",
    //         postID: postID,
    //         content: content
    //     },
    //     function(result) {

    //         var json = JSON.parse(result);

    //         if (json.success == "true") {
    //             console.log(json.data);
    //             console.log(json.data.created_at == json.data.last_edited_at);
    //             postElement.id = "p.old";
    //             main.insertBefore(
    //                 generatePostElement(
    //                     json.data.postID,
    //                     json.data.user_id,
    //                     json.data.content,
    //                     json.data.num_likes,
    //                     json.data.is_liked,
    //                     -1,
    //                     json.data.is_editable,
    //                     (json.data.created_at != json.data.last_edited_at),
    //                     json.data.last_edited_at,
    //                     json.data.has_image,
    //                     json.data.image_filename),
    //                 postElement
    //             );
    //             postElement.remove()

    //             // document.location = json.location;
    //         }
    //         else {
    //             console.log(result);
    //         }
    //     }
    // );
}
//send post to controller which sends to db
function post_createPost() {

    var formData = new FormData();
    var content = document.getElementsByClassName('post')[0].querySelector('textarea').value;
    
    formData.append('content', content);

    var files = document.getElementsByClassName('post')[0].querySelector('input').files;

    if (files.length > 0) {
        formData.append('image', files[0]);
    }

    for (var value of formData.values()) {
        console.log(value);
    }

    // post_uploadPostImage(formData, postID);

    $.ajax({
        url: "create_post.php",
        type: "POST",
        data: formData,
        contentType: false,
        cache: false,
        processData:false,
        
        success: function(result) {
            console.log(result);
            var json = JSON.parse(result);

            if (json.success == "true") {
                document.location = json.location;
            }
        }         
    });

    // $.post(
    //     "create_post.php",
    //     {
    //         content: content
    //     },
    //     function(result) {

    //         var json = JSON.parse(result);

    //         if (json.success == "true") {
    //             document.location = json.location;
    //         }
    //     }
    // );
}

function post_addNewPostBox() {
    $.post(
        "php/controller.php",
        {
            function: "getUser",
        },
        function(result) {

            var json = JSON.parse(result);

            if (json.success == "true") {
                addNewPostBox(json.user);
            }
            else {
                console.log(json.message);
            }
        }
    );
}

function post_logout() {
    $.post(
        "php/controller.php",
        {
            function: "logout",
        },
        function(result) {

            var json = JSON.parse(result);

            if (json.success == "true") {
                document.location = json.location;
            }
            else {
                console.log(json.message);
            }
        }
    );
}

function post_uploadImage(formData) {

    //var data = {formData: formData, function: "uploadFile"};

    formData.append('function', 'uploadProfilePicture');

    $.ajax({
        url: "php/controller.php",
        type: "POST",
        data: formData,
        contentType: false,
        cache: false,
        processData:false,
        
        success: function(data) {
            console.log(data);
        }         
    });
}

function post_uploadPostImage(formData, postID) {

    //var data = {formData: formData, function: "uploadFile"};

    console.log(psotID);

    formData.append('function', 'uploadPostImage');
    formData.append('postID', postID);

    $.ajax({
        url: "php/controller.php",
        type: "POST",
        data: formData,
        contentType: false,
        cache: false,
        processData:false,
        
        success: function(data) {
            console.log(data);
        }         
    });
}

function post_setUserProfilePicture(username, imageElement) {
    $.post(
        "php/controller.php",
        {
            function: "getUserProfilePicture",
            username: username
        },
        function(result) {

            var json = JSON.parse(result);

            if (json.success == "true") {
                imageElement.src = "../images/profile/" + json.filename;
            }
            else {
                console.log(json.message);
            }
        }
    );
}